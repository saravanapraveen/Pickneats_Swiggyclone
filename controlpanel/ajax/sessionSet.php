<?php
    session_start();

    if(!isset($_SESSION['login_id'])){
        if($_POST['check'] == 1){
            $_SESSION['check_login_id'] = $_POST['login'];
        } else{
            $_SESSION['login_id'] = $_POST['login'];
        }
        echo 'false';
    } else{
        echo 'true';
    }
?>