<?php
    ini_set('display_errors','off');
    include('include/connection.php');
    include('../api/password.php');

    $cityActivation = 'active';
    $cityToggle = 'true';

    $city_id = $_REQUEST['city_id'];

    $sql = "SELECT * FROM city WHERE city_id='$city_id'";
    $result = $conn->query($sql);
    $city = $result->fetch_assoc();

    $pageName = $city['city_name'].' Details';

    if(isset($_POST['add'])){
        $name = $_POST['name'];
        $username = $_POST['username'];
        $controller_name = $_POST['controller_name'];
        $controller_phone = $_POST['controller_phone'];
        $controller_address = $_POST['controller_address'];
        $radius = $_POST['radius'];
        $password = $_POST['password'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        $responce = json_decode(generatePass($conn,$password));

        if($responce->status){
            $decrypted = $responce->password;
            $cipher = $responce->cipher;

            $sql = "INSERT INTO login (login_name,username,password,cipher,login_phone_number,address,control,city_id) VALUES ('$controller_name','$username','$decrypted','$cipher','$controller_phone','$controller_address','5','$city_id')";
            if($conn->query($sql) === TRUE){
                $sql = "SELECT * FROM login ORDER BY login_id DESC LIMIT 1";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();

                $login_id = $row['login_id'];

                $sql = "INSERT INTO area (login_id,area_name,control_radius,area_latitude,area_longitude) VALUES ('$login_id','$name','$radius','$latitude','$longitude')";
                if($conn->query($sql) === TRUE){
                    header("Location: view-city.php?city_id=$city_id&msg=Area added!");
                }
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
    <title><?php echo $city['city_name'] ?> Details | Pickneats</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png"/>
    
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/manual.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/elements/avatar.css" rel="stylesheet" type="text/css" />
    <link href="plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/users/user-profile.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/elements/breadcrumb.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="assets/css/forms/switches.css">
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
                    <div class="col-sm-9">
                        <nav class="breadcrumb-two mt-4 mb-4" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="city.php">City</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0);"><?php echo $city['city_name'] ?></a></li>
                                <li class="breadcrumb-item" aria-current="page"><a href="javascript:void(0);">City Details</a></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-xl-3 col-md-3 col-sm-3 col-3">
                        <a class="btn btn-primary float-right mt-4 mb-4" data-toggle="modal" data-target="#cityModal">Add New Area</a>
                        <form method="post">
                            <div class="modal fade" id="cityModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Add Area</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="name" class="col-form-label">Area Name</label>
                                                    <input type="text" name="name" id="name" class="form-control" placeholder="Area Name" autocomplete="off">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="name" class="col-form-label">Controller Name</label>
                                                    <input type="text" name="controller_name" id="controller_name" class="form-control" placeholder="Controller Name" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="name" class="col-form-label">Controller Phone Number</label>
                                                    <input type="text" name="controller_phone" id="controller_phone" class="form-control" placeholder="Phone Number" autocomplete="off">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="name" class="col-form-label">Controller Address</label>
                                                    <input type="text" name="controller_address" id="controller_address" class="form-control" placeholder="Address" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="name" class="col-form-label">Control Radius (in KM)</label>
                                                    <input type="number" min="0.01" step="0.01" name="radius" id="radius" class="form-control" placeholder="Radius" autocomplete="off">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="name" class="col-form-label">User Name</label>
                                                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" autocomplete="off" onkeyup="checkUsername(this.value)">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="password" class="col-form-label">Password</label>
                                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="off">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="retype" class="col-form-label">Retype Password</label>
                                                    <input type="password" name="retype" id="retype" class="form-control" placeholder="Retype Password" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    <label for="latitude" class="col-form-label">Latitude</label>
                                                    <input type="text" name="latitude" id="latitude" class="form-control" placeholder="Latitude" autocomplete="off">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="longitude" class="col-form-label">Longitude</label>
                                                    <input type="text" name="longitude" id="longitude" class="form-control" placeholder="Longitude" autocomplete="off">
                                                </div>
                                            </div>
                                            <p class="text-center" id="ErrorMes" syle="color: red"></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                            <button type="submit" name="add" class="btn btn-primary" onclick="return areaCheck(event)">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <?php
                        $sql = "SELECT * FROM login WHERE city_id='$city_id' AND control='5'";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()){
                            $login_id = $row['login_id'];

                            $sql1 = "SELECT * FROM shop WHERE area_id='$login_id'";
                            $result1 = $conn->query($sql1);
                            $shop = $result1->num_rows;

                            $sql1 = "SELECT * FROM delivery_partner WHERE area_id='$login_id'";
                            $result1 = $conn->query($sql1);
                            $deliveryPartner = $result1->num_rows;

                            $sql1 = "SELECT * FROM area WHERE login_id='$login_id'";
                            $result1 = $conn->query($sql1);
                            $row1 = $result1->fetch_assoc();
                    ?>
                            <div class="col-sm-3">
                                <div class="statbox widget box box-shadow">
                                    <div class="widget-header">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <h4><a href="view-city.php?city_id=<?php echo $city_id ?>"><?php echo $row1['area_name'] ?></a></h4>
                                            </div>
                                            <div class="col-sm-2" style="margin-top: 10px;">
                                                <div class="dropdown  custom-dropdown">
                                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-1">
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#cityModal<?php echo $row['city_id'] ?>">
                                                            <svg style="width: 28px;height: 28px" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 p-1 br-6 mb-1">
                                                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                            </svg>
                                                        </a>
                                                        <form method="post">
                                                            <div class="modal fade" id="cityModal<?php echo $row['city_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="cityLabel<?php echo $row['city_id'] ?>" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="cityLabel<?php echo $row['city_id'] ?>">Edit City</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <label for="name" class="col-form-label">City Name</label>
                                                                                    <input type="text" name="name" id="name<?php echo $row['city_id'] ?>" class="form-control" placeholder="City Name" autocomplete="off" value="<?php echo $row['city_name'] ?>">
                                                                                    <input type="hidden" name="city_id" value="<?php echo $row['city_id'] ?>">
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-3">
                                                                                <div class="col-sm-6">
                                                                                    <label for="latitude" class="col-form-label">Latitude</label>
                                                                                    <input type="text" name="latitude" id="latitude<?php echo $row['city_id'] ?>" class="form-control" placeholder="Latitude" autocomplete="off" value="<?php echo $row['city_latitude'] ?>">
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <label for="longitude" class="col-form-label">Longitude</label>
                                                                                    <input type="text" name="longitude" id="longitude<?php echo $row['city_id'] ?>" class="form-control" placeholder="Longitude" autocomplete="off" value="<?php echo $row['city_longitude'] ?>">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                                                            <button type="submit" name="edit" class="btn btn-primary" onclick="return cityCheck(<?php echo $row['city_id'] ?>)">Save</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <a class="dropdown-item" href="javascript:void(0);">Update</a>
                                                        <a class="dropdown-item" href="javascript:void(0);">Download</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget widget-one">
                                        <div class="row mb-3">
                                            <div class="col-sm-4">
                                                <a href="shop.php?city_id=<?php echo $city_id ?>&area_id=<?php echo $login_id ?>" class="btn btn-primary mt-3 mb-3 ml-2" style="padding: 5px !important;margin: 0px !important;">
                                                    Shops <span class="badge badge-light ml-2"><?php echo $shop ?></span>
                                                </a>
                                            </div>
                                            <div class="col-sm-6">
                                                <a href="city-service.php?city_id=<?php echo $city_id ?>" class="btn btn-primary mt-3 mb-3 ml-2" style="padding: 5px !important;margin: 0px !important;">
                                                    Delivery Boys <span class="badge badge-light ml-2"><?php echo $deliveryPartner ?></span>
                                                </a>
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="switch s-icons s-outline s-outline-success mt-1 mb-3 ml-2 float-right" style="margin-bottom: 0px !important">
                                                    <input type="checkbox" <?php echo $status ?> id="status<?php echo $city_id ?>" onclick="return cityStatus(<?php echo $city_id ?>)">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="w-chart">
                                            <div class="w-chart-section total-visits-content" style="width: 100%">
                                                <div class="w-detail">
                                                    <p class="w-title">Total Sales</p>
                                                    <p class="w-stats">₹ <?php echo number_format($row1['total_amount'], 2) ?></p>
                                                </div>
                                                <div class="w-chart-render-one">
                                                    <div id="<?php echo $city_id ?>" class="totalSalesArea"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    ?>
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
    <script src="assets/js/map.js"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/elements/custom-search.js"></script>
    <script src="plugins/notification/snackbar/snackbar.min.js"></script>
    <script src="assets/js/components/notification/custom-snackbar.js"></script>
    <script src="plugins/apex/apexcharts.min.js"></script>
</body>
</html>