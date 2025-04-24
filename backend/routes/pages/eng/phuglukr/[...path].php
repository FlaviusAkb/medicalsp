<?php
require_once '../' . $_ENV["BACKEND"] . '/classes/model/widget/widget.php'; // Adjust path accordingly

$widgetModel = new Widget();

$path = implode('/', $_POST["path"]);
$widget = $widgetModel->getByUrl($path);
if ($widget) {

    $path = $_SERVER['DOCUMENT_ROOT'] . $_ENV["CURRENT_PATH"] . '\\upload\\hidden\\widgets\\' . $widget['file'];
    if (file_exists($path)) {
        // echo "The file $path exists";
        // exit();
    } else {
        header("Location: " . $_ENV["CURRENT_PATH"] . "/");
        exit();
    }
    $ext = strtolower(pathinfo($widget['file'], PATHINFO_EXTENSION));
    header("Content-type: " . $_ENV["formats_file"][$ext]);
    header("Content-Disposition: ; filename=" . basename($path));
    header("Content-Length: " . filesize($path));
    header("Cache-control: private");
    header("Pragma: public"); // IE issue work around
    readfile($path);
} else {
    // echo json_encode(['status' => 'error', 'message' => 'Widget not found']);
    header("Location: " . $_ENV["CURRENT_PATH"] . "/404");
    exit();
}
