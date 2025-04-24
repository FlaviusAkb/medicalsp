<?php
class Routing
{
    public static function parseRoute($request, $folder)
    {
        $pathSegments = explode("/", $request);
        array_shift($pathSegments); //remove first element ( that actually is "api")

        $directory = "../" . $_ENV["VIEWSFOLDER"] . $folder; // Your directory path
        if (!is_dir($directory)) {
            http_response_code(500);
            echo json_encode(["Framework not installed properly, api route not found"]);
            exit();
        }

        // Array to hold any dynamic parameters extracted from the URL.
        $params = [];
        // Resolve the route file from the URL segments and API directory.
        $result = self::findRoute($pathSegments, $directory, $params);
        if ($result) {
            include $result;
        } else {
            self::response("Route not found", 404);
        }
    }

    /**
     * Recursively resolve a route file based on URL segments and collect middleware.
     *
     * @param array  $segments    The URL segments to match.
     * @param string $directory   The current directory to search in.
     * @param array  &$params     Collected dynamic parameters.
     * @return string|null        The path of the file if found, or null if no match.
     */
    public static function findRoute(array $segments, string $directory, array &$params = []): ?string
    {
        // Check middleware
        $middlewareFile = rtrim($directory, '/') . '/_middleware.php';
        if (file_exists($middlewareFile)) {
            include $middlewareFile;
        }

        if (empty($segments)) {
            $indexFile = rtrim($directory, '/') . '/index.php';
            return file_exists($indexFile) ? $indexFile : null;
        }

        $currentSegment = array_shift($segments);

        // 1. Check for a literal directory match
        $literalDir = rtrim($directory, '/') . '/' . $currentSegment;
        if (is_dir($literalDir)) {
            return self::findRoute($segments, $literalDir, $params);
        }

        // 2. Check for a literal file match
        $literalFile = rtrim($directory, '/') . '/' . $currentSegment . '.php';
        if (file_exists($literalFile) && empty($segments)) {
            return $literalFile;
        }

        // **3.5. Check for mixed literal-dynamic matches BEFORE pure dynamic matches**
        $files = scandir($directory);
        foreach ($files as $file) {
            if (preg_match('/^([a-zA-Z0-9_-]+)\[([a-zA-Z0-9_]+)\]\.php$/', $file, $matches)) {
                $literalPart = $matches[1];
                $paramName = $matches[2];

                if (strpos($currentSegment, $literalPart) === 0) {
                    $dynamicValue = substr($currentSegment, strlen($literalPart));
                    if ($dynamicValue !== '') {
                        $params[$paramName] = $dynamicValue;
                        $_POST[$paramName] = $dynamicValue; // Make available in $_POST
                        $mixedFile = rtrim($directory, '/') . '/' . $file;
                        return empty($segments) ? $mixedFile : self::findRoute($segments, $directory, $params);
                    }
                }
            }
        }

        //  **3. Check for pure dynamic matches `[id].php]` AFTER mixed matches**
        foreach ($files as $file) {
            if (preg_match('/^\[([a-zA-Z0-9_]+)\]\.php$/', $file, $matches)) {
                $paramName = $matches[1];
                $params[$paramName] = $currentSegment;
                $_POST[$paramName] = $currentSegment; // Make available in $_POST
                return empty($segments) ? rtrim($directory, '/') . '/' . $file : self::findRoute($segments, $directory, $params);
            }
        }

        // 4. Check for catch-all dynamic routes (`[...params].php`)
        foreach ($files as $file) {
            if (preg_match('/^\[\.\.\.([a-zA-Z0-9_]+)\]\.php$/', $file, $matches)) {
                $paramName = $matches[1];
                $params[$paramName] = array_merge([$currentSegment], $segments);
                $_POST[$paramName] = $params[$paramName]; // Make available in $_POST
                return rtrim($directory, '/') . '/' . $file;
            }
            if (preg_match('/^\[\[\.\.\.([a-zA-Z0-9_]+)\]\]\.php$/', $file, $matches)) {
                $paramName = $matches[1];
                $params[$paramName] = array_merge([$currentSegment], $segments);
                $_POST[$paramName] = $params[$paramName]; // Make available in $_POST
                return rtrim($directory, '/') . '/' . $file;
            }
        }

        return null;
    }


    static function response($text, $status = 200, $header = "Content-Type: application/json")
    {
        header($header);
        $response["status"] = $status;
        $response["message"] = $text;
        echo json_encode($response);
        exit();
    }
}
