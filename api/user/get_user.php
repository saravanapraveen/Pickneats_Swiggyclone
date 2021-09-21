<?php
    include("../../controlpanel/include/connection.php");
    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id)){
        $user_id = $data->user_id;

        $sql = "SELECT user_name,user_phone_number,user_alternate_phone_number,user_email FROM user WHERE user_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            http_response_code(200);
            $output['GTS'] = $result->fetch_assoc();
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