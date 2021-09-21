<?php
    include("../../controlpanel/include/connection.php");
    include("../worker/combo.php");
    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->shop_id)){
        $login_id = $data->shop_id;

        $sql = "SELECT * FROM shop WHERE login_id='$login_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "SELECT * FROM combos WHERE login_id='$login_id' AND combo_status=1";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $i = 0;
                while($row = $result->fetch_assoc()){
                    $combo_id = $row['combo_id'];

                    $output['GTS'][$i] = getCombo($conn,$combo_id);

                    $i++;
                }
            } else{
                http_response_code(404);
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