<?php


class Login
{


    public function __construct()
    {
        //ekko("booyah");
    }


    public static function updateUserInfo($conn, $id)
    {
        $sql = "SELECT *  FROM mkIntern_users WHERE id LIKE ?";
        $stmt  = $conn->prepare($sql);
        if ($stmt === false) {
            return false;
        }
        $stmt->execute([$id]);
        $result = $stmt->get_result();
        if ($result->num_rows == 0)
            return false;

        $row = $result->fetch_assoc();

        self::saveUserInfo($row);
        return true;
    }

    public static function saveUserInfo($row)
    {
        $_SESSION['id']        = $row['id'];
        $_SESSION['name']      = $row['name'];
        $_SESSION['birthDate'] = $row['birthDate'];
        $_SESSION['gender']    = $row['gender'];
        $_SESSION['email']     = $row['email'];
        $_SESSION['status']    = $row['status'];
        $_SESSION['grade']     = $row['grade'];
        $_SESSION['active']    = $row['active'];
    }


    static function logUser($POST)
    {
        if (!array_key_exists("email", $POST) || !array_key_exists("pass1", $POST) || !array_key_exists("token", $POST)) {
            basicError("Autentificarea nu a putut fi efectuată");
        }


        if (!captcha::verifyCaptcha($POST['token']))
            basicError("Autentificarea nu a putut fi efectuată");


        $POST['email'] = strtolower(trim($POST['email']));
        $POST['pass1'] = trim($POST['pass1']);

        $conn = database::dbConnect("mk");

        // VERIFY IF USER EXISTS IN DATABASE START
        $sql = "SELECT *  FROM mkIntern_users WHERE email LIKE ? AND active LIKE 1  AND (status LIKE 1 OR status LIKE -1 OR status LIKE -2) LIMIT 1";
        $stmt  = $conn->prepare($sql);
        if ($stmt === false) {
            basicError("Autentificarea nu a putut fi efectuată");
        }
        $stmt->bind_param("s", $POST['email']); // Using the splat operator to unpack the array

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0)
            basicError("Autentificarea nu a putut fi efectuată!");

        $row = $result->fetch_assoc();
        // VERIFY PASSWORD
        if (password_verify($POST['pass1'], $row["password"]) != 1) {
            basicError("Autentificarea nu a putut fi efectuată!");
        }

        self::saveUserInfo($row);

        basicReturn("Log successful");
    }

    function getUser()
    {
    }


    function updateUser()
    {
    }


    function deleteUser()
    {
    }
}
