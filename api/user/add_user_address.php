<?php
    include("../../controlpanel/include/connection.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id)){
        $user_id = $data->user_id;
        $address_name = $data->address_name;
        $address = $data->address;
        $landmark = $data->landmark;
        $latitude = $data->latitude;
        $longitude = $data->longitude;

        $sql = "SELECT * FROM user WHERE user_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql = "SELECT * FROM user_address WHERE user_address_name='$address_name' AND user_id='$user_id'";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $sql = "UPDATE user_address SET user_address_details='$address',user_address_landmark='$landmark',user_address_latitude='$latitude',user_address_longitude='$longitude' WHERE user_address_name='$address_name' AND user_id='$user_id'";
            } else{
                $sql = "INSERT INTO user_address (user_id,user_address_name,user_address_details,user_address_landmark,user_address_latitude,user_address_longitude) VALUES ('$user_id','$address_name','$address','$landmark','$latitude','$longitude')";
            }
            if($conn->query($sql) === TRUE){
                $sql = "SELECT * FROM user_address WHERE user_address_name='$address_name' AND user_id='$user_id'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();

                http_response_code(200);
                $output_array['status'] = 'success';
                $output_array['message'] = 'OK';
                $output_array['user_address_id'] = $row["user_address_id"];
            }
        } else{
            http_response_code(404);
        }
    } else{
        http_response_code(400);
    }

    if(count($output_array)){
        echo json_encode($output_array);
    }
?>