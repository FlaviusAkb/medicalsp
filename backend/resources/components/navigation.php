<?php

?>
<div class="menu master">
    <nav id="mkNav" class="mkNav bg-white shadow-md fixed top-0 left-0 w-full z-50 flex items-center justify-between px-6 py-4">
        <a href="<?php echo $_ENV["CURRENT_PATH"] . $_SESSION["AL"] . '/'; ?>" class="logo">
            <?php echo $_ENV["svgLogo"]; ?>
        </a>

        <div class="hT hmTrig usn flex items-center space-x-4">
            <div class="goTo text-gray-700 font-bold cursor-pointer">Go to...</div>
            <button id="menuBtn" class="hamburger_trigger p-2 rounded-md focus:outline-none">
                <svg class="w-6 h-6 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>

        <?php
        include "menu.php";
        (new Menu($menu, $_SESSION["AL"]))->createMenu();
        ?>
    </nav>
</div>

<script>
    document.getElementById("menuBtn").addEventListener("click", () => {
        document.getElementById("mkNav").classList.toggle("shadow-lg");
    });

    // Handle submenu positioning
    document.querySelectorAll(".menuOption").forEach(element => {
        element.addEventListener("mouseover", dynamicMenu);
        element.addEventListener("mousedown", dynamicMenu);
        element.addEventListener("touchstart", dynamicMenu);
    });

    function dynamicMenu(e) {
        let submenu = e.currentTarget.querySelector("ul.third");
        if (submenu) {
            let rect = e.currentTarget.getBoundingClientRect();
            submenu.classList.toggle("left-0", rect.right + rect.width > window.innerWidth);
        }
    }
</script>

<!-- Fls added -->




<?php
/*
include "../" . $_ENV["BACKEND"] . "/resources/components/nav.php"; 
*/
?>
<?php include "../" . $_ENV["BACKEND"] . "/resources/components/hero-image.php"; ?>