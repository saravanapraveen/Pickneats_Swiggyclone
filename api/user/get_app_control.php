<?php
    include("../../controlpanel/include/connection.php");
    $output = array();

    $sql = "SELECT * FROM app_control";
    $result = $conn->query($sql);

    $output['GTS'] = $result->fetch_assoc();

    echo json_encode($output);
?>