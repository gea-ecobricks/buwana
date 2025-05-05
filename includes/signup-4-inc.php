    <?php require_once ("../meta/$page-$lang.php");?>

    <STYLE>



    .hidden {
        display: none;
    }
    .error {
        color: red;
    }
    .success {
        color: green;
    }




.spinner {
    display: none;
    position: absolute;
    top: 30%;  /* Center vertically in the input field */
    left: 20px; /* Distance from the right edge of the input field */
    transform: translateY(-50%); /* Ensures the spinner is exactly centered vertically */
    width: 20px;
    height: 20px;
    border: 4px solid rgba(0,0,0,0.1);
    border-top: 4px solid var(--emblem-pink);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.pin-icon {
    display: block;
    position: absolute;
    top: 25%;  /* Center vertically in the input field */
    left: 20px; /* Distance from the right edge of the input field */
    transform: translateY(-50%); /* Ensures the spinner is exactly centered vertically */
    width: 15px;
    height: 0px;

}

.spinner.green {
    background-color: green;
    border: 1px solid green;
}

.spinner.red {
    background-color: red;
    border: 1px solid red;
}

@keyframes spin {
    0% { transform: rotate(0deg); translateY(-50%); }
    100% { transform: rotate(360deg); translateY(-50%); }
}


/* .pin-icon { */
/* margin: -37px 0px 20px 12px; */
/* } */

    </STYLE>


    <?php require_once ("../header-2025.php");?>

