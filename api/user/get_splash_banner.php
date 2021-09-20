<?php
    include("../../controlpanel/include/connection.php");

    $output = array();

    $sql = "SELECT * FROM splash_banner WHERE splash_banner_status=1";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $i = 0;
        while($row = $result->fetch_assoc()){
            $output['GTS'][$i] = $row;

            $output['GTS'][$i]['splash_banner_id'] = (int)$output['GTS'][$i]['splash_banner_id'];
            $output['GTS'][$i]['splash_banner_status'] = (int)$output['GTS'][$i]['splash_banner_status'];

            $i++;
        }
    } else{
        http_response_code(404);
    }

    if(count($output)){
        echo json_encode($output);
    }
?>