

<?php require_once ("../meta/$page-$lang.php");?>

<STYLE>



#last-name-field {
    position: absolute;
    left: -9999px;
    top: auto;
    width: 1px;
    height: 1px;

    z-index: 0;
}


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
  display: inline-block;
  width: 24px;
  height: 24px;
  border: 3px solid rgba(255, 255, 255, 0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
  vertical-align: middle;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}


</STYLE>





<?php require_once ("../header-2025.php");?>



