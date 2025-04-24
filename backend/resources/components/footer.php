<footer class="flex w-full bg-msp-dark">
    <div class="flex container mx-auto flex-col w-full bg-msp-dark text-center font-roboto justify-center items-center">
        <hr class=" w-10/12 h-[1px] text-msp-ui mt-16 bg-white opacity-25 md:w-11/12">
        <div class="flex flex-col w-10/12 mx-auto mb-[20px] mt-[10px] text-[#818181] font-light tracking-[0.04em] justify-between items-center leading-[18px] md:flex-row md:flex-wrap md:w-11/12 lg:flex-nowrap">

            <a href="/" class="text-[13px] md:order-4 md:w-full lg:order-1 lg:w-auto">Copyright Medical Simulator Projects, Inc. All Rights Reserved.</a>
            <div class="my-4 md:order-2 md:w-1/3 lg:w-auto">
                <?php echo mspButton(text: "Aboneaza-te"); ?>
            </div>
            <div class="flex gap-1 justify-center items-center md:order-1 md:w-1/3 lg:order-3  lg:w-auto">
                <a href="<?php $_ENV["CURRENT_PATH"] . '/contact' ?>" class="text-[13px] ">Contact</a>
                <div class="h-[13px] w-[2px] bg-msp-ui">&nbsp;</div>
                <a href="<?php $_ENV["CURRENT_PATH"] . '/gdpr' ?>" class="text-[13px]">GDPR</a>
                <div class="h-[13px] w-[2px] bg-msp-ui">&nbsp;</div>
                <a href="<?php $_ENV["CURRENT_PATH"] . '/cookies' ?>" class="text-[13px]">Cookie</a>
            </div>
            <div class="flex mt-2 gap-4 justify-center items-center md:order-3 md:w-1/3 lg:w-auto lg:order-4">
                <a href="https://www.facebook.com/Medical-Simulator-Projects-1955016421259752/"><i class="fab fa-facebook text-[20px] hover:text-msp-primary transition-all duration-500 ease-in-out"></i></a>
                <a href="https://www.linkedin.com/company/medical-simulator-projects?trk=public_profile_topcard_current_company"><i class="fab fa-linkedin text-[20px] hover:text-msp-primary transition-all duration-500 ease-in-out"></i></a>
                <a href="/"><i class="fab fa-youtube text-[20px] hover:text-msp-primary transition-all duration-500 ease-in-out"></i></a>
            </div>
        </div>
    </div>
</footer>