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

    <div class="flex flex-col container w-10/12 mt-[50px] mb-[100px] mx-auto items-top justify-start md:w-11/12 xlg:max-w-full text-black">
        <div class="flex flex-col items-center  w-full h-full md:items-start md:flex-row">
            <div class="flex flex-col text-center w-full justify-center items-center">
                <h1 class="w-full relative mb-6 text-center">Singura companie dedicată simulării medicale</h1>
                <div class="msp-gradient"></div>
                <?php

                $side_by_side_sections = [
                    [
                        "image_side" => "right",
                        "title" => "Despre noi",
                        "paragraphs" => [
                            "Echipa noastră de specialiști are o experiență vastă în domeniul Simulării Medicale, reușind până în acest moment să echipeze atât Spitale și Clinici importante, cât și Facultăți și Universități de Medicină și Farmacie din România cu echipamente și simulatoare de ultimă generație, care cu siguranță în următorii ani, vor schimba modul de abordare al formării profesionale din domeniul medical.",
                            "Mai mult decât atât, echipa noastră a avut onoarea și privilegiul de a face parte din echipa de dezvoltare și echipare a celui mai mare Centru de Simulare Medicală Multidisciplinară din Europa Centrală și de Est construit în București – Centrul de Simulare Medicală <a href='https://lifesim.ro/' class='text-msp-primary'>LifeSIM</a>."
                        ],
                        "image_url" => "22",
                        "additional_section" => "",
                    ],
                    [
                        "image_side" => "left",
                        "title" => "Ce este simularea medicală?",
                        "paragraphs" => [
                            "Simularea Medicală are ca scop principal Informarea, Instruirea și Evaluarea personalului medical cu ajutorul simulatoarelor medicale. Procesul de învățare prin simulare are loc într-un mediu controlat, ce imită situații din viața reală și din mediul spitalicesc, fiind o experiență practică ce presupune reproducerea convingătoare a unui eveniment sau a unui set de condiţii.",
                            "Simularea oferă un model de învățare orientat către cursanți, oferindu-le acestora șansa de a practica proceduri și tehnici medicale în siguranța, într-un mediu controlat, dar și posibilitatea de a determina în avans natura cazurilor care trebuie abordate, prin crearea propriilor Scenarii.",
                            "Simularea medicală poate fi folosită atât în scop didactic, cât și în procesul de evaluare, fiind un instrument obiectiv cu ajutorul căruia se poate cuantifica cu precizie evoluţia personalului medical în cadrul procesului de formare profesională continuă.
"
                        ],
                        "image_url" => "333",
                        "additional_section" => '
        <p class="mt-4">Simularea medicală poate fi folosită atât în scop didactic, cât și în procesul de evaluare, fiind un instrument obiectiv cu ajutorul căruia se poate cuantifica cu precizie evoluţia personalului medical în cadrul procesului de formare profesională continuă.</p>
        <h2 class="my-4">Simularea implică</h2>
        <ul>
            <li><i aria-hidden="true" class="far fa-check-square mr-4 text-msp-green"></i>Mai multe niveluri de dificultate</li>
            <li><i aria-hidden="true" class="far fa-check-square mr-4 text-msp-green"></i>Strategii de învățare - individual sau în echipă</li>
            <li><i aria-hidden="true" class="far fa-check-square mr-4 text-msp-green"></i>Experiențe educaționale reproductibile, standardizate</li>
        </ul>
        <p class="mt-4">Integrarea in curriculum universitar se traduce atat prin însușirea noilor tehnici și proceduri, și evaluarea competențelor profesionale, cat și pe termen lung, prin siguranța pacientului și cost-eficiență. În ultimii ani au fost realizate studii clinice importante care au demonstrat îmbunătăţiri semnificative ale actului medical cu ajutorul simulării, precum și o scădere semnificativă a costurilor asociate malpraxis-ului, astfel încât universităţi și centre medicale de renume mondial au încorporat simularea medicală în procesul lor de educaţie și de certificare.</p>
        ',
                    ],
                    [
                        "image_side" => "right",
                        "title" => "Cui ne adresăm?",
                        "paragraphs" => [
                            "Simulatoarele medicale au fost concepute ca instrumente pentru formarea continuă a specialiștilor din domeniul medical.",
                            "Simulatoarele noastre se adresează atât studenților, rezidenților, asistenților și personalului medical specializat, cât și tuturor instituțiilor de pregătire a personalului medical, școlilor militare și de pompieri."
                        ],
                        "image_url" => "4444",
                        "additional_section" => '
        <div class="flex flex-col my-4 md:flex-row">
        <div class="flex flex-col">
        <ul>
            <li><i aria-hidden="true" class="fas fa-check mr-4 text-msp-primary"></i>Universități de Medicină și Farmacie</li>
            <li><i aria-hidden="true" class="fas fa-check mr-4 text-msp-primary"></i>Facultăți de Bioinginerie Medicală</li>
            <li><i aria-hidden="true" class="fas fa-check mr-4 text-msp-primary"></i>Facultăți de Medicina Veterinară</li>
            <li><i aria-hidden="true" class="fas fa-check mr-4 text-msp-primary"></i>Facultăți de Moașe și Asistenți Medicali</li>
        </ul>
        </div>
           <div class="flex flex-col">
        <ul>
            <li><i aria-hidden="true" class="fas fa-check mr-4 text-msp-primary"></i>Facultăți de Medicină </li>
            <li><i aria-hidden="true" class="fas fa-check mr-4 text-msp-primary"></i>Facultăți de Medicina Dentară</li>
            <li><i aria-hidden="true" class="fas fa-check mr-4 text-msp-primary"></i>Servicii de Ambulanță și Protecție Civilă</li>
            <li><i aria-hidden="true" class="fas fa-check mr-4 text-msp-primary"></i>Facultăți de Farmacie</li>
        </ul>
        </div>
           <div class="flex flex-col">
                <ul>
                    <li><i aria-hidden="true" class="fas fa-check mr-4 text-msp-primary"></i>Școli postliceale sanitare</li>
                    <li><i aria-hidden="true" class="fas fa-check mr-4 text-msp-primary"></i>Licee sanitare</li>
                    <li><i aria-hidden="true" class="fas fa-check mr-4 text-msp-primary"></i>Societăți, Asociații, Fundații, ONG-uri</li>
                    <li><i aria-hidden="true" class="fas fa-check mr-4 text-msp-primary"></i>Alte instituții cu profil medical</li>
                </ul>
            </div>
        </div>',
                    ],
                    [
                        "image_side" => "left",
                        "title" => "Gama de simulatoare",
                        "paragraphs" => [
                            "Echipa noastră este în strânsă colaborare cu partenerii noștri internaționali, care dezvoltă în mod continuu soluții și tehnologii inovatoare, și stabilesc noi standarde pentru viitorul simulării medicale. Portofoliul nostru complet cuprinde cele mai noi tehnologii în domeniul simulării medicale dezvoltate până în prezent.",
                            '<div class="flex items-center justify-center w-full">' . mspButton(text: "Portofoliu", link_class: "px-[1em] py-[0.5em] text-base", href: $_ENV["CURRENT_PATH"] . 'portofoliu/') . '</div>',
                        ],
                        "image_url" => "3594579-768x454",
                        "additional_section" => '',
                    ],
                ];
                ?>

                <?php
                foreach ($side_by_side_sections as $section) {
                ?>
                    <div class="flex flex-col items-center gap-4 w-full <?= $section["image_side"] === "left" ? 'flex-col-reverse md:flex-row-reverse' : '' ?> md:flex-row">
                        <div class="flex flex-col gap-4 justify-center text-left w-full md:w-1/2">
                            <h2 class="my-4 text-nowrap"><?php echo $section["title"]; ?></h2>
                            <?php
                            foreach ($section["paragraphs"] as $paragraph) {
                                echo "<p>" . $paragraph . "</p>";
                            } ?>
                        </div>
                        <div class="flex flex-col w-full justify-center items-center md:w-1/2">
                            <img src="<?php echo $_ENV['CURRENT_PATH'] . "/upload/siteMedia/" . $section["image_url"] . ".webp"; ?>" class="w-10/12" alt="">
                        </div>
                    </div>
                    <div class="flex flex-col text-left"><?= $section["additional_section"]; ?></div>
                    <div class="msp-gradient my-16"></div>
                <?php }
                ?>


                <?php

                $side_by_side_column_section = [
                    [
                        "title" => "Soluții complete pentru centre de simulare",
                        "left_column" =>
                        '
        <p><span class="font-bold">Medical Simulator Projects</span> oferă soluții complete pentru Simulare si Centre de Simulare. Printr-o colaborare strânsă cu parteneri certificați și cu experiență dovedită, punem la dispozitie soluțiile și suportul de care aveți nevoie.</p>
        <ul>
            <li><i aria-hidden="true" class="far fa-check-square mr-4 text-msp-green"></i>Consultanță pentru planificarea, proiectarea, construcția sau extinderea unui Centru de Simulare</li>
            <li><i aria-hidden="true" class="far fa-check-square mr-4 text-msp-green"></i>Infrastructura și dotarea cu instalații și echipamente medicale</li>
            <li><i aria-hidden="true" class="far fa-check-square mr-4 text-msp-green"></i>Organizarea și funcționarea unui Centru de Simulare</li>
        </ul>
        <p class="font-bold">Un centru de simulare care să corespundă standardelor internaționale este constituit din minim următoarele:</p>
        <ul>
            <li><i aria-hidden="true" class="far fa-check-square mr-4 text-msp-green"></i>Recepție</li>
            <li><i aria-hidden="true" class="far fa-check-square mr-4 text-msp-green"></i>Vestiar</li>
            <li><i aria-hidden="true" class="far fa-check-square mr-4 text-msp-green"></i>Sala de curs</li>
            <li><i aria-hidden="true" class="far fa-check-square mr-4 text-msp-green"></i>Săli de simulare</li>
            <li><i aria-hidden="true" class="far fa-check-square mr-4 text-msp-green"></i>Cameră de control</li>
            <li><i aria-hidden="true" class="far fa-check-square mr-4 text-msp-green"></i>Sală debriefing</li>
        </ul>
        ',
                        "image_url" => "22",
                        "right_column" => '
        <div class="flex flex-col p-6">
            <p class="text-white my-4">CONSULTANȚĂ ȘI PROIECTARE ÎN 3 ETAPE</p>
            <p class="text-white">1.INSPECȚIA LOCAȚIEI PENTRU CONSTRUCȚIE</p>
            <p class="text-white mb-4">Activitate de consultanță pentru a găsi cea mai bună soluție pentru planificarea distribuirii corecte a spațiului.</p>
            <p class="text-white">2. CONSULTANȚĂ SPECIFICĂ</p>
            <p class="text-white mb-4">Asistență în alegerea poziționării sistemului audio-video, plan business, analize de autosustenabilitate, identificarea obiectivelor de formare, planuri ocupaționale, evaluarea sistemelor și instalațiilor.</p>
            <p class="text-white">3. PROIECTARE PLAN ȘI VIZUALIZARE 3D</p>
            <p class="text-white mb-4">Valorificarea spațiului pentru funcționalitatea maximă a fiecărei încăperi, conform standardelor internaționale. Randări 3D pentru tur virtual al Centrului de Simulare înainte de construcție.</p>
            <p class="text-white mb-4">Pentru ca Centrele de Simulare să funcționeze conform standardelor impuse, acestea trebuie să integreze în planul de proiectare sistemele audio-video pentru Recording/Debrifieng – sisteme pentru înregistrarea activităților de simulare desfășurate în Centrul de Simulare, în baza cărora cursanții împreună cu profesorul/trainerul vor face analize și dezbateri în sala de Debriefing.</p>
        </div>',
                        "additional_section" =>
                        '<div class="flex items-center justify-center w-full mt-8">' . mspButton(text: "Contactează-ne", link_class: "px-[1em] py-[1em] text-base", href: $_ENV["CURRENT_PATH"] . '/contact/') . '</div>',
                    ],

                ];
                ?>
                <?php
                foreach ($side_by_side_column_section as $section) {
                ?>
                    <div class="flex flex-col items-start gap-4 w-full md:flex-row">
                        <div class="flex flex-col gap-4 justify-center text-left w-full md:w-1/2">
                            <h2 class="my-4"><?php echo $section["title"]; ?></h2>
                            <?= $section["left_column"] ?>
                        </div>
                        <div class="flex flex-col text-left w-full md:w-1/2 bg-msp-gray"><?= $section["right_column"]; ?></div>
                    </div>

                    <div class="flex flex-col text-left"><?= $section["additional_section"]; ?></div>
                <?php }
                ?>
            </div>
        </div>
    </div>


    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/global_script.php"; ?>
    <?php include "../" . $_ENV["BACKEND"] . "/resources/components/footer.php"; ?>

</body>

</html>