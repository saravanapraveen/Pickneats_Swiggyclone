<?php
    include("../../controlpanel/include/connection.php");
    $output_array = array();

    $sql = "SELECT * FROM category WHERE category_status='1' ORDER BY category_arrangement ASC";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $i = 0;
        while($row = $result->fetch_assoc()){
            $output_array['GTS'][$i]['category_id'] = $row['category_id'];
            $output_array['GTS'][$i]['category_name'] = $row['category_name'];
            $output_array['GTS'][$i]['category_image'] = $row['category_image'];
            $i++;
        }
        http_response_code(200);
        $output_array['status'] = 'success';
        $output_array['message'] = 'Ok';
    } else{
        http_response_code(404);
        $output_array['status'] = 'fail';
        $output_array['message'] = 'No category found';
    }

    echo json_encode($output_array);
?>