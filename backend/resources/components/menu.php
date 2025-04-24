<?php

$menu = [
    [
        "name" => ["ro" => "Acasa", "eng" => "Home"],
        "url" => ["/"],
        "options" => []
    ],
    [
        "name" => ["ro" => "", "eng" => "Editorial board"],
        "url" => ["/"],
        "options" => []
    ],
    [
        "name" => ["ro" => "", "eng" => "Publication Ethics"],
        "url" => ["/"],
        "options" => []
    ],
    [
        "name" => ["ro" => "", "eng" => "Instructions for authors"],
        "url" => ["/"],
        "options" => []
    ],
    [
        "name" => ["ro" => "", "eng" => "Fees"],
        "url" => ["/"],
        "options" => []
    ],
    [
        "name" => ["ro" => "", "eng" => "Open access"],
        "url" => ["/"],
        "options" => []
    ],
    [
        "name" => ["ro" => "", "eng" => "Contact"],
        "url" => ["/"],
        "options" => []
    ],
];

class Menu
{
    public $menu;
    public $currentLang;

    public function __construct($menu, $currentLang)
    {
        $this->menu = $menu;
        $this->currentLang = $currentLang;
    }

    function activePage($nameList)
    {
        return in_array($_SESSION["REQUEST"], $nameList) ? 'text-msp-primary' : 'text-msp-dark';
    }

    public function makeA($value)
    {
        $url = array_key_exists('url', $value) && count($value["url"]) > 0 && trim($value["url"][0]) != "" ? urlLang($this->currentLang, $value["url"]) : "#";

        return '<a href="' . htmlspecialchars($url) . '" 
                    class="block px-4 py-2 hover:text-msp-primary ' . $this->activePage($value["url"]) . '">
                    ' . localDic($value["name"]) . '
                </a>';
    }

    function languageSwitch()
    {
        $languages = json_decode($_ENV["LANGUAGES"]);
        $langSwitch = '<div class="relative group">';
        $langSwitch .= '<button class="p-2 rounded-md hover:bg-gray-100 transition">
                        🌍
                      </button>';
        $langSwitch .= '<div class="absolute left-0 mt-2 w-24 bg-white shadow-md rounded-md hidden group-hover:block">';

        foreach ($languages as $value) {
            if (trim(strval($value)) != trim(strval($_SESSION["LANG"]))) {
                $langSwitch .= '<a href="' . $_ENV["CURRENT_PATH"] . "/" . (trim($value) == $_ENV["DEFAULTLANG"] ? "" : $value . "/") . $_SESSION["REQUEST"] . $_SESSION["requestParameters"] . '"
                                class="block px-4 py-2 hover:bg-gray-100">
                                <img src="' . $_ENV["CURRENT_PATH"] . '/upload/siteMedia/flags/' . $value . '.svg" class="w-5 h-5 inline-block mr-2"> 
                                ' . strtoupper($value) . '
                              </a>';
            }
        }

        $langSwitch .= '</div></div>';
        return $langSwitch;
    }

    public function createMenu()
    {
        echo '<nav class="bg-white shadow-md h-[60px] lg:h-[80px] sticky top-0 z-50 transition-shadow duration-300">';
        echo '<div class="container mx-auto px-4 flex justify-between items-center h-full">';

        // Mobile Menu Button
        echo '<button id="menuBtn" class="lg:hidden p-2 rounded-md focus:outline-none">
                <svg class="w-6 h-6 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
              </button>';

        // Desktop Menu
        echo '<div class="hidden lg:flex space-x-6 uppercase text-[14px] font-bold tracking-wide">';
        foreach ($this->menu as $value) {
            echo $this->makeA($value);
        }
        echo '</div>';

        // Language Switch
        echo $this->languageSwitch();

        echo '</div>';

        // Mobile Menu
        echo '<div id="mobileMenu" class="hidden lg:hidden bg-white shadow-md absolute top-full left-0 w-full">';
        foreach ($this->menu as $value) {
            echo $this->makeA($value);
        }
        echo '</div>';

        echo '</nav>';
    }
}

?>

<!-- JavaScript for Toggle Menu -->
<script>
    const menuBtn = document.getElementById("menuBtn");
    const mobileMenu = document.getElementById("mobileMenu");

    menuBtn.addEventListener("click", () => {
        mobileMenu.classList.toggle("hidden");
    });

    // Shadow on scroll
    const navbar = document.querySelector("nav");
    window.addEventListener("scroll", () => {
        if (window.scrollY > 10) {
            navbar.classList.add("shadow-lg");
        } else {
            navbar.classList.remove("shadow-lg");
        }
    });
</script>