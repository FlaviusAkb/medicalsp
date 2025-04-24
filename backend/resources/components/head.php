<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="icon" type="image/png" href="<?php echo $_ENV["CURRENT_PATH"]; ?>/upload/siteMedia/48X48.png">


<?php ($seo ?? null)?->renderMeta(); ?>


<link rel="stylesheet" type="text/css" href="<?php echo $_ENV["CURRENT_PATH"]; ?>/css/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $_ENV["CURRENT_PATH"]; ?>/css/defaulticons/styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo $_ENV["CURRENT_PATH"]; ?>/css/output.css">


<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- FA-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
<!-- Fonts-->
<!-- <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> -->

<!-- Custom Tailwind CDN -->
<!-- <script src=" https://cdn.tailwindcss.com">
</script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    roboto: ['Roboto', 'sans-serif'],
                },
                boxShadow: {
                    neumorphic: '5px 5px 10px rgba(0,0,0,0.1), -5px -5px 10px #fff',
                },
                colors: {
                    'msp-primary': '#DD4949',
                    'msp-secondary': '#eae3e3',
                    'msp-accent': '#FF7F11',
                    'msp-light': '#F5F5F5',
                    'msp-dark': '#2F2F30',
                    'msp-ui': '#656565',
                    'msp-black': '#000000',
                    "msp-green": '#23A455',
                    "msp-gray": '#7A7A7A',
                },
                keyframes: {
                    verticalAlternating: {
                        '0%': {
                            top: '0px'
                        },
                        '50%': {
                            top: '20px'
                        },
                        '100%': {
                            top: '0px'
                        },
                    },
                },
                animation: {
                    verticalAlternating: 'verticalAlternating 5s infinite alternate ease-in-out',
                },
            },

        }
    }
</script> -->
<!-- Custom Tailwind CDN -->