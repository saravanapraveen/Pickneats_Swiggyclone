<?php
    include('include/connection.php');

    $pageName = 'Shop Details';
    $shopActivation = 'active';
    $shopToggle = 'true';

    $login_id = $_REQUEST['login_id'];

    $sql = "SELECT * FROM login WHERE login_id='$login_id'";
    $result = $conn->query($sql);
    $login = $result->fetch_assoc();

    $sql = "SELECT * FROM shop WHERE login_id='$login_id'";
    $result = $conn->query($sql);
    $shop = $result->fetch_assoc();

    $sql = "SELECT * FROM category WHERE login_id='$login_id'";
    $result = $conn->query($sql);
    $category = $result->num_rows;

    // $sql = "SELECT * FROM product WHERE login_id='$login_id'";
    // $result = $conn->query($sql);
    // $product = $result->num_rows;

    $sql = "SELECT * FROM addon WHERE login_id='$login_id'";
    $result = $conn->query($sql);
    $addon = $result->num_rows;

    $sql = "SELECT * FROM combos WHERE login_id='$login_id'";
    $result = $conn->query($sql);
    $combos = $result->num_rows;

    if(isset($_POST['add'])){

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Shop Details | Pickneats</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png"/>
    
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/manual.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/elements/avatar.css" rel="stylesheet" type="text/css" />
    <link href="plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />

    <link href="assets/css/users/user-profile.css" rel="stylesheet" type="text/css" />

    <link href="assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
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
                    <div class="col-sm-4">
                        <div class="user-profile layout-spacing">
                            <div class="widget-content widget-content-area">
                                <div class="d-flex justify-content-between">
                                    <h3 class="">Info</h3>
                                    <a href="edit-shop.php?id=<?php echo $shop['shop_id'] ?>" class="mt-2 edit-profile"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg></a>
                                </div>
                                <div class="text-center user-info">
                                    <img src="<?php echo $shop['shop_image'] ?>" alt="avatar" style="width: 100px;">
                                    <p class=""><?php echo $login['login_name'] ?></p>
                                </div>
                                <div class="user-info-list">

                                    <div class="">
                                        <ul class="contacts-block list-unstyled">
                                            <li class="contacts-block__item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-coffee"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg> <?php echo $login['address'];?>
                                            </li>
                                            <li class="contacts-block__item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><?php echo $shop['open_time'].'-'.$shop['close_time'];?>
                                            </li>
                                            <li class="contacts-block__item">
                                                <a target="_blank" href="https://www.google.com/maps/@10.3708863,78.8139796,21z">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin" style="margin-right: 0px">
                                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                        <circle cx="12" cy="10" r="3"></circle>
                                                    </svg>
                                                    10.3708863,78.8139796
                                                </a>
                                            </li>
                                            <li class="contacts-block__item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg><?php echo $login['login_phone_number'];?>
                                            </li>
                                        </ul>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="row layout-top-spacing">
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-6 layout-spacing">
                                <a href="category.php">
                                    <div class="widget widget-card-four">
                                        <div class="widget-content">
                                            <div class="w-header">
                                                <div class="w-info">
                                                    <h6 class="value">Category's</h6>
                                                </div>
                                            </div>

                                            <div class="w-content">
                                                <div class="w-info">
                                                    <p class="value"><?php echo $category;?> <span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-6 layout-spacing">
                                <a href="product.php?login_id=<?php echo $login_id;?>">
                                    <div class="widget widget-card-four">
                                        <div class="widget-content">
                                            <div class="w-header">
                                                <div class="w-info">
                                                    <h6 class="value">Product's</h6>
                                                </div>
                                            </div>

                                            <div class="w-content">
                                                <div class="w-info">
                                                    <p class="value"><?php echo $category;?><span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-6 layout-spacing">
                                <a href="addon.php?login_id=<?php echo $login_id;?>">
                                    <div class="widget widget-card-four">
                                        <div class="widget-content">
                                            <div class="w-header">
                                                <div class="w-info">
                                                    <h6 class="value">Addon's</h6>
                                                </div>
                                            </div>

                                            <div class="w-content">
                                                <div class="w-info">
                                                    <p class="value"><?php echo $addon;?><span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-6 layout-spacing">
                                <a href="combos.php?login_id=<?php echo $login_id;?>">
                                    <div class="widget widget-card-four">
                                        <div class="widget-content">
                                            <div class="w-header">
                                                <div class="w-info">
                                                    <h6 class="value">Combo's</h6>
                                                </div>
                                            </div>

                                            <div class="w-content">
                                                <div class="w-info">
                                                    <p class="value"><?php echo $combos;?><span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
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
</body>
</html>