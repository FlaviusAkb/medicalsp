<?php
error_reporting(E_ALL);
error_reporting(-1);
// ini_set('error_reporting', E_ALL);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

$BACKEND = "backend";
require "../$BACKEND/bootstrap.php"; // load your functions
$_ENV["BACKEND"] = "$BACKEND";
$_ENV["VIEWSFOLDER"] = $_ENV["BACKEND"] . "/" . $_ENV["VIEWSFOLDER"];



$request = str_ireplace($_ENV["CURRENT_PATH"], "", $_SERVER['REQUEST_URI']); // remove under site host origin
$reqAndParams = explode("?", $request); //explode to get 2 values => 0 for request path , 1 for parameters

$_SESSION["requestParameters"] = array_key_exists(1, $reqAndParams) ? "?" . $reqAndParams[1] : ""; // let it be

// elimiante slashes at start and end   -- start
$request = $reqAndParams[0];
$request = (substr($request, -1) == "/") ? substr($request, 0, -1) : $request; // if your request ends like this website.com/smth/ eliminate the / from the end
$request = (substr($request, 0, 1) == "/") ? substr($request, 1) : $request; // if your request starts like this /smth eliminate the / from the start
$requestExploded = explode("/", $request);
// elimiante slashes at start and end   -- end

// remove selected language from request - start
$defaultLanguage = $_ENV["DEFAULTLANG"]; // set default language
if (in_array(strtolower($requestExploded[0]), json_decode($_ENV["LANGUAGES"], true))) {
    // if your URL contains a language set it as default langauge for your request
    $defaultLanguage = strtolower($requestExploded[0]);

    $request = ''; // also remake your request without the language tag
    foreach (array_slice($requestExploded, 1) as $key => $value) { //remake request
        $request .= "$value/";
    }
    $request = (substr($request, -1) == "/") ? substr($request, 0, -1) : $request; // delete last / ( yea, you may need to revise this code)
}
// remove selected language from request - stop

$_SESSION["LANG"] = $defaultLanguage;
$_SESSION["REQUEST"] = $request;

$_SESSION["AL"] = ($_ENV["DEFAULTLANG"] !== $_SESSION["LANG"]) ? "/" . $_SESSION["LANG"] : "";
// AL- Actual language 
// keep it here cuz maybe you need the current language somewhere down in the page

///////// same for web   -FFFFFFFF





/// route parsing for api
if ($requestExploded[0] === "api") {
    Routing::parseRoute($request, "/api");
    exit();
}

// route parsing for general pages
$viewPages = '/pages';
if (!empty($_SESSION["LANG"]) && Web::parseRoute($request, $viewPages . "/" . $_SESSION["LANG"])) {
    // Route found sl
} elseif (!empty($_ENV["DEFAULTLANG"]) && Web::parseRoute($request, $viewPages . "/" . $_ENV["DEFAULTLANG"])) {
    // Route found in dl
} else {
    // No routes matched, handle the not found case
    if (!empty($_SESSION["LANG"]) && Web::pageNotFound($viewPages, $_SESSION["LANG"])) {
        // Try to get not found page in the sl
    } elseif (!empty($_ENV["DEFAULTLANG"]) && Web::pageNotFound($viewPages, $_ENV["DEFAULTLANG"])) {
        // Try to get not found page in the dl
    } else {
        http_response_code(404);
        echo ("404 - Page not found");
        exit();
    }
}


// in case of anything
http_response_code(404);
echo ("404 - Page not found");
exit();
