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
<svg width="13.136mm" height="2.451mm" version="1.1" viewBox="0 0 13.136 2.451" xmlns="http://www.w3.org/2000/svg">
 <g id="logo-gobrik" transform="translate(-64.993 -84.481)">
  <path d="m64.993 84.481v0.42478h0.22479v1.9875h0.47594v-0.043408c0.052708 0.02274 0.10852 0.04031 0.16743 0.05271 0.058911 0.0124 0.12092 0.018603 0.18604 0.018603 0.12092 0 0.23203-0.021706 0.33331-0.065112 0.10129-0.044442 0.18862-0.10439 0.262-0.17983 0.073381-0.076481 0.13022-0.16588 0.17053-0.2682 0.04134-0.10232 0.062012-0.21136 0.062012-0.32711 0-0.11472-0.021189-0.22324-0.063562-0.32556-0.041342-0.10232-0.098703-0.19172-0.17208-0.2682-0.073381-0.076483-0.16071-0.13694-0.262-0.18138s-0.21136-0.066663-0.33021-0.066663c-0.06201 0-0.12247 0.007238-0.18138 0.021704-0.058908 0.014467-0.11627 0.035139-0.17208 0.062012v-0.84181h-0.70073zm7.1856 0.7705c-0.12919 0-0.25166 0.01912-0.36742 0.05736-0.10482 0.034942-0.20576 0.084379-0.30282 0.1478v-0.1695h-0.83871v0.42478h0.17208l-0.20464 0.62322-0.33331-1.048h-0.45424l-0.33021 0.99684-0.17053-0.57206h0.18138v-0.42478h-0.83871v0.42478h0.17983l0.39843 1.1875h0.45114l0.34262-1.0247 0.33641 1.0247h0.46354l0.42943-1.1875h0.21549v-0.1819l0.12144 0.24546c0.068214-0.036174 0.13901-0.063566 0.21239-0.082166 0.074414-0.01964 0.1602-0.029455 0.25735-0.029455 0.04651 0 0.089401 0.004652 0.12867 0.013952 0.039273 0.008267 0.072863 0.022218 0.10077 0.041858 0.027907 0.0186 0.049613 0.042891 0.065113 0.072864 0.0155 0.02894 0.023254 0.063563 0.023254 0.10387v0.04961c-0.051675-0.011373-0.108-0.020672-0.16898-0.027905-0.059945-0.008267-0.12661-0.012403-0.19999-0.012403-0.10025 0-0.18965 0.012918-0.2682 0.038758-0.077518 0.024806-0.14315 0.059946-0.19689 0.10542-0.053742 0.044441-0.095083 0.098184-0.12402 0.16123-0.027907 0.063045-0.041858 0.13177-0.041858 0.20619 0 0.073379 0.013951 0.14159 0.041858 0.20464 0.027909 0.062014 0.067699 0.11627 0.11937 0.16278 0.05271 0.045474 0.11679 0.081648 0.19224 0.10852 0.076481 0.02584 0.16278 0.038757 0.2589 0.038757 0.03824 0 0.07338-0.002551 0.10542-0.007751 0.03204-0.004134 0.063044-0.011371 0.093017-0.021704 0.029974-0.009307 0.059944-0.021707 0.089917-0.037207 0.029974-0.016534 0.063046-0.036172 0.099219-0.058912v0.086817h0.66973v-0.42478h-0.21549v-0.58446c0-0.11576-0.01757-0.21394-0.05271-0.29456-0.034105-0.080618-0.081647-0.14573-0.14263-0.19534-0.060978-0.05064-0.13436-0.086814-0.22014-0.10852-0.084749-0.02274-0.17725-0.034106-0.2775-0.034106zm3.7186 0c-0.12919 0-0.25166 0.01912-0.36742 0.05736-0.11472 0.038241-0.22479 0.093535-0.33021 0.16588l0.14883 0.30076c0.068214-0.036174 0.13901-0.063566 0.21239-0.082166 0.074414-0.01964 0.1602-0.029455 0.25735-0.029455 0.04651 0 0.089401 0.004652 0.12867 0.013952 0.039273 0.008267 0.072863 0.022218 0.10077 0.041858 0.027907 0.0186 0.049613 0.042891 0.065113 0.072864 0.0155 0.02894 0.023254 0.063563 0.023254 0.10387v0.04961c-0.051675-0.011373-0.108-0.020672-0.16898-0.027905-0.059945-0.008267-0.12661-0.012403-0.19999-0.012403-0.10025 0-0.18965 0.012918-0.2682 0.038758-0.077518 0.024806-0.14315 0.059946-0.19689 0.10542-0.053742 0.044441-0.095083 0.098184-0.12402 0.16123-0.027907 0.063045-0.041858 0.13177-0.041858 0.20619 0 0.017325 0.001028 0.03441 0.002584 0.05116h-0.16278v-0.57516c0-0.20774-0.052193-0.36535-0.15658-0.47284-0.10439-0.10852-0.25322-0.16278-0.44648-0.16278-0.091984 0-0.17983 0.015502-0.26355 0.046509-0.082682 0.029976-0.15761 0.071316-0.22479 0.12402v-0.14108h-0.73174v0.42478h0.23254v0.75654h-0.23564v0.42478h0.99839v-0.42478h-0.26045v-0.61857c0.051673-0.06511 0.1049-0.10955 0.15968-0.13333 0.05478-0.023773 0.10904-0.035656 0.16278-0.035656 0.078551 0 0.1416 0.025834 0.18914 0.077514 0.04754 0.050643 0.071314 0.12092 0.071314 0.21084v0.92398h0.73329v-0.22169c0.024951 0.041783 0.056135 0.079511 0.093535 0.11317 0.05271 0.045474 0.11679 0.081648 0.19224 0.10852 0.076481 0.02584 0.16278 0.038757 0.2589 0.038757 0.03824 0 0.07338-0.002551 0.10542-0.007751 0.03204-0.004134 0.063044-0.011371 0.093017-0.021704 0.029974-0.009307 0.059944-0.021707 0.089917-0.037207 0.029974-0.016534 0.063046-0.036172 0.099219-0.058912v0.086817h0.66973v-0.42478h-0.21549v-0.58446c0-0.11576-0.01757-0.21394-0.05271-0.29456-0.034105-0.080618-0.081647-0.14573-0.14263-0.19534-0.060978-0.05064-0.13436-0.086814-0.22014-0.10852-0.084749-0.02274-0.17725-0.034106-0.2775-0.034106zm-9.0976 0.035656v0.42478h0.20929v0.5209c0 0.11472 0.016536 0.21446 0.049609 0.29921 0.034104 0.084746 0.080096 0.15554 0.13798 0.21239 0.058911 0.055809 0.12764 0.097667 0.20619 0.12557 0.078548 0.027907 0.16226 0.041858 0.25115 0.041858 0.096118 0 0.18087-0.013434 0.25425-0.040307 0.073378-0.026874 0.13746-0.065115 0.19224-0.11472v0.13643h0.68058v-0.42478h-0.19379v-1.1813h-0.75964v0.42478h0.25735v0.62942c-0.022741 0.065112-0.059431 0.11679-0.11007 0.15503-0.050641 0.03824-0.10697 0.057361-0.16898 0.057361-0.087846 0-0.15916-0.026359-0.21394-0.079065-0.05478-0.053744-0.082166-0.13436-0.082166-0.24185v-0.94568h-0.71003zm-0.76378 0.16382a0.63622 0.63622 0 0 1 0.63614 0.63614 0.63622 0.63622 0 0 1-0.63614 0.63614 0.63622 0.63622 0 0 1-0.63614-0.63614 0.63622 0.63622 0 0 1 0.63614-0.63614zm6.0095 0.78806h0.37207v0.20464c-0.042373 0.034107-0.093532 0.063043-0.15348 0.086816-0.059945 0.023774-0.12661 0.035657-0.19999 0.035657-0.07028 0-0.12299-0.016019-0.15813-0.048059-0.034106-0.032042-0.051159-0.071833-0.051159-0.11937 0-0.025833 0.005169-0.048572 0.015503-0.068212 0.011366-0.01964 0.025834-0.036177 0.043408-0.04961 0.0186-0.01344 0.039272-0.023772 0.062012-0.031006 0.022733-0.007233 0.045989-0.010852 0.069763-0.010852zm3.7186 0h0.37207v0.20464c-0.042373 0.034107-0.093532 0.063043-0.15348 0.086816-0.059945 0.023774-0.12661 0.035657-0.19999 0.035657-0.070279 0-0.12299-0.016019-0.15813-0.048059-0.034106-0.032042-0.051159-0.071833-0.051159-0.11937 0-0.025833 0.005169-0.048572 0.015503-0.068212 0.011373-0.01964 0.025841-0.036177 0.043408-0.04961 0.0186-0.01344 0.039272-0.023772 0.062012-0.031006 0.022733-0.007233 0.045989-0.010852 0.069763-0.010852z" stroke-width=".26458"/>
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
