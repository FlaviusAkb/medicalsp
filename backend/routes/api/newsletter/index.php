<?php
$newsletterModel = new Newsletter();

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'getAll':
        $newsletters = $newsletterModel->getAll();
        echo json_encode($newsletters);
        break;

    case 'getById':
        $id = (int)($_POST['id'] ?? 0);
        $newsletter = $newsletterModel->getById($id);
        if ($newsletter) {
            echo json_encode($newsletter);
        } else {
            response("Newsletter not found !", 500);
        }
        break;

    case 'save':

        //email check
        $email =  isset($_POST['email']) ? $_POST['email'] : "N/A";
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $safe_email = $email;
        } else {
            response("Invalid email address.", 400);
            exit;
        }

        //date check
        $publish_date = isset($_POST['publish_date']) ? $_POST['publish_date'] : (new DateTime())->format('Y-m-d');
        $date_check = DateTime::createFromFormat('Y-m-d', $publish_date);
        if (!$date_check || $date_check->format('Y-m-d') !== $publish_date) {
            response("Invalid publish date.", 400);
            exit;
        }
        $safe_date = $publish_date;

        // status check
        $status = isset($_POST['status']) ? $_POST['status'] : "N/A";
        $valid_status_options = ["newsletter-footer", "newsletter-home", "newsletter-contact"];
        if (in_array($status, $valid_status_options)) {
            $safe_status = $status;
        } else {
            response("Invalid status value.", 400);
            exit;
        }

        $data = [
            'email' => $safe_email,
            'nume' => default_input('nume'),
            'telefon' => default_input('telefon'),
            'domeniu' => default_input('domeniu'),
            'publish_date' => $safe_date,
            'mesaj' => default_input('mesaj'),
            'status' => $safe_status,
        ];

        $id = $_POST['id'] ?? null;
        if (!empty($id) && $id !== 'undefined') {
            $newsletterModel->update((int)$id, $data);
        } else {
            $newsletterModel->add($data);
        }

        response("Actualizare reusita !", 200);
        break;

    case 'delete':
        $id = (int)($_POST['id'] ?? 0);
        $newsletterModel->delete($id);
        response("Actualizare reusita !", 200);
        break;

    default:
        response("Invalid action !", status: 400);
        break;
}


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return substr($data, 0, 255);
}

function default_input($key)
{
    return array_key_exists($key, $_POST) ? test_input($_POST[$key]) : null;
}
