<?php

require_once '../' . $_ENV["BACKEND"] . '/classes/model/widget/widget.php';

$widgetModel = new Widget();

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'getAll':
        $widgets = $widgetModel->getAll();
        echo json_encode($widgets);
        break;

    case 'getById':
        $id = $_POST['id'];
        $widget = $widgetModel->getById($id);
        if ($widget) {
            echo json_encode($widget);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Widget not found']);
        }
        break;

    case 'save':

        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/upload/hidden/widgets/';
        $uploadedFilePath = null;
        $imageUrl = null;
        $newFileName = null;
        $id = $_POST['id'];
        $existingWidget = $widgetModel->getById($id);


        // Check if a file was uploaded
        if (!empty($_FILES['image_file']['name'])) {
            $fileName = basename($_FILES['image_file']['name']);
            $formattedFileName = str_replace(' ', '-', $fileName);
            $timestamp = time();

            $date = new DateTime($_POST['publish_date']);
            $year = $date->format('Y');
            $month = $date->format('m');

            $newFileName = $timestamp . '-' . $formattedFileName;
            $targetFilePath = $uploadDir . $newFileName;

            // Validate file type (only image allowed)
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array(strtolower($fileType), $allowedTypes)) {
                echo json_encode(['status' => 'error', 'message' => 'Only image files are allowed']);
                exit;
            }

            // Move the uploaded file to the designated folder
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetFilePath)) {
                $uploadedFilePath = $targetFilePath;
                $imageUrl = '/upload/hidden/widgets/' . $newFileName;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload file']);
                exit;
            }
        }

        $data = [
            'id' => $id,
            'title' => $_POST['title'] ?: $existingWidget['title'],
            'website_url' => $_POST['website_url'] ?: $existingWidget['website_url'],
            'notes' => $_POST['notes'] ?: $existingWidget['notes'],
            'publish_date' => $_POST['publish_date'] ?: $existingWidget['publish_date'],
            'position' => isset($_POST['position']) ? $_POST['position'] : $existingWidget['position'],
            'status' => isset($_POST['status']) ? $_POST['status'] : $existingWidget['status'],
            'image_url' => $imageUrl ?: $existingWidget['image_url'],
        ];

        if (isset($_POST['id']) && $_POST['id'] !== 'undefined' && !empty($_POST['id'])) {
            $id = $_POST['id'];
            $widgetModel->update($id, $data);
        } else {
            $widgetModel->add($data);
        }

        echo json_encode(['status' => 'success']);
        break;


    case 'delete':
        $id = $_POST['id'];
        $widgetModel->delete($id);
        echo json_encode(['status' => 'success']);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}
