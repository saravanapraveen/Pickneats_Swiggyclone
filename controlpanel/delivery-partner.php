<?php
    ini_set('display_errors','off');
    include('include/connection.php');

    $pageName = 'Delivery Partner';
    $deliveryPartnerActivation = 'active';
    $deliveryPartnerToggle = 'true';

    $ser_id = $_REQUEST['service_id'];
    $c_id = $_REQUEST['city_id'];
    $area_id = $_REQUEST['area_id'];

    if($c_id == ""){
        if($login_city_id != "0"){
            $c_id = $login_city_id;
        }
    }

    if($c_id != ''){
        $sql = "SELECT * FROM city WHERE city_id='$c_id'";
        $result = $conn->query($sql);
        $city = $result->fetch_assoc();
    }

    if($area_id != ''){
        $sql = "SELECT * FROM area WHERE login_id='$area_id'";
        $result = $conn->query($sql);
        $area = $result->fetch_assoc();
    }

    if(isset($_POST['delete'])){
        $login_id = $_POST['login_id'];

        $sql = "SELECT * FROM login WHERE login_id='$login_id'";
        if($conn->query($sql) === TRUE){
            $sql = "SELECT * FROM shop WHERE login_id='$login_id'";
            if($conn->query($sql) === TRUE){
                header('Location: shop.php?msg=Shop deleted!');
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
    <link href="assets/css/elements/breadcrumb.css" rel="stylesheet" type="text/css" />
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
                        <nav class="breadcrumb-two mt-4 mb-4" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <?php
                                    if($c_id != ''){
                                ?>
                                        <li class="breadcrumb-item"><a href="city.php">City</a></li>
                                        <?php
                                            if($area_id != ''){
                                        ?>
                                                <li class="breadcrumb-item"><a href="view-city.php?city_id=<?php echo $city['city_id'] ?>"><?php echo $city['city_name'] ?></a></li>
                                                <li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0);"><?php echo $area['area_name'] ?></a></li>
                                        <?php
                                            } else{
                                        ?>
                                                <li class="breadcrumb-item"><a href="city-service.php?city_id=<?php echo $city['city_id'] ?>"><?php echo $city['city_name'] ?></a></li>
                                                <li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0);"><?php echo $service['service_name'] ?></a></li>
                                        <?php
                                            }
                                        ?>
                                        <li class="breadcrumb-item" aria-current="page"><a href="javascript:void(0);">Delivery Partner Details</a></li>
                                <?php
                                    }
                                ?>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-10 col-md-10 col-sm-10 col-10">
                                        <h4>All Delivery Partners</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <table id="zero-config" class="table dt-table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Shop Name</th>
                                            <?php
                                                if($c_id ==""){
                                                    ?>
                                                        <th>City</th>
                                                    <?php
                                                }
                                            ?>
                                            <?php
                                                if($ser_id ==""){
                                                    ?>
                                                        <th>Service</th>
                                                    <?php
                                                }
                                            ?>
                                            <th>Owner</th>
                                            <th>Image</th>
                                            <th>Phone</th>
                                            <th class="no-content">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if($area_id != '' && $ser_id != ''){
                                                $sql = "SELECT * FROM shop WHERE service_id='$ser_id' AND area_id='$area_id' ORDER BY shop_id DESC";
                                            } else{
                                                if($area_id != ''){
                                                    $sql = "SELECT * FROM shop WHERE area_id='$area_id' ORDER BY shop_id DESC";
                                                } else{
                                                    if($ser_id == "" && $c_id == ""){
                                                        $sql = "SELECT * FROM shop ORDER BY shop_id DESC";
                                                    } else{
                                                        if($ser_id !="" && $c_id !=""){
                                                            $sql = "SELECT * FROM shop WHERE service_id='$ser_id' AND city_id='$c_id' ORDER BY shop_id DESC";
                                                        } else{
                                                            if($ser_id !=""){
                                                                $sql = "SELECT * FROM shop WHERE service_id='$ser_id' ORDER BY shop_id DESC";
                                                            } else{
                                                                $sql = "SELECT * FROM shop WHERE city_id='$c_id' ORDER BY shop_id DESC";
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            
                                            $resuult = $conn->query($sql);
                                            $count = 1;
                                            while($row = $resuult->fetch_assoc())
                                            {
                                                
                                                $login_id = $row['login_id'];
                                                $service_id = $row['service_id'];
                                                $owner_id = $row['owner_id'];
                                                
                                                $sql1 = "SELECT * FROM login WHERE login_id='$login_id'";
                                                $result1 = $conn->query($sql1);
                                                $row1 = $result1->fetch_assoc();

                                                $city_id = $row1['city_id'];

                                                $sql2 = "SELECT * FROM city WHERE city_id='$city_id'";
                                                $result2 = $conn->query($sql2);
                                                $row2 = $result2->fetch_assoc();

                                                $sql3 = "SELECT * FROM service WHERE service_id='$service_id'";
                                                $result3 = $conn->query($sql3);
                                                $row3 = $result3->fetch_assoc();

                                                $sql4 = "SELECT * FROM login WHERE login_id='$owner_id'";
                                                $result4 = $conn->query($sql4);
                                                $row4 = $result4->fetch_assoc();

                                                ?>
                                                    <tr>
                                                        <td><?php echo $count;?></td>
                                                        <td><a href="view-shop.php?login_id=<?php echo $row1['login_id'];?>"><?php echo $row1['login_name'];?></a></td>
                                                        <?php
                                                            if($c_id ==""){
                                                                ?>
                                                                    <td><?php echo $row2['city_name'];?></td>
                                                                <?php
                                                            }
                                                        ?>
                                                        <?php
                                                            if($ser_id ==""){
                                                                ?>
                                                                    <td><?php echo $row3['service_name'];?></td>
                                                                <?php
                                                            }
                                                        ?>
                                                        <td><?php echo $row4['login_name'];?></td>
                                                        <td><img src="<?php echo $row['shop_image'];?>" alt="" style="height: 50px;width: 50px"></td>
                                                        <td><?php echo $row1['login_phone_number'];?></td>
                                                        <td>
                                                            <ul class="table-controls">
                                                                <li>
                                                                    <a href="edit-shop.php?id=<?php echo $row['shop_id'];?>">
                                                                        <svg style="width: 28px;height: 28px" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 p-1 br-6 mb-1">
                                                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                                        </svg>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a data-toggle="modal" data-target="#cityDeleteModal<?php echo $row['login_id'] ?>">
                                                                        <svg style="width: 28px;height: 28px" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash p-1 br-6 mb-1">
                                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                        </svg>
                                                                    </a>
                                                                    <form method="post">
                                                                        <div class="modal fade" id="cityDeleteModal<?php echo $row['login_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="cityDeleteLabel<?php echo $row['login_id'] ?>" aria-hidden="true">
                                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title" id="cityDeleteLabel<?php echo $row['login_id'] ?>">Delete Shop</h5>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <p class="text-center">Are you sure to delete <?php echo $row1['login_name'] ?>!</p>
                                                                                        <input type="hidden" name="login_id" value="<?php echo $row['login_id'] ?>">
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                                                                        <button type="submit" name="delete" class="btn btn-primary">Delete</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </li>
                                                                <li>
                                                                    <a data-toggle="modal" data-target="#changepassword<?php echo $row['login_id'] ?>">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-unlock">
                                                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                                                            <path d="M7 11V7a5 5 0 0 1 9.9-1"></path>
                                                                        </svg>
                                                                    </a>
                                                                    <div class="modal fade" id="changepassword<?php echo $row['login_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="changepasswordLabel<?php echo $row['login_id'] ?>" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="changepasswordLabel<?php echo $row['login_id'] ?>">Change Password</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="row">
                                                                                        <div class="col-sm-6">
                                                                                            <label for="name" class="col-form-label">Password</label>
                                                                                            <input type="password" name="password" id="password<?php echo $row['login_id'];?>" class="form-control" placeholder="Password" autocomplete="off">
                                                                                        </div>
                                                                                        <div class="col-sm-6">
                                                                                            <label for="name" class="col-form-label">Re Password</label>
                                                                                            <input type="password" name="re_password" id="re_password<?php echo $row['login_id'];?>" class="form-control" placeholder="Re Password" autocomplete="off">
                                                                                            <p id="PasswordError<?php echo $row['login_id'];?>" class="ErrorMes"></p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col-sm-12">
                                                                                            <p id="ErroMsg<?php echo $row['login_id'];?>" class="ErrorMes text-center"></p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                                                                    <button name="changepassword" class="btn btn-primary" onclick="checkadminpassword(<?php echo $row['login_id'] ?>)">Save</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                <?php
                                                $count++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
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
    </script>
</body>
</html>