<!-- PHP starts by laying out canonical URLs for the page and language -->

<?php
	$parts = explode ("/", $_SERVER['SCRIPT_NAME']);
	$name = $parts [count($parts)-1];
	if (strcmp($name, "welcome.php") == 0)
  $name = "";


	;?>


	<link rel="canonical" href="https://buwana.ecobricks.org/<?php echo ($lang); ;?>/<?php echo ($name); ;?>">
	<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">

	<link rel="alternate" href="https://buwana.ecobricks.org/en/<?php echo ($name); ;?>" hreflang="en">
	<link rel="alternate" href="https://buwana.ecobricks.org/id/<?php echo ($name); ;?>" hreflang="id">
	<link rel="alternate" href="https://buwana.ecobricks.org/es/<?php echo ($name); ;?>" hreflang="es">
	<link rel="alternate" href="https://buwana.ecobricks.org/fr/<?php echo ($name); ;?>" hreflang="fr">
	<link rel="alternate" href="http://buwana.ecobricks.org/en/<?php echo ($name); ;?>" hreflang="x-default">


<meta property="og:site_name" content="Buwana EarthenAuth">


<!-- This allows the site to be used a PWA on iPhones-->

<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="apple-mobile-web-app-title" content="Buwana EarthenAuth">
<meta name="apple-mobile-web-app-status-bar-style" content="black">


<link rel="apple-touch-icon" sizes="57x57" href="../icons/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="../icons/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="../icons/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="../icons/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="../icons/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="../icons/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="../icons/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="../icons/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="../icons/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="32x32" href="../icons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="../icons/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="../icons/favicon-16x16.png">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="../icons/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

<!--MAIN STYLE SHEETS -->

<link rel="stylesheet" type="text/css" href="../styles/mode-slider.css?v=4">
<link rel="stylesheet" type="text/css" href="../styles/content-2025.css?v=3<?php echo ($version); ;?>">

<link rel="stylesheet" type="text/css" href="../styles/main.css?v=6<?php echo ($version); ;?>">

<!--Default Light Styles to load first-->
<link rel="preload" href="../styles/mode-light.css?v=1<?php echo ($version); ;?>" as="style" onload="this.rel='stylesheet'">
 <!--Stylesheets for light and dark mode.  They need to be called here-->
<link rel="stylesheet" href="../styles/mode-light.css?v=1<?php echo ($version); ;?>" media="(prefers-color-scheme: no-preference), (prefers-color-scheme: light)">
<link rel="stylesheet" href="../styles/mode-dark.css?v=1<?php echo ($version); ;?>" media="(prefers-color-scheme: dark)">

<link rel="stylesheet" type="text/css" href="../styles/footer.css?v=<?php echo ($version); ;?>">



<script src="../scripts/language-switcher.js?v=<?php echo ($version); ;?>"></script>

<script>


/*ROLL CALL*/
//
// window.onload = function() {
//      var siteName = 'gobrik.com';
//      var currentLanguage = '<?php echo ($lang); ?>'; // Default language code
//      switchLanguage(currentLanguage);
//  }

//
// function switchLanguage(langCode) {
//  currentLanguage = langCode; // Update the global language variable
//
//     // Dynamic selection of the correct translations object
//     const languageMappings = {
//         'en': {...en_Translations, ...en_Page_Translations},
//         'fr': {...fr_Translations, ...fr_Page_Translations},
//         'es': {...es_Translations, ...es_Page_Translations},
//         'id': {...id_Translations, ...id_Page_Translations}
//     };
//
//     const currentTranslations = languageMappings[currentLanguage];
//
//
//     const elements = document.querySelectorAll('[data-lang-id]');
//     elements.forEach(element => {
//         const langId = element.getAttribute('data-lang-id');
//         const translation = currentTranslations[langId]; // Access the correct translations
//         if (translation) {
//             if (element.tagName.toLowerCase() === 'input' && element.type !== 'submit') {
//                 element.placeholder = translation;
//             } else if (element.hasAttribute('aria-label')) {
//                 element.setAttribute('aria-label', translation);
//             } else if (element.tagName.toLowerCase() === 'img') {
//                 element.alt = translation;
//             } else {
//                 element.innerHTML = translation; // Directly set innerHTML for other elements
//             }
//         }
//     });
//
// }
</script>






<script src="../scripts/core-2025.js?v=3<?php echo ($version); ;?>"></script>


<!--This enables the Light and Dark mode switching-->
<script type="module" src="../scripts/mode-toggle.mjs.js?v=<?php echo ($version); ;?>"></script>
<!-- <script src="https://unpkg.com/website-carbon-badges@1.1.3/b.min.js" defer></script>  -->

<script src="../scripts/guided-tour.js?v=<?php echo ($version); ;?>" defer></script>

<script src="../scripts/site-search.js?v=<?php echo ($version); ;?>" defer></script>





<!-- Inline styling to lay out the most important part of the page for first load view-->

<STYLE>

@font-face {
  font-family: "Mulish";
  src: url("../fonts/Mulish-Light.ttf");
  font-display: swap;
  font-weight: 300;
 }

 @font-face {
  font-family: "Mulish";
  src: url("../fonts/Mulish-Regular.ttf");
  font-display: swap;
  font-weight: 500;
 }

 @font-face {
  font-family: "Arvo";
  src: url("../fonts/Arvo-Regular.ttf");
  font-display: swap;
 }


/* .rotate-plus { */
/*     display: inline-block; */
/*     transform: rotate(45deg); */
/*     transition: transform 0.5s ease; */
/* } */

/* .rotate-minus { */
/*     display: inline-block; */
/*     transform: rotate(90deg);  *//* This effectively rotates it back by 45 degrees from the .rotate-plus state */
/*     transition: transform 0.5s ease; */
/* } */





/*-----------------------------------------

INFO MODAL

--------------------------------------*/

#form-modal-message {
position: fixed;
top: 0px;
left: 0px;
width: 100%;
height: 100%;
background-color: var(--show-hide);
justify-content: center;
align-items: center;
z-index: 1000;
display: none;
}

.modal-content-box {

  position: relative;

  color: var(--h1);
  font-family: 'Mulish', sans-serif;

  display: flex;
  margin: auto;

}

@media screen and (min-width: 700px) {


    .modal-content-box {
        padding: 20px;
        border-radius: 10px;
        max-width: 90%;
        max-height: 80vh;
        min-height: 50%;
        min-width: 70%;
        width: 50%;

    }
}

@media screen and (max-width: 700px) {

    .modal-content-box {
        padding: 10px;
        border-radius: 8px;
        width: 88%;
        height: 80%;
        /*background: none !important;*/
    }

}

.modal-message {
  margin: auto;
}



.modal-hidden {
    display: none;
}

.modal-shown {
    display: flex;
}


.the-app-logo {

  background: url('<?= htmlspecialchars($app_info['app_logo_url']) ?>') center no-repeat;
  background-size: contain;
  width: 80%;
  height: 25%;
  margin-right: auto;
  margin-left: auto;
  margin:auto;
}

@media screen and (max-width: 700px) {
.the-app-logo {
max-height: 200px;
}

 .buwana-word-mark {
  max-height: 22px;
  }
}

#top-app-logo {
background: url('<?= htmlspecialchars($app_info['app_wordmark_url']) ?>') center no-repeat;
  background-size: contain;
 height: 80%;
 display: flex;
 cursor: pointer;
 width:100%;
 margin-right:70px;
 margin-top: 5px;
 }

 .buwana-word-mark {
 background: url('../svgs/b-logo.svg') center no-repeat;
   background-size: contain;
  height: 30px;
  width: 200px;
  margin: auto;
  margin-top: 5px;

  }





    @media (prefers-color-scheme: dark) {
        .the-app-logo {

          background: url('<?= htmlspecialchars($app_info['app_logo_dark_url']) ?>') center no-repeat;
          background-size: contain;
        }

    #top-app-logo {
    background: url('<?= htmlspecialchars($app_info['app_wordmark_dark_url']) ?>') center no-repeat;
      background-size: contain;
      }
    }



</style>





</HEAD>


<BODY>


<div id="form-modal-message" class="modal-hidden">
    <button type="button" onclick="closeInfoModal()" aria-label="Click to close modal" class="x-button"></button>
    <div class="modal-content-box" id="modal-content-box">
        <div class="modal-message"></div>
    </div>
    <div class="modal-photo-box" id="modal-photo-box">
        <div class ="modal-photo"></div>
    </div>

</div>



<!-- MAIN MENU -->
<div id="main-menu-overlay" class="overlay-settings" style="display:none;">
  <button type="button" onclick="closeMainMenu()" aria-label="Click to close main menu page" class="x-button"></button>
  <div class="overlay-content-settings">

   <div class="the-app-logo" alt="<?= htmlspecialchars($app_info['app_display_name']) ?> App Logo" title="<?= htmlspecialchars($app_info['app_display_name']) ?> <?= htmlspecialchars($app_info['app_version']) ?> | <?= htmlspecialchars($app_info['app_slogan']) ?>"></div>


    <div class="menu-page-item">
      <a href="<?= htmlspecialchars($app_info['app_url']) ?>" data-lang-id="1000-landing-page-x">
        Back to <?= htmlspecialchars($app_info['app_display_name']) ?>
      </a>
      <span class="status-circle" style="background-color: GREEN;" title="Deployed. Working well!"></span>
    </div>

<!--<div class="menu-page-item">
      <a href="bug-report.php" data-lang-id="1000-bug-report">
        Report a Bug

      </a>
      <span class="status-circle" style="background-color: blue;" title="Under development"></span>
    </div>-->

<div class="menu-page-item">
<a href="#" onclick="openTermsModal(); return false;">Terms of Use</a>

  <span class="status-circle" style="background-color: YELLOW;" title="Terms of Use"></span>
</div>
<div class="menu-page-item">
<a href="#" onclick="openPrivacyModal(); return false;">Privacy Policy</a>

  <span class="status-circle" style="background-color: ORANGE;" title="Privacy Policy"></span>
</div>

<div class="menu-page-item">
  <a href="javascript:void(0);" onclick="openAboutBuwanaModal()" data-lang-id="1000-about-buwana">
    About Buwana
  </a>
  <span class="status-circle" style="background-color: RED;" title="Under development"></span>
</div>


<!--<h3><?= htmlspecialchars($app_info['app_slogan']) ?></h3>
-->
<p style="margin:auto;margin-bottom: 5px;font-size: smaller; text-align: center;">Authentication by</p>
<div class="buwana-word-mark" alt="Buwana Logo" title="Authentication by Buwana" href="https://github.com/gea-ecobricks/buwana"></div>


  </div> <!-- close overlay-content-settings -->
</div> <!-- close main menu -->



<div id="page-content"> <!--modal blur added here-->





<!-- HEADER / TOP MENU -->
<header id="header" class="top-menu">
  <!-- Left Menu Button -->
  <button type="button" class="side-menu-button" onclick="openSideMenu()" aria-label="Open Main Menu"></button>

  <!-- App Logo -->
  <div id="top-app-logo"
       title="<?= htmlspecialchars($app_info['app_display_name']) ?> | v<?= htmlspecialchars($app_info['app_version']) ?>"
       onclick="redirectToAppHome('<?= htmlspecialchars($app_info['app_url']) ?>')">
  </div>

  <!-- Right Settings Buttons -->
  <div id="function-icons">
    <div id="settings-buttons" aria-label="App Settings Panel">
      <button type="button"
              id="top-settings-button"
              aria-label="Toggle settings menu"
              aria-expanded="false"
              aria-controls="language-menu-slider login-menu-slider"
              onclick="toggleSettingsMenu()">
      </button>

      <!-- Language Switch -->
      <div id="language-code"
           onclick="showLangSelector()"
           role="button"
           tabindex="0"
           aria-haspopup="true"
           aria-expanded="false"
           aria-controls="language-menu-slider"
           aria-label="Switch language">
        🌐 <span data-lang-id="000-language-code">EN</span>
      </div>

      <!-- Login Services -->
      <button type="button"
              class="top-login-button"
              onclick="showLoginSelector()"
              aria-haspopup="true"
              aria-expanded="false"
              aria-controls="login-menu-slider"
              aria-label="Login Services">
      </button>

      <!-- Dark Mode Toggle -->
      <dark-mode-toggle
        id="dark-mode-toggle-5"
        class="slider"
        style="min-width:82px;margin-top:-5px;margin-bottom:-15px;"
        appearance="toggle">
      </dark-mode-toggle>
    </div>
  </div>
</header>

<!-- LANGUAGE SELECTOR -->
<div id="language-menu-slider" class="top-slider-menu" tabindex="-1" role="menu">
  <div class="lang-selector-box">
    <button onclick="navigateTo('../id/<?php echo ($page); ?>.php')">🇮🇩 IN</button>
    <button onclick="navigateTo('../es/<?php echo ($page); ?>.php')">🇪🇸 ES</button>
    <button onclick="navigateTo('../fr/<?php echo ($page); ?>.php')">🇫🇷 FR</button>
    <button onclick="navigateTo('../en/<?php echo ($page); ?>.php')">🇬🇧 EN</button>
  </div>
</div>

<!-- LOGIN SELECTOR -->
<div id="login-menu-slider" class="top-slider-menu" tabindex="-1" role="menu">
  <div class="login-selector-box">
    <a class="login-selector" target="_blank" href="https://gobrik.com/en/go.php#home">🌍 GoBrik</a>
    <a class="login-selector" target="_blank" href="https://gobrik.com/email">🌒 EarthCal</a>
  </div>
</div>







<div id="main">
