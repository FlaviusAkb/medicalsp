<?php

class Web extends Routing
{
    /**
     * Overrides parseRoute to load web pages.
     */
    public static function parseRoute($request, $folder)
    {
        $pathSegments = explode("/", $request);

        $directory = "../" . $_ENV["VIEWSFOLDER"] . $folder; // Your directory path
        if (!is_dir($directory)) {
            http_response_code(500);
            echo json_encode(["Framework not installed properly, page route not found"]);
            exit();
        }

        $params = [];
        $result = self::findRoute($pathSegments, $directory, $params);


        if ($result) {
            require $result;
            exit();
        } else {
            return false;
        }
    }

    /**
     * Handles page not found.
     */
    public static function pageNotFound($folder, $language)
    {
        http_response_code(404);
        $notFoundPath = "../" . $_ENV["VIEWSFOLDER"] . $folder . "/" . $language . "/notFound.php";

        if (file_exists($notFoundPath)) {
            require $notFoundPath;
            exit();
        } else {
            return false;
        }
    }
}
