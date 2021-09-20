<?php
    include('../include/connection.php');

	$output = array();

    if(isset($_POST['city_id'])){
		$city_id = $_POST["city_id"];

        $today = date('Y-m-d');
        $weekStart = date('Y-m-d', strtotime('-6 days'));

        $color[0] = '#'.substr(str_shuffle('ABCDEF0123456789'), 0, 6);

		$sql = "SELECT * FROM city WHERE city_id='$city_id'";
		$result = $conn->query($sql);
        if($result->num_rows > 0){
            $salesArray = array();

            for($checkDate=$weekStart;$checkDate<=$today;$checkDate=date('Y-m-d', strtotime($checkDate.'+ 1 day'))){
                $sql = "SELECT sum(total_amount) AS total_amount FROM orders WHERE city_id='$city_id' AND order_date='$checkDate' AND order_status=6 AND payment_status=1";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();

                array_push($salesArray, round($row['total_amount']));
            }
            $output['status'] = TRUE;
            $output['color'] = $color;
            $output['sales'] = $salesArray;
            // $output['sales'] = [23,46,43,54,5,35];
        } else{
            $output['status'] = FALSE;
        }
	}

	echo json_encode($output);
?>