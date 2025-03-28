<!-- PHP starts by laying out canonical URLs for the page and language -->

<?php
	$parts = explode ("/", $_SERVER['SCRIPT_NAME']);
	$name = $parts [count($parts)-1];
	if (strcmp($name, "welcome.php") == 0)
  $name = "";


	;?>


	<link rel="canonical" href="https://gobrik.com/<?php echo ($lang); ;?>/<?php echo ($name); ;?>">
	<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">

	<link rel="alternate" href="https://gobrik.com/en/<?php echo ($name); ;?>" hreflang="en">
	<link rel="alternate" href="https://gobrik.com/id/<?php echo ($name); ;?>" hreflang="id">
	<link rel="alternate" href="https://gobrik.com/es/<?php echo ($name); ;?>" hreflang="es">
	<link rel="alternate" href="https://gobrik.com/fr/<?php echo ($name); ;?>" hreflang="fr">
	<link rel="alternate" href="http://gobrik.com/en/<?php echo ($name); ;?>" hreflang="x-default">


<meta property="og:site_name" content="gobrik.com">
<meta property="article:publisher" content="https://web.facebook.com/gobrik.com">


<!-- This allows gobrik.com to be used a PWA on iPhones-->

<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="apple-mobile-web-app-title" content="gobrik.com">
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
<link rel="stylesheet" type="text/css" href="../styles/content-2024.css?v=3<?php echo ($version); ;?>">

<link rel="stylesheet" type="text/css" href="../styles/main.css?v=6<?php echo ($version); ;?>">

<!--Default Light Styles to load first-->
<link rel="preload" href="../styles/mode-light.css?v=1<?php echo ($version); ;?>" as="style" onload="this.rel='stylesheet'">
 <!--Stylesheets for light and dark mode.  They need to be called here-->
<link rel="stylesheet" href="../styles/mode-light.css?v=1<?php echo ($version); ;?>" media="(prefers-color-scheme: no-preference), (prefers-color-scheme: light)">
<link rel="stylesheet" href="../styles/mode-dark.css?v=1<?php echo ($version); ;?>" media="(prefers-color-scheme: dark)">



<link rel="stylesheet" type="text/css" href="../styles/footer.css?v=<?php echo ($version); ;?>">

<?php if ($page === "messenger"): ?>
    <style>



    @media screen and (max-width: 800px) {




         .start-convo-button {
       width: 100%;
       border-radius: 15px;
        }


    #mobileBackToConvos {
      position: absolute;
      z-index: 100;
      top: 20px;
      left: -10px;
      width: 40px;
      padding: 10px 5px 10px 12px;
      border: 1px grey solid;
      font-size: 2em;
      background: #747474;

      }

    #messenger-welcome {
    display: none;
    }

    #toggleConvoDrawer  {
    display: none;
    }

        .form-container {
            width: calc(100% - 4px) !important;
            margin-right: 2px !important;
            margin-left: 0px !important;
            /* max-width: 600px; */
            padding: 2px;
            position: relative;

        }

/*         #form-submission-box { */

/*             margin-top: 100px !important; */
/*         } */


        #messageInput {
            padding: 10px;
            padding-left: 14px;
            background: var(--main-background);
            color: var(--text-color);
            font-size: 1em;
            border-radius: 20px;
            width: -moz-available;
            margin-left: 55px;
            resize: none; /* Prevents manual resizing */
            overflow: hidden; /* Hides the scrollbar */
            max-height: calc(1.5em * 5 + 30px); /* Adjusts to a max of 5 rows plus padding */
            line-height: 1.5em;
            border: none; /* Removes all borders */
            outline: none; /* Removes the border when selected */
            font-family: 'Mulish', sans-serif;
            width: 100%;
        }

        .conversation-list-container {
            width:100%;
            }

        .message-thread {
            display:none;
        }

        #startConvoDrawer {
            display:none;
            }

        #header {
            padding-top:10px !important;
            height: 60px;
            }



        #main {
        background: var(--top-header-main)};
                }


        .messenger-container {
            height: calc(100vh - 120px) !important;
            }
}
    </style>
<?php endif; ?>




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






<script src="../scripts/core-2024.js?v=3<?php echo ($version); ;?>"></script>


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


.rotate-plus {
    display: inline-block;
    transform: rotate(45deg);
    transition: transform 0.5s ease;
}

.rotate-minus {
    display: inline-block;
    transform: rotate(90deg); /* This effectively rotates it back by 45 degrees from the .rotate-plus state */
    transition: transform 0.5s ease;
}





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

<!-- TOUR SLIDER -->



<div id="guided-tour" class="modal">
    <div class="tour-content">
      <span class="close-tour close" style="padding:10px" onclick="closeTour();">&times;</span>

      <div id="information-one" class="information">
        <div class="tour-image tour-slide1"></div>
        <div class="modal-header" data-lang-id="200-tour-1-header-welcome">Welcome to Gobrik!</div>
        <div class="modal-description" data-lang-id="201-tour-1-description">GoBrik is ecological action platform that supports individuals and communities to manage their ecobricks, projects and more.</div>
        <button class="next-slide" data-lang-id="202-tour-start-button">Start Tour ➔</button>
    </div>

    <div id="information-two" style="display:none;" class="information">
        <div class="tour-image tour-slide2"></div>
        <div class="modal-header" data-lang-id="203-tour-2-header">Your ecobricks are valuable.</div>
        <div class="modal-description" data-lang-id="204-tour-2-description">We're here for your ecobricking. Every ecobrick represents plastic (and carbon!) that won't be polluting the biosphere or churning a factory.  We've built GoBrik to help you track and manage each gram of plastic that you sequester so to amplify our collective ecological service.</div>
        <button class="back" data-lang-id="205-tour-back-button">↩ Back</button>
        <button class="next-slide" data-lang-id="206-tour-next-button">Next ➔</button>
        <div class="reference" data-lang-id="207-tour-2-reference">What is an ecobrick? <a href="https://en.wikipedia.org/wiki/Ecobricks" target="_blank">wikipedia/wiki/ecobricks</a>  |   <a href="https://ecobricks.org/en/what.php" target="_blank">Ecobricks.org</a></div>
    </div>



      <div id="information-three" style="display:none;" class="information">
      <div class="tour-image tour-slide3"></div>

        <div class="modal-header" data-lang-id="208-tour-3-header">Peer Validation</div>
        <div class="modal-description" data-lang-id="209-tour-3-description">Not every bottle packed with plastic is an ecobrick.  On GoBrik, ecobricks are peer-reviewed and authenticated.  Authenticated ecobricks represent sequestered plastic which generates brikcoins for the validators.</div>
        <button class="back" data-lang-id="205-tour-back-button">↩ Back</button>
        <button class="next-slide" data-lang-id="206-tour-next-button">Next ➔</button>
        <div class="reference" data-lang-id="210-reference-tour-3-description">Authenticated Ecobrick Sequestered Plastic: <a href="https://ecobricks.org/aes/">ecobricks.org/aes</a></div>
      </div>


      <div id="information-four" style="display:none;" class="information">
      <div class="tour-image tour-slide4"></div>
        <div class="modal-header" data-lang-id="211-tour-4-header" >For our Children's Children</div>
        <div class="modal-description" data-lang-id="212-tour-4-description">Your ecobrick will last for centuries!  Our Brikchain record of authenticated ecobricks is designed to last for just as long.  Using serial numbers, ecobricks can be managed and their data can be looked up.</div>
        <button class="back" data-lang-id="205-tour-back-button">↩ Back</button>
        <button class="next-slide" data-lang-id="206-tour-next-button">Next ➔</button>
        <div class="reference" data-lang-id="213-tour-4-reference">Why we ecobrick: <a href="build">gobrik.com/why</a></div>
    </div>



      <div id="information-five" style="display:none;" class="information" >
      <div class="tour-image tour-slide5"></div>
      <div class="modal-header" data-lang-id="214-tour-5-header">Projects & Exchanges</div>
      <div class="modal-description" data-lang-id="215-tour-5-description">GoBrik let's you allocate your ecobricks into projects and communities.  Keep track of how many ecobricks you've got and how much plastic and carbon your construction will sequester.  Once complete, log your project and inspire other ecobrickers.</div>
        <button class="back" data-lang-id="205-tour-back-button">↩ Back</button>
        <button class="next-slide" data-lang-id="206-tour-next-button">Next ➔</button>
        <div class="reference" data-lang-id="216-tour-5-reference">Projects are coming soon on GoBrik 3.0</div>
      </div>

      <div id="information-six" style="display:none;" class="information" >
        <div class="tour-image tour-slide6"></div>

        <div class="modal-header" data-lang-id="217-tour-6-header">Good Green Vibes</div>
        <div class="modal-description" data-lang-id="218-tour-6-description">Our GoBrik app and Buwana accounts are guided by regenerative Earthen principles.  Our code is open source, our books transparent and as of GoBrik 3.0 we do not use any corporate services or protocols.</div>
        <button class="back" data-lang-id="205-tour-back-button">↩ Back</button>

        <button class="next-slide" onclick="closeTour();" data-lang-id="219-tour-done-button">✓ Done</button>
        <div class="reference" data-lang-id="220-tour-6-reference">Learn more about us <a href="https://ecobricks.org/about">ecobricks.og/about</a> and our <a href="https://ecobricks.org/principles">principles.</a></div>

      </div>
    </div>
    </div>

<!-- MAIN MENU -->
<div id="main-menu-overlay" class="overlay-settings" style="display:none;">
  <button type="button" onclick="closeSettings()" aria-label="Click to close settings page" class="x-button"></button>
  <div class="overlay-content-settings">
    <!-- Check if the user is logged in before displaying the logged-in status box : earthen values set by earthenAuth_helper-->
    <?php if ($is_logged_in): ?>
      <div class="menu-page-item" style="display: flex; flex-direction: column; align-items: flex-start; cursor:unset;">
        <div style="width:100%; display: flex; align-items: center;">
          <div style="color: var(--text-color); margin-left: 0px;">
              <span data-lang-id="1000-logged-user"></span>
              <span><?php echo htmlspecialchars($first_name); ?></span>
              <span style="color: var(--subdued);">
                <?php
                if ($gea_status !== null) {
                    echo "  |  " . htmlspecialchars($gea_status);
                } else {
                    $response['error'] = 'gea_status_error';
                    echo "GEA Status: Not available"; // Optional: display an alternative message
                }
                ?>
                </span>
            </div>
        </div>

        <div class="logged-in-links" style="width:100%; font-size: 0.8em; margin-top: 5px; text-align: left;">
           <p style="font-size:0.9em; margin-bottom: 3px;
  margin-top: 5px;"><span id="continent-icon"><?php echo htmlspecialchars($user_continent_icon); ?> </span> <span style="color:green;"><?php echo htmlspecialchars($user_location_watershed); ?></span> <span style="color:grey">| <?php echo htmlspecialchars($user_community_name); ?></span></p>

           <p style="font-size:0.9em;">
  ⚙️ <span onclick="openProfile()" class="underline-link" data-lang-id="1000-profile-settings" style="cursor: pointer;" class="underline-link" title="Update your user settings">Profile settings</span> |
  🐳 <span onclick="logoutUser()" class="underline-link" data-lang-id="1000-log-out" style="cursor: pointer;" class="underline-link" title="Log out completely">Log out</span>
</p>

        </div>
      </div>
      <div class="menu-page-item">
        <a href="dashboard.php" aria-label="Log" data-lang-id="1000-dashboard">Dashboard</a>
        <span class="status-circle" style="background-color: green;" title="Working. Under development"></span>
      </div>
    <?php else: ?>
      <!-- If the user is not logged in, show the login/signup options -->
      <div class="menu-page-item">
        <a href="login.php" data-lang-id="1000-login" style="margin-right:10px; min-width: 65px;width:75px;">Log in</a> |
        <a href="signup.php" data-lang-id="1000-sign-up" style="margin-left:10px">Sign up</a>
        <span class="status-circle" style="background-color: limegreen;" title="Deployed. Under beta testing."></span>
      </div>
    <?php endif; ?>



<!-- Other menu items -->
<div class="menu-page-item">
  <a href="log.php" data-lang-id="1000-log-ecobricks">
    Log Ecobricks

  </a>
  <span class="status-circle" style="background-color: green;" title="Working.  Being tested."></span>
</div>

<div class="menu-page-item">
  <a href="newest-briks.php" data-lang-id="1000-latest-ecobricks">
    Latest Ecobricks

  </a>
  <span class="status-circle" style="background-color: green;" title="Working well."></span>
</div>

<!-- Uncommented for demonstration purposes
<div class="menu-page-item">
  <a href="brikchain.php" data-lang-id="1000-brikchain">
    The Brikchain
    <span class="status-circle" style="background-color: red;" title="Under development, but active!"></span>
  </a>
</div>

<div class="menu-page-item">
  <a href="newest-projects.php" data-lang-id="1000-featured-projects">
    Featured Projects
    <span class="status-circle" style="background-color: red;" title="Not yet deployed"></span>
  </a>
</div>

<div class="menu-page-item">
  <a href="newest-trainings.php" data-lang-id="1000-latest-trainings">
    Latest Trainings
    <span class="status-circle" style="background-color: red;" title="Not yet deployed"></span>
  </a>
</div>
-->

<div class="menu-page-item">
  <a href="bug-report.php" data-lang-id="1000-bug-report">
    Report a Bug

  </a>
  <span class="status-circle" style="background-color: green;" title="Working."></span>
</div>

<!--
    <div class="menu-page-item">
  <a href="messenger.php" data-lang-id="1000-bug-report">
    Messenger

  </a>
  <span class="status-circle" style="background-color: yellow;" title="Under development. Only working on desktop"></span>
</div>-->

<div class="menu-page-item">
  <a href="index.php" data-lang-id="1000-landing-page">
    Home page

  </a>
  <span class="status-circle" style="background-color: green;" title="Deployed. Working well!"></span>
</div>


    <!-- GoBrik Tour at the bottom -->
    <div class="menu-page-item">
      <a data-lang-id="1001-gobrik-tour" onclick="closeSettings(); setTimeout(guidedTour, 500);">GoBrik Tour</a>
      <span class="status-circle" style="background-color: yellow;" title="Working. Not translated."></span>
    </div>
  </div> <!-- close overlay-content-settings -->
</div> <!-- close main menu -->



<div id="page-content"> <!--modal blur added here-->





<!-- SEARCH PAGE -->
<div id="right-search-overlay" class="search-overlay">
    <button type="button" onclick="closeSearch(), clearResults()" aria-label="Close Search" class="x-button" id="modal-x-button"></button>
    <div class="search-overlay-content">
        <div>
            <h1 style="font-family:'Arvo', serif;text-shadow: none;" data-lang-id="1100-search-title">Search</h1>
            <p style="text-align:center; width:100%;" data-lang-id="1101-search-intro">Find any ecobrick on the Brikchain</p>
        </div>
        <div class="search-box">
            <div class="search-section" data-lang-id="1102-search-section">
                <input id="search_input" type="text" placeholder="Enter serial..." aria-label="Enter Serial...">
                <button class="btn main-search-button" onclick="ecobrickSearch()" aria-label="Search Button"></button>
            </div>
        </div>
        <!-- Search results div -->
        <div id="search-results" style="display: none;">
            <table id="ecobrick-search-return" class="display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th data-lang-id="1103-brik">Brik</th>
                        <th data-lang-id="1104-weight">Weight</th>
                        <th data-lang-id="1108-volume">Volume</th>
                        <th data-lang-id="1111-maker">Maker</th>
                        <th data-lang-id="1110-date-logged">Logged</th>
                        <th data-lang-id="1105-location">Location</th>
                        <th data-lang-id="1106-status">Status</th>
                        <th data-lang-id="1107-serial">Serial</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- DataTables will populate this via AJAX -->
                </tbody>
            </table>
            <p style="color:var(--subdued-text);font-size:medium;margin-top:40px;margin-bottom:20px;" data-lang-id="1108-search-footer">
                Looking for general information about ecobricks? Search on
                <a target="_blank" href="https://ecobricks.org/faqs">ecobricks.org</a>
            </p>
        </div>
    </div>
</div>








    <div id="header" class="top-menu" style="display: flex;
    flex-flow: row;">

        <button type="button" class="side-menu-button"  onclick="openSideMenu()" aria-label="Open Menu">
        </button>

    <div id="logo-title" style="height: 100%; display: flex; cursor: pointer;width:100%;margin-right:70px;" title="gobrik.com | v<?php echo $version; ?>" onclick="redirectToWelcome()">


<!-- Created with Inkscape (http://www.inkscape.org/) -->
<svg width="86.737mm" height="27.367mm" version="1.1" viewBox="0 0 86.737 27.367" xmlns="http://www.w3.org/2000/svg" id="gea-logo">
 <g id="logo-gobrik" transform="translate(-37.306 -28.575)" fill="var(--logo-color)">
  <path d="m66.878 28.575h6.1451v7.3822q0.73414-0.35348 1.5091-0.54381t1.5906-0.19033q1.5635 0 2.8958 0.5846 1.3323 0.5846 2.2976 1.5906 0.96526 1.006 1.5091 2.352 0.55741 1.3459 0.55741 2.855 0 1.5227-0.54381 2.8686-0.53022 1.3459-1.4955 2.352-0.96526 0.99246-2.2976 1.5771-1.3323 0.571-2.923 0.571-0.8565 0-1.6314-0.16314t-1.4683-0.46224v0.38067h-4.1737v-17.429h-1.9713zm8.9593 10.4q-0.89729 0-1.6178 0.40786-0.70695 0.40786-1.1964 1.1692v4.2145q0.43505 0.65257 1.1556 1.0604 0.73414 0.39426 1.6314 0.39426 0.69336 0 1.3187-0.24471 0.62538-0.25831 1.0876-0.72055 0.47583-0.47584 0.74774-1.1284 0.2719-0.66617 0.2719-1.4955 0-0.84291-0.2719-1.5227t-0.73414-1.1556-1.0876-0.72055q-0.61179-0.25831-1.3051-0.25831zm20.985 1.7946h-3.8203v-1.4547q-0.55741 0-1.0196 0.08157-0.46224 0.06798-0.81572 0.24472-0.33988 0.17674-0.5846 0.48943-0.23112 0.31269-0.32629 0.77493v5.0982h2.8686v3.7251h-9.1904v-3.7251h2.1073v-6.6345h-2.0801v-3.7251h6.2674v0.99246q0.59819-0.47584 1.3459-0.73414 0.74774-0.25831 1.6314-0.25831 0.89729 0 1.849 0.0136 0.95167 0.0136 1.7674 0.0136zm1.0412-5.1254h6.3218v10.36h1.9577v3.7251h-8.2795v-3.7251h2.1209v-6.6345h-2.1209zm1.9033-4.5408q0-0.50302 0.17674-0.93807 0.19034-0.44864 0.51662-0.77493 0.32629-0.32629 0.76134-0.51662 0.44864-0.19033 0.96526-0.19033 0.48943 0 0.92448 0.19033t0.76133 0.51662q0.32629 0.32629 0.51662 0.77493 0.19034 0.43505 0.19034 0.93807 0 0.50302-0.19034 0.95167-0.19033 0.43505-0.51662 0.76134-0.32628 0.32629-0.76133 0.51662-0.43505 0.19033-0.92448 0.19033-0.51662 0-0.96526-0.19033-0.43505-0.19033-0.76134-0.51662-0.32628-0.32629-0.51662-0.76134-0.17674-0.44864-0.17674-0.95167zm7.2814-2.5287h6.349v12.195h2.3792l0.97886-1.4003v-3.7251h7.2871v3.7251h-2.5287l-2.6239 3.0317 2.6783 3.2357h2.3384v4.0922h-3.8203l-4.568-6.0363h-2.1209v2.3112h1.4411v3.7251h-7.7901v-3.7251h2.1752v-13.704h-2.1752z"/>
  <path d="m47.72 48.492q-0.69336 0.73414-1.6178 1.142-0.91088 0.40786-2.0801 0.40786-1.4275 0-2.6647-0.48943-1.2236-0.50302-2.1345-1.4547-0.89729-0.96526-1.4139-2.352-0.50302-1.4003-0.50302-3.1949 0-1.6314 0.48943-2.9638 0.48943-1.3459 1.3867-2.284 0.89729-0.95167 2.1616-1.4683 1.2644-0.53022 2.8142-0.53022 1.0604 0 1.9033 0.24472 0.8565 0.23112 1.6586 0.81572v-0.72055h2.5287v13.527q0 3.3444-1.5906 5.0574-1.5771 1.713-4.9215 1.713-0.73414 0-1.5363-0.10876-0.80212-0.10876-1.5906-0.35348-0.77493-0.23112-1.5227-0.59819-0.73414-0.36707-1.3595-0.89729l1.3595-2.0529q0.40786 0.35348 0.92448 0.65257 0.53022 0.2991 1.1284 0.50302 0.59819 0.21752 1.2372 0.33988t1.278 0.12236q1.0468 0 1.8082-0.21752 0.77493-0.21752 1.2644-0.77493 0.50302-0.54381 0.74774-1.4819 0.24472-0.93807 0.24472-2.3792zm-3.3716-0.65257q0.62538 0 1.142-0.12236 0.51662-0.12236 0.93807-0.31269 0.42145-0.20393 0.73414-0.46224 0.32629-0.25831 0.55741-0.54381v-7.7085q-0.69336-0.61179-1.6042-0.88369-0.89729-0.2719-1.849-0.2719-0.89729 0-1.6858 0.33988-0.78852 0.32629-1.3867 0.96526-0.5846 0.63898-0.92448 1.5635-0.33988 0.92448-0.33988 2.1209 0 1.3051 0.32629 2.2976t0.91088 1.6722q0.5846 0.66617 1.4003 1.006 0.81572 0.33988 1.781 0.33988zm7.9204-5.1526q0.0136-1.6722 0.53022-3.0453 0.53022-1.3731 1.4683-2.352 0.93807-0.99245 2.2296-1.5227 1.2916-0.53022 2.8414-0.53022 1.5635 0 2.855 0.53022 1.3051 0.53022 2.2296 1.5091 0.92448 0.96526 1.4411 2.3384 0.51662 1.3731 0.51662 3.0453 0 1.645-0.51662 3.0317-0.51662 1.3731-1.4411 2.3656t-2.2296 1.5499q-1.2916 0.54381-2.855 0.54381-1.5906 0-2.8822-0.54381t-2.216-1.5227q-0.92448-0.99245-1.4411-2.3656-0.51662-1.3731-0.53022-3.0317zm2.6783 0q0 1.21 0.33988 2.1888 0.35348 0.97886 0.95167 1.6722 0.61179 0.67976 1.4003 1.0604 0.80212 0.36707 1.6994 0.36707 0.93807 0 1.7402-0.36707 0.81572-0.36707 1.4139-1.0468 0.59819-0.69336 0.93807-1.6722 0.33988-0.97886 0.33988-2.2024 0-1.278-0.33988-2.2704-0.33988-0.99245-0.93807-1.6586-0.5846-0.66617-1.4003-1.006-0.81572-0.33988-1.7538-0.33988-0.89729 0-1.6994 0.33988-0.78853 0.33988-1.4003 1.006-0.59819 0.66617-0.95167 1.6586-0.33988 0.99245-0.33988 2.2704z"/>
 </g>
</svg>


        </div>

        <div id="function-icons" style="display: flex;flex-flow:row;margin:auto 10px auto auto;">


        <!--<button type="button" class="top-search-button"  onclick="openSearch()"  aria-label="Search site">
                </button>-->

            <div id="settings-buttons">

                <button type="button" id="top-settings-button"  aria-label="Open site settings"></button>

                <div id="language-code" onclick="showLangSelector()" aria-label="Switch languages">🌐 <span data-lang-id="000-language-code">EN</span></div>

                <button type="button" class="top-search-button"  onclick="openSearch()"  aria-label="Search site">
                </button>

                <!--<button type="button" class="top-login-button" onclick="showLoginSelector()" aria-label="Login options"></button>

                <button type="button" class="top-lang-button" onclick="showLangSelector()" aria-label="Switch languages"></button>
-->
                <dark-mode-toggle
                id="dark-mode-toggle-5" style="min-width:82px;margin-top:-5px;margin-bottom:-15px;"
                class="slider"
                appearance="toggle">
                </dark-mode-toggle>
            </div>
        </div>
    </div>


    <div id="language-menu-slider">
  <div class="lang-selector-box">
    <button type="button" class="lang-selector" onclick="navigateTo('../id/<?php echo ($page); ?>.php')" aria-label="Buka versi bahasa Indonesia">🇮🇩 IN</button>
    <button type="button" class="lang-selector" onclick="navigateTo('../es/<?php echo ($page); ?>.php')" aria-label="Ir a la versión en español">🇪🇸 ES</button>
    <button type="button" class="lang-selector" onclick="navigateTo('../fr/<?php echo ($page); ?>.php')" aria-label="Aller à la version française">🇫🇷 FR</button>
    <button type="button" class="lang-selector" onclick="navigateTo('../en/<?php echo ($page); ?>.php')" aria-label="Go to English version">🇬🇧 EN</button>
  </div>
</div>


<script>
function navigateTo(url) {
  window.location.href = url;
}
</script>


<!--
<div id="language-menu-slider">
    <div class="lang-selector-box">
      <button type="button" class="lang-selector" onclick="switchLanguage('id')">🇮🇩 IN</button>
      <button type="button" class="lang-selector" onclick="switchLanguage('es')">🇪🇸 ES</button>
      <button type="button" class="lang-selector" onclick="switchLanguage('fr')">🇫🇷 FR</button>
      <button type="button" class="lang-selector" onclick="switchLanguage('en')">🇬🇧 EN</button>
    </div>
  </div> -->


<div id="login-menu-slider">
  <div class="login-selector-box">
    <a class="login-selector" target="_blank" href='https://gobrik.com/en/go.php#home'>
      <i style="background: url(../icons/gobrik-icon-white.svg) no-repeat; width:15px; height:15px;display: inline-block;background-size:contain;margin-right:4px;"></i>GoBrik</a>
    <a class="login-selector" target="_blank" href='https://gobrik.com/email'>✉️ Trainer Email</a>
    <a class="login-selector" target="_blank" href='https://nextcloud.gobrik.com'><i style="background: url(../icons/next-cloud-white.svg) no-repeat; width:22px; height:11px;display: inline-block;background-size:contain;margin-right:4px;"></i>Trainer NextCloud</a>
    <button type="button" class="login-selector" onclick="clearSiteCache()" data-lang-id="1003-reset-preferences">❌ Reset Preferences</button>
  </div>
</div>


<div id="main">
