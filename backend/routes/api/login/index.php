<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    header('Content-Type: application/json');

    if (array_key_exists("email", $_POST) && array_key_exists("pass1", $_POST)) {
    } else {
        response("Oops :/", 400);
        exit();
    }
    $_POST['email'] = trim($_POST['email']);
    $_POST['pass1'] = trim($_POST['pass1']);

    //Admin?122333!
    // if ($_POST['email'] === 'jmk@jmk.ro' && $_POST['pass1'] === '1234') {

    if ($_POST['email'] == 'a' && $_POST['pass1'] == 'a') {
        $_SESSION['id'] = "007";
        echo response("Autentificare reusita !", 200);
    } else {
        echo response("Cont gresit !", 400);
    }
} else {
    echo response("Method Not Allowed", 405);
}

exit();
