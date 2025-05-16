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

        //spam prevention
        $form_time = isset($_POST['form_time']) ? (int)$_POST['form_time'] : 0;
        $current_time = time();
        $last_submit_time = $_SESSION['last_submit_time'] ?? 0;

        if ($form_time === 0 || ($current_time - $form_time) < 5) {
            response("Form submitted too quickly. Please wait a few seconds.", 429);
            exit;
        }

        // Optional: block if last submit was less than 5 seconds ago
        if ($last_submit_time > 0 && ($current_time - $last_submit_time) < 5) {
            response("Please wait before submitting again.", 429);
            exit;
        }

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

        // source check
        $source = isset($_POST['source']) ? $_POST['source'] : "N/A";
        $valid_source_options = ["newsletter-footer", "newsletter-home", "newsletter-contact"];
        if (in_array($source, $valid_source_options)) {
            $safe_source = $source;
        } else {
            response("Invalid source value.", 400);
            exit;
        }

        $data = [
            'email' => $safe_email,
            'nume' => default_input('nume'),
            'telefon' => default_input('telefon'),
            'domeniu' => default_input('domeniu'),
            'publish_date' => $safe_date,
            'mesaj' => default_input('mesaj'),
            'source' => $safe_source,
        ];

        $id = $_POST['id'] ?? null;
        if (!empty($id) && $id !== 'undefined') {
            $newsletterModel->update((int)$id, $data);
        } else {
            $newsletterModel->add($data);
        }
        $_SESSION['last_submit_time'] = time();
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
