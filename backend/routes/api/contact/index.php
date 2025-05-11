<?php

require_once '../' . $_ENV["BACKEND"] . '/classes/model/contact/contact.php';

$contactModel = new Contact();

function clean_input($data)
{
    return htmlspecialchars(trim($data));
}


$method = $_SERVER["REQUEST_METHOD"];

if ($method === "POST") {

    $action = $_POST['action'] ?? '';

    switch ($action) {

        case 'getAll':
            echo json_encode($contactModel->getAll());
            break;

        case 'getById':
            $id = $_POST['id'] ?? '';
            if (!$id) {
                response("Invalid ID", 500);
            }
            $contact = $contactModel->getById($id);
            if ($contact) {
                echo json_encode($contact);
            } else {
                response("Contact not found", 404);
            }
            break;

        case 'save':
            $id = $_POST["id"] ?? null;
            $existingContact = [];

            // Validate status input
            $posted_status = $_POST["status"] ?? null;
            $status = in_array($posted_status, ["0", "1"], true)
                ? clean_input($posted_status)
                : "1";

            $data = [
                'email' => filter_var($_POST["email"], FILTER_SANITIZE_EMAIL),
                'nume' => clean_input($_POST["nume"]),
                'telefon' => clean_input($_POST["telefon"]),
                'domeniu' => clean_input($_POST["domeniu"]),
                'mesaj' => clean_input($_POST["mesaj"]),
                'status' => $status,
            ];

            // Validation
            if (empty($data['email'])) {
                response("Email is required!", 500);
            }
            if (empty($data['mesaj'])) {
                response("Mesajul lipseste!", 500);
            }

            if (!empty($id) && $id !== 'undefined') {
                $data['publish_date'] = clean_input($_POST["publish_date"]);
                // $data['status'] = $posted_status;
                $contactModel->update($id, $data);
                response("Form updated successfully!", 200);
            } else {
                $data['publish_date'] = new DateTime()->format('Y-m-d');
                // $data['status'] = "1";
                $contactModel->add($data);
                response("Form submitted successfully!", 200);
            }

            break;


        case 'delete':
            $id = $_POST['id'] ?? '';
            if (!$id) {
                response("Can't delete! Invalid ID", 500);
            }

            $existingContact = $contactModel->getById($id);
            if (!$existingContact) {
                response("Contact not found", 404);
            }

            $contactModel->delete($id);
            response("Form removed", 200);
            break;

        default:
            response("Unknown action", 500);
    }
} else {
    response("Invalid method.", 405);
}
