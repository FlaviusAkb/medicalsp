<?php



class Captcha
{
    public static function verifyCaptcha($recaptchaResponse)
    {
        // Verify reCAPTCHA response
        $recaptchaSecretKey = $_ENV["SECRET_KEY"];
        //$recaptchaResponse = $_POST['token'];
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

        if ($result["success"] == NULL || $result["success"] != 1) {
            return false;
        } else {
            return true;
        }
        // Verify reCAPTCHA response
    }
}
