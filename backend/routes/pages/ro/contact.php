<?php

use function PHPSTORM_META\type;

include "../" . $_ENV["BACKEND"] . "/classes/seo.php";

$items_data = [
    "Str. Odobești, Nr. 1, Sector 3, București",
    "Telefon: +40 213 485 272",
    "Fax: +40 372 872 626",
    "Mobil: +40 731 510 882",
    "E-mail: office@medicalsimulator.ro"

];
$image_src = $_ENV['CURRENT_PATH'] . "/upload/siteMedia/contactimg.webp";
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

    <div class="flex flex-col container w-10/12 mt-[50px] mb-[100px] mx-auto items-top justify-start md:w-11/12 xlg:max-w-full text-black">
        <div class="flex flex-col items-center  w-full h-full md:items-start md:flex-row">
            <div class="flex flex-col text-center w-full justify-center items-center md:flex-row">
                <div class="flex flex-col text-center w-full justify-center items-center md:max-w-1/2">
                    <div class="msp-gradient hidden md:flex md:my-4"></div>
                    <h6 class=" w-full relative mb-6 text-center lg:w-[60%] font-bold text-black">MEDICAL SIMULATOR PROJECTS S.R.L.</h6>
                    <div class="flex flex-col">
                        <?php
                        foreach ($items_data as $item) { ?>
                            <?php if (!empty($item)): ?>
                                <p class="block my-[10px] text-msp-ui"><?php echo htmlspecialchars($item) ?></p>
                            <?php endif; ?>
                        <?php } ?>
                    </div>
                    <div class="msp-gradient hidden md:flex md:my-4"></div>
                </div>
                <div class="block bg-no-repeat bg-cover bg-center relative z-10 h-[268px] w-full lg:h-[384px] md:w-full md:bg-contain"
                    style="background-image: url('<?= isset($image_src) ? $image_src : 'https://placehold.co/600x400' ?>')">


                    <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 z-20">
                        <?php echo mspButton(text: "Trimite-ne un mesaj", link_class: "px-4 py-2", action: "makeCrud()", type: 'button'); ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function test() {
            alert("Nice!");
        };
    </script>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/global_script.php"; ?>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/footer.php"; ?>

    <script>
        function makeCrud() {
            const contactFields = [{
                    id: "email",
                    label: "Email",
                    type: "email"
                },
                {
                    id: "nume",
                    label: "Nume",
                    type: "text"
                },
                {
                    id: "telefon",
                    label: "Telefon",
                    type: "text"
                },
                {
                    id: "domeniu",
                    label: "Domeniul de activitate",
                    type: "text"
                },
            ];


            let contentHtml = contactFields.map(field => `
                    <div class="relative">
                        <input id="contact-${field.id}" type="${field.type}" name="${field.id}" ${field.id==="email" ? "required" : ""} 
                            class="peer leading-8 mb-4 block p-2 px-4 rounded-xl w-full  placeholder:text-base focus:outline-none bg-white"
                            placeholder="${field.id === "email" ? field.label + '*' : field.label + " (opțional)"}" value=""/>
                       
                    </div>
                `).join('');



            const crudBody = document.createElement("div");
            crudBody.className = `inset-0 flex justify-center items-center`;
            crudBody.id = `contactModal`;
            crudBody.innerHTML = `
                    <form id="contact-form" enctype="multipart/form-data" class="flex flex-col w-full gap-4" method="POST" onsubmit="validateForm(event)">
                        <div class="flex gap-4 flex-col items-center justify-center">
                            <div class="flex flex-col w-10/12">
                                ${contentHtml}                     
                        <div class="relative w-full">
                            <textarea required rows="4" id="mesaj" name="mesaj" 
                                class="bg-white peer block p-4 rounded-xl w-full placeholder-text-gray-500 focus:outline-none" 
                                placeholder="Mesaj*"></textarea>
                        </div>
                         <input type="hidden" id="status" name="status" value="newsletter-contact">
                            <button type="submit" class="text-white px-1 py-3 my-4 rounded-full bg-msp-blue w-1/3 self-center hover:bg-[#4632da] transition duration-700 ease-in-out cursor-pointer">Trimite<span class="ml-2"><i aria-hidden="true" class="fas fa-arrow-right text-white"></i></span></button>
                    </form>
                `;

            document.body.appendChild(crudBody);

            const newPop = new popUps({
                content: crudBody,
                extra: {
                    btnExit: false,
                    css: `
			@keyframes slideInTop {
				from {
					transform: translateY(-300px);
					opacity: 0;
				}
				to {
					transform: translateY(0);
					opacity: 1;
				}
			}
			@keyframes slideOutBottom {
				from {
					transform: translateY(0);
					opacity: 1;
				}
				to {
					transform: translateY(600px);
					opacity: 1;
				}
			}
			.htmlContent {
				animation: slideInTop 1s ease-out forwards;
			}
			.htmlMaster[data-hiding] .htmlContent {
				animation: slideOutBottom 0.4s ease-in forwards;
			}
		`,
                },
                cta: {
                    show: () => {
                        // Remove hiding flag before showing
                        newPop.htmlMaster.removeAttribute("data-hiding");
                    },
                },
            });
            newPop.htmlContent.style.cssText += 'background-image: linear-gradient(180deg, #AFE7FF 0%, #6377D3 100%); width:90%; max-width:469px;';
            newPop.htmlUpBar.classList.add("flex", "w-full", "items-center", "font-bold", "text-2xl", "py-4", "justify-center", "text-black");


            // override AFTER class is done constructing
            newPop.fInfo.cta.clsBtn = function() {
                newPop.htmlMaster.setAttribute("data-hiding", "true");
                console.log("custom close called");

                newPop.htmlContent.addEventListener(
                    "animationend",
                    () => {
                        newPop.remove();
                    }, {
                        once: true
                    }
                );
            };

            newPop.show();

        }
    </script>

</body>

</html>