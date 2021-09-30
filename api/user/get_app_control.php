<?php
    include("../../controlpanel/include/connection.php");
    $output = array();

    $sql = "SELECT api_key,pick_delivery_charge,whatsapp,facebook,instagram,deal_of_the_day_banner FROM app_control";
    $result = $conn->query($sql);

    $output['GTS'] = $result->fetch_assoc();

    echo json_encode($output);
?>