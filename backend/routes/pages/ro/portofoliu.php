<?php
include "../" . $_ENV["BACKEND"] . "/classes/seo.php";


$items_data = [
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2020/08/gaumard-1.jpg",
        "url" => "https://www.gaumard.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2020/04/intelligentultrasound.jpg",
        "url" => "https://www.intelligentultrasound.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2023/01/virtamed.jpg",
        "url" => "https://www.virtamed.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2020/08/Sectra.png",
        "url" => "https://sectra.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2020/08/simulab-1.jpg",
        "url" => "https://www.simulab.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2020/04/otosim-1.jpg",
        "url" => "https://www.otosim.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2020/04/simstation.jpg",
        "url" => "https://www.simstation.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2020/08/simcharacters-1.jpg",
        "url" => "https://www.simcharacters.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2020/10/mentice-5.jpg",
        "url" => "https://www.mentice.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2020/10/uhealth-3.jpg",
        "url" => "https://umiamihealth.org/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2020/04/syndaver.jpg",
        "url" => "https://syndaver.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2023/01/kyoto-kagaku.jpg",
        "url" => "https://www.kyotokagaku.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2020/02/accurate.jpg",
        "url" => "https://www.accuratesolutions.it/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2020/04/orsim.jpg",
        "url" => "https://www.orsim.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2021/02/MAI.jpg",
        "url" => "https://www.mai.ai/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2023/01/bodyinteract.jpg",
        "url" => "https://bodyinteract.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2020/10/tcg-1.jpg",
        "url" => "https://www.thecgroup.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2020/10/biomed-sim.jpg",
        "url" => "https://www.biomedsimulation.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2020/04/veterinary.jpg",
        "url" => "https://vetsimulators.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2021/02/swemac.jpg",
        "url" => "https://www.swemac.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2021/02/VRMAGIC.jpg",
        "url" => "https://www.vrmagic.com/"
    ],
    [
        "image_src" => "https://medicalsimulator.ro/wp-content/uploads/2020/10/immersivetouch-1.jpg",
        "url" => "https://www.immersivetouch.com/"
    ]
];


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

    <div class="flex flex-col container w-11/12 mt-[50px] mb-[100px] mx-auto items-top justify-start xlg:max-w-full text-black">
        <div class="flex flex-col items-center  w-full h-full md:items-start md:flex-row">
            <div class="flex flex-col text-center w-full justify-center items-center">
                <h5 class="w-full relative mb-6 text-center lg:w-[60%]">Partenerii noștri de încredere sunt companii din întreaga lume, cu renume mondial în domeniul simulării medicale</h5>
                <div class="msp-gradient"></div>


                <div class="w-full grid grid-cols-1 mt-6 md:gap-x-12 md:gap-y-8 md:grid-cols-2 lg:gap-x-12 lg:gap-y-4">
                    <?php
                    foreach ($items_data as $item) { ?>
                        <?php if (!empty($item["url"])): ?>
                            <a href="<?php echo htmlspecialchars($item["url"]) ?>" class="block mx-auto my-[10px] h-[140px] w-[362px] md:h-[126px] md:w-[325px] lg:h-[194px] lg:w-[500px]">
                                <div class="flex bg-no-repeat bg-cover bg-center relative shadow-[0px_0px_10px_0px_rgba(0,0,0,0.3)] z-10 w-full h-full"
                                    style="background-image: url('<?= isset($item["image_src"]) ? $item["image_src"] : 'https://placehold.co/600x400' ?>')">
                                </div>
                            </a>
                        <?php endif; ?>
                    <?php } ?>
                </div>


                <!-- <div class="w-full grid grid-cols-1 md:grid-cols-2 mt-6">
                    <div class="flex flex-col gap-8 lg:items-center lg:gap-y-12">
                        <?php foreach (array_filter($items_data, fn($k) => $k % 2 === 0, ARRAY_FILTER_USE_KEY) as $item) : ?>
                            <a href="<?= htmlspecialchars($item["url"]) ?>" class="block">
                                <div class="block relative bg-no-repeat bg-contain 
    bg-[url('<?= $item["image_src"] ?? 'https://placehold.co/600x400' ?>')] shadow-[0px_0px_10px_0px_rgba(0,0,0,0.3)] z-10 
    h-[140px] w-[362px] md:h-[126px] md:w-[325px] lg:h-[194px] lg:w-[500px]">
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <div class="flex flex-col gap-8 items-end lg:items-center lg:gap-y-12">
                        <?php foreach (array_filter($items_data, fn($k) => $k % 2 !== 0, ARRAY_FILTER_USE_KEY) as $item) : ?>
                            <a href="<?= htmlspecialchars($item["url"]) ?>" class="block">
                                <div class="block relative bg-no-repeat bg-contain 
    bg-[url('<?= $item["image_src"] ?? 'https://placehold.co/600x400' ?>')] shadow-[0px_0px_10px_0px_rgba(0,0,0,0.3)] z-10 
    h-[140px] w-[362px] md:h-[126px] md:w-[325px] lg:h-[194px] lg:w-[500px]">
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div> -->



            </div>
        </div>
    </div>


    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/global_script.php"; ?>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/footer.php"; ?>

</body>

</html>