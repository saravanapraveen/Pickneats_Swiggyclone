<?php
    include("../../controlpanel/include/connection.php");
    $output_array = array();

    $sql = "SELECT * FROM app_control";
    $result = $conn->query($sql);

    http_response_code(200);
    $output_array['GTS'] = $result->fetch_assoc();
    $output_array['status'] = 'success';
    $output_array['message'] = 'Ok';

    echo json_encode($output_array);
?>