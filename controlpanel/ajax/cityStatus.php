<?php
    include('../include/connection.php');
    ini_set('display_errors','off');
    
    if(!empty($_POST['city_id'])){
        $city_id = $_POST['city_id'];

        $sql1 = "SELECT * FROM city WHERE city_id='$city_id'";
        $result1 = $conn->query($sql1);
        $row1 = $result1->fetch_assoc();

        if($row1['city_status'] == "1"){
            $sql = "UPDATE city SET city_status='0' WHERE city_id='$city_id'";
        }else{
            $sql = "UPDATE city SET city_status='1' WHERE city_id='$city_id'";
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