<?php
    
    include_once("pages/func.php");
    session_start();

    if(isset($_GET['id'])) {
        include_once("pages/mypage.php");
    } else if (isset($_GET['setings'])) {
        include_once("pages/setings.php");
    } else {
        include_once("pages/registration.php");
    }
?>