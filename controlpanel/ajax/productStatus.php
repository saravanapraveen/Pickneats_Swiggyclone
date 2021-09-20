<?php
    include('../include/connection.php');
    
    if(!empty($_POST['product_id'])){
        $product_id = $_POST['product_id'];

        $sql1 = "SELECT * FROM product WHERE product_id='$product_id'";
        $result1 = $conn->query($sql1);
        $row1 = $result1->fetch_assoc();

        if($row1['product_status'] == "1"){
            $sql = "UPDATE product SET product_status='0' WHERE product_id='$product_id'";
        }else{
            $sql = "UPDATE product SET product_status='1' WHERE product_id='$product_id'";
        }
        if($conn->query($sql) === TRUE){
            $output['status'] = 'success';
            $output['message'] = 'Status updated';
        }else{
            $output['status'] = 'failed';
            $output['message'] = 'Status updation failed';
        }
    }
    echo json_encode($output);
?>