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
        'rar' => 'application/x-rar-compressed'
    );

    public static function parse()
    {
        $chosenProcess = explode("/", $_SESSION["REQUEST"]);
        $conn = database::dbConnect($_ENV["DB_NAME"]);
        $collumn = ["id", "name", "storageName", "path", "status"];
        $where = [];
        array_push($where, [
            "key" => "storageName",
            "value" => $chosenProcess[1]
        ]);

        array_push($where, [
            "key" => "visibility",
            "value" => "public",
            "type" => "s"
        ]);


        $result = database::select($_ENV["UPLOAD_TABLE"], $conn, $collumn, $where);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row["status"] == 1) {
                $path = $row["path"] . '/' . $chosenProcess[1];
                // these 2 ifs so no matter where you save the folder backend or frontend it switches automatically
                if (file_exists('../' . $_ENV['BACKEND'] . '/' . $path)) {
                    $path = '../' . $_ENV['BACKEND'] . '/' . $path;
                }
                if (file_exists('../' . $_ENV['PUBLIC_FOLDER'] . '/' . $path)) {
                    $path = '../' . $_ENV['PUBLIC_FOLDER'] . '/' . $path;
                }

                if (file_exists($path)) {
                    $ext = strtolower(pathinfo($chosenProcess[1], PATHINFO_EXTENSION));
                    header("Content-type: " . self::$filesFormat[$ext]);
                    header("Content-Disposition: ; filename=" . basename($path));
                    header("Content-Length: " . filesize($path));
                    header("Cache-control: private");
                    header("Pragma: public"); // IE issue work around
                    readfile($path);
                } else {
                    http_response_code(404);
                    // echo 'Se pare că fisierul dvs nu există';
                    readFile::get404picture();
                }
            } else {
                // basicError("500 - operation unsuccessful");
                readFile::get404picture();
            }
            exit();
        } else {
            readFile::get404picture();
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
