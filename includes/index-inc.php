
<!--  Set any page specific graphics to preload-->

<!--  Set any page specific graphics to preload
<link rel="preload" as="image" href="../webps/ecobrick-team-blank.webp" media="(max-width: 699px)">
<link rel="preload" as="image" href="../svgs/richard-and-team-day.svg">
<link rel="preload" as="image" href="../svgs/richard-and-team-night.svg">
<link rel="preload" as="image" href="../webps/biosphere2.webp">
<link rel="preload" as="image" href="../webps/biosphere-day.webp">-->

<?php require_once ("../meta/$page-$lang.php");?>


<style>



@-webkit-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
@-moz-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
@keyframes fadeIn { from { opacity:0; } to { opacity:1; } }


/*COMMON*/



/* --------------------------------------------------------------------------


2. Document Setup


-------------------------------------------------------------------------- */
:root {
    --unit-100vh: 100vh;
}
@supports (height: 100dvh) {
    :root {
        --unit-100vh: 100dvh;
    }
}

html {
  height: 100%;
  }


  body {
    z-index: 0;
    position: relative;
    /*position: bottom;*/
    margin-top: 0px;
    padding-bottom: 0px;
    margin-left: 0px;
    margin-right: 0px;
    background-color: var(--general-background);
      overflow-x: hidden;
    /*General interface background slightly off-white to make the white buttons and boxes show up better*/
  }


  @media screen and (min-width: 1200px) {

  .landing-content {
  width: 70%;
  margin: auto;
  margin-bottom: 0px;
      }
    }



    @media screen and (min-width: 700px) and (max-width: 1200px) {

    .landing-content {
    width: 80%;
    margin: auto;
    margin-bottom: 0px;

        }
      }


    @media screen and (max-width: 700px) {
  .landing-content {
    background: none;
  max-width: 90%;
  margin: auto;
        }
      }



/*------------------------------------


3. Header


------------------------------------*/



/* #header { */
/*   transition: 0.4s; */
/*   /*display: flex; */
/*   justify-content: center;*/ */
/*   z-index: 25; */
/* position: relative; */
/* background: var(--top-header); */
/* /*box-shadow: 0px 0px 12px var(--shadow);	*/ */
/*   width: 100%; */
/*   border-bottom: var(--header-accent) 0.5px solid; */
/*   box-shadow: 0px 0px 15px rgba(0, 0, 10, 0.805); */
/* } */

  @media screen and (min-width: 701px) {
#header {
  height: 70px;
}
}

@media screen and (max-width: 700px) {
#header {

  height: 70px;
}
}



.gobrik-logo {
margin: auto;
position: absolute;
width: fit-content;
margin-top: 13px;
width:165px;
height:47px;
border: none;
right: 0;
left: 0;
border: none;
cursor:pointer;
}


.main-menu-button {
  position:absolute;
  left:0;
  margin-top: 22px;
  margin-left:25px;
  border: none;
  margin-right:10px;
  cursor:pointer;
  height:30px;
  width:30px;
}


.main-menu-button:hover  {
  border: none;
  margin-right:10px;
  cursor:pointer;
  height:30px;
  width:30px;
}



.top-menu-login-button {

  position:absolute;
  left:0;
 margin-top: 22px;
 margin-left: 22px;


  font-family:'Mulish';
  background: none;
  border:0.5px;
  border-style:solid;

  border-radius:5px;
  align-content: center;
  font-size: 0.9em;
  padding: 3px 14px 3px 14px;
  margin-right:10px;
  cursor: pointer;
  color: var(--header-accent);
  border-color: var(--header-accent);
  border-width: 0.5px;
}

.top-menu-login-button:hover {
  color: var(--top-header);
  background-color: var(--header-accent);
}

@media screen and (max-width: 700px) {
.top-menu-login-button {
  display:none;
}
}


.top-settings-button  {
  border: none;
  margin-right:10px;
  cursor:pointer;
  height:30px;
  width:30px;
}

/* LANDING SPECIFIC */



/* First view Landing content layout */
/* .bio-top { */
/* width:100%; */
/* height: fit-content; */
/* margin-bottom: -160px; */
/* margin-top:-5px; */
/* z-index: 10; */
/* position: relative; */
/* background-color: var(--gallery); */
/* border:none; */
/* } */



.biosphere {
  position: relative;
z-index: 0;
height: 50vh;
width: 100%;
text-align: center;
margin: auto;
margin-bottom: -50vh;
  }



/* @media screen and (max-width: 700px) {
    .biosphere {
      margin: -40vh auto -9vh auto;
    }
  }

  @media screen and (min-width: 701px) and (max-width: 1300px){
    .biosphere {
      margin: -25vh auto -21vh auto;
    }
  }

  @media screen and (min-width: 1301px) {
    .biosphere {
      margin: -27vh auto -19vh auto;
    }
  } */



.main-landing-graphic {
    margin: auto;
      position: relative;
      z-index: 11;


  }

  .main-landing-graphic  img {
  height: auto;
  width: 100%;
}


  @media screen and (max-width: 700px) {
    .main-landing-graphic {
      width: 93%;
/*       height:39%; */
margin-top: 70px;
      margin-bottom: -5px;
      min-width: 250px;
    min-height: 200px;
    }
  }

  @media screen and (min-width: 701px) and (max-width: 1300px){
    .main-landing-graphic {
      width: 75%;
/*       height:31%; */
     /* min-width: 644px;
      min-height:272px;*/
      margin-bottom: -5px;
      margin-top: 110px;
    }
  }

  @media screen and (min-width: 1301px) {
    .main-landing-graphic {
      width: 66%;
/*       height:28%; */
      margin-bottom: -5px;
      margin-top: 70px;
      /*min-width: 644px;
      min-height:272px;*/
    }
  }







.big-header {

 font-family: 'Arvo', serif;
 margin-top: 20px;
 text-align: center !important;
 line-height: 1.2 !important;
 color: var(--h1);
 z-index:10;

}

@media screen and (max-width: 700px) {
.big-header {
 font-size: 1.95em !important;
 margin-bottom: 14px;

}
}


@media screen and (min-width: 701px) and (max-width: 1300px){
.big-header {
  font-size: 2.3em !important;
  margin-bottom: 16px;

}
}



@media screen and (min-width: 1301px) {
.big-header {
 font-size: 2.6em !important;
 margin-bottom: 19px;

}
}


.main-statement {

 font-family: 'Mulish', sans-serif;
 text-align: center !important;
 line-height: 1.3 !important;
 margin: 20px 0px 13px 0px;
 color:var(--text-color);

}

@media screen and (min-width: 770px) and (max-width: 2000px) {
 .main-statement {
  font-size: 2.5em !important;
 }
}

@media screen and (max-width: 769px) {
 .main-statement {
   font-size: 1.5em !important;
 }
}

.sign-innn {
font-family: 'mulish', sans-serif !important;
display: block;
margin: auto;
background: var(--button-2-1);
background-image: -webkit-linear-gradient(top, var(--button-2-1), var(--button-2-2));
background-image: -moz-linear-gradient(top,  var(--button-2-1), var(--button-2-2));
background-image: -ms-linear-gradient(top,  var(--button-2-1), var(--button-2-2));
background-image: -o-linear-gradient(top,  var(--button-2-1), var(--button-2-2));
background-image: linear-gradient(to bottom,  var(--button-2-1), var(--button-2-2));
-webkit-border-radius: 8px 0px 0px 8px;
-moz-border-radius: 8px 0px 0px 8px;
border-radius: 8px 0px 0px 8px;
color: #fff;
font-size: 1.3em;
font-weight: 700;
padding: 9px 18px 9px 18px ;
text-decoration: none;
margin-top: 0px;
margin-bottom: 12px;
border: none;
margin-right: 3px;
}


.sign-innn:hover {
background: var(--button-2-1-over);
background-image: -webkit-linear-gradient(top,  var(--button-2-1-over), var(--button-2-2-over));
background-image: -moz-linear-gradient(top, var(--button-2-1-over), var(--button-2-2-over));
background-image: -ms-linear-gradient(top, var(--button-2-1-over), var(--button-2-2-over));
background-image: -o-linear-gradient(top, var(--button-2-1-over), var(--button-2-2-over));
background-image: linear-gradient(to bottom, var(--button-2-1-over), var(--button-2-2-over));
text-decoration: none;
}



.sign-uppp {
font-family: 'mulish', sans-serif !important;
display: block;
margin: auto;
background: var(--button-1-1);
background-image: -webkit-linear-gradient(top, var(--button-1-1), var(--button-1-2));
background-image: -moz-linear-gradient(top, var(--button-1-1), var(--button-1-2));
background-image: -ms-linear-gradient(top, var(--button-1-1), var(--button-1-2));
background-image: -o-linear-gradient(top, var(--button-1-1), var(--button-1-2));
background-image: linear-gradient(to bottom, var(--button-1-1), var(--button-1-2));
-webkit-border-radius: 0px 8px 8px 0px;
-moz-border-radius: 0px 8px 8px 0px;
border-radius: 0px 8px 8px 0px;
color: #fff;
font-size: 1.3em;
font-weight: 700;
padding: 9px 18px 9px 9px ;
text-decoration: none;
margin-top: 0px;
margin-bottom: 12px;

border: none;
margin-left: 3px;
}


.sign-uppp:hover {
background: var(--button-1-1-over);
background-image: -webkit-linear-gradient(top, var(--button-1-1-over), var(--button-1-2-over));
background-image: -moz-linear-gradient(top, var(--button-1-1-over), var(--button-1-2-over));
background-image: -ms-linear-gradient(top, var(--button-1-1-over), var(--button-1-2-over));
background-image: -o-linear-gradient(top, var(--button-1-1-over), var(--button-1-2-over));
background-image: linear-gradient(to bottom, var(--button-1-1-over), var(--button-1-2-over));
text-decoration: none;
}


.tree-coins {
      position: relative;
      z-index: 0;
      text-align: center;
      width: 80%;
      height: 100%;
      margin: 15px auto 10px auto;
      }


  @media screen and (max-width: 700px) {
    .tree-coins {
      width: 90%;

    }
  }

  @media screen and (min-width: 700px) {
    .tree-coins {
      width: 60%;

    }
  }


.welcome-text {

 font-size: 1.6em !important;
 font-family: 'Mulish', sans-serif;
 text-align: center !important;
 color:var(--text-color);

   }



@media screen and (max-width: 700px) {
.welcome-text {
 font-size: 1.11em !important;
 margin-bottom: 26px;
}
}

@media screen and (min-width: 701px) and (max-width: 1300px) {
.welcome-text {
   font-size: 1.5em !important;
   margin-bottom: 28px;
}
}

@media screen and (min-width: 1301px) {
.welcome-text {
   font-size: 1.7em !important;
   margin-bottom: 30px;
}
}


  .tree-text {
    font-size: 0.83em ;
    font-family: 'Mulish', sans-serif;
    width: 85%;
    text-align: center;
    line-height: 1.4;
    position: relative;
    z-index: 5;
    font-weight: 300;
    margin: 5px auto 5px auto;;
    color:var(--text-color);
  }

.aes-logos {
  width: 100%;
height: 60px;
margin: auto;
text-align: center;
margin-top: 26px;
}

#lang-button {position: relative !important;
transition: 0.3s;}




/* -------------------------------------------------------------------------- */

/*	6. User Settings Overlay Curtain

    Comes in from the right after clicking +- button.

/* -------------------------------------------------------------------------- */

#main-menu-overlay {
  background-color: var(--side-overlays) /*var(--overlays-and-headers:)*/;
  color:  var(--text-color); /*var(--text-color)*/ ;
  z-index: 26;
}


/* Table of Contents Menu (background) */
.overlay-settings {
  height: 100%;
  width: 0%;
  position: fixed; /* Stay in place */
  z-index: 21; /* Sit on top */
  right: 0;
  top: 0;
  overflow-x: hidden; /* Disable horizontal scroll */
  transition: 0.5s; /* 0.5 second transition effect to slide in or slide down the overlay (height or width, depending on reveal) */
}


/* Position the content inside the overlay */
.overlay-content-settings {
  position: initial;
  text-align: center; /* Centered text/links */
   /*margin-top: 30px; 30px top margin to avoid conflict with the close button on smaller screens */
  font-family: "Mulish";
  /*font-size: smaller;*/
  display: flex;
  justify-content: center;
  flex-flow: column;
  height:100%;
  margin: auto;
}


@media screen and (max-width: 700px) {
  .overlay-content-settings {
    width: 77%;
    font-size: 0.9em;
    /*margin-top: 6%;*/
}
}

@media screen and (min-width: 700px) and (max-width: 1324px) {
  .overlay-content-settings {
    width: 65%;
    font-size: 0.9em;
    /*margin-top: 2%;*/
}
}

@media screen and (min-width: 1325px) {
    .overlay-content-settings {
      width: 69%;
      margin: auto;
    }
}

.settings-label {
  font-family: 'Mulish';
  font-size: 1.2em;
  margin: 18px 0px 8px 0px;
}

.language-box {
  display: flex;
  margin: 10px auto 10px auto;
  justify-content: center;
  padding: 5px 30px 5px 30px;
  background: var(--slide-highlight);
  border-radius: 55px;
  width: fit-content;
}

.language-selector {
  font-family: 'Mulish';
  padding: 10px 20px 10px 20px;
  background: var(--side-overlays);
  border-radius: 10px;
  margin: 10px;
 /* filter: invert(100);*/
  font-size: 1.1em;
  cursor: pointer;
  color: var(--text-color);
  /*border-color:var(--header-accent);*/
  border-width:0.5px;

}

.language-selector:hover {
  background: var(--header-accent);
  border-width:1px;
  color:var(--top-header);
}

.language-selector a {
  color: var(--side-overlays);
}



/*Carbon Badge */

#wcb.wcb-d #wcb_a {
  color: #2e2e2e !important;
background: #27ad37 !important;
border-color: #00a112 !important;
}

#wcb #wcb_a,
#wcb #wcb_g {
  border: 0.2em solid #2cb03c !important;
}

#wcb.wcb-d #wcb_2 {
  color: var(--footer-text) !important;
}


/*MENU ITEMS*/


.menu-page-item {
  padding:10px;
  font-family:'Mulish';
  font-size:1.4em;
  color:var(--text-color);
  border-bottom:1px solid var(--subdued-text);
  cursor: pointer;

}


.menu-page-item:hover {

  border-bottom:2px solid var(--text-color);
  color: var(--header-accent);

}

.menu-page-item a {  text-decoration: none;
color: var(--subdued-text);
}

.menu-page-item a:hover {  text-decoration: none;
  color: var(--h1);
  }

[part="darkLabel"], [part="lightLabel"], [part="toggleLabel"] {

  font-size: 22px !important;
}

p a {
  color: var(--text-color) !important;
}

p a:hover {
  color: var(--h1) !important;
}





/*Right Close Button*/

#right-close-button {
  position: absolute;
  right: 0px;
  transition: 0.3s;
  height: 75px;
  width:75px;
  padding-right: 30px;
  padding-top: 30px;
  right: 0px;
  top: 0px;
}


.x-button {
    background: url('../svgs/right-x.svg') no-repeat;
    padding: 10px;
    background-size: contain;
    width: 75px;
    height: 75px;
    border:none;
}

.x-button:hover {
    background: url('../svgs/x-over.svg') no-repeat;
    padding: 10px;
    background-size: contain;

}



/* FEATURED ECOBRICKS GALLERY FORMATING */
/*
.brik-co2 {
  font-size: 0.7em;
  color: white;
  font-family: 'Impact', 'Haettenschweiler', 'Arial Narrow Bold', sans-serif;
  margin-top: -60px;
  text-align: left;
  padding: 0px 0px 10px 20px;
  background-color: black !important;
} */

.gallery-flex-container {
display: flex;
flex-wrap: wrap;
justify-content: center;
margin: 0px -15px 30px -15px}


@media screen and (min-width: 700px) {

.gallery-flex-container > .gal-photo {

  padding: 5px;
  max-height: 100px;
  max-width:100px;
  /* width:100px; */
  overflow: hidden;
}

.gallery-flex-container > .gal-project-photo {

padding: 5px;
max-height: 160px;
max-width:160px;
/* width:160px; */
overflow: hidden;
}
/*
.gallery-flex-container::before {
  content: '';
  flex: auto;
}

.gal-photo:nth-child(2n + 1):last-child {
  margin-left: auto;
} */



.gal-photo img {
  /* width:100px; */
  height: 100px;
  background: grey;
  font-family: 'Mulish';
  font-size: 0.8em;
  cursor: pointer;
  color: var(--text-color);
}


.gal-project-photo img {
  /* width:160px; */
  height: 160px;
  background: grey;
  font-family: 'Mulish';
  font-size: 0.8em;
  cursor: pointer;
  color: var(--text-color);
}



.photo-box-end {

  height: 100px;
  width: 100px;
  margin: 5px;
  overflow: hidden;
  animation: blinker 1.5s cubic-bezier(0,.43,1,.64) infinite;
  background: url(../icons/gobrik-icon-darker.svg) no-repeat center;
  background-size: contain;
  background-color: var(--emblem-green);
}



.project-photo-box-end {

height: 160px;
width: 160px;
margin: 5px;
overflow: hidden;
animation: blinker 1.5s cubic-bezier(0,.43,1,.64) infinite;
background: url(../icons/gobrik-icon-darker.svg) no-repeat center;
background-size: contain;
background-color: var(--emblem-green);
}
}

@media screen and (max-width: 700px) {

.gallery-flex-container > .gal-photo {
  max-height: 60px;
  max-width:60px;
  width:60px;
  overflow: hidden;
}

.gallery-flex-container > .gal-project-photo {
  max-height: 100px;
  max-width:100px;
  /* width:100px; */
  overflow: hidden;
}


.gal-photo img {
  margin: 4px;
  width:60px;
  height: 60px;
  background: grey;
  font-family: 'Mulish';
  font-size: 0.6em;
  cursor: pointer;
  color: var(--text-color);
}

.gal-project-photo img {
  margin: 4px;
  /* width:100px; */
  height: 100px;
  background: grey;
  font-family: 'Mulish';
  font-size: 0.6em;
  cursor: pointer;
  color: var(--text-color);
}

.photo-box-end {

  height: 60px;
  width: 60px;
  margin: 3px;

  animation: blinker 1.5s cubic-bezier(0,.43,1,.64) infinite;
  background: url(../icons/gobrik-icon-darker.svg) no-repeat center;
  background-size: contain;
  background-color: var(--emblem-green);
}

.photo-project-box-end {

height: 100px;
width: 100px;
margin: 3px;

animation: blinker 1.5s cubic-bezier(0,.43,1,.64) infinite;
background: url(../icons/gobrik-icon-darker.svg) no-repeat center;
background-size: contain;
background-color: var(--emblem-green);
}
}


.feed-live {
  text-align:center;
  background: var(--darker);
  border-radius: 15px 15px 0px 0px ;
  font-size: 0.9em;
  margin:15px auto -10px auto;
}



  @media screen and (min-width: 700px) {

.feed-live {
width: 80%;
padding: 10px;


/* background-color: #DFDFDF; */
}

.feed-live p {
font-size: 0.9em;
font-family: courier new,monospace !important;
color: var(--subdued-text);
line-height: 1.1;
font-weight: 300;

}
  }


  @media screen and (max-width: 700px) {
.feed-live {
width: 80%;
padding: 10px;
}

.feed-live p {
font-family: courier new,monospace !important;
line-height: 1.1em;
font-weight: 300;
font-size: 0.8em;

}
  }






@media screen and (max-width: 700px) {
.gallery-content-block {
  text-align: center;
  min-height: 67vh;
  z-index: 5;
  position: relative;
      background-color: #DFDFDF;
  display: flex;
   flex-wrap: wrap;
   box-sizing: border-box;
  flex-direction: row;
  width: 120%;
    margin-right: -5%;
    margin-left: -5%;
  margin-top: 33px;
      padding-top: 12px;
  overflow: hidden;
  /*box-shadow: 0 8px 7px rgba(85, 84, 84, 0.4);
  margin-bottom: 40px;
  padding-bottom: 15px;*/
}

}


@media screen and (min-width: 700px) {
.gallery-content-block {
  text-align: center;
  min-height: 67vh;
  z-index: 5;
  position: relative;
      background-color: #dfdfdf;
  display: flex;
   flex-wrap: wrap;
   box-sizing: border-box;
  flex-direction: row;
  width: 120%;
    margin-right: -5%;
    margin-left: -5%;
  margin-top: 0px;
  overflow: hidden;

      padding-top: 10px;

  /*box-shadow: 0 8px 7px rgba(85, 84, 84, 0.4);
  margin-bottom: 40px;
  padding-bottom: 15px;*/

}
}


/* FEATURE BOX AFTER GALLERY */


.feature-big-header {
  font-family: 'Arvo', serif;
  text-align: center;
  line-height: 1.3;
  text-shadow: 0 0 10px var(--background-color);
  font-weight: 500;
  color: var(--h1) !important;
  margin-bottom: 10px;
}

@media screen and (max-width: 769px) {
  .feature-big-header {
      font-size: 1.9em;
  }
}
@media screen and (min-width: 770px) and (max-width: 1200px) {
  .feature-big-header {
      font-size: 2.6em;
  }
}
@media screen and (min-width: 1201px) {
  .feature-big-header {
      font-size: 2.5em;
  }
}


.feature-sub-text {
    font-family: 'Mulish', sans-serif;
    text-align: center;
    line-height: 1.4;
    color: var(--text-color);
    margin-bottom: 20px;
  }


  @media screen and (max-width: 769px) {
    .feature-sub-text {
        font-size: 1.1em;
    }
  }
  @media screen and (min-width: 770px) and (max-width: 1024px) {
    .feature-sub-text {
        font-size: 1.4em;
    }
  }
  @media screen and (min-width: 1024px) {
    .feature-sub-text {
        font-size: 1.6em;
    }
  }


  .feature-button {
    font-family: 'Mulish', sans-serif;

    background: #00a1f2;
    background-image: -webkit-linear-gradient(top, #00a1f2, #008ad4);
    background-image: -moz-linear-gradient(top, #00a1f2, #008ad4);
    background-image: -ms-linear-gradient(top, #00a1f2, #008ad4);
    background-image: -o-linear-gradient(top, #00a1f2, #008ad4);
    background-image: linear-gradient(to bottom, #00a1f2, #008ad4);
    -webkit-border-radius: 8;
    -moz-border-radius: 8;
    border-radius: 8px !important;
    color: #fff;
    font-size: 1.4em;
    padding: 8px 18px 8px 18px !important;
    text-decoration: none !important;
    margin-top: 18px;
    margin-bottom: 16px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    border:none;
    margin: auto;
    text-align: center;
  }

  .feature-button:hover {
    background: #3cb0fd;
    background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
    background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
    background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
    background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
    background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
    text-decoration: underline;
  }


  .feature-reference-links {
    width: fit-content;
    margin: auto;
    margin-bottom: 30px;
    text-align: center;
    color: var(--subdued-text);
    font-family: 'Mulish', sans-serif;
    margin-bottom: 30px;
    margin-top: 12px;

  }


  @media screen and (max-width: 769px) {
    .feature-reference-links {
        font-size: 0.8em;
    }
  }
  @media screen and (min-width: 770px) and (max-width: 1024px) {
    .feature-reference-links {
        font-size: 0.9em;
    }
  }
  @media screen and (min-width: 1024px) {
    .feature-reference-links {
        font-size: 1.0em;
    }
  }


  .feature-reference-links a {
    text-decoration: none;
    color: var(--subdued-text);
  }

  .feature-reference-links a:hover {
    text-decoration: underline;
    color: var(--subdued-text);
  }


</style>





<?php require_once ("../header-2024.php");?>



