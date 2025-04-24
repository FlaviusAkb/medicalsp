<?php
/*
maybe we should move this into something session related later - start
*/
function initializeSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();

        // Ensure the session cookie is only sent over secure connections
        ini_set('session.cookie_secure', '1'); // Force HTTPS
        ini_set('session.cookie_httponly', '1'); // Prevent JavaScript from accessing session cookies
    }
}

/**
 * Check and enforce session timeout (60 minutes of inactivity kills the session)
 */
function checkSessionTimeout($timeout = 3600)
{
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
        // Session has expired, clear session and redirect
        session_unset();
        session_destroy();
        header("Location: /");
        exit();
    }

    // Update last activity timestamp
    $_SESSION['last_activity'] = time();
}

// Call the functions
initializeSession();
checkSessionTimeout();

/*
maybe we should move this into something session related later - end
*/



/**
 * TwoFactorAuth handles 2FA authentication via email.
 * 
 * Features:
 * - Generates and verifies 2FA codes.
 * - Implements session-based authentication.
 * - Includes AJAX form handling.
 * - Implements brute-force protection with timeouts and lockouts.
 * 
 * @maxAttempts Max failed attempts before locking out a user.
 * @attemptTimeout Time window (10 min) for counting failed attempts.
 * @lockoutDuration Lockout period (30 min) after too many failed attempts.
 * @currentPath Stores the base path for form actions.
 */
class TwoFactorAuth
{
    private $maxAttempts = 5;
    private $attemptTimeout = 600;
    private $lockoutDuration = 1800;
    private $currentPath;
    private  $adminEmailsArray;

    public function __construct()
    {
        $this->currentPath = $_ENV['CURRENT_PATH'] ?? "";
        $this->adminEmailsArray = json_decode($_ENV['ADMIN_EMAILS'], true);
    }

    /** Generate the Email Submission Form */
    public function emailForm()
    {
        return '<form method="POST" action="' . $this->currentPath . '/api/2fa" id="email_form" class="ajax-form flex flex-col gap-4">
                    <label for="code" class="block text-sm font-medium text-gray-700">You must use the admin-defined email address to receive the code</label>
                    <input type="email" name="email" class="mt-1 w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-gray-500 focus:outline-none focus:border-gray-500" placeholder="Enter your email" required>
                    <input type="hidden" name="case" value="sendMail">
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 rounded-xl transition">Send 2FA Code</button>
                </form>';
    }

    /** Generate the 2FA Code Verification Form */
    public function verificationForm()
    {
        return '<form method="POST" action="' . $this->currentPath . '/api/2fa" id="2fa_form" class="ajax-form flex flex-col gap-4">
                    <label for="code" class="block text-sm font-medium text-gray-700">Enter the received 2FA code (expires after 10 minutes)</label>
                    <input type="text" id="code" name="code" 
                        class="mt-1 w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-gray-500 focus:outline-none focus:border-gray-500"
                        placeholder="6-digit code" required>
                    <input type="hidden" name="case" value="verifyCode">
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 rounded-xl transition">
                        Verify
                    </button>
                </form>';
    }
    /** Include AJAX JavaScript */
    public function ajaxScript()
    {
        return '
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $(".ajax-form").submit(function(e) {
                    e.preventDefault();
                    var form = $(this);
                    $.ajax({
                        type: "POST",
                        url: "' . $this->currentPath . '/api/2fa",
                        data: form.serialize(),
                        success: function(response) {
                            try {
                                if (response.status === 200) {
                                    alert(response.message);
                                    if (form.attr("id") === "email_form") {
                                        // window.location.href = "' . $this->currentPath . '/admin/2fa/2fa-check";
                                    } else if (form.attr("id") === "2fa_form") {
                                        window.location.href = "' . $this->currentPath . '/admin/widget";
                                    }
                                } else {
                                    alert(response.message);
                                }
                            } catch (error) {
                                console.error("Error parsing response: ", error);
                                alert("Unexpected response from the server.");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error occurred: ", error);
                            alert("An error occurred. Please try again.");
                        }
                    });
                });
            });
        </script>';
    }
    /** Handle API Requests */
    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] !== "POST") {
            $this->basicError("Invalid request method.");
        }

        header('Content-Type: application/json');

        if (!isset($_POST['case'])) {
            $this->basicError("No case parameter provided!");
        }

        switch ($_POST['case']) {
            case 'sendMail':
                $email = $this->validateEmail();
                $this->send2FACode($email);
                break;
            case 'verifyCode':
                $this->verifyCode();
                break;
            case 'logout':
                $this->killSession();
                break;
            default:
                $this->basicError("Invalid case provided.");
        }
    }

    /** Validate Email and Check Lockout */
    private function validateEmail()
    {
        $email = trim($_POST["email"] ?? "");
        if (empty($email)) {
            $this->basicError("No email provided.");
        }
        if ($this->isLockedOut($email)) {
            $this->basicError("Too many requests. Try again later.");
        }
        if (!in_array($email, $this->adminEmailsArray)) {
            $this->basicError("You're not the admin buddy.");
        }
        return $email;
    }

    /** Send 2FA Code via Email */
    private function send2FACode($email)
    {
        $otpCode = rand(100000, 999999);
        $_SESSION['2fa_code'] = $otpCode;
        $_SESSION['2fa_email'] = $email;
        $_SESSION['2fa_time'] = time();

        $mailBody = "<body>Your 2FA code is: <strong>$otpCode</strong></body>";

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = "///////////";
        $mail->SMTPAuth = true;
        $mail->Username = '///////////////';
        $mail->Password = '////////////////';
        $mail->Port = 587;
        $mail->SMTPSecure = "tls";

        $mail->isHTML(true);
        $mail->setFrom("/////////////////", "Test");
        $mail->addAddress($_SESSION['2fa_email']);
        $mail->Subject = "Your 2FA Code";
        $mail->Body = $mailBody;

        if ($mail->send()) {
            $this->response("Email sent successfully!", 200);
        } else {
            $this->basicError("Error sending email. Please try again later.");
        }
    }

    /** Verify the 2FA Code */
    private function verifyCode()
    {
        $email = $_SESSION['2fa_email'] ?? "";
        if (empty($email) || !isset($_POST['code']) || empty(trim($_POST['code']))) {
            $this->basicError("No OTP code provided for your email.");
        }
        if (!isset($_SESSION['2fa_code']) || time() - $_SESSION['2fa_time'] > 600) {
            unset($_SESSION['2fa_code'], $_SESSION['2fa_email'], $_SESSION['2fa_time']);
            $this->basicError("2FA code expired. Request a new code.");
        }
        if ($_POST['code'] == $_SESSION['2fa_code']) {
            $_SESSION['2fa_verified'] = true;
            session_regenerate_id(true);
            unset($_SESSION['2fa_attempts'][$email], $_SESSION['2fa_code'], $_SESSION['2fa_email'], $_SESSION['2fa_time']);
            $this->response("2FA verification successful!", 200);
        } else {
            $this->trackFailedAttempt($email);
            $this->basicError("Invalid 2FA code. Try again.");
        }
    }

    /** Check if the email is locked due to too many attempts */
    private function isLockedOut($email)
    {
        $attempts = $_SESSION['2fa_attempts'][$email] ?? null;
        return $attempts && $attempts['locked'] && (time() - $attempts['locked_time']) < $this->lockoutDuration;
    }

    /** Track failed attempts and lock user if necessary */
    private function trackFailedAttempt($email)
    {
        $attempts = &$_SESSION['2fa_attempts'][$email];
        if (!$attempts || time() - $attempts['first_attempt'] > $this->attemptTimeout) {
            $attempts = ['count' => 0, 'first_attempt' => time(), 'locked' => false, 'locked_time' => 0];
        }
        $attempts['count']++;
        if ($attempts['count'] >= $this->maxAttempts) {
            $attempts['locked'] = true;
            $attempts['locked_time'] = time();
            $this->basicError("Too many failed attempts. Locked for 30 minutes.");
        }
    }

    /** Secure page using 2FA check */
    public static function require2FA($redirect = "/admin/2fa")
    {
        if (!isset($_SESSION['2fa_verified']) || $_SESSION['2fa_verified'] !== true) {
            header("Location: $redirect");
            exit();
        }
    }

    /** Send JSON Response */
    private function response($message, $status = 200)
    {
        echo json_encode(["status" => $status, "message" => $message]);
        exit();
    }

    /** Handle Errors */
    private function basicError($message)
    {
        $this->response($message, 400);
    }

    /** Destroy the session and redirect */
    public static function killSession($redirect = "/admin/2fa")
    {
        session_unset();
        session_destroy();
        header("Location: $redirect");
        exit();
    }
}
