
<!--  Set any page specific graphics to preload-->

<!--  Set any page specific graphics to preload
<link rel="preload" as="image" href="../webps/ecobrick-team-blank.webp" media="(max-width: 699px)">
<link rel="preload" as="image" href="../svgs/richard-and-team-day.svg">
<link rel="preload" as="image" href="../svgs/richard-and-team-night.svg">
<link rel="preload" as="image" href="../webps/biosphere2.webp">
<link rel="preload" as="image" href="../webps/biosphere-day.webp">-->



<?php require_once ("../meta/$page-$lang.php");?>

<STYLE>
/* ----------  Earthling picker  ---------- */

#emoji-section { margin-top: 20px; }

.emoji-tabs {
    display: flex;
    list-style: none;
    margin: 0 0 12px 0;
    padding: 0;
    border-bottom: 2px solid var(--subdued-text);
    overflow-x: auto;              /* mobile friendliness */
}
.emoji-tabs li {
    cursor: pointer;
    padding: 8px 14px;
    white-space: nowrap;
    font-size: 0.95rem;
    border-bottom: 3px solid transparent;
    transition: background .15s, border-color .15s;
}
.emoji-tabs li:hover      { background:var(--form-background); }
.emoji-tabs li.active     { border-color:#2d8cff; font-weight:600; }

.emoji-grid {
    display: none;                /* only the active grid is shown */
    flex-wrap: wrap;
    gap: 8px;
}
.emoji-grid.active { display: flex; }

.emoji-option {
    font-size: 28px;
    padding: 6px 10px;
    border: 2px solid transparent;
    border-radius: 8px;
    cursor: pointer;
    transition: border-color .15s, transform .1s;
}
.emoji-option:hover        { border-color:#bbb; }
.emoji-option.selected     { border-color:#2d8cff; transform: scale(1.05); }

.emoji-hint { margin-top: 6px; font-size: .9em; color:#555; }






/* .spinner { */
/*   display: inline-block; */
/*   width: 24px; */
/*   height: 24px; */
/*   border: 3px solid rgba(255, 255, 255, 0.3); */
/*   border-top-color: white; */
/*   border-radius: 50%; */
/*   animation: spin 0.6s linear infinite; */
/*   vertical-align: middle; */
/* } */

/* @keyframes spin { */
/*   to { */
/*     transform: rotate(360deg); */
/*   } */
/* } */







/* .earthcycles-logo { */
/*   margin:15px auto 0 auto; */
/*   padding:5px 5px 5px 5px; */
/*   width: 200px; */
/*   height: 200px; */
/*   border: none; */
/* } */



/* .module-btn { */
/*   background: var(--emblem-green); */
/*   width: 100%; */
/*   display: flex; */
/* } */

/* .module-btn:hover { */
/*   background: var(--emblem-green-over); */
/* } */




/* Confirm email */


  .code-boxes {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    .code-box {
        text-align: center;
        font-family: 'Arvo', serif;
        font-size: 2em;
        max-width: 3em;
    }
    #second-code-confirm {
        display: none;
    }


/*     .hidden { */
/*         display: none; */
/*     } */
/*     .error { */
/*         color: red; */
/*     } */
/*     .success { */
/*         color: green; */
/*     } */


/* SUBSCRIPTION LAYOUT PAGE */


/* Container for subscription boxes */
.subscription-boxes {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

/* Individual subscription box */
.sub-box {
    display: flex;
    position: relative;
    border: 1px solid rgba(128, 128, 128, 0.5);
    border-radius: 10px;
    transition: border 0.5s, background-color 0.5s, filter 0.5s;
    cursor: pointer;
    width: calc(50% - 20px); /* Two columns when screen width is above 1000px */
    box-sizing: border-box;
    background-color: transparent; /* Default background */
    align-items: stretch;
}

/* Hover effect changes brightness and contrast */
.sub-box:hover {
    background-color: var(--lighter);
    filter: brightness(1.1) contrast(0.95);
}

/* Checkbox for selection */
.sub-checkbox {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 2;
    cursor: pointer;
    transform: scale(1.1); /* Increase checkbox size slightly */
}

/* Label for the checkbox */
.checkbox-label {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 22px; /* Slightly increased size */
    height: 22px; /* Slightly increased size */
/*     border: 1px solid grey; */
    border-radius: 4px;
    cursor: pointer;
}

/* Style for checked state */
.sub-checkbox:checked + .checkbox-label {
    background-color: green;
    border-color: green;
}

/* Image covering 25% of the sub-box */
.sub-image {
    width: 25%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-color: grey; /* Default grey background */
    border-radius: 10px 0 0 10px;
    margin-right: 15px;
}

/* Custom images for specific sub-box slugs */
#default-newsletter .sub-image {
    background: url('../webps/earthen-newsletter-image.webp') no-repeat;
    background-size: cover;
}

#gea-trainers .sub-image {
    background: url('../webps/trainer-newsletter-image.webp') no-repeat;
    background-size: cover;
}

#gea-trainer-newsletter-indonesian .sub-image {
    background: url('../webps/pelatih-newsletter-image.webp') no-repeat;
    background-size: cover;
}

#updates-by-russell .sub-image {
    background: url('../webps/ayyew-newsletter-image.webp') no-repeat;
    background-size: cover;
}

#gobrik-news-updates .sub-image {
    background: url('../webps/gobrik-newsletter-image.webp') no-repeat;
    background-size: cover;
}


/* Content area of the box */
.sub-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    text-align: left;
    padding: 15px; /* Added padding here */
}

/* Sub-header to group the icon and title */
.sub-header {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

/* Icon inside the sub-header */
.sub-icon {
    width: 40px;
    height: 40px;
    background-size: contain;
    background-position: center;
    background-color: grey; /* Default grey background */
    border-radius: 4px;
    margin-right: 10px;
}


#gobrik-news-updates .sub-icon {

background: url('../icons/gobrik-news-icon.webp') no-repeat;
    background-size: contain;
}
/* Custom icons for specific sub-box slugs */
#default-newsletter .sub-icon {
    background: url('../icons/earthen-newsletter-icon.webp') no-repeat;
    background-size: contain;
}

#gea-trainers .sub-icon {
    background: url('../icons/trainer-newsletter-icon.webp') no-repeat;
    background-size: contain;
}

#gea-trainer-newsletter-indonesian .sub-icon {
    background: url('../icons/pelatih-newsletter-icon.webp') no-repeat;
    background-size: contain;
}

#updates-by-russell .sub-icon {
    background: url('../icons/ayyew-newsletter-icon.webp') no-repeat;
    background-size: contain;
}

/* Grouping text elements */
.sub-header-text {
    display: flex;
    flex-direction: column;
}

/* Text styles */
.sub-name {
    font-size: 1.3em;
    font-family: 'Mulish', sans-serif;
    color: var(--h1);
    margin: 0;
}

.sub-sender-name {
    font-size: 0.9em;
    font-family: 'Mulish', sans-serif;
    color: var(--subdued-text);
    margin-top: 2px;
}

.sub-description {
    font-size: 1em;
    font-family: 'Mulish', sans-serif;
    color: var(--text-color);
    margin: 10px 0;
}

.sub-lang {
    font-size: 0.9em;
    font-family: 'Mulish', sans-serif;
    color: var(--subdued-text);
}

/* When box is selected, set background color */
.sub-box.selected {
    background-color: var(--darker);
}

/* Responsive behavior: single column for screen widths below 1000px */
@media (max-width: 1000px) {
    .sub-box {
        width: 100%;
    }
}


</STYLE>





<?php require_once ("../header-2025.php");?>



