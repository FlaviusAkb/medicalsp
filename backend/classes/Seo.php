<?php

class Seo
{
    public $pageDescription;
    public $title;

    public function __construct()
    {
        $this->pageDescription = '';
        $this->title = strlen(trim($_SESSION["REQUEST"])) > 0 ? $_SESSION["REQUEST"] : localDic(["ro" => "Medical Simulator Projects | Solutii complete pentru Simulare Medicala si Centre de Simulare", "eng" => ""]);
    }


    public function setTitle($value)
    {
        $this->title = str_replace('"', '\"', $value);
    }

    public function ogTitle()
    {
        //og specifics meta are used for social media, when sharing links and so on
        return '<meta property="og:title" content="' . $this->title . '">';
    }
    public function ogType()
    {
        return '<meta property="og:type" content="section">';
    }
    public function ogImage()
    {
        return '<meta property="og:image" content="' . $_ENV["CURRENT_PATH"] . '/upload/siteMedia/128X128.png">';
    }
    public function ogUrl()
    {
        return '<meta property="og:url" content="' . $this->metaUrl() . $_SESSION["AL"] . '/' . $_SESSION["REQUEST"] . '">';
    }
    public function ogDescription()
    {
        // one is for social media the other is for seo purposes
        if (strlen(trim($this->pageDescription)) > 0) {
            return '
            <meta property="og:description" content="' . $this->pageDescription . '">
            <meta name="description" content="' . $this->pageDescription . '">
            ';
        } else {
            return '';
        }
    }
    public function setOgDescription($value)
    {
        $this->pageDescription = str_replace('"', '\"', $value);
    }
    public function ogSite_name()
    {
        return '<meta property="og:type" content="Medical Simulator Projects | Solutii complete pentru Simulare Medicala si Centre de Simulare">';
    }
    public function ogLocale()
    {
        return '
            <meta property="og:locale" content="ro">
            <meta property="og:locale:alternate" content="eng" />ß
            ';
    }

    public function metaUrl()
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://" . $_SERVER['HTTP_HOST'] . '' . $_ENV["CURRENT_PATH"] . "";
    }

    public function metaTitle()
    {
        return '<title>' . (!empty(trim($_SESSION["REQUEST"])) ? $_SESSION["REQUEST"] . " - " : "") . localDic(["ro" => "Medical Simulator Projects | Solutii complete pentru Simulare Medicala si Centre de Simulare", "eng" => ""]) . '</title>';
    }




    public function hreflang()
    {
        //The hreflang attribute uses language and optional region codes to specify the language and geographic targeting for different versions of a webpage
        //To target specific regions, combine the language code with a region code based on the ISO 3166-1 Alpha-2 standard. Use the format language-region (e.g., en-US for English in the United States).

        $hreflangs = "";
        foreach (json_decode($_ENV["LANGUAGES"]) as $key => $value) {
            $hreflangs .= '<link rel="alternate" hreflang="' . $value . '" href="' . $this->metaUrl() . '' . ($value != $_ENV["DEFAULTLANG"] ? "/" . $value : "") . '/' . $_SESSION["REQUEST"] . '" />';
        }
        $hreflangs .= '<link rel="alternate" hreflang="x-default" href="' . $this->metaUrl() . '" />';
        return $hreflangs;
    }
    public function appleSpecifics()
    {

        // set apple icons so the ios apps know what icon to use
        return '<link rel="apple-touch-icon" sizes="128x128" href="' . $_ENV["CURRENT_PATH"] . '/upload/siteMedia/128X128.png">';
    }

    public function microsoftSpecifics()
    {
        //The <meta name="msapplication-152x152logo"> tag is used to specify an icon for Microsoft-specific features, particularly for pinned sites in the Windows operating system.
        // set apple icons so the ios apps know what icon to use
        return '
        <meta name="msapplication-TileColor" content="#c60000">
        <meta name="msapplication-128x128logo" content="' . $_ENV["CURRENT_PATH"] . '/upload/siteMedia/128X128.png" />';
    }

    public function xSpecifics()
    {
        // set apple icons so the ios apps know what icon to use
        $twitter = '';
        $twitter .= '<meta name="twitter:card" content="summary">';
        $twitter .= '<meta name="twitter:title" content="' . $this->title . '">';
        $twitter .= strlen(trim($this->pageDescription)) > 0 ? '<meta name="twitter:description" content="' . $this->pageDescription . '">' : "";
        $twitter .= '<meta name="twitter:image" content="' . $_ENV["CURRENT_PATH"] . '/upload/siteMedia/128X128.png">';
        return $twitter;
    }


    public function renderMeta()
    {
        $finalMeta = '';

        $finalMeta .= '<meta name="robots" content="index, follow">'; // Robots (Control Indexing):        index, follow: Allow indexing and link following.;   noindex, nofollow: Block indexing and link following.
        $finalMeta .= '<meta name="theme-color" content="#c60000">'; // Theme Color (Browser UI Customization):
        $finalMeta .= $this->metaTitle();
        $finalMeta .= $this->ogTitle();
        $finalMeta .= $this->ogType();
        $finalMeta .= $this->ogImage();
        $finalMeta .= $this->ogUrl();
        $finalMeta .= $this->ogDescription();

        $finalMeta .= $this->hreflang();

        $finalMeta .= $this->appleSpecifics();
        $finalMeta .= $this->microsoftSpecifics();
        $finalMeta .= $this->xSpecifics();

        echo $finalMeta;
    }
}

$seo = new seo();
