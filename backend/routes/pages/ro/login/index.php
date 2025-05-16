<?php
include "../" . $_ENV["BACKEND"] . "/classes/Seo.php";

if (isset($_SESSION['id'])) {
    header("Location: " . $_ENV["CURRENT_PATH"] . "/");
    exit();
}

?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION["LANG"]; ?>">

<head>
    <?php

    require "../" . $_ENV["BACKEND"] . "/resources/components/head.php"; ?>

    <style>
        body {
            background-image: url("<?php echo $_ENV["CURRENT_PATH"] ?>/upload/siteMedia/loginBackground.webp");
            background-size: cover;
            background-position: center center;
            font-family: Raleway, sans-serif !important;
        }

        .modal_login {
            max-width: 600px;
            padding: 20px;
            width: 100%;
            aspect-ratio: 14 / 9;
            background: rgba(0, 0, 0, 0.4);
            margin-inline: auto;
        }
    </style>
</head>



<body>

    <div style="/*display: grid; justify-content: center; */ align-content: center; " class="h-screen">
        <div class="modal_login">
            <div class="csM">
                <div class="csIH csM1" style="margin-top:25px;width:100%;">
                    <div class="cssIStrip"></div>
                    <input id="email" class="" type="text" placeholder="">
                    <div class="csLabel">
                        <div>E-mail</div>
                    </div>
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr auto; grid-gap:10px;">
                <div class="csM">
                    <div class="csIH csM1" style="margin-top:25px;width:100%;">
                        <div class="cssIStrip"></div>
                        <input id="pass1" class="" type="password" placeholder="">
                        <div class="csLabel">
                            <div>Parola</div>
                        </div>
                    </div>
                </div>
                <button type="button" id="togglePassword" style=" margin-top:25px; background: white; border-radius: 0.25rem; padding: 5px; display: grid ; align-items: center; justify-content: center;">
                    <div class="fixIcon icon icon-eye"></div>
                </button>
            </div>

            <div class="modal_element" style="margin-top:25px;">
            </div>
            <div class="csM">
                <div class="csIH">
                    <button id="button" type="button" style="margin-top:0px;">Submit</button>
                </div>
            </div>
            <br>
            <div style="display:flex; flex-wrap:wrap; justify-content:space-between;">
                <a style="font-weight:normal;" href="<?php echo $_ENV["CURRENT_PATH"]; ?>/" class="standard_link cw">Înapoi la site</a>
            </div>



            <div class="cobai"></div>
        </div>


    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const togPass = document.getElementById("togglePassword");
            togPass.addEventListener("click", () => {
                if (pass1.type == "password") {
                    pass1.type = "text";
                    togPass.children[0].classList.remove("icon-eye");
                    togPass.children[0].classList.add("icon-eye-off");
                } else {
                    pass1.type = "password";
                    togPass.children[0].classList.add("icon-eye");
                    togPass.children[0].classList.remove("icon-eye-off");
                }
            });

            function load_process(e) {
                let form_data = new FormData();
                let k = {
                    value: 0
                };

                validate($("#email"), form_data, k, {
                    "error": ""
                });
                validate($("#pass1"), form_data, k, {
                    "error": ""
                });


                if (k.value == 0) {
                    const button = document.querySelector("#button");
                    button.disabled = true;
                    e.preventDefault();
                    fetch("<?php echo $_ENV["CURRENT_PATH"]; ?>/api/login", {
                            method: "POST",
                            body: form_data,
                        })
                        .then(response => response.json().then(data => {
                            // If the response status is not OK, throw an error with the message from the server.
                            if (!response.ok) {
                                throw new Error(data.message || response.statusText);
                            }
                            return data;
                        })).then(data => {
                            errors.alertBody({
                                body: data.message,
                                style: "info",
                                closeDuration: 10,
                            });
                            // On success, redirect (or perform any other success action)
                            window.location.href = "<?php echo $_ENV["CURRENT_PATH"]; ?>/";
                        }).catch(error => {
                            console.log("err", error.message);
                            errors.alertBody({
                                body: error.message,
                                style: "error",
                                closeDuration: 10,
                            });
                        }).finally(() => {
                            // Re-enable the button regardless of outcome
                            button.disabled = false;
                        });


                }
            }



            let preventSpam;
            $("#button").click((e) => {
                clearTimeout(preventSpam);
                preventSpam = setTimeout(() => {
                    load_process(e);
                }, 350);
            });

            $(document).keydown((e) => {
                if (e.keyCode == 13) {
                    clearTimeout(preventSpam);
                    preventSpam = setTimeout(() => {
                        load_process(e);
                    }, 350);
                }
            });
        });
    </script>

</body>
<?php include "../" . $_ENV["BACKEND"] . "/resources/components/global_script.php"; ?>
<script>

</script>
<?php //include "../" . $_ENV["BACKEND"] . "/resources/components/footer.php"; 
?>

</html>