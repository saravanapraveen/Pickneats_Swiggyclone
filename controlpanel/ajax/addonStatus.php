<?php
    include('../include/connection.php');

    if(!empty($_POST['addon_id'])){
        $addon_id = $_POST['addon_id'];
        $control_status = $_POST['control_status'];

        $sql1 = "SELECT * FROM addon WHERE addon_id='$addon_id'";
        $result1 = $conn->query($sql1);
        $row1 = $result1->fetch_assoc();

        if($row1['addon_status'] == "1"){
            $sql = "UPDATE addon SET addon_status='0' WHERE addon_id='$addon_id'";
        }else{
            $sql = "UPDATE addon SET addon_status='1' WHERE addon_id='$addon_id'";
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