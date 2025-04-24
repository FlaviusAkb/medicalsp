<?php $currentPath = $_ENV["CURRENT_PATH"]; ?>

<script>
    let currentPath = `<?php echo $_ENV["CURRENT_PATH"]; ?>`;
    let filesAllowed = <?php echo $_ENV["FILES_ALLOWED"]; ?>;
    let maxFileSize = <?php echo $_ENV["MAX_SIZE"]; ?>;
    let pattern_pass = <?php echo $_ENV["PASSREGEX"]; ?>;
    let phoneRegex = <?php echo $_ENV["PHONEREGEX"]; ?>;
    let cp = '<?php echo $_ENV["CURRENT_PATH"]; ?>';

    function detectDevice() {
        const ua = navigator.userAgent;
        if (/iPad|Android(?!.*Mobile)|Tablet|Nexus 7|Nexus 10|KF[A-Z]+/i.test(ua)) {
            return 'tablet';
        } else if (/Mobile|Mobi|Android|iPhone|iPod|IEMobile|Opera Mini/i.test(ua)) {
            return 'mobile';
        } else {
            return 'desktop';
        }
    }
    const deviceType = detectDevice();

    function fixContaining(parent, image) {
        function resize() {
            if (image.offsetWidth <= parent.offsetWidth) {
                image.style.setProperty("--imgWidth", "100%");
                image.style.setProperty("--imgHeight", "auto");
            }
            if (image.offsetHeight <= parent.offsetHeight) {
                image.style.setProperty("--imgWidth", "auto");
                image.style.setProperty("--imgHeight", "102vh");
            } else {
                image.style.setProperty("--imgWidth", "100%");
                image.style.setProperty("--imgHeight", "auto");
            }
        }
        if (image.tagName === 'VIDEO') {
            image.addEventListener("loadedmetadata", () => {
                resize();
            });
        } else {
            image.addEventListener("load", () => {
                resize();
            });
        }
        window.addEventListener("resize", () => {
            resize();
        });
    }
    document.addEventListener("DOMContentLoaded", () => {
        // let first = document.querySelectorAll(".first")[0];
        // first.addEventListener("transitionend", (event) => {
        //     if (event.propertyName === "height") {
        //         const target = event.currentTarget;
        //         // Reset height to 'unset' after expansion so when other under lvl menus open it can stretch all the way
        //         if (target.style.height !== "0px" && target.style.height !== "") {
        //             console.log("hey height");
        //             console.log(typeof target.style.height);
        //             target.style.height = "unset";
        //             target.style.overflowY = "auto";
        //         }
        //         if (target.style.height == "0px") {
        //             target.removeAttribute("style"); //when it is set to 0px remove it, you have 0px from css, also if you switch from mobile to desktop menu the height 0 doesnt fuck anything
        //         }
        //     }
        // });
        // $(".hmTrig").click(function() {
        //     $(".hamburger_trigger").toggleClass("is-active");
        //     //$(".menu_nav").toggleClass("show_element");
        //     if ($(".hamburger_trigger").hasClass("is-active")) {
        //         first.style.transition = `height 0.3s ease-in-out`;
        //         first.style.height = first.scrollHeight + "px"; // Set the current height to allow transition to 0
        //     } else {
        //         first.style.height = first.scrollHeight + "px"; // Set the current height to allow transition to 0
        //         requestAnimationFrame(() => { // Ensure the height is applied before collapsing
        //             first.style.transition = `height 0.3s ease-in-out`;
        //             first.style.height = "0px";
        //             first.style.overflowY = "hidden";
        //         });
        //     }
        // });
    });
</script>
<script src="<?php echo $currentPath; ?>/js/jquery.min.js"></script>
<?php autoFiles('js/auto'); ?>