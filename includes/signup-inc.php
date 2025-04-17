
<!--  Set any page specific graphics to preload-->

<!--  Set any page specific graphics to preload
<link rel="preload" as="image" href="../webps/ecobrick-team-blank.webp" media="(max-width: 699px)">
<link rel="preload" as="image" href="../svgs/richard-and-team-day.svg">
<link rel="preload" as="image" href="../svgs/richard-and-team-night.svg">
<link rel="preload" as="image" href="../webps/biosphere2.webp">
<link rel="preload" as="image" href="../webps/biosphere-day.webp">-->



<?php require_once ("../meta/$page-$lang.php");?>

<STYLE>


/* Form Group Wrapper (your existing .form-item) */
.form-item {
    position: relative;
    border-radius: 5px;
    padding: 16px 12px 14px 12px; /* extra top padding for floating label space */
    background-color: #00000015; /* or use var(--input-bg-secondary) if defined */
    margin-top: 12px;
    margin-bottom: 16px;
    overflow: hidden;
    transition: background-color 0.2s ease;
}

/* Input Styling (now larger & more comfortable) */
.float-label-group input[type="text"],
.float-label-group input[type="name"] {
    width: 100%;
    padding: 12px 10px 10px 10px;
    font-size: 1.6em;
    font-family: inherit;
    box-sizing: border-box;
    border: 2px solid var(--button-2-1);
    border-radius: 5px;
    background-color: var(--top-header);
    color: var(--h1);
    transition: border-color 0.2s ease, background-color 0.2s ease;
}

/* Floating Label */
.float-label-group label {
    position: absolute;
    left: 16px;
    top: 18px;
    font-size: 1.6em;
    color: var(--h1);
    background-color: transparent;
    padding: 0 4px;
    transition: 0.2s ease all;
    pointer-events: none;
}

/* Label floats when input is focused or filled */
.float-label-group input:focus + label,
.float-label-group input:not(:placeholder-shown) + label {
    top: 2px;
    left: 12px;
    font-size: 0.95em;
    color: var(--button-2-1);
    background-color: var(--top-header); /* Matches input bg for cleaner floating */
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



