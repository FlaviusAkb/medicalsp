<?php $currentPath = $_ENV["CURRENT_PATH"]; ?>
<script src="<?php echo $_ENV["CURRENT_PATH"]; ?>/js/qof.js"></script>
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
</script>
<script src="<?php echo $currentPath; ?>/js/jquery.min.js"></script>
<?php autoFiles('js/auto'); ?>