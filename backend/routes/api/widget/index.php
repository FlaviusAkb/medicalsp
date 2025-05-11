<?php

require_once '../' . $_ENV["BACKEND"] . '/classes/model/widget/widget.php';

$widgetModel = new Widget();
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'getAll':
        echo json_encode($widgetModel->getAll());
        break;

    case 'getById':
        $id = $_POST['id'];
        $widget = $widgetModel->getById($id);
        echo json_encode($widget ?: ['status' => 'error', 'message' => 'Widget not found']);
        break;

    case 'save':
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/upload/hidden/widgets/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $uploadedFilePath = null;
        $imageUrl = null;
        $newFileName = null;
        $id = $_POST['id'] ?? '';
        $existingWidget = $widgetModel->getById($id);

        if (!empty($_POST['remove_image']) && $_POST['remove_image'] === "1" && !empty($existingWidget['image_url'])) {
            $imagePath = $_SERVER['DOCUMENT_ROOT'] . parse_url($existingWidget['image_url'], PHP_URL_PATH);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $existingWidget['image_url'] = "";
        }

        if (!empty($_FILES['image_file']['name'])) {
            $fileName = basename($_FILES['image_file']['name']);
            $formattedFileName = str_replace(' ', '-', $fileName);
            $timestamp = time();

            try {
                $date = new DateTime($_POST['publish_date']);
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid publish date']);
                exit;
            }

            $newFileName = $timestamp . '-' . $formattedFileName;
            $targetFilePath = $uploadDir . $newFileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($fileType, $allowedTypes)) {
                echo json_encode(['status' => 'error', 'message' => 'Only image files are allowed']);
                exit;
            }

            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetFilePath)) {
                $uploadedFilePath = $targetFilePath;
                $imageUrl = '/upload/hidden/widgets/' . $newFileName;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload file']);
                exit;
            }
        }

        $widgets = $widgetModel->getAll();
        $widgetsNo = count($widgets);

        // Check if update or add
        $isUpdate = !empty($existingWidget);
        if (!$isUpdate) {
            $id = generateUuid();
        }

        // Create data array for saving
        $data = [
            'id' => $id,
            'title' => $_POST['title'] ?? ($existingWidget['title'] ?? ''),
            'website_url' => $_POST['website_url'] ?? ($existingWidget['website_url'] ?? ''),
            'notes' => $_POST['notes'] ?? ($existingWidget['notes'] ?? ''),
            'publish_date' => $_POST['publish_date'] ?? ($existingWidget['publish_date'] ?? ''),
            'position' => $existingWidget['position'] ?? $widgetsNo,
            'status' => intval($_POST['status'] ?? ($existingWidget['status'] ?? 1)),
            'image_url' => $imageUrl ?? ($existingWidget['image_url'] ?? ''),
        ];

        // Update or add widget
        if ($isUpdate) {
            $widgetModel->update($id, $data);
        } else {
            $widgetModel->add($data);
        }

        // Recalculate positions after adding
        $widgetModel->recalculatePositions();

        echo json_encode(['status' => 'success']);
        break;

    case 'reorder':
        $order = json_decode($_POST['order'], true);
        if (!is_array($order)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid order format']);
            exit;
        }

        $allWidgets = $widgetModel->getAll();
        $indexedWidgets = [];

        // Index widgets by UUID
        foreach ($allWidgets as $widget) {
            $indexedWidgets[$widget['id']] = $widget;
        }

        // Update positions
        foreach ($order as $item) {
            $id = $item['id'];
            $position = intval($item['position']);

            if (isset($indexedWidgets[$id])) {
                $indexedWidgets[$id]['position'] = $position;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Widget ID not found: ' . $id]);
                exit;
            }
        }

        // Sort and save
        usort($indexedWidgets, fn($a, $b) => $a['position'] <=> $b['position']);
        $widgetModel->saveAll(array_values($indexedWidgets));

        echo json_encode(['status' => 'success']);
        break;

    case 'delete':
        $id = $_POST['id'];
        $existingWidget = $widgetModel->getById($id);

        // Delete the widget
        $widgetModel->delete($id);

        // Recalculate the positions of the remaining widgets
        $widgetModel->recalculatePositions();

        // Remove the image if it exists
        if (!empty($existingWidget['image_url'])) {
            $imagePath = $_SERVER['DOCUMENT_ROOT'] . parse_url($existingWidget['image_url'], PHP_URL_PATH);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        echo json_encode(['status' => 'success']);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}
