<?php
    include("../../controlpanel/include/connection.php");
    include("../worker/product.php");
    $output_array = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->category_id)){
        $category_id = $data->category_id;
        $user_id = $data->user_id;

        $sql = "SELECT * FROM product WHERE product_status='1' AND category_id='$category_id' ORDER BY product_name ASC";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $i = 0;
            while($row = $result->fetch_assoc()){
                $product_id = $row['product_id'];
                
                $output_array['GTS'][$i] = getProduct($conn,$product_id);

                $i++;
            }

            if($i > 0){
                http_response_code(200);
                $output_array['status'] = 'success';
                $output_array['message'] = 'Ok';
            } else{
                http_response_code(404);
                $output_array['status'] = 'fail';
                $output_array['message'] = 'No products found';
            }
        } else{
            http_response_code(404);
            $output_array['status'] = 'fail';
            $output_array['message'] = 'No products found';
        }
    } else{
        http_response_code(404);
        $output_array['status'] = 'fail';
        $output_array['message'] = 'Bad request';
    }

    echo json_encode($output_array);
?>