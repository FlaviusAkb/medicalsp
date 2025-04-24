<?php
$currentURL = $_SERVER['REQUEST_URI'];
?>
<header id="navbar" class=" shadow-md h-[80px] lg:h-[190px] sticky top-0 right-0 left-0 z-50 transition-shadow duration-300 bg-msp-light
">
    <div class="h-full container w-11/12 mx-auto flex items-center justify-between lg:flex-row lg:items-center">

        <!-- Logo -->
        <a href="<?php echo $_ENV["CURRENT_PATH"] . $_SESSION["AL"] . '/'; ?>"
            class="h-[60px] w-[103px] bg-center bg-no-repeat bg-contain 
                   bg-[url('https://medicalsimulator.ro/wp-content/uploads/2020/04/Logo-MSP-FINAL-400.png')] md:h-[100px] lg:w-[185px] lg:h-[107px]">
        </a>

        <!-- Desktop Menu -->
        <?php
        $menuItems = [
            "Despre noi" => $_ENV["CURRENT_PATH"] . "/despre-noi",
            "Portofoliu" => $_ENV["CURRENT_PATH"] . "/portofoliu",
            "Contact" => $_ENV["CURRENT_PATH"] . "/contact",
        ];
        ?>



        <div class="flex-col justify-end items-center">
            <div class="hidden w-full justify-between gap-24 lg:flex">
                <div class="flex items-center gap-4">
                    <p><i class="fa-solid fa-phone text-msp-primary mr-2"></i><span>+40 213 485 272</span></p>
                    <div class="h-[13px] w-[1px] bg-msp-ui opacity-25">&nbsp;</div>
                    <p><i class="fa-solid fa-map-pin text-msp-primary mr-2"></i><span>Odobesti 1, Bucuresti</span></p>
                </div>
                <div class="flex gap-4 justify-center items-center md:order-3 md:w-1/3 lg:w-auto lg:order-4">
                    <a href="https://www.facebook.com/Medical-Simulator-Projects-1955016421259752/">
                        <i class="fab fa-facebook text-[20px] text-msp-primary hover:text-black hover:scale-110 transition-all duration-500 ease-in-out"></i>
                    </a>
                    <a href="https://www.linkedin.com/company/medical-simulator-projects?trk=public_profile_topcard_current_company">
                        <i class="fab fa-linkedin text-[20px] text-msp-primary hover:text-black hover:scale-110 transition-all duration-500 ease-in-out"></i>
                    </a>
                    <a href="/">
                        <i class="fab fa-youtube text-[20px] text-msp-primary hover:text-black hover:scale-110 transition-all duration-500 ease-in-out"></i>
                    </a>
                </div>
            </div>
            <hr class="hidden h-[3px] border-0 my-3 bg-msp-primary w-full lg:flex">
            <!-- MD Menu -->
            <nav class="hidden md:flex md:w-full md:items-center md:justify-end leading-[80px] tracking-[2px] cursor-pointer transition-[opacity,color] duration-[300ms] ease-in-out">
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
            </nav>







        </div>
        <!-- Hamburger Button -->
        <button id="menuBtn" class="md:hidden p-2 rounded-md focus:outline-none">
            <i class="fa-solid fa-bars text-[27px] text-msp-primary font-extrabold"></i>
        </button>

        <!-- Mobile Menu -->
        <nav id="mobileMenu" class="flex flex-col fixed top-0 right-0 h-full w-[160px] bg-white shadow-md transform translate-x-full transition-transform duration-300 ease-in-out  p-4 lg:hidden">
            <button id="closeMenuBtn" class="self-end p-2 focus:outline-none text-[18px] text-msp-primary font-roboto">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <?php foreach ($menuItems as $name => $link) :
                $isActive = (strpos($currentURL, $link) !== false) ? "text-msp-primary" : "text-msp-dark";
            ?>
                <a href="<?= htmlspecialchars($link); ?>" class="block py-3 px-4 text-[18px] font-extrabold font-roboto <?= $isActive ?>">
                    <?= htmlspecialchars($name); ?>
                </a>
            <?php endforeach; ?>
        </nav>
</header>

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