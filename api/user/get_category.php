<?php
    include("../../controlpanel/include/connection.php");
    include("../worker/category.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->shop_id)){
        $shop_id = $data->shop_id;

        $sql = "SELECT * FROM category WHERE login_id='$shop_id' AND category_status='1' ORDER BY category_arrangement ASC";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $i = 0;
            while($row = $result->fetch_assoc()){
                $category_id = $row['category_id'];

                $output_array['GTS'][$i] = getCategory($conn,$category_id);
                $i++;
            }
            http_response_code(200);
        } else{
            http_response_code(404);
        }
    } else{
        http_response_code(400);
    }

    echo json_encode($output_array);
?>