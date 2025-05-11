<?php
session_start();
date_default_timezone_set('Europe/Bucharest'); // set the timezone to europe

require "vendor/autoload.php"; // autoload all the classes you still ned to use the use tho...

$dotenv = Dotenv\Dotenv::createImmutable("../" . $BACKEND);
$dotenv->load();

require "src/qof.php";
require "classes/Routing.php";
require "classes/Web.php";
require "classes/TwoFactorAuth.php";
require "classes/readFile.php";
require "classes/Auth.php";
require "classes/model/widget/Widget.php";
require "resources/languages/languages.php";


function mspButton($href = "#", $link_class = "px-[1em] py-[0.5em]", $action = null, $type = null, $text = "Abonează-te", $text_class = null)
{
    $href = htmlspecialchars($href, ENT_QUOTES, 'UTF-8');
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

    $link_safe_class = htmlspecialchars($link_class, ENT_QUOTES, 'UTF-8');
    $text_safe_class = htmlspecialchars($text_class, ENT_QUOTES, 'UTF-8');

    $link_default_class = 'relative inline-block text-msp-primary cursor-pointer text-center border-none transition-all duration-300 rounded-[3px] overflow-hidden group';
    $link_safe_class = trim("$link_default_class " . ($link_safe_class ?? ''));

    $text_default_class = "relative z-10 text-msp-primary";
    $text_safe_class = trim("$text_default_class " . ($text_safe_class ?? ''));

    $content = "
            <span class='$text_safe_class'>{$text}</span>
            <span class='absolute top-0 left-0 border-2 border-msp-primary transition-all duration-[0.6s] ease-in-out w-[15%] h-[25%] group-hover:w-[100%] group-hover:h-[100%] border-r-transparent border-b-transparent group-hover:border-b-msp-primary group-hover:border-r-msp-primary'></span>
            <span class='absolute bottom-0 right-0 border-2 border-msp-primary transition-all duration-[0.6s] ease-in-out w-[15%] h-[25%] group-hover:w-[100%] group-hover:h-[100%] border-l-transparent border-t-transparent group-hover:border-t-msp-primary group-hover:border-l-msp-primary'></span>";

    // If an action is provided, render a button
    if ($type) {
        $href = null;
        $onclick = $action ? "onclick='$action'" : "";
        return "<button $onclick type='$type' class='$link_safe_class'>$content</button>";
    } else {
        // Default: render as a link
        $action = null;
        $type = null;
        return "<a href='$href' class='$link_safe_class'>$content</a>";
    }
}


function generateBreadcrumbs($separator = ' / ')
{
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = trim($path, '/');
    $segments = explode('/', $path);

    $breadcrumbs = [];
    $link = '/';

    // Home link
    // $breadcrumbs[] = "<a href=\"admin\">$home</a>";

    foreach ($segments as $index => $segment) {
        $link .= $segment . '/';
        // Format segment: remove extension and capitalize
        $name = ucfirst(str_replace(['.php', '_'], ['', ' '], $segment));
        // If it's the last segment, no link
        if ($index === count($segments) - 1) {
            $breadcrumbs[] = $name;
        } else {
            $breadcrumbs[] = "<a href=\"$link\">$name</a>";
        }
    }

    return implode($separator, $breadcrumbs);
}


// UUID generator
function generateUUID(): string
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}
