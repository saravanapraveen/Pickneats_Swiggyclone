<?php
    include("../../controlpanel/include/connection.php");

    $output = array();

    $sql = "SELECT * FROM payment_mode WHERE payment_mode_status=1";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $i = 0;
        while($row = $result->fetch_assoc()){
            $output['GTS'][$i] = $row;

            $output['GTS'][$i]['payment_mode_id'] = (int)$output['GTS'][$i]['payment_mode_id'];
            $output['GTS'][$i]['payment_mode_status'] = (int)$output['GTS'][$i]['payment_mode_status'];

            $i++;
        }
    } else{
        http_response_code(404);
    }

    if(count($output)){
        echo json_encode($output);
    }
?>