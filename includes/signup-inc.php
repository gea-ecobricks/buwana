
<!--  Set any page specific graphics to preload-->

<!--  Set any page specific graphics to preload
<link rel="preload" as="image" href="../webps/ecobrick-team-blank.webp" media="(max-width: 699px)">
<link rel="preload" as="image" href="../svgs/richard-and-team-day.svg">
<link rel="preload" as="image" href="../svgs/richard-and-team-night.svg">
<link rel="preload" as="image" href="../webps/biosphere2.webp">
<link rel="preload" as="image" href="../webps/biosphere-day.webp">-->



<?php require_once ("../meta/$page-$lang.php");?>

<STYLE>


/* Floating Label Container */
.float-label-group {
  position: relative;
  margin-top: 1.5rem;
  margin-bottom: 2rem;
}

/* Input Styling (inherits your existing styles + minor tweaks) */
.float-label-group input[type="text"] {
  width: 100%;
  padding: 8px 10px;
  margin: 4px 0;
  font-size: 18px !important;
  box-sizing: border-box;
  border: 2px solid var(--button-2-1) !important;
  border-radius: 5px;
  background-color: var(--top-header) !important;
  color: var(--h1);
  transition: border-color 0.2s ease, background-color 0.2s ease;
}

/* Floating Label Default Position */
.float-label-group label {
  position: absolute;
  left: 12px;
  top: 10px;
  color: var(--h1);
  background-color: var(--top-header);
  font-size: 20px;
  padding: 0 4px;
  transition: 0.2s ease all;
  pointer-events: none;
}

/* Floating Behavior */
.float-label-group input:focus + label,
.float-label-group input:not(:placeholder-shown) + label {
  top: -10px;
  left: 10px;
  font-size: 14px;
  color: var(--button-2-1);
  background-color: var(--top-header);
}





.earthcycles-logo {
  margin:15px auto 0 auto;
  padding:5px 5px 5px 5px;
  width: 200px;
  height: 200px;
  border: none;
}


    .app-signup-banner {
        background: url('<?= htmlspecialchars($app_info['signup_top_img_url']) ?>') no-repeat center;
        background-size: contain;
    }


    @media (prefers-color-scheme: dark) {
        .app-signup-banner {
            background: url('<?= htmlspecialchars($app_info['signup_top_img_dark_url']) ?>') no-repeat center;
            background-size: contain;
        }

    }




#main {
    height: fit-content;
}


.module-btn {
  background: var(--emblem-green);
  width: 100%;
  display: flex;
}

.module-btn:hover {
  background: var(--emblem-green-over);
}

#splash-bar {
  background-color: var(--top-header);
  filter: none !important;
  margin-bottom: -200px !important;
}


</STYLE>





<?php require_once ("../header-2025.php");?>



