
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

/* Animations */

@keyframes shake {
  0% { transform: translateX(0); }
  20% { transform: translateX(-16px); }
  40% { transform: translateX(16px); }
  60% { transform: translateX(-14px); }
  80% { transform: translateX(14px); }
  100% { transform: translateX(0); }
}


@keyframes powerStripeEntrance {
  from {
    left: 15%;
  }
  to {
    left: 80%;
  }
}



@keyframes powerStripeIdle {
  0% {
    left: 10%;
  }
  50% {
    left: 20%;
  }
  100% {
    left: 10%;
  }
}

@keyframes powerStripePulse {
  0% {
    left: 80%;
  }
  50% {
    left: 60%;
  }
  100% {
    left: 80%;
  }
}

@keyframes powerStripeReturn {
  from {
    left: 70%;
  }
  to {
    left: 10%;
  }
}

@keyframes powerStripeClick {
  0% {
    left: 70%;
  }
  66% {
    left: 15%;
  }
  100% {
    left: 104%;
  }
}



@keyframes powerStripeStride {
  0% {
    left: 0%;
  }
  100% {
    left: 100%;
  }
}


.kick-ass-submit[data-hovered="true"].pulse-started::before {
  animation: powerStripeEntrance 0.4s ease forwards, powerStripePulse 1.1s ease-in-out infinite;
  animation-delay: 0s, 0.4s; /* Entrance starts immediately, pulse starts after 0.4s */
}



/* The pull back animation on click */
.kick-ass-submit.click-animating::before {
  animation: powerStripeClick 0.6s ease forwards;
}

/* The striding animation while processing and emoji animating */
.kick-ass-submit.striding::before {
  animation: powerStripeStride 0.5s linear infinite;
}

.shake {
  animation: shake 0.4s ease;
}



/* Kick-ass button core */
.kick-ass-submit {
  position: relative;
  display: inline-block;
  width: 77%;
  max-width: 400px;
     height:53px;
  padding: 14px 24px;
  font-size: 1.3em;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  background-color: var(--button-2-2);
  color: white;
  cursor: pointer;
  transition:
    background-color 0.3s ease,
    box-shadow 0.2s ease,
    transform 0.1s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  overflow: hidden;
  line-height: normal;
}

/* Power Stripe */
.kick-ass-submit::before {

  content: "";
  position: absolute;
  top: 0;
  left: 10%;
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
  animation: powerStripeIdle 3s ease-in-out infinite;
}

.kick-ass-submit .hidden-text {
  opacity: 0;
  visibility: hidden;
}


.submit-emoji {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  font-size: 28px;
  transition: opacity 0.1s ease-in-out;
  z-index: 3;
  pointer-events: none; /* Don't let it affect interactions */
  height: 35px;
  margin-top: -25px;
  opacity: 1 !important;
}





/* Button content stays above the stripe */
.kick-ass-submit span,
.kick-ass-submit > * {
  position: relative;
  z-index: 2;
}

/* Hover/active states */
.kick-ass-submit:hover {
  background-color: var(--button-2-2-over, #005fa3); /* Fallback color if var missing */
  box-shadow: 0 6px 14px rgba(0, 0, 0, 0.2);
}

.kick-ass-submit:active {
  transform: scale(0.98);
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
}

.kick-ass-submit.returning::before {
  animation: powerStripeReturn 0.5s ease forwards;
}


    /* Responsive width */
    @media (max-width: 769px) {
      .kick-ass-submit {
        width: 90%;




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
      pointer-events: none !important;
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

.float-label-group input:focus + label,
.float-label-group input:not(:placeholder-shown) + label {
  top: -3px;
  right: 10px;
  left: auto;
  transform: none;
  font-size: 14px;
  color: var(--subdued-text);
  background-color: var(--top-header);
  border-radius: 5px 5px 0px 0px;
  border: solid 2px var(--button-2-1);
  border-bottom: none;
  padding: 5px 10px 7px 10px;
  text-align: right;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}






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


.modal-content-box h1,
.modal-content-box h2,
.modal-content-box h3 {
  margin-top: 1.4em;
  color: var(--h1);
}

.modal-content-box p,
.modal-content-box ul {
  font-size: 1.05em;
  line-height: 1.6;
  margin-bottom: 1em;
}

.modal-content-box ul {
  padding-left: 1.2em;
}


</STYLE>





<?php require_once ("../header-2025.php");?>



