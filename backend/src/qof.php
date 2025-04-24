<?php

function ekko($value)
{
    if (is_array($value)) {
        echo "<pre>";
        print_r($value);
        echo "</pre>";
    } else {
        echo $value . "<br>";
    }
}

function localDic($options) // local dictionary
{
    $sessionLanguage = $_SESSION["LANG"];
    $defaultLanguage = $_ENV["DEFAULTLANG"];
    $languagesList = $_ENV["LANGUAGES"];
    // check if currently pickekd language exists in the ecosystem, if it does keep it otherwise go to default
    $selectedLang = in_array($sessionLanguage, json_decode($languagesList), true) ? $sessionLanguage : $defaultLanguage;

    // now check if selected option exists, if it doesnt then go to default language
    if (array_key_exists($selectedLang, $options)) {
        return $options[$selectedLang];
    } else {
        if (array_key_exists($defaultLanguage, $options)) { // selected option doesnt exist, then check if default option exists
            return $options[$defaultLanguage];
        } else {
            return "Not found";
        }
    }
}

function urlLang($currentLang, $address)
{
    return $_ENV["CURRENT_PATH"] . $currentLang . '/' . (trim($address[0]) == "/" ? "" : trim($address[0]));
}
function timestamp()
{
    $dateTime = new DateTime();
    $timestamp = $dateTime->getTimestamp();
    return $dateTime->getTimestamp();
}

function uploadFile($file, $jInfo = [])
{
    //ekko($jInfo);
    if (!array_key_exists("path", $jInfo)) {
        $jInfo["path"] = "uploads/";
    }
    if (!array_key_exists("allowedExtensions", $jInfo)) {
        $jInfo["allowedExtensions"] = ["png", "jpeg", "jpg", "gif"];
    }
    if (!array_key_exists("maxFileSize", $jInfo)) {
        $jInfo["maxFileSize"] = 23;
    }
    // Define allowed extensions and max file size
    $jInfo["maxFileSize"] = $jInfo["maxFileSize"] * 1024 * 1024; // 25 MB

    // Retrieve file details
    $originalName = $file['name'];
    $tmpFilePath = $file['tmp_name'];
    $fileSize = $file['size'];

    if ($file["error"] == 1) {
        return ["status" => 400, "message" => "File too big"];
    }

    // Check file extension
    $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $jInfo["allowedExtensions"])) {
        return ["status" => 400, "message" => "Invalid file extension. Only PNG, JPEG, JPG, and GIF files are allowed."];
    }

    // Check file size
    if ($fileSize > $jInfo["maxFileSize"]) {
        return ["status" => 400, "message" => "File size exceeds the maximum allowed size (25 MB)."];
    }

    // Generate new file name with timestamp
    $newFileName = time() . '_' . uniqid() . '.' . $fileExtension;

    // Upload file to the 'uploads' directory
    $uploadPath = $jInfo["path"] . $newFileName;

    if (!move_uploaded_file($tmpFilePath, $uploadPath)) {
        chmod($uploadPath, 0644); // Set appropriate permissions
        return ["status" => 500, "message" => "Failed to upload the file."];
    }


    // Return success response
    return [
        "status" => 200,
        "message" => "File uploaded successfully!",
        "originalName" => $originalName,
        "newName" => $newFileName,
        "path" => $uploadPath
    ];
}
function loadController($fileName)
{
    require '../' . $_ENV["BACKEND"] . '/controller/' . $fileName . '.php';
}
function freeScript($value)
{
    if (is_string($value))
        return preg_replace('/<\s*script\b[^>]*>(.*?)<\s*\/\s*script\s*>/is', '', $value);

    return $value;
}

function basicError($text, $status = 500)
{
    $response["status"] = $status;
    $response["message"] = $text;
    echo json_encode($response);
    exit();
}

function basicReturn($text, $status = 200)
{
    $response["status"] = $status;
    $response["message"] = $text;
    echo json_encode($response);
    exit();
}

function response($text, $status = 200)
{
    $response["status"] = $status;
    $response["message"] = $text;
    http_response_code($status);
    echo json_encode($response);
    exit();
}


function isVideoFile($fileName)
{
    // Define an array of common video file extensions
    $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm', 'm4v', '3gp', 'mpeg', 'mpg', 'ogg', 'ogv', 'vob', 'm2ts', 'mts', 'ts'];

    // Extract the file extension from the given file name
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);

    // Check if the extracted extension is in the array of video extensions
    return in_array(strtolower($extension), $videoExtensions);
}
$formats_file =  array(
    'doc' => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'eps' => 'application/postscript',
    'gif' => 'image/gif',
    'gz' => 'application/x-gzip',
    'html' => 'text/html',
    'jpg' => 'image/jpeg',
    'mp3' => 'audio/mpeg',
    'mp4' => 'video/mp4',
    'pdf' => 'application/pdf',
    'png' => 'image/png',
    'ppt' => 'application/vnd.ms-powerpoint',
    'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'ps' => 'application/postscript',
    'rtf' => 'text/rtf',
    'tar' => 'application/tar',
    'txt' => 'text/plain',
    'wp' => 'application/wordperfect',
    'xls' => 'application/vnd.ms-excel',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'xml' => 'application/xml',
    'zip' => 'application/zip'
);
$_ENV["formats_file"] = $formats_file;


function autoFiles($path) //automatically load files from the specified folder
{

    $contents = array_diff(scandir($path), array('.', '..'));
    foreach ($contents as $item) {
        if (substr($item, -4) === '.css') {
            echo '<link rel="stylesheet" type="text/css" href="' . $_ENV["CURRENT_PATH"] . '/css/auto/' . $item . '">';
        }
        if (substr($item, -3) === '.js') {
            echo '<script src="' . $_ENV["CURRENT_PATH"] . '/js/auto/' . $item . '"></script>';
        }
    }
}

function random_s($length)
{
    $characters = 'DSFGLKJHDFSGDSFLIKJGHDFSGDSFKGJHDSFGSDLKJH';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function my_simple_crypt($string, $action = 'e')
{
    // you may change these values to your own
    $secret_key = 'SDLJKHFSDAFALSKJHFW34Q978RYWAEF9874O3QI54UY2W3I@*#&q^$HEKSFHD';
    $secret_iv = 'DSFGIUE3WWTG03487GYH34!*^&@#%qWHG3498TGUYJ3Q4039P94OUWERDFC89823O';

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'e') {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } else if ($action == 'd') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}


function curlReq($url)
{
    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Set the following two options to false to ignore SSL certificate verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    // Execute cURL session and get the response
    $response = curl_exec($ch);
    // Check for cURL errors
    if (curl_errno($ch)) {
        return null;
    } else {
        // Print the response format (e.g., JSON, XML, HTML, etc.)
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        // echo 'Response Format: ' . $contentType . PHP_EOL;

        // Print the actual response
        return json_decode($response, true);
    }
    // Close cURL session
    curl_close($ch);
}


function capthcaVerify($frontEndToken)
{

    try {
        // Verify reCAPTCHA response
        $recaptchaSecretKey = $_ENV["CAPTCHA_KEY"];
        $recaptchaResponse = $frontEndToken;
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $captchaData = [
            'secret' => $recaptchaSecretKey,
            'response' => $recaptchaResponse
        ];
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($captchaData),
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        // Decode the result
        $result = json_decode($result, true);
        if ($result["success"] != NULL && $result["success"] == 1) {
            return true;
        } else {
            return false;
        }
    } catch (\Throwable $th) {
        return false;
    }
}


function generateLinks($case)
{

    $timestamp = timestamp();
    $randomString = random_s('10');
    $tokenUrl = $timestamp . $randomString;
    $encryptedToken = my_simple_crypt($tokenUrl, 'e');
    $state = my_simple_crypt($case, "e");
    $linkActivare = my_simple_crypt("state=" . $state . "&token=" . $encryptedToken, 'e');

    return array(
        "encryptedToken" => $encryptedToken,
        "state" => $state,
        "linkActivare" => $linkActivare
    );
}



class logChecker
{
    public $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
        $loginMode = $_ENV["loginMode"];
        // login mode documentation, can have the following values
        // session -> sets the system to check for connection
        // noSession -> no longer asks user for account 
        if ($loginMode == "noSession") {
            $_SESSION["id"] = (whatPage("login") || whatPage("login.aspx")) ? "noSession" : $_SESSION["id"];
        }
    }


    function isConnected()
    {
        if (array_key_exists("id", $_SESSION)  != null &&  $_SESSION["id"] != "noSession") // vezi daca e conectat
        {
            $sql = "SELECT TOP(1) *  FROM utilizatori WHERE id  LIKE ? AND activ LIKE 1  AND valabil LIKE 1";
            $params = array($_SESSION["id"]);
            $stmt = sqlsrv_query($this->conn, $sql, $params);
            if ($stmt === false) {
                return false;
            }
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

            if ($row == NULL || count($row) == 0) {
                return false;
            } else {
                $_SESSION['nume']             = $row['numePrenume'];
                $_SESSION['nume_prenume']     = $row['numePrenume'];
                $_SESSION['data_nasterii']    = $row['dataNasterii'];
                $_SESSION['sex']              = $row['sex'];
                $_SESSION['adresa_email']     = $row['adresaEmail'];
                $_SESSION['numar_telefon']    = $row['telefon'];
                $_SESSION['organizatie']      = $row['numeOrganizatie'];
                $_SESSION['domeniu']          = $row['domeniuActivitate'];
                $_SESSION['tip_organizatie']  = $row['tipOrganizatie'];
                $_SESSION['functie']          = $row['functie'];
                $_SESSION['judet']            = $row['judet'];
                $_SESSION['oras']             = $row['oras'];
                $_SESSION['grad']             = $row['grad'];
                $_SESSION['id']               = $row['id'];
                $_SESSION['rol']             = $row['grad'];
                return true;
            }
        } else {
            if (array_key_exists("id", $_SESSION) &&  $_SESSION["id"] == "noSession") {
                $_SESSION['nume']             = "noSession";
                $_SESSION['nume_prenume']     = "noSession";
                $_SESSION['data_nasterii']    = "";
                $_SESSION['sex']              = "none";
                $_SESSION['adresa_email']     = "guest@email.com";
                $_SESSION['numar_telefon']    = "";
                $_SESSION['organizatie']      = "";
                $_SESSION['domeniu']          = "";
                $_SESSION['tip_organizatie']  = "";
                $_SESSION['functie']          = "";
                $_SESSION['judet']            = "";
                $_SESSION['oras']             = "";
                $_SESSION['grad']             = "6";
                $_SESSION['id']               = "noSession";
                $_SESSION['rol']             = "6";
                return true;
            } else {
                return false;
            }
        }
    }
}
