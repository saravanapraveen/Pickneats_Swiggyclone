<?php
    include('include/connection.php');

    $pageName = 'Product';
    $shopActivation = 'active';
    $shopToggle = 'true';

    $login_id = $_REQUEST['login_id'];
    $cat_id = $_REQUEST['cat_id'];

    $sql = "SELECT * FROM login WHERE login_id='$login_id'";
    $result = $conn->query($sql);
    $shop = $result->fetch_assoc();

    if(isset($_POST['delete'])){
        $product_id = $_POST['product_id'];

        $sql4 = "DELETE FROM product_addon WHERE product_id='$product_id'";
        $conn->query($sql4);

        $sql4 = "DELETE FROM product_timing WHERE product_id='$product_id'";
        $conn->query($sql4);

        $sql4 = "DELETE FROM product_variation WHERE product_id='$product_id'";
    
        if($conn->query($sql4)==TRUE){
            $sql = "DELETE FROM product WHERE product_id='$product_id'";
            if($conn->query($sql)==TRUE){
                header("Location: product.php?login_id=$login_id&msg=Product Deleted!");
            }else{
                header("Location: product.php?login_id=$login_id&msg=Product Deleted!");
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
    <link rel="stylesheet" type="text/css" href="assets/css/forms/switches.css">

    <style>
        ul{
            list-style-type: none;
        }
    </style>
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
                                        <h4>All productss of <?php echo $shop['login_name'] ?></h4>
                                    </div>
                                    <div class="col-xl-2 col-md-2 col-sm-2 col-2">
                                        <a href="add-product.php?login_id=<?php echo $login_id ?>" class="btn btn-primary float-right mt-2">Add New</a>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <table id="zero-config" class="table dt-table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>Category Name</th>
                                            <th>Image</th>
                                            <th>percentage</th>
                                            <th>Variation</th>
                                            <th>Status</th>
                                            <th class="no-content">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sql = "SELECT * FROM product WHERE login_id='$login_id' ORDER BY product_name ASC";
                                            $result = $conn->query($sql);
                                            $count = 1;
                                            while($row = $result->fetch_assoc()){
                                                $product_id = $row['product_id'];
                                                $category_id = $row['category_id'];
                                                $status = '';

                                                if($row['product_status'] == 1){
                                                    $status = 'checked';
                                                }

                                                $sql1 = "SELECT * FROM category WHERE category_id='$category_id'";
                                                $result1 = $conn->query($sql1);
                                                $row1 = $result1->fetch_assoc();
                                        ?>
                                                <tr>
                                                    <td><?php echo $count++ ?></td>
                                                    <td><a href="product.php?cat_id=<?php echo $row['product_id'].'&login_id='.$login_id ?>"><?php echo $row['product_name'] ?></a></td>
                                                    <td><a href="category.php?cat_id=<?php echo $row1['category_id'].'&login_id='.$login_id ?>"><?php echo $row1['category_name'] ?></a></td>
                                                    <td><img src="<?php echo $row['product_image'] ?>" style="width: 150px"></td>
                                                    <td><?php echo $row['percentage'];?></td>
                                                    <td>
                                                        <?php
                                                            $sql2 = "SELECT * FROM product_variation WHERE product_id='$product_id'";
                                                            $result2 = $conn->query($sql2);
                                                            if($result2->num_rows > 0){
                                                                ?>
                                                                    
                                                                <?php
                                                                while($row2 = $result2->fetch_assoc()){
                                                                    ?>
                                                                        <li><?php echo $row2['product_variation_name'];?></li>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <?php
                                                            }else{  
                                                                echo "N/A";
                                                            }
                                                        ?>
                                                    </td>
                                                    <td class="no-content">
                                                        <label class="switch s-icons s-outline s-outline-success mr-2" style="margin-bottom: 0px !important">
                                                            <input type="checkbox" <?php echo $status ?> id="status<?php echo $product_id ?>" onclick="return productStatus(<?php echo $product_id ?>)">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <a href="edit-product.php?product_id=<?php echo $row['product_id'];?>&login_id=<?php echo $login_id;?>">
                                                            <svg style="width: 28px;height: 28px" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 p-1 br-6 mb-1">
                                                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                            </svg>
                                                        </a>
                                                        <!-- <a href="delete-product.php?product_id=<?php echo $row['product_id'];?>&login_id=<?php echo $login_id;?>">
                                                            <svg style="width: 28px;height: 28px" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash p-1 br-6 mb-1">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            </svg>
                                                        </a> -->
                                                        <a data-toggle="modal" data-target="#cityDeleteModal<?php echo $row['product_id'] ?>">
                                                            <svg style="width: 28px;height: 28px" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash p-1 br-6 mb-1">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            </svg>
                                                        </a>
                                                        <form method="post">
                                                            <div class="modal fade" id="cityDeleteModal<?php echo $row['product_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="cityDeleteLabel<?php echo $row['product_id'] ?>" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="cityDeleteLabel<?php echo $row['product_id'] ?>">Delete City</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p class="text-center">Are you sure to delete <?php echo $row['product_name'] ?>!</p>
                                                                            <input type="hidden" name="product_id" value="<?php echo $row['product_id'] ?>">
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                                                            <button type="submit" name="delete" class="btn btn-primary">Delete</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
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