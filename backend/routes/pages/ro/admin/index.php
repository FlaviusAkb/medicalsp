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
    <div class="container flex flex-col px-6 mt-[50px] mb-[100px] mx-auto items-center justify-start md:gap-4 lg:px-4 xlg:max-w-full">
        <ul class="flex flex-col">
            <li><a href="/admin/widget">Portfolio Widgets</a></li>
            <li><a href="/admin/newsletter">Newsletter form entries</a></li>
        </ul>

    </div>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/global_script.php"; ?>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/footer.php"; ?>
</body>

</html>