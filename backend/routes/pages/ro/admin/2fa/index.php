<?php
include "../" . $_ENV["BACKEND"] . "/classes/seo.php";
$auth = new TwoFactorAuth();
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/nav.php"; ?>
    <div class="flex flex-col w-full">
        <div class="flex flex-col mx-auto my-16 gap-8  bg-white p-6 rounded-2xl shadow-lg w-full max-w-sm space-y-4 border border-gray-200">

            <div>
                <h2 class="w-full mb-6 text-lg font-bold">Step 1: Enter Email for 2FA</h2>
                <?= $auth->emailForm(); ?>
            </div>

            <div>
                <h2 class="w-full mb-6 text-lg font-bold">Step 2: Enter 2FA Code</h2>
                <?= $auth->verificationForm(); ?>
            </div>

        </div>
    </div>


    <?= $auth->ajaxScript(); ?>
</body>
<?php include "../" . $_ENV["BACKEND"] . "/resources/components/global_script.php"; ?>
<script>

</script>
<?php include "../" . $_ENV["BACKEND"] . "/resources/components/footer.php"; ?>

</html>