
<!--  Set any page specific graphics to preload-->

<!--  Set any page specific graphics to preload
<link rel="preload" as="image" href="../webps/ecobrick-team-blank.webp" media="(max-width: 699px)">
<link rel="preload" as="image" href="../svgs/richard-and-team-day.svg">
<link rel="preload" as="image" href="../svgs/richard-and-team-night.svg">
<link rel="preload" as="image" href="../webps/biosphere2.webp">
<link rel="preload" as="image" href="../webps/biosphere-day.webp">-->



<?php require_once ("../meta/$page-$lang.php");?>

<STYLE>

    .float-label-group {
        position: relative;
        margin-top: 1.5rem;
        margin-bottom: 2rem;
    }

    .float-label-group input {
        width: 100%;
        padding: 12px 10px;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 6px;
        background-color: var(--input-bg, white);
    }

    .float-label-group label {
        position: absolute;
        left: 10px;
        top: 12px;
        background: var(--input-bg, white);
        color: #888;
        font-size: 1rem;
        padding: 0 4px;
        transition: 0.2s ease all;
        pointer-events: none;
    }

    .float-label-group input:focus + label,
    .float-label-group input:not(:placeholder-shown) + label {
        top: -10px;
        left: 6px;
        font-size: 0.85rem;
        color: var(--accent-color, #0066cc);
        background: var(--input-bg, white);
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



