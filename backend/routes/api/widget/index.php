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

        $uploadDir = $_ENV["CURRENT_PATH"] . 'upload/hidden/widgets/';
        $uploadedFilePath = null;

        // Check if a file was uploaded
        if (!empty($_FILES['pdf_file']['name'])) {
            $fileName = basename($_FILES['pdf_file']['name']);
            $formattedFileName = str_replace(' ', '-', $fileName);
            $timestamp = time();

            $date = new DateTime($_POST['publish_date']);
            $year = $date->format('Y');
            $month = $date->format('m');

            $newFileName = $timestamp . '-' . $formattedFileName;
            $targetFilePath = $uploadDir . $newFileName;

            // Validate file type (only PDF allowed)
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            if (strtolower($fileType) !== 'pdf') {
                echo json_encode(['status' => 'error', 'message' => 'Only PDF files are allowed']);
                exit;
            }

            // Move the uploaded file to the designated folder
            if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $targetFilePath)) {
                $uploadedFilePath = $targetFilePath;
                $pdfUrl = $year . '/' . $month . '/' . $formattedFileName;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload file']);
                exit;
            }
        }

        $data = [
            'publish_date' => $_POST['publish_date'],
            'title' => $_POST['title'],
            'authors' => $_POST['authors'],
            'pages' => $_POST['pages'],
            'doi' => $_POST['doi'],
            'keywords' => $_POST['keywords'],
            'abstract' => $_POST['abstract'],
            'pdf_url' => $pdfUrl,
            'file' => $newFileName,
            'volume' => $_POST['volume'],
            'position' => $_POST['position'],
            'status' => $_POST['status'],
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
