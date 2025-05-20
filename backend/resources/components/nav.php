<?php
$currentURL = $_SERVER['REQUEST_URI'];
?>
<header id="navbar" class=" shadow-md h-[80px] lg:h-[190px] sticky top-0 right-0 left-0 z-50 transition-shadow duration-300 bg-msp-light
">
    <div class="h-full container w-11/12 mx-auto flex items-center justify-between lg:flex-row lg:items-center">

        <?php $imgPath = $_ENV['CURRENT_PATH'] . "/upload/siteMedia/originalLogo.png"; ?>
        <!-- Logo -->
        <a href="<?php echo $_ENV["CURRENT_PATH"] . $_SESSION["AL"] . '/'; ?>"
            class="h-[60px] w-[103px] bg-center bg-no-repeat bg-contain md:h-[100px] lg:w-[185px] lg:h-[107px]" style="background-image:url('<?php echo $imgPath;; ?>')">
        </a>

        <!-- Desktop Menu -->
        <?php

        $menuItems = [];

        $menuItems = [
            "Despre noi" => $_ENV["CURRENT_PATH"] . "/despre-noi",
            "Portofoliu" => $_ENV["CURRENT_PATH"] . "/portofoliu",
            "Contact" => $_ENV["CURRENT_PATH"] . "/contact",
        ];
        if (!array_key_exists("id", $_SESSION)) {
            $menuItems["Login"] = $_ENV["CURRENT_PATH"] . "/login";
        }
        if (array_key_exists("id", $_SESSION)) {
            $menuItems["Admin"] = $_ENV["CURRENT_PATH"] . "/admin";
        }
        ?>

        <div class="flex-col justify-end items-center">
            <div class="hidden w-full justify-between gap-24 lg:flex">
                <div class="flex items-center gap-4">
                    <div class="flex gap-1 items-center">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" width="20px">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier" class="fill-msp-primary">
                                <path d="M16.5562 12.9062L16.1007 13.359C16.1007 13.359 15.0181 14.4355 12.0631 11.4972C9.10812 8.55901 10.1907 7.48257 10.1907 7.48257L10.4775 7.19738C11.1841 6.49484 11.2507 5.36691 10.6342 4.54348L9.37326 2.85908C8.61028 1.83992 7.13596 1.70529 6.26145 2.57483L4.69185 4.13552C4.25823 4.56668 3.96765 5.12559 4.00289 5.74561C4.09304 7.33182 4.81071 10.7447 8.81536 14.7266C13.0621 18.9492 17.0468 19.117 18.6763 18.9651C19.1917 18.9171 19.6399 18.6546 20.0011 18.2954L21.4217 16.883C22.3806 15.9295 22.1102 14.2949 20.8833 13.628L18.9728 12.5894C18.1672 12.1515 17.1858 12.2801 16.5562 12.9062Z"></path>
                            </g>
                        </svg>

                        <span>+40 213 485 272</span>
                    </div>
                    <div class="h-[13px] w-[1px] bg-msp-ui opacity-25">&nbsp;</div>
                    <div class="flex gap-1 items-center">

                        <svg height="20px" width="20px" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" class="fill-msp-primary">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">

                                <g>
                                    <path d="M256.102,275.689c-13.136,0-26.131-2.015-38.62-5.994l-2.073-0.66v203.275c0,5.792,2.199,11.289,6.227,15.512 l17.453,17.216c4.276,4.488,10.047,6.96,16.246,6.96c6.202,0,11.97-2.473,16.211-6.926l17.525-17.286 c3.992-4.192,6.19-9.685,6.19-15.477V269.556l-2.052,0.625C281.193,273.838,268.709,275.689,256.102,275.689z"></path>
                                    <path d="M255.999,0c-68.961,0-125.063,56.106-125.063,125.069c0,68.959,56.102,125.065,125.063,125.065 s125.065-56.106,125.065-125.065C381.064,56.106,324.96,0,255.999,0z M217.269,126.047c-20.052,0-36.366-16.312-36.366-36.366 c0-20.05,16.314-36.362,36.366-36.362s36.366,16.312,36.366,36.362C253.635,109.735,237.321,126.047,217.269,126.047z"></path>
                                </g>
                            </g>
                        </svg>


                        <span>Odobești 1, București</span>
                    </div>
                </div>
                <div class="flex gap-4 justify-center items-center md:order-3 md:w-1/3 lg:w-auto lg:order-4">
                    <?php socials_icons(reversed: true); ?>
                </div>
            </div>
            <hr class="hidden h-[3px] border-0 my-3 bg-msp-primary w-full lg:flex">
            <!-- MD Menu -->
            <nav class="hidden md:flex md:w-full md:items-center md:justify-end leading-[80px] tracking-[2px] cursor-pointer transition-[opacity,color] duration-[300ms] ease-in-out ">
                <?php foreach ($menuItems as $name => $link) :
                    $isActive = (strpos($currentURL, $link) !== false);
                    $isTextColor = $isActive ? "text-msp-primary" : "text-msp-dark group-hover:text-msp-primary";
                ?>
                    <a href="<?= htmlspecialchars($link); ?>"
                        class="relative block px-4 py-3 group transition-all duration-500 ease-in-out text-[15px]">

                        <!-- Border layer -->
                        <span class="absolute inset-0 border-2 pointer-events-none transition-all duration-500 ease-in-out
                <?= $isActive ? 'border-msp-primary scale-100' : 'border-[#DD4949] scale-0 group-hover:scale-100' ?>">
                        </span>

                        <!-- Text with color applied directly -->
                        <span class="relative z-10 font-medium font-roboto text-base <?= $isTextColor ?>">
                            <?= htmlspecialchars($name); ?>
                        </span>
                    </a>
                <?php endforeach; ?>
                <?php
                if (array_key_exists("id", $_SESSION)) { ?>
                    <a href="/logout" class="bg-msp-primary text-white px-4 py-2 rounded ml-4 hover:bg-red-600 transition delay-150 duration-300 ease-in-out hover:-translate-y-1 cursor-pointer">Logout</a>
                <?php
                }
                ?>
            </nav>
        </div>
        <!-- Hamburger Button -->
        <button id="menuBtn" class="cursor-pointer p-2 rounded-md w-[50px] focus:outline-none md:hidden">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#dd4949">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path d="M4 18L20 18" stroke="#00000" stroke-width="3" stroke-linecap="round"></path>
                    <path d="M4 12L20 12" stroke="#00000" stroke-width="3" stroke-linecap="round"></path>
                    <path d="M4 6L20 6" stroke="#00000" stroke-width="3" stroke-linecap="round"></path>
                </g>
            </svg>
        </button>

        <!-- Mobile Menu -->
        <nav id="mobileMenu" class="flex flex-col fixed top-0 right-0 h-full w-[160px] bg-white shadow-md transform translate-x-full transition-transform duration-300 ease-in-out p-4 lg:hidden">
            <button id="closeMenuBtn" class="self-end p-2 focus:outline-none text-[18px] text-msp-primary font-roboto cursor-pointer">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#dd4949">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <?php foreach ($menuItems as $name => $link) :
                $isActive = (strpos($currentURL, $link) !== false) ? "text-msp-dark" : "text-msp-primary";
            ?>
                <a href="<?= htmlspecialchars($link); ?>" class="block py-3 px-4 text-[18px] font-extrabold font-roboto <?= $isActive ?>">
                    <?= htmlspecialchars($name); ?>
                </a>

                <?php if ($name === "Log out") { ?>
                    <a href="/logout" class="bg-msp-primary text-white px-4 py-2 rounded hover:bg-red-600 transition delay-150 duration-300 ease-in-out hover:-translate-y-1 cursor-pointer">Logout</a>
                <?php } ?>
            <?php endforeach; ?>
        </nav>

</header>
<?php
/*
#####################################           /admin/
*/
if (array_key_exists("id", $_SESSION)) {
?>
    <div class="flex">
        <nav class="flex w-10/12 mx-auto justify-between items-center py-4 md:hidden">
            <div class="text-sm text-gray-500 space-x-1 w-full">
                <?php echo generateBreadcrumbs(' <span class="text-gray-400">/</span> '); ?>
            </div>
        </nav>
    </div>
<?php }

?>


<!-- JavaScript to Toggle Shadow on Scroll -->
<script>
    const navbar = document.getElementById("navbar");

    window.addEventListener("scroll", () => {
        if (window.scrollY > 10) {
            navbar.classList.add("shadow-lg");
        } else {
            navbar.classList.remove("shadow-lg");
        }
    });

    // Mobile Menu Toggle
    const menuBtn = document.getElementById("menuBtn");
    const closeMenuBtn = document.getElementById("closeMenuBtn");
    const mobileMenu = document.getElementById("mobileMenu");

    menuBtn.addEventListener("click", () => {
        mobileMenu.classList.remove("translate-x-full");
        mobileMenu.classList.add("translate-x-0");
    });

    closeMenuBtn.addEventListener("click", () => {
        mobileMenu.classList.remove("translate-x-0");
        mobileMenu.classList.add("translate-x-full");
    });
</script>