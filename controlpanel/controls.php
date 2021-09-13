<?php
    ini_set('display_errors','off');
    include('include/connection.php');
    include('../api/password.php');

    $pageName = 'Control';
    $controlActivation = 'active';
    $controlToggle = 'true';

    $login_id = $_REQUEST['login_id'];

    $sql = "SELECT * FROM login WHERE login_id='$login_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $sql1 = "SELECT * FROM app_control";
    $result1 = $conn->query($sql1);
    $row1 = $result1->fetch_assoc();

    if(isset($_POST['add'])){
        $maximum_distance = $_POST['maximum_distance'];
        $pick_delivery_charge = $_POST['pick_delivery_charge'];
        $first_mile_charge = $_POST['first_mile_charge'];
        $last_mile_charge = $_POST['last_mile_charge'];
        $whatsapp = $_POST['whatsapp'];
        $facebook = $_POST['facebook'];
        $instagram = $_POST['instagram'];

        $sql = "UPDATE app_control SET maximum_distance='$maximum_distance',pick_delivery_charge='$pick_delivery_charge',first_mile_charge='$first_mile_charge',last_mile_charge='$last_mile_charge',whatsapp='$whatsapp',facebook='$facebook',instagram='$instagram'";
        if($conn->query($sql)==TRUE)
        {
            header("Location: controls.php?msg=Controls Updated!");
        }else{
            header("Location: controls.php?msg=Controls Updation Failed!");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Product | Pickneats</title>
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
    <link rel="stylesheet" type="text/css" href="plugins/bootstrap-select/bootstrap-select.min.css">
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
                                        <h4>App Controls</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <form method="post" class="p-4">

                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <label for="owner">Maximum Distance</label>
                                            <input type="text" name="maximum_distance" id="maximum_distance" placeholder="Maximum Distance" class="form-control" value="<?php echo $row1['maximum_distance'];?>">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="category">Pick & Deliver Delivery Charge</label>
                                            <input type="text" name="pick_delivery_charge" id="pick_delivery_charge" placeholder="Pick & Deliver Delivery Charge" class="form-control" value="<?php echo $row1['pick_delivery_charge'];?>">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <label for="owner">First Mile Charge</label>
                                            <input type="text" name="first_mile_charge" id="first_mile_charge" placeholder="First Mile Charge" class="form-control" value="<?php echo $row1['first_mile_charge'];?>">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="category">Last Mile Charge</label>
                                            <input type="text" name="last_mile_charge" id="last_mile_charge" placeholder="Last Mile Charge" class="form-control" value="<?php echo $row1['last_mile_charge'];?>">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <label for="owner">Whatsapp</label>
                                            <input type="text" name="whatsapp" id="whatsapp" placeholder="Whatsapp" class="form-control" value="<?php echo $row1['whatsapp'];?>">
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="category">Facebook</label>
                                            <input type="text" name="facebook" id="facebook" placeholder="Product Name" class="form-control" value="<?php echo $row1['facebook'];?>">
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="category">Instagram</label>
                                            <input type="text" name="instagram" id="instagram" placeholder="Product Name" class="form-control" value="<?php echo $row1['instagram'];?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <label id="Error" style="color: red"></label>
                                        </div>
                                        <div class="col-sm-12">
                                            <input type="submit" name="add" value="Add" class="btn btn-primary float-right" onclick="return controlsCheck()">
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
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/elements/custom-search.js"></script>
    <script src="plugins/notification/snackbar/snackbar.min.js"></script>
    <script src="assets/js/components/notification/custom-snackbar.js"></script>
    <script src="plugins/table/datatable/datatables.js"></script>
    <script src="plugins/bootstrap-select/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
</body>
</html>