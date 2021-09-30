<?php
    // ini_set('display_errors','off');
    date_default_timezone_set("Asia/Calcutta");
    session_start();

    $host="localhost"; // Host name 
    $username="root"; // Mysql username 
    $password=""; // Mysql password 
    $db_name="pickneats"; // Database name 
    $conn = mysqli_connect("$host", "$username", "$password")or die("cannot connect"); 
    mysqli_select_db($conn,"$db_name")or die("cannot select DB");

    $login_id = $_SESSION['login_id'];

    $sql = "SELECT * FROM login WHERE login_id='$login_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $control_id = $row['control'];
    $login_city_id = $row['city_id'];
?>