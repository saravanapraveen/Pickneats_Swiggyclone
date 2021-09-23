<?php
    include("vendor/autoload.php");
    date_default_timezone_set('UTC');
    use \Firebase\JWT\JWT;

    function create_token($conn,$login_id,$check = 0){
        $sql = "SELECT * FROM app_control WHERE app_control_id='1'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $secret_key = $row['jwt_secret'];

        $issuer_claim = $_SERVER['SERVER_NAME'];
        $issuedat_claim = microtime(true);
        $notbefore_claim = $issuedat_claim;
        $current_expire_claim = $issuedat_claim + 300;
        $current_token = array(
            "iss" => $issuer_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $current_expire_claim,
            "login_id" => $login_id,
        );

        if($check == 0){
            $refresh_expire_claim = $issuedat_claim + 604800;
            $refresh_token = array(
                "iss" => $issuer_claim,
                "iat" => $issuedat_claim,
                "nbf" => $notbefore_claim,
                "exp" => $refresh_expire_claim,
                "login_id" => $login_id,
            );
            $out['refresh_token'] = JWT::encode($refresh_token, $secret_key);
        }

        $out['current_token'] = JWT::encode($current_token, $secret_key);

        return $out;
    }

    function check_token($conn,$token){
        $sql = "SELECT * FROM app_control WHERE app_control_id='1'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $secret_key = $row['jwt_secret'];
        try {
            $decoded = JWT::decode($token, $secret_key, array('HS256'));

            $res['status'] = 1;
            $res['data'] = (array) $decoded;
        } catch (Exception $e) {
            $res['status'] = 0;
            $res['content'] = $decoded;
            $res['data'] = $e->getMessage();
        }
        return $res;
    }
?>