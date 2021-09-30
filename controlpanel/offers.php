<?php
    include('include/connection.php');

    $pageName = 'Offers';
    $offerActivation = 'active';
    $offerToggle = 'true';

    if(isset($_POST['add'])){

        $coupon_code = $_POST['coupon_code'];
        $minimum_order = $_POST['minimum_order'];
        $percentage = $_POST['percentage'];
        $maximum_discount = $_POST['maximum_discount'];
        $flat_amount = $_POST['flat_amount'];

        $shopLimit = 0;
        if(count($_POST['shops'])){
            $shopLimit = 1;
        }

        $sql = "INSERT INTO offer (offer_coupon_code,minimum_order_amount,percentage,maximum_discount_amount,flat_amount,offer_shop) VALUES ('$coupon_code','$minimum_order','$percentage','$maximum_discount','$flat_amount','$shopLimit')";
        if($conn->query($sql) === TRUE){
            $sql = "SELECT * FROM offer ORDER BY offer_id DESC LIMIT 1";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $offer_id = $row['offer_id'];

            if($shopLimit){
                for($i = 0;$i<count($_POST['shops']);$i++){
                    $login_id = $_POST['shops'][$i];

                    $sql = "SELECT * FROM shop WHERE login_id='$login_id'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();

                    $city_id = $row['city_id'];

                    $sql = "INSERT INTO offer_details (offer_id,city_id,login_id) VALUES ('$offer_id','$city_id','$login_id')";
                    $conn->query($sql);
                }
            } else{
                for($i = 0;$i<count($_POST['city']);$i++){
                    $city_id = $_POST['city'][$i];

                    $sql = "INSERT INTO offer_details (offer_id,city_id) VALUES ('$offer_id','$city_id')";
                    $conn->query($sql);
                }
            }
            header('Location: offers.php?msg=Combo added!');
        }
    }

    if(isset($_POST['edit'])){

        $coupon_code = $_POST['coupon_code'];
        $minimum_order = $_POST['minimum_order'];
        $percentage = $_POST['percentage'];
        $maximum_discount = $_POST['maximum'];
        $flat_amount = $_POST['flat_amount'];
        $offer_id = $_POST['offer_id'];
        $shops = $_POST['shops'];
        $for_shop = $_POST['for_shop'];
        $in_app = $_POST['in_app'];

        $sql = "UPDATE offer SET offer_coupon_code='$coupon_code',minimum_order_amount='$minimum_order',percentage='$percentage',maximum_discount_amount='$maximum_discount',flat_amount='$flat_amount',offer_city='$city',offer_shop='$shops',for_shop='$for_shop',offer_inapp_status='$in_app' WHERE offer_id='$offer_id'";
        if($conn->query($sql)==TRUE){
            header('Location: offers.php?msg=Combos updated!');
        }else{
            header("Location: offers.php?msg=Combos Failed!");
        }
    }

    if(isset($_POST['delete'])){
        $offer_id = $_POST['offer_id'];

        $sql = "DELETE from offer WHERE offer_id='$offer_id'";
        if($conn->query($sql)==TRUE){

            header('Location: offers.php?msg=Offers deleted!');
        }else{
            header('Location: offers.php?msg=Offers deletion failed!');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Offers | Pickneats</title>
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
                        <div class="statbox widget box box-shadow">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-10 col-md-10 col-sm-10 col-10">
                                        <h4>All Offers</h4>
                                    </div>
                                    <div class="col-xl-2 col-md-2 col-sm-2 col-2">
                                        <a class="btn btn-primary float-right mt-2" data-toggle="modal" data-target="#cityModal">Add New</a>
                                        <form method="post">
                                            <div class="modal fade" id="cityModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Add Offer's</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <label for="name" class="col-form-label">Coupon Code</label>
                                                                    <input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="Coupon Code" autocomplete="off" onkeyup="checkOfferName(this.value)"> 
                                                                    <p id="errorMsg" class="ErrorMes"></p>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label for="name" class="col-form-label">Minimum Ordering Amount</label>
                                                                    <input type="text" name="minimum_order" id="minimum_order" class="form-control" placeholder="Minimum Ordering Amount" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <label for="name" class="col-form-label">Percentage</label>
                                                                    <input type="text" name="percentage" id="percentage" class="form-control" placeholder="Percentage" autocomplete="off" onkeyup="setFlat()">
                                                                    
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label for="name" class="col-form-label">Maximum Discount Amount</label>
                                                                    <input type="text" name="maximum_discount" id="maximum_discount" class="form-control" placeholder="Maximum Discount Amount" autocomplete="off" onkeyup="setFlat()">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <label for="name" class="col-form-label">Falt Amount</label>
                                                                    <input type="text" name="flat_amount" id="flat_amount" class="form-control" placeholder="Falt Amount" autocomplete="off" onkeyup="setPercentage()">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <label for="name" class="col-form-label">City</label>
                                                                    <select name="city[]" id="city" class="form-control selectpicker" multiple data-live-search="true" title="Select City" data-selected-text-format="count" displayName="cities" onchange="getShops()" data-actions-box="true" required>
                                                                        <?php
                                                                            $sql1 = "SELECT * FROM city";
                                                                            $result1 = $conn->query($sql1);
                                                                            while($row1 = $result1->fetch_assoc())
                                                                            {
                                                                                ?>
                                                                                    <option value="<?php echo $row1['city_id'];?>"><?php echo $row1['city_name'];?></option>
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label for="name" class="col-form-label">Shops</label>
                                                                    <select name="shops[]" id="shops" class="form-control selectpicker" multiple data-live-search="true" title="Select Shop" data-selected-text-format="count" displayName="shops" uniqueId='shopload' data-actions-box="true">
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                                            <input type="submit" name="add" class="btn btn-primary" onclick="return comboOffer(event)" value="Add" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <table id="zero-config" class="table dt-table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Coupon Code</th>
                                            <th>Minimum Ordering Amount</th>
                                            <th>Percentage</th>
                                            <th>Maximum Discount Amount</th>
                                            <th>Falt Amount</th>
                                            <th class="no-content">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if($control_id == 0)
                                        {
                                            $sql = "SELECT * FROM offer";
                                        }else{
                                            $sql = "SELECT * FROM offer WHERE offer_city='$login_city_id'";
                                        }
                                            $result = $conn->query($sql);
                                            while($row = $result->fetch_assoc()){
                                                $offer_id = $row['offer_id'];
                                                $for_shop = $in_app = "";

                                                if($row['for_shop'] == "1")
                                                {
                                                    $for_shop = "checked";
                                                }
                                                if($row['offer_inapp_status'] == "1")
                                                {
                                                    $in_app = "checked";
                                                }
                                        ?>
                                                <tr>
                                                    <td><?php echo $row['offer_id'] ?></td>
                                                    <td><?php echo $row['offer_coupon_code'] ?></td>
                                                    <td><?php echo $row['minimum_order_amount'];?></td>
                                                    <td><?php echo $row['percentage'];?></td>
                                                    <td><?php echo $row['maximum_discount_amount'];?></td>
                                                    <td class="no-content">
                                                        <label class="switch s-icons s-outline s-outline-success mr-2" style="margin-bottom: 0px !important">
                                                            <input type="checkbox" <?php echo $status ?> id="status<?php echo $product_id ?>" onclick="return productStatus(<?php echo $product_id ?>)">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <ul class="table-controls">
                                                            <li>
                                                                <a data-toggle="modal" data-target="#cityModal<?php echo $row['offer_id'] ?>">
                                                                    <svg style="width: 28px;height: 28px" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 p-1 br-6 mb-1">
                                                                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                                    </svg>
                                                                </a>
                                                                <form method="post">
                                                                    <div class="modal fade" id="cityModal<?php echo $row['offer_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="cityLabel<?php echo $row['offer_id'] ?>" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="cityLabel<?php echo $row['offer_id'] ?>">Edit Offer</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="row">
                                                                                        <div class="col-sm-6">
                                                                                            <label for="name" class="col-form-label">Coupon Code</label>
                                                                                            <input type="text" name="coupon_code" id="combo_name<?php echo $row['offer_id'] ?>" class="form-control" placeholder="Coupon Code" autocomplete="off" value="<?php echo $row['offer_coupon_code'] ?>" onkeyup="editcheckOfferName(this.value, <?php echo $row['offer_id'] ?>)">
                                                                                            <p id="editErrorMsg<?php echo $row['offer_id'] ?>" class="ErrorMes"></p>
                                                                                            <input type="hidden" name="offer_id" value="<?php echo $row['offer_id'] ?>">
                                                                                        </div>
                                                                                        <div class="col-sm-6">
                                                                                            <label for="name" class="col-form-label">Minimum Ordering Amount</label>
                                                                                            <input type="text" name="minimum_order" id="minimum_order<?php echo $row['offer_id'] ?>" class="form-control" placeholder="Minimum Ordering Amount" value="<?php echo $row['minimum_order_amount'] ?>" autocomplete="off">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col-sm-6">
                                                                                            <label for="name" class="col-form-label">Percentage</label>
                                                                                            <input type="text" name="percentage" id="percentage<?php echo $row['offer_id'] ?>" class="form-control" placeholder="Percentage" autocomplete="off" value="<?php echo $row['percentage'] ?>">
                                                                                        </div>
                                                                                        <div class="col-sm-6">
                                                                                            <label for="name" class="col-form-label">Maximum Discount Amount</label>
                                                                                            <input type="text" name="maximum" id="maximum<?php echo $row['offer_id'] ?>" class="form-control" placeholder="Maximum Discount Amount" value="<?php echo $row['maximum_discount_amount'] ?>" autocomplete="off">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col-sm-12">
                                                                                            <label for="name" class="col-form-label">Falt Amount</label>
                                                                                            <input type="text" name="flat_amount" id="flat_amount<?php echo $row['offer_id'] ?>" class="form-control" placeholder="Coupon Code" autocomplete="off" value="<?php echo $row['flat_amount'] ?>">
                                                                                            <input type="hidden" name="offer_id" value="<?php echo $row['offer_id'] ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <?php
                                                                                            if($login_city_id == 0){
                                                                                                ?>
                                                                                                    <div class="col-sm-6">
                                                                                                        <label for="name" class="col-form-label">City</label>
                                                                                                        <select name="city" id="city<?php echo $row['offer_id'] ?>" class="form-control" onchange="editgetShops(this.value,<?php echo $row['offer_id'];?>)">
                                                                                                            <option value="" selected value disabled>Select City</option>
                                                                                                            <?php
                                                                                                                if($row['offer_city']=="All")
                                                                                                                {
                                                                                                                    $all = "selected";
                                                                                                                }else{ $all = ""; }
                                                                                                            ?>
                                                                                                            <option value="All" <?php echo $all;?>>All</option>
                                                                                                            <?php
                                                                                                                $sql1 = "SELECT * FROM city";
                                                                                                                $result1 = $conn->query($sql1);
                                                                                                                while($row1 = $result1->fetch_assoc())
                                                                                                                {
                                                                                                                    $selected_city = "";
                                                                                                                    if($row['offer_city'] == $row1['city_id'])
                                                                                                                    {
                                                                                                                        $selected_city = "selected";
                                                                                                                    }
                                                                                                                    ?>
                                                                                                                        <option value="<?php echo $row1['city_id'];?>" <?php echo $selected_city;?>><?php echo $row1['city_name'];?></option>
                                                                                                                    <?php
                                                                                                                }
                                                                                                            ?>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                <?php
                                                                                            }
                                                                                        ?>
                                                                                        <div class="col-sm-6">
                                                                                            <label for="name" class="col-form-label">Shops</label>
                                                                                            <select name="shops" id="shops<?php echo $row['offer_id'] ?>" class="form-control">
                                                                                                <option value="" selected value disabled>Select City</option>
                                                                                                <?php
                                                                                                    if($row['offer_city']!="All")
                                                                                                    {
                                                                                                        $city_id = $row['offer_city'];

                                                                                                        $sql2 = "SELECT * FROM login WHERE BINARY city_id='$city_id'";
                                                                                                        $result2 = $conn->query($sql2);
                                                                                                        while($row2 = $result2->fetch_assoc())
                                                                                                        {
                                                                                                            $selected_shop = "";

                                                                                                            if($row2['login_id'] == $row['offer_shop'])
                                                                                                            {
                                                                                                                $selected_shop = "selected";
                                                                                                            }
                                                                                                            ?>
                                                                                                                <option value="<?php echo $row2['login_id'];?>" <?php echo $selected_shop;?>><?php echo $row2['login_name'];?></option>
                                                                                                            <?php
                                                                                                        }
                                                                                                    }
                                                                                                ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row mt-2">
                                                                                        <div class="col-sm-3">
                                                                                            <input type="checkbox" name="for_shop" id="discount_status<?php echo $row['offer_id'] ?>" value="1" <?php echo $for_shop;?>><label for="discount_status<?php echo $row['offer_id'] ?>" style="margin-left: 10px;">For Shop</label>
                                                                                        </div>
                                                                                        <div class="col-sm-3">
                                                                                            <input type="checkbox" name="in_app" id="in_app<?php echo $row['offer_id'] ?>" value="1" <?php echo $in_app;?>><label for="in_app<?php echo $row['offer_id'] ?>" style="margin-left: 10px;">In App</label>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                                                                    <button type="submit" name="edit" class="btn btn-primary" onclick="return editcomboCheck(<?php echo $row['offer_id'] ?>)">Save</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <a data-toggle="modal" data-target="#cityDeleteModal<?php echo $row['offer_id'] ?>">
                                                                    <svg style="width: 28px;height: 28px" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash p-1 br-6 mb-1">
                                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                    </svg>
                                                                </a>
                                                                <form method="post">
                                                                    <div class="modal fade" id="cityDeleteModal<?php echo $row['offer_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="cityDeleteLabel<?php echo $row['offer_id'] ?>" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="cityDeleteLabel<?php echo $row['offer_id'] ?>">Delete City</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <p class="text-center">Are you sure to delete <?php echo $row['city_name'] ?>!</p>
                                                                                    <input type="hidden" name="offer_id" value="<?php echo $row['offer_id'] ?>">
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
                                                        </ul>
                                                    </td>   
                                                </tr>
                                        <?php
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
    <script src="plugins/bootstrap-select/bootstrap-select.min.js"></script>
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