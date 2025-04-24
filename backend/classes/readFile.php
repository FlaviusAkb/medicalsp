<?php

class readFile
{
    public static $filesFormat =  array(
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'eps' => 'application/postscript',
        'gif' => 'image/gif',
        'gz' => 'application/x-gzip',
        'html' => 'text/html',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'mp3' => 'audio/mpeg',
        'mp4' => 'video/mp4',
        'pdf' => 'application/pdf',
        'png' => 'image/png',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'ps' => 'application/postscript',
        'rtf' => 'text/rtf',
        'tar' => 'application/tar',
        'txt' => 'text/plain',
        'wp' => 'application/wordperfect',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'xml' => 'application/xml',
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'webm' => 'video/webm',
        'webp' => 'image/webp'
    );

    public static function parse()
    {


        $chosenProcess = explode("/", $_SESSION["REQUEST"]);



        $extraPath = "";
        if (isset($_GET["path"]))
            $extraPath = str_replace(".", "/", $_GET["path"]);
        $path =  'upload/hidden/' . $extraPath . $chosenProcess[1];


        // these 2 ifs so no matter where you save the folder backend or frontend it switches automatically
        if (file_exists('../' . $_ENV['BACKEND'] . '/' . $path)) {
            $path = '../' . $_ENV['BACKEND'] . '/' . $path;
        }
        if (file_exists('../' . $_ENV['PUBLIC_FOLDER'] . '/' . $path)) {
            $path = '../' . $_ENV['PUBLIC_FOLDER'] . '/' . $path;
        }



        if (file_exists($path)) {
            $ext = strtolower(pathinfo($chosenProcess[1], PATHINFO_EXTENSION));
            // Check if the extension has a defined MIME type
            $mimeType = self::$filesFormat[$ext] ?? 'application/octet-stream';

            error_log("Before filesize check for: $path");
            $size = filesize($path);
            error_log("File size: $size");

            header("Content-Type: $mimeType");
            header("Content-Disposition: inline; filename=" . basename($path)); // Use 'attachment' for downloads
            header("Content-Length: " . filesize($path));
            header("Cache-Control: public, max-age=3600");
            header("Pragma: public"); // Workaround for IE caching issues

            if (!is_readable($path)) {
                http_response_code(403); // Forbidden
                readFile::get404picture();
                exit();
            }
            $bytesRead = readfile($path);
            //readfile($path);
            exit();
        } else {
            http_response_code(404);
            // echo 'Se pare că fisierul dvs nu există';
            readFile::get404picture();
            exit();
        }
        exit();
    }

    static function get404picture()
    {
        // basicError("500 - operation unsuccessful");
        $path = 'upload/siteMedia/404.png';
        if (file_exists($path)) {
            // echo "The file $path exists";
        } else {
            http_response_code(404);
            echo 'Se pare că fisierul dvs nu există';
            exit();
        }
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        header("Content-type: " . self::$filesFormat[$ext]);
        header("Content-Disposition: ; filename=" . basename($path));
        header("Content-Length: " . filesize($path));
        header("Cache-control: private");
        header("Pragma: public"); // IE issue work around
        http_response_code(404);
        readfile($path);
    }
}
