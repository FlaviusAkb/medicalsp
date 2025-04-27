<footer class="flex w-full bg-msp-dark">
    <div class="flex container mx-auto flex-col w-full bg-msp-dark text-center font-roboto justify-center items-center">
        <hr class=" w-10/12 h-[1px] text-msp-ui mt-16 bg-white opacity-25 md:w-11/12">
        <div class="flex flex-col w-10/12 mx-auto mb-[20px] mt-[10px] text-[#818181] font-light tracking-[0.04em] justify-between items-center leading-[18px] md:flex-row md:flex-wrap md:w-11/12 lg:flex-nowrap">

            <a href="/" class="text-[13px] md:order-4 md:w-full lg:order-1 lg:w-auto">Copyright Medical Simulator Projects, Inc. All Rights Reserved.</a>
            <div class="my-4 md:order-2 md:w-1/3 lg:w-auto">
                <?php echo mspButton(text: "Aboneaza-te", action: "makeFooter()", type: 'button'); ?>
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
<script>
    function makeFooter() {
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
                type: "tel"
            },
            {
                id: "domeniu_activitate",
                label: "Domeniul de activitate",
                type: "text"
            },
        ];


        let contentHtml = contactFields.map(field => `
                    <div class="relative">
                        <input id="contact-${field.id}" type="${field.type}" name="${field.id}" ${field.id==="email" || field.id==="nume" ? "required" : ""}  ${field.type==="tel" ? "pattern='[0-9()#&amp;+*-=.]+'" : ""}
                            class="w-full bg-transparent placeholder:text-msp-gray text-black text-xs rounded-sm px-3 py-2 transition duration-300 ease shadow-neumorphic focus:shadow-none focus:outline-none focus:border border-slate-20 mb-8"
                            placeholder="${field.id === "email"|| field.id === "nume" ? field.label + '*' : field.label + " (opțional)"}" value=""/>
                       
                    </div>
                `).join('');



        const crudBody = document.createElement("div");
        crudBody.className = `inset-0 flex justify-center items-center`;
        crudBody.id = `footerModal`;
        crudBody.innerHTML = `
                    <form id="contact-form" enctype="multipart/form-data" class="flex flex-col w-full gap-4">
                        <div class="flex gap-4 flex-col items-center justify-center">
                            <div class="flex flex-col w-10/12">
                                <input type="hidden" id="contact-article-id" name="id" value="">
                                ${contentHtml}                     
                           <button type="submit" class="relative overflow-hidden text-white px-4 py-3 my-4 rounded-full w-[45%] self-center bg-gradient-to-r from-[#365BE2] via-[#365BE2] to-[#1F99EE] transition duration-700 ease-in-out hover:from-[#365BE2] hover:via-[#1F99EE] hover:to-[#00dafc]">Trimite<span class="ml-2"><i aria-hidden="true" class="fas fa-arrow-right text-white"></i></span></button>
                    </form>
                `;

        document.body.appendChild(crudBody);

        const newPop = new popUps({
            content: crudBody,
            title: document.createTextNode('Vei primi cele mai noi informații despre simulatoarele medicale!'),
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
        // newPop.title.style.cssText += ""
        newPop.htmlContent.style.cssText += 'background-color:#f5f5f5; width:90%; max-width:469px;';
        newPop.htmlUpBar.classList.add("flex", "w-10/12", "items-center", "font-bold", "text-2xl", "py-12", "pl-0", "justify-center", "mx-auto", "gradient-text");

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



    $(document).on("submit", "footerForm", function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append("action", "save");

        $.ajax({
            url: controllerPath,
            type: "POST",
            data: formData,
            contentType: false, // Prevent jQuery from setting content-type
            processData: false, // Prevent jQuery from converting to URL encoding
            success: function(response) {
                if (response.includes("success")) {
                    alert("Article saved successfully!");
                    loadArticles();
                } else {
                    alert("Error saving article.");
                }
            },
            error: function() {
                alert("Error saving article.");
            },
        });
    });

    $('#footerButton').click(function() {
        makeCrud({});
    });
</script>