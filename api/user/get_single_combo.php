<?php
    include("../../controlpanel/include/connection.php");
    include("../worker/combo.php");
    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->combo_id)){
        $combo_id = $data->combo_id;
        $product_id = $data->product_id;

        $sql = "SELECT * FROM combos WHERE combo_id='$combo_id' AND combo_status=1";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            if($product_id){
                $output['GTS'] = getCombo($conn,$combo_id,$product_id);
            } else{
                $output['GTS'] = getCombo($conn,$combo_id);
            }
        } else{
            http_response_code(404);
        }
    } else{
        http_response_code(400);
    }

    if(count($output)){
        echo json_encode($output);
    }
?>