<?php
    function Send_OTP($phone,$msg,$otp){
        $url = "http://sms.cloudsolz.com/api/otp.php?authkey=349348ALtWCC01w6im5fdf6363P1&mobile=$phone&message=$msg&sender=user&otp=$otp";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $responce = curl_exec($ch);

        return json_decode($responce);
    }
?>