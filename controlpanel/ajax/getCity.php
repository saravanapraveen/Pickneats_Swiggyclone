<?php
	ini_set('display_errors','off');
    include('../include/connection.php');

    if(isset($_POST['city'])){

		$city = $_POST["city"];

        ?>
            <option value="All">All Shops</option>
        <?php

		$sql = "SELECT * FROM login WHERE BINARY city_id='$city'";
		$result = $conn->query($sql);
		while($row = $result->fetch_assoc())
        {
            ?>
                <option value="<?php echo $row['login_id'];?>"><?php echo $row['login_name'];?></option>
            <?php
        }
	}
?>