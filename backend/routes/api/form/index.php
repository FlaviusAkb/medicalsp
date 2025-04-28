<?php

require_once "../" . $_ENV["BACKEND"] . "/classes/Database.php";

/*
 
some kind of check maybe or spam prevention ?

 */

function clean_input($data)
{
    return htmlspecialchars(trim($data));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // field clean

    $email = filter_var($_POST["email"] ?? '', FILTER_VALIDATE_EMAIL);
    $nume = clean_input($_POST["nume"] ?? '');
    $telefon = clean_input($_POST["telefon"] ?? '');
    $domeniu = clean_input($_POST["domeniu"] ?? '');
    $mesaj = clean_input($_POST["mesaj"] ?? '');

    // Contact form ? Newsletter form
    if (!empty($mesaj)) {
        // validation
        if (empty($email) || empty($nume) || empty($mesaj)) {
            response("Toate câmpurile obligatorii trebuie completate corect?", 500);
            response("Nume: $nume, email: $email, mesaj: $mesaj", 500);
        } else {
            // contact_entries table
            // $conn = Database::dbConnect($_ENV["DB_NAME"]);
            // if ($conn->connect_error) {
            //     response("Internal error!", 500);
            // }

            $columns = [
                ["key" => "email", "value" => $email],
                ["key" => "nume", "value" => $nume],
                ["key" => "telefon", "value" => $telefon],
                ["key" => "domeniu", "value" => $domeniu],
                ["key" => "mesaj", "value" => $mesaj],
            ];

            // $insertResult = Database::insert('contact_entries', $conn, $columns);
            // if ($insertResult === false) {
            //     response("A apărut o eroare. Formularul nu a fost trimis.", 500);
            // } else {
            //     response("Formular trimis cu succes!", 200);
            // }

            response("Formular trimis cu succes!", 200);
        }
    } else {
        // form validation
        if (empty($email)) {
            response("Toate câmpurile obligatorii trebuie completate corect!!!", 500);
        } else {
            // newsletter_entries table
            // $conn = Database::dbConnect($_ENV["DB_NAME"]);
            // if ($conn->connect_error) {
            //     response("Internal error!", 500);
            // }

            $columns = [
                ["key" => "email", "value" => $email],
                ["key" => "nume", "value" => $nume],
                ["key" => "telefon", "value" => $telefon],
                ["key" => "domeniu", "value" => $domeniu],
            ];

            // $insertResult = Database::insert('newsletter_entries', $conn, $columns);
            // if ($insertResult === false) {
            //     response("A apărut o eroare. Formularul nu a fost trimis.", 500);
            // } else {
            //     response("Formular trimis cu succes!", 200);
            // }

            response("Formular trimis cu succes!", 200);
        }
    }
}
