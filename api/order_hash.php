<?php
    function generateHash($conn,$data){
        $sql = "SELECT * FROM app_control WHERE app_control_id='1'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $key = $row['order_hash'];
        $ciphering = "AES-128-CTR";
        $option = 0;
        $cipher = rand(1111111111111111,9999999999999999);

        $encrypted = openssl_encrypt($data,$ciphering,$key,$option,$cipher);

        $out['status'] = TRUE;
        $out['data'] = $encrypted;
        $out['cipher'] = $cipher;

        return json_encode($out);
    }
    function degenerateHash($conn,$oldPass,$cipher,$newPass){
        $sql = "SELECT * FROM app_control WHERE app_control_id='1'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $decryption_key = $row['order_hash'];
        $ciphering = "AES-128-CTR";
        $option = 0;

        $decrypted = openssl_decrypt($oldPass,$ciphering,$decryption_key,$option,$cipher);

        if($decrypted == $newPass){
            return TRUE;
        } else{
            return FALSE;
        }
    }
?>