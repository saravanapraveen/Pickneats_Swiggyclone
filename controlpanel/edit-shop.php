<?php
    include('include/connection.php');
    include('../api/password.php');

    $pageName = 'Shop';
    $shopActivation = 'active';
    $shopToggle = 'true';

    $shop_id = $_REQUEST['id'];

    $sql = "SELECT * FROM shop WHERE shop_id='$shop_id'";
    $result = $conn->query($sql);
    $num_rows = $result->num_rows;

    if($num_rows > 0){
        $row = $result->fetch_assoc();
        
        $login_id = $row['login_id'];
        $service_id = $row['service_id'];
        $owner_id = $row['owner_id'];
        $image = $row['shop_image'];
        $open_time = $row['open_time'];
        $close_time = $row['close_time'];
        $packing_charge = $row['packing_charge'];
        $tax = $row['tax'];
        $latitude = $row['latitude'];
        $longitude = $row['longitude'];
        $license = $row['license'];
        $delivery_charge = $row['delivery_charge'];
        $minimum_distance = $row['minimum_distance'];
        $increment = $row['increment'];
        $delivery_type = $row['delivery_type'];
        $free_delivery = $row['free_delivery'];
        $percentage = $row['percentage'];
        $amount = $row['amount'];
        
        $sql1 = "SELECT * FROM login WHERE login_id='$login_id'";
        $result1 = $conn->query($sql1);
        $row1 = $result1->fetch_assoc();

        $city_id = $row1['city_id'];
        $shop_name = $row1['login_name'];
        $username = $row1['username'];
        $shop_phone = $row1['login_phone_number'];
        
        if($row['packing_check'] == "1"){
            $packingCheck = "checked";
        }

        if($row['tax_check'] == "1"){
            $taxCheck = "checked";
        }

    }else{
        header("Location: shop.php?err=Unable to fetch data!");
    }

    if(isset($_POST['add'])){
        $name = $_POST['name'];
        $service = $_POST['service'];
        $owner = $_POST['owner'];
        $image = $_POST['image'];
        $username = $_POST['username'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $opentime = $_POST['opentime'];
        $closetime = $_POST['closetime'];
        $packingCharge = $_POST['packingCharge'];
        $tax = $_POST['tax'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $license = $_POST['license'];
        $deliveryCharge = $_POST['deliveryCharge'];
        $minimumDistance = $_POST['minimumDistance'];
        $increment = $_POST['increment'];
        $deliveryType = $_POST['deliveryType'];
        $free = $_POST['free'];
        $per = $_POST['per'];
        $amo = $_POST['amo'];

        $packingCheck = $taxCheck = 0;

        if(isset($_POST['packingCheck'])){
            $packingCheck = 1;
        }
        if(isset($_POST['taxCheck'])){
            $taxCheck = 1;
        }
        if($owner == ''){
            $owner = 0;
        }

        if($login_city_id ==0){
            $city = $_POST['city'];
        }else{
            $city = $login_city_id;
        }

        $sql = "UPDATE login SET login_name='$name',city_id='$city',username='$username',login_phone_number='$phone',cipher='$cipher' WHERE login_id='$login_id'";
        if($conn->query($sql) === TRUE){
            $sql = "SELECT * FROM login WHERE username='$username'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $login_id = $row['login_id'];

            $sql = "UPDATE shop SET city_id='$city',service_id='$service',owner_id='$owner',shop_image='$image',open_time='$opentime',close_time='$closetime',packing_charge='$packingCharge',packing_check='$packingCheck',tax='$tax',tax_check='$taxCheck',latitude='$latitude',longitude='$longitude',license='$license',delivery_charge='$deliveryCharge',minimum_distance='$minimumDistance',increment='$increment',delivery_type='$deliveryType',free_delivery='$free',percentage='$per',amount='$amo' WHERE shop_id='$shop_id'";
            if($conn->query($sql) === TRUE){
                header('Location: shop.php?msg=Shop added!');
            }
        }
        
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Shop | Pickneats</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png"/>
    
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/manual.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/elements/avatar.css" rel="stylesheet" type="text/css" />
    <link href="plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="assets/css/tables/table-basic.css"/>

    <link rel="stylesheet" type="text/css" href="plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="assets/css/forms/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="plugins/table/datatable/dt-global_style.css">

    <link href="assets/css/components/custom-modal.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="plugins/font-icons/fontawesome/css/regular.css">
    <link rel="stylesheet" href="plugins/font-icons/fontawesome/css/fontawesome.css">
    
</head>
<body class="sidebar-noneoverflow">
    <?php include('include/navbar.php') ?>
    <div class="main-container" id="container">
        <div class="overlay"></div>
        <div class="cs-overlay"></div>
        <div class="search-overlay"></div>

        <?php include('include/sidebar.php') ?>

        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-10 col-md-10 col-sm-10 col-10">
                                        <h4>Add Shop</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <form method="post" class="p-4">
                                    <div class="row mb-3">
                                        <?php
                                            if($login_city_id !=0){
                                                ?>
                                                    <div class="col-sm-6">
                                                <?php
                                            }else{
                                                ?> <div class="col-sm-12"> <?php
                                            }
                                        ?>
                                            <label for="name">Shop Name</label>
                                            <input type="text" name="name" id="name" value="<?php echo $shop_name;?>" class="form-control" placeholder="Shop Name" autocomplete="off" required>
                                        </div>
                                    <?php
                                    if($login_city_id == 0){
                                        ?>
                                            <div class="col-sm-6">
                                                <label for="city">City</label>
                                                <select name="city" id="city" class="form-control" required>
                                                    <option selected disabled>Select City</option>
                                                    <?php
                                                        $sql = "SELECT * FROM city ORDER BY city_name ASC";
                                                        $result = $conn->query($sql);
                                                        while($row = $result->fetch_assoc()){
                                                            $selected_city = "";
                                                            if($row['city_id'] == $city_id){
                                                                $selected_city = "selected";
                                                            }else{
                                                                if($login_city_id !=0){
                                                                    $selected_city = "disabled";
                                                                }
                                                            }
                                                    ?>
                                                            <option value="<?php echo $row['city_id'] ?>" <?php echo $selected_city;?>><?php echo $row['city_name'] ?></option>
                                                    <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        <?php
                                    }
                                    ?>
                                        <div class="col-sm-6">
                                            <label for="service">Service</label>
                                            <select name="service" id="service" class="form-control" required>
                                                <option selected disabled>Select Service</option>
                                                <?php
                                                    $sql = "SELECT * FROM service ORDER BY service_name ASC";
                                                    $result = $conn->query($sql);
                                                    while($row = $result->fetch_assoc()){
                                                        $selected_service = "";
                                                        if($row['service_id'] == $service_id){
                                                            $selected_service = "selected";
                                                        }
                                                ?>
                                                        <option value="<?php echo $row['service_id'] ?>" <?php echo $selected_service;?>><?php echo $row['service_name'] ?></option>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <label for="owner">Owner</label>
                                            <select name="owner" id="owner" class="form-control" required>
                                                <option selected disabled>Select Owner</option>
                                                <?php
                                                    $sql = "SELECT * FROM login WHERE control='4' ORDER BY login_name ASC";
                                                    $result = $conn->query($sql);
                                                    while($row = $result->fetch_assoc()){
                                                        $selected_owner = "";
                                                        if($row['login_id'] == $owner_id){
                                                            $selected_owner = "selected";
                                                        }
                                                ?>
                                                        <option value="<?php echo $row['login_id'] ?>" <?php echo $selected_owner;?>><?php echo $row['login_name'] ?></option>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="image">Image</label>
                                            <input type="text" name="image" id="image" placeholder="Image" value="<?php echo $image;?>" class="form-control" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <label for="username" class="col-form-label">User Name</label>
                                            <input type="text" name="username" id="username" class="form-control" value="<?php echo $username;?>" placeholder="User Name" autocomplete="off" onkeyup="checkUsername(this.value)" requrired>
                                            <label id="ErrorMes" style="padding: 10px 0px 0px 10px;"></label>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="phone" class="col-form-label">Phone Number</label>
                                            <input type="number" name="phone" id="phone" class="form-control" value="<?php echo $shop_phone;?>" placeholder="Phone Number" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <label for="opentime" class="col-form-label">Opening Time</label>
                                            <input type="time" name="opentime" id="opentime" class="form-control" value="<?php echo $open_time;?>" placeholder="Opening Time" autocomplete="off" value="08:00" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="closetime" class="col-form-label">Closing Time</label>
                                            <input type="time" name="closetime" id="closetime" class="form-control" value="<?php echo $close_time;?>" placeholder="Closing Time" autocomplete="off" value="22:00" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <label for="packingCharge">Packing Charge</label>
                                            <input type="number" min="0" name="packingCharge" id="packingCharge" placeholder="Packing Charge" value="<?php echo $packing_charge;?>" class="form-control" autocomplete="off" required>
                                        </div>
                                        <div class="col-sm-2 mt-5">
                                            <input type="checkbox" name="packingCheck" id="packingCheck" <?php echo $packingCheck;?>>
                                            <label for="packingCheck">For Pickneats</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="tax">Tax %</label>
                                            <input type="number" min="0" name="tax" id="tax" placeholder="Tax %" value="<?php echo $tax;?>" class="form-control" autocomplete="off" required>
                                        </div>
                                        <div class="col-sm-2 mt-5">
                                            <input type="checkbox" name="taxCheck" id="taxCheck" <?php echo $taxCheck;?>>
                                            <label for="taxCheck">For Pickneats</label>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <label for="latitude">Latitude</label>
                                            <input type="text" name="latitude" id="latitude" value="<?php echo $latitude;?>" placeholder="Latitude" class="form-control" autocomplete="off" required>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="longitude">Longitude</label>
                                            <input type="text" name="longitude" id="longitude" value="<?php echo $longitude;?>" placeholder="Longitude" class="form-control" autocomplete="off" required>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="license">License Number ( fssai )</label>
                                            <input type="text" name="license" id="license" value="<?php echo $license;?>" placeholder="License Number ( fssai )" class="form-control" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <label for="deliveryCharge">Delivery Charge</label>
                                            <input type="number" min="0" name="deliveryCharge" id="deliveryCharge" value="<?php echo $delivery_charge;?>" placeholder="Delivery Charge" class="form-control" autocomplete="off" required>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="minimumDistance">Minimum delivery charge distance</label>
                                            <input type="number" min="0" name="minimumDistance" id="minimumDistance" value="<?php echo $minimum_distance;?>" placeholder="Minimum delivery charge distance" class="form-control" autocomplete="off" required>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="increment">Increment per KM</label>
                                            <input type="number" min="0" name="increment" id="increment" value="<?php echo $increment;?>" placeholder="Increment per KM" class="form-control" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div id="displayClass" class="col-sm-12">
                                            <label for="deliveryType">Delivery Type</label>
                                            <select name="deliveryType" id="deliveryType" class="form-control" onchange="showDeliveryType(this.value)" required>
                                                <?php
                                                    if($delivery_type == 0){
                                                        $normal = "selected";
                                                    }else{
                                                        if($delivery_type == 1){
                                                            $percentage = "selected";
                                                        }else{
                                                            $share = "selected";
                                                        }
                                                    }
                                                ?>
                                                <option value="0" <?php echo $normal;?>>Normal</option>
                                                <option value="1" <?php echo $percentage;?>>Percentage</option>
                                                <option value="2" <?php echo $share;?>>Share</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4" id="free">
                                            <label for="free">Free Delivery Limit (Rs)</label>
                                            <input type="number" name="free" placeholder="Free Delivery Limit (Rs)" value="<?php echo $free_delivery;?>" class="form-control" >
                                        </div>
                                        <div class="col-sm-4" id="per">
                                            <label>Percentage</label>
                                            <input type="number" name="per" class="form-control" value="<?php echo $percentage;?>" placeholder="Percentage">
                                        </div>
                                        <div class="col-sm-4" id="amo">
                                            <label>Amount</label>
                                            <input type="number" name="amo" class="form-control" value="<?php echo $amount;?>" placeholder="Amount">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <label id="Error" style="color: red"></label>
                                        </div>
                                        <div class="col-sm-12">
                                            <input type="submit" name="add" value="Add" class="btn btn-primary float-right">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('include/footer.php') ?>
        </div>
    </div>

    <script src="assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="assets/js/manual.js"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/elements/custom-search.js"></script>
    <script src="plugins/notification/snackbar/snackbar.min.js"></script>
    <script src="assets/js/components/notification/custom-snackbar.js"></script>
    <script src="plugins/table/datatable/datatables.js"></script>
    <script>
        $('#zero-config').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
        "<'table-responsive'tr>" +
        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 7 
        });
        function showDeliveryType(value){
            if(value == 0){
                document.getElementById('displayClass').classList.remove('col-sm-4')
                document.getElementById('displayClass').classList.add('col-sm-12')
                document.getElementById('free').style.display = 'none'
                document.getElementById('amo').style.display = 'none'
                document.getElementById('per').style.display = 'none'
            } else{
                document.getElementById('displayClass').classList.add('col-sm-4')
                document.getElementById('displayClass').classList.remove('col-sm-12')
                document.getElementById('free').style.display = 'block'
                if(value == 1){
                    document.getElementById('per').style.display = 'block'
                    document.getElementById('amo').style.display = 'none'
                } else{
                    document.getElementById('amo').style.display = 'block'
                    document.getElementById('per').style.display = 'none'
                }
            }
        }
    </script>
    <script>
        let value = <?php echo $delivery_type;?>;
        window.addEventListener('load', (event) => {
            if(value == 0){
                document.getElementById('displayClass').classList.remove('col-sm-4')
                document.getElementById('displayClass').classList.add('col-sm-12')
                document.getElementById('free').style.display = 'none'
                document.getElementById('amo').style.display = 'none'
                document.getElementById('per').style.display = 'none'
            } else{
                document.getElementById('displayClass').classList.add('col-sm-4')
                document.getElementById('displayClass').classList.remove('col-sm-12')
                document.getElementById('free').style.display = 'block'
                if(value == 1){
                    document.getElementById('per').style.display = 'block'
                    document.getElementById('amo').style.display = 'none'
                } else{
                    document.getElementById('amo').style.display = 'block'
                    document.getElementById('per').style.display = 'none'
                }
            }
        })
    </script>
</body>
</html>