<?php
    ini_set('display_errors','off');
    include('include/connection.php');

    $pageName = 'City';
    $cityActivation = 'active';
    $cityToggle = 'true';

    if(isset($_POST['add'])){
        $name = $_POST['name'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        $sql = "INSERT INTO city (city_name,city_latitude,city_longitude) VALUES ('$name','$latitude','$longitude')";
        if($conn->query($sql) === TRUE){
            header('Location: city.php?msg=City added!');
        }
    }
    if(isset($_POST['edit'])){
        $city_id = $_POST['city_id'];
        $name = $_POST['name'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        $sql = "UPDATE city SET city_name='$name',city_latitude='$latitude',city_longitude='$longitude' WHERE city_id='$city_id'";
        if($conn->query($sql) === TRUE){
            header('Location: city.php?msg=City updated!');
        }
    }
    if(isset($_POST['delete'])){
        $city_id = $_POST['city_id'];

        $sql = "DELETE FROM city WHERE city_id='$city_id'";
        if($conn->query($sql) === TRUE){
            header('Location: city.php?msg=City deleted!');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>City | Pickneats</title>
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
    <link href="plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/elements/miscellaneous.css" rel="stylesheet" type="text/css" />
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
                    <div class="col-sm-12">
                        <div class="row mb-4">
                            <div class="col-xl-9 col-md-9 col-sm-9 col-9">
                                <nav class="breadcrumb-two mt-4 mb-4" aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item active" aria-current="page"><a>City</a></li>
                                        <li class="breadcrumb-item"><a>City List</a></li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="col-xl-3 col-md-3 col-sm-3 col-3">
                                <a class="btn btn-primary float-right mt-4 mb-4" data-toggle="modal" data-target="#cityModal">Add New City</a>
                                <form method="post">
                                    <div class="modal fade" id="cityModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Add City</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <label for="name" class="col-form-label">City Name</label>
                                                            <input type="text" name="name" id="name" class="form-control" placeholder="City Name" autocomplete="off">
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
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                                    <button type="submit" name="add" class="btn btn-primary" onclick="return cityCheck()">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row h-100">
                            <?php
                                $sql = "SELECT * FROM city ORDER BY city_name ASC";
                                $result = $conn->query($sql);
                                $i = 0;
                                while($row = $result->fetch_assoc()){
                                    $city_id = $row['city_id'];
                                    $status = '';
                                    if($row['city_status'] == 1){
                                        $status = 'Checked';
                                    }
                                    $area = $service = 0;

                                    $sql1 = "SELECT * FROM login WHERE city_id='$city_id'";
                                    $result1 = $conn->query($sql1);
                                    while($row1 = $result1->fetch_assoc()){
                                        if($row1['control'] == 5){
                                            $area++;
                                        }
                                    }

                                    $sql1 = "SELECT * FROM service ORDER BY service_name ASC";
                                    $result1 = $conn->query($sql1);
                                    while($row1 = $result1->fetch_assoc()){
                                        $service_id = $row1['service_id'];

                                        $sql2 = "SELECT * FROM service_status WHERE city_id='$city_id' AND service_id='$service_id'";
                                        $result2 = $conn->query($sql2);
                                        if($result2->num_rows == 0){
                                            $service++;
                                        }
                                    }

                                    $sql1 = "SELECT sum(total_amount) AS total_amount FROM orders WHERE city_id='$city_id' AND order_status=6 AND payment_status=1";
                                    $result1 = $conn->query($sql1);
                                    $row1 = $result1->fetch_assoc();
                            ?>
                                    <div class="col-sm-3">
                                        <div class="statbox widget box box-shadow">
                                            <div class="widget-header">
                                                <div class="row">
                                                    <div class="col-sm-10">
                                                        <h4>
                                                            <a href="view-city.php?city_id=<?php echo $city_id ?>"><?php echo $row['city_name'] ?></a>
                                                            <a data-toggle="modal" data-target="#cityModal<?php echo $row['city_id'] ?>">
                                                                <svg style="width: 28px;height: 28px" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 p-1 br-6 mb-1">
                                                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                                </svg>
                                                            </a>
                                                        </h4>
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
                                                    </div>
                                                    <div class="col-sm-2" style="margin-top: 10px;">
                                                        <label class="switch s-icons s-outline s-outline-success mt-3 mb-3 ml-2 float-right" style="margin-bottom: 0px !important">
                                                            <input type="checkbox" <?php echo $status ?> id="status<?php echo $city_id ?>" onclick="return cityStatus(<?php echo $city_id ?>)">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget widget-one">
                                                <div class="row">
                                                    <div class="col-sm-6 text-center">
                                                        <a href="view-city.php?city_id=<?php echo $city_id ?>" class="btn btn-primary mt-3 mb-3 ml-2" style="padding: 5px !important">
                                                            Areas <span class="badge badge-light ml-2"><?php echo $area ?></span>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-6 text-center">
                                                        <a href="city-service.php?city_id=<?php echo $city_id ?>" class="btn btn-primary mt-3 mb-3 ml-2" style="padding: 5px !important">
                                                            Services <span class="badge badge-light ml-2"><?php echo $service ?></span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="w-chart">
                                                    <div class="w-chart-section total-visits-content" style="width: 100%">
                                                        <div class="w-detail">
                                                            <p class="w-title">Total Sales</p>
                                                            <p class="w-stats">₹ <?php echo number_format($row1['total_amount'], 2) ?></p>
                                                        </div>
                                                        <div class="w-chart-render-one">
                                                            <div id="<?php echo $city_id ?>" class="totalSales"></div>
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
    <!-- <script src="assets/js/dashboard/dash_1.js"></script> -->
</body>
</html>