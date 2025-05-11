<?php
include "../" . $_ENV["BACKEND"] . "/classes/seo.php";

$widget = new Widget();
$items_data = $widget->getAll();
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
                    <?php foreach ($items_data as $item): ?>
                        <?php if (($item["status"] === 1)): ?>
                            <a href="<?= !empty($item["website_url"]) ? htmlspecialchars($item["website_url"]) : '/#' ?>"
                                class="block mx-auto my-[10px] h-auto w-full md:w-[325px] lg:w-[500px] shadow-[0px_0px_10px_0px_rgba(0,0,0,0.3)]"
                                target="_blank" rel="noopener">

                                <img src="<?= htmlspecialchars($item["image_url"] ? $item["image_url"] : "/upload/siteMedia/placeholdery.png")  ?>"
                                    alt="<?= htmlspecialchars($item["title"]) ?>"
                                    class="w-full h-auto object-contain">

                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/global_script.php"; ?>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/footer.php"; ?>
</body>

</html>