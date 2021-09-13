<?php
    include("../../controlpanel/include/connection.php");
    $output_array = array();

    if(!empty($_POST["user_id"])){
        $user_id = $_POST["user_id"];
        $image = $_FILES['image'];

        $sql = "SELECT * FROM delivery_partner WHERE delivery_partner_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $type = Pathinfo($image['name'],PATHINFO_EXTENSION);
            $path = "Images/Delivery/$user_id.$type";
            $ImagePath = "../../dashboard/$path";

            if(move_uploaded_file($image["tmp_name"], $ImagePath)){
                $sql = "UPDATE delivery_partner SET delivery_partner_image='$path' WHERE delivery_partner_id='$user_id'";
                if($conn->query($sql) === TRUE){
                    http_response_code(200);
                    $output_array['status'] = 'success';
                    $output_array['message'] = 'Ok';
                }
            } else{
                http_response_code(404);
                $output_array['status'] = 'fail';
                $output_array['message'] = 'Image Upload Failed';
            }
        } else{
            http_response_code(403);
            $output_array['status'] = 'fail';
            $output_array['message'] = 'User not registered';
        }
    } else{
        http_response_code(400);
        $output_array['status'] = 'fail';
        $output_array['message'] = 'Bad Request';
    }

    echo json_encode($output_array);
?>