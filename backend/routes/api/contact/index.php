<?php

class contact
{
    private $db;
    private $response;

    function __construct($POST)
    {
        // $this->db = db::dbConnect("mk_local");
        $this->response = array(
            "status" => 200,
            "message" => ""
        );

        ekko($POST);

        if (!array_key_exists("case", $POST)) {
            basicError("case not found");
        }


        if ($POST["case"] == "sendMail") {
            $this->sendMail($POST);
        }
    }


    public function sendMail($POST)
    {

        $mailBody  = '
            Name: ' . $_POST['first'] . ' ' . $_POST['first'] . ',<br>
            Email: ' . $_POST['email'] . ',<br>
            Phone: ' . $_POST['phone'] . ',<br>
            Project description: ' . $_POST['text12'] . '<br>
        ';
        $mailBody .= '';

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        //SMTP Settings
        $mail->isSMTP();
        $mail->Host = "mail.prokopovindustries.com";
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL'];
        $mail->Password = $_ENV['MAILPASS'];
        $mail->Port = 465; // 587  465
        $mail->SMTPSecure = "ssl"; //tls ssl

        //Email settings
        $mail->isHTML(true);
        $mail->setFrom("noreply@prokopovindustries.com","Prokopov");
        $mail->addAddress("office@prokopovindustries.com");
        $mail->Subject = "Contact website";
        $mail->Body = $mailBody;

        if ($mail->send()) {
            basicReturn("The mail was sent successfully!");
        } else {
            // echo "<br>";
            // echo $mail->ErrorInfo;
            // echo !extension_loaded('openssl') ? "Not Available" : "Available";
            // echo "<br>";
            basicError("Error connecting to the server! please come back later or contact us by phone <br> Thank you for your understanding!");
        }
        exit();
    }
}
