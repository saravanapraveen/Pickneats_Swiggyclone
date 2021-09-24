<?php
    include('../include/connection.php');

    if(isset($_POST['city'])){
		$city = $_POST["city"];

        $inner = $outer = '';

        for($j = 0;$j<count($city);$j++){
            $city_id = $city[$j];

            $sql = "SELECT * FROM login WHERE city_id='$city_id' AND control=2";
            $result = $conn->query($sql);
            $i = 0;
            while($row = $result->fetch_assoc()){
                $inner .= '<option value="'.$row['login_id'].'">'.$row['login_name'].'</option>';
                $outer .= '<a tabindex="0" class="dropdown-item" data-original-index="'.$i.'"><span class="dropdown-item-inner " data-tokens="null" role="option" tabindex="0" aria-disabled="false" aria-selected="false"><span class="text">'.$row['login_name'].'</span><span class="  check-mark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></span></span></a>';
                $i++;
            }
        }

        $out['inner'] = $inner;
        $out['outer'] = $outer;

        echo json_encode($out);
	} else{
        $inner = $outer = '';

        $out['inner'] = $inner;
        $out['outer'] = $outer;

        echo json_encode($out);
    }
?>