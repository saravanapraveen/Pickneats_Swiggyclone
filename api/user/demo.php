<?php
    include("../../controlpanel/include/connection.php");
    include("../order_hash.php");

    $data = json_decode(generateHash($conn,'sgsg'));

    var_dump($data);
    
    echo base64_decode($data->data);
?>