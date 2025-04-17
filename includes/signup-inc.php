
<!--  Set any page specific graphics to preload-->

<!--  Set any page specific graphics to preload
<link rel="preload" as="image" href="../webps/ecobrick-team-blank.webp" media="(max-width: 699px)">
<link rel="preload" as="image" href="../svgs/richard-and-team-day.svg">
<link rel="preload" as="image" href="../svgs/richard-and-team-night.svg">
<link rel="preload" as="image" href="../webps/biosphere2.webp">
<link rel="preload" as="image" href="../webps/biosphere-day.webp">-->



<?php require_once ("../meta/$page-$lang.php");?>

<STYLE>


    /* Wrapper for button centering */
    .submit-button-wrapper {
      text-align: center;
      margin: 20px auto;
    }


   .kick-ass-submit {
     position: relative;
     display: inline-block;
     width: 100%;
     max-width: 400px;
     padding: 14px 24px;
     font-size: 1.3em;
     font-weight: 600;
     border: none;
     border-radius: 8px;
     background-color: var(--button-1-2);
     color: white;
     cursor: pointer;
     transition:
       background-color 0.3s ease,
       box-shadow 0.2s ease,
       transform 0.1s ease;
     box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
     overflow: hidden;
   }

   /* Power Stripe */
   .kick-ass-submit::before {
     content: "";
     position: absolute;
     top: 0;
     left: 20%;
     width: 40px;
     height: 100%;
     background: linear-gradient(
       to right,
       rgba(255, 255, 255, 0),
       rgba(255, 255, 255, 0.2)
     );
     transform: skewX(-45deg);
     pointer-events: none;
     z-index: 1;
     transition: left 0.5s ease;
   }

   /* Hover animation: stripe slides right */
   .kick-ass-submit:hover::before {
     left: 80%;
   }

   /* Button content stays above the stripe */
   .kick-ass-submit span,
   .kick-ass-submit > * {
     position: relative;
     z-index: 2;
   }




    /* Hover/active states */
    .kick-ass-submit:hover {
      background-color: var(--button-1-2-over);
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.2);
    }

    .kick-ass-submit:active {
      transform: scale(0.98);
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
    }

    /* Responsive width */
    @media (min-width: 769px) {
      .kick-ass-submit {
        width: 77%;
      }
    }

/* Styles for the disabled state */
.disabled {
    background-color: #868e9c;
    cursor: not-allowed !important;
}

.disabled:hover {
    background-color: #868e9c;
      cursor: not-allowed;
      box-shadow: none;
      pointer-events: none;
}


/* Floating Label for FIRST NAME Container */
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
  font-size: 22px !important;
  box-sizing: border-box;
  border: 2px solid var(--button-2-1) !important;
  border-radius: 5px;
  background-color: var(--top-header) !important;
  color: var(--h1);
  transition: border-color 0.2s ease, background-color 0.2s ease;
}

.float-label-group select[type="credential"] {
  width: 100%;
  padding: 8px 10px;
  margin: 4px 0;
  font-size: 20px !important;
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
  left: 20px;
  top: 26px;
  color: var(--subdued-text);
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
  left: 25px;
  font-size: 15px;
  color: var(--subdued-text);
  background-color: var(--top-header);
  border-radius: 5px 5px 0px 0px;
  border: solid 2px var(--button-2-1);
  border-bottom: none;
}



/*FLOATING CREDENTIAL SELECT */

/* SELECT styling to match inputs */
.float-label-group select {
  width: 100%;
  padding: 8px 10px;
  margin: 4px 0;
  font-size: 22px;
  box-sizing: border-box;
  border: 2px solid var(--button-2-1);
  border-radius: 5px;
  background-color: var(--top-header);
  color: var(--h1);
  transition: border-color 0.2s ease, background-color 0.2s ease;
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
}

/* Match the label styling */
.float-label-group select + label {
  position: absolute;
  left: 20px;
  top: 24px;
  color: var(--subdued-text);
  background-color: var(--top-header);
  font-size: 20px;
  padding: 0 4px;
  transition: 0.2s ease all;
  pointer-events: none;
}

/* Floating behavior triggered by focus or valid selection */
.float-label-group select:focus + label,
.float-label-group select:not([value=""]) + label {
  top: -8px;
  left: 25px;
  font-size: 14px;
  color: var(--button-2-1);
  background-color: var(--top-header);
  border-radius: 5px 5px 0px 0px;
  border: solid 2px var(--button-2-1);
  border-bottom: none;
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



