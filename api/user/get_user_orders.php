<?php
    include("../../controlpanel/include/connection.php");

    $output = array();

    $data = json_decode(file_get_contents('php://input'));

    if(!empty($data->user_id)){
        $user_id = $data->user_id;
        $page = $data->page;

        $end = ($page + 1) * 10;
        $start = $end - 10;

        $sql = "SELECT * FROM user WHERE user_id='$user_id'";
        $result = $conn->query($sql);
        if($result->num_rows){
            $sql = "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY order_id DESC LIMIT $end";
            $result = $conn->query($sql);
            if($result->num_rows){
                $i = $j = 0;
                while($row = $result->fetch_assoc()){
                    if($j >= $start){
                        $output['GTS'][$i]['order_id'] = (int)$row['order_id'];
                        $output['GTS'][$i]['order_string'] = $row['order_string'];
                        $output['GTS'][$i]['order_date'] = date('d-m-Y', strtotime($row['order_date']));
                        $output['GTS'][$i]['order_time'] = strtotime($row['order_time']);
                        $output['GTS'][$i]['order_status'] = (int)$row['order_status'];
                        $output['GTS'][$i]['payment_status'] = (int)$row['payment_status'];

                        $i++;
                    }
                }
                if(!count($output)){
                    http_response_code(404);
                }
            } else{
                http_response_code(404);
            }
        } else{
            http_response_code(403);
        }
    } else{
        http_response_code(400);
    }

    if(count($output)){
        echo json_encode($output);
    }
?>