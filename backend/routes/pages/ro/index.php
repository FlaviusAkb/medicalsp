<?php
include "../" . $_ENV["BACKEND"] . "/classes/seo.php";

?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION["LANG"]; ?>">

<head>
    <?php
    $seo->setOgDescription(
        localDic([
            "ro" => 'Exemplu de a scrie metadata',
            "eng" => 'Custom example of setting meta data',
        ])
    );
    require "../" . $_ENV["BACKEND"] . "/resources/components/head.php"; ?>
</head>

<body>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/nav.php"; ?>


    <div class="flex flex-col container w-11/12 mt-[50px] mb-0 mx-auto items-top justify-start xlg:max-w-full text-black">
        <div class="flex flex-col-reverse items-center w-full md:flex-row md:gap-4">
            <div class="flex flex-col gap-4 justify-center text-center items-center w-11/12 md:w-2/5">
                <h2 class="mb-2 md:my-4 font-medium typewriter-text">
                    Abonează-te!
                </h2>
                <p class="text-lg mb-4">Îți trimitem cele mai noi informații despre simulatoarele medicale!</p>
                <form id="formAbonat" class="flex flex-col gap-6 w-full md:w-9/12" method="POST" onsubmit="validateForm(event)">
                    <input class="w-full bg-transparent placeholder:text-msp-placeholder-gray text-black text-xs rounded-full px-3 py-3 transition duration-300 ease shadow-neumorphic focus:shadow-none focus:outline-none focus:border border-slate-200 md:py-2 placeholder:text-sm" required name="email" placeholder="Email*" type="email" />
                    <input class="w-full bg-transparent placeholder:text-msp-placeholder-gray text-black text-xs rounded-full px-3 py-3 transition duration-300 ease shadow-neumorphic focus:shadow-none focus:outline-none focus:border border-slate-200 md:py-2 placeholder:text-sm" name="nume" placeholder="Nume (Optional)" type="text" />
                    <input class="w-full bg-transparent placeholder:text-msp-placeholder-gray text-black text-xs rounded-full px-3 py-3 transition duration-300 ease shadow-neumorphic focus:shadow-none focus:outline-none focus:border border-slate-200 md:py-2 placeholder:text-sm" name="telefon" placeholder="Telefon (Optional)" type="tel" />
                    <input class="w-full bg-transparent placeholder:text-msp-placeholder-gray text-black text-xs rounded-full px-3 py-3 transition duration-300 ease shadow-neumorphic focus:shadow-none focus:outline-none focus:border border-slate-200 md:py-2 placeholder:text-sm" name="domeniu" placeholder="Domeniul de activitate (Optional)" type="text" />
                    <?= mspButton(text: "Trimite", type: 'submit', link_class: "px-[0.5em] py-[0.5em] text-lg w-1/3 mt-4 self-center md:w-3/4 lg:w-2/4"); ?>
                </form>
            </div>



            <?php
            $imgPath = $_ENV['CURRENT_PATH'] . "/upload/siteMedia/fundal-cu-oameni-1.webp";
            $imgPath2 = $_ENV['CURRENT_PATH'] . "/upload/siteMedia/inima-1.webp"
            ?>
            <div class="flex flex-col w-full justify-center items-center md:w-3/5 h-full">
                <div class="block relative bg-no-repeat bg-contain
             z-1 w-full h-full min-h-[290px] md:min-h-[300px] md:w-full md:bg-cover" style="background-image:url('<?php echo $imgPath; ?>')">
                    <img src="<?php echo $imgPath2; ?>" class="block mt-6 relative w-1/4 animate-vertical-alternating mx-auto " alt="">
                </div>
            </div>
        </div>
        <div class="msp-gradient my-16"></div>

        <div class="flex flex-col-reverse items-center w-full md:flex-row-reverse md:gap-4">
            <div class="flex flex-col gap-4 justify-center text-center items-center w-full md:w-2/5">
                <h3 class="hidden mb-2 my-4 text-left h3-gray md:flex ">Parteneri cu renume mondial în domeniul simulării medicale</h3>
                <p class="text-left text-lg mb-6">Aceștia dezvoltă echipamente și simulatoare medicale de ultimă generație, pentru a le oferi cadrelor medicale posibilitatea de a dobândi abilități tehnice fundamentale prin practică medicală, înainte de a lucra cu pacienți reali.</p>
                <?= mspButton(text: "Mai multe detalii", link_class: "px-[0.5em] py-[0.8em] text-base w-1/2 mb-4 self-center md:w-3/5 lg:w-3/5 ", href: $_ENV["CURRENT_PATH"] . '/portofoliu/'); ?>
            </div>
            <div class="flex flex-col w-full justify-start items-center md:w-3/5 h-full">
                <h3 class="mb-6 text-center w-10/12 h3-gray md:hidden">Parteneri cu renume mondial în domeniul simulării medicale</h3>

                <?php
                $imgPath3 = $_ENV['CURRENT_PATH'] . "/upload/siteMedia/imagine-3-700.webp";
                ?>
                <div class="block relative bg-no-repeat bg-cover z-1 w-full h-full min-h-[290px] mb-8 md:min-h-[430px] md:w-full md:bg-contain" style="background-image:url('<?php echo $imgPath3; ?>')">
                </div>
            </div>
        </div>
        <div class=" msp-gradient my-16">
        </div>

        <?php
        $imgPath4 = $_ENV['CURRENT_PATH'] . "/upload/siteMedia/finalmodificat.webp";
        ?>
        <div class="relative -mb-[30px] bg-no-repeat bg-contain z-1 w-full h-[300px] bg-bottom md:w-full md:h-[400px] md:-mb-[60px] lg:-mb-[74px]" style="background-image:url('<?php echo $imgPath4; ?>')">
            <div class="flex flex-col items-center justify-start w-full md:w-1/2">
                <h3 class="mb-6 text-center w-4/5 h3-gray md:text-left">Soluții complete pentru centre de simulare</h3>
                <?= mspButton(text: "Mai multe detalii", link_class: "px-[0.5em] py-[0.8em] text-base w-1/2 mb-4 self-center lg:w-2/5", href: $_ENV["CURRENT_PATH"] . '/despre-noi/#solutiicomplete'); ?>

            </div>
        </div>
    </div>


    </div>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/global_script.php"; ?>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/footer.php"; ?>

</body>


</html>