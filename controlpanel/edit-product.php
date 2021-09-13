<?php
    ini_set('display_errors','off');
    include('include/connection.php');
    include('../api/password.php');

    $pageName = 'Product';
    $shopActivation = 'active';
    $shopToggle = 'true';

    $login_id = $_REQUEST['login_id'];
    $product_id = $_REQUEST['product_id'];

    $sql = "SELECT * FROM login WHERE login_id='$login_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $sql1 = "SELECT * FROM product WHERE product_id='$product_id'";
    $result1 = $conn->query($sql1);
    if($result1->num_rows > 0){
        $product = $result1->fetch_assoc();    
       
    }else{
        header("Location: product.php?msg=Something Wrong");
    }

    if(isset($_POST['save'])){
        $name = $_POST['name'];
        $category_name = $_POST['category_name'];
        $image = $_POST['image'];
        $type = $_POST['type'];
        $percentage = $_POST['percentage'];

        $sql = "UPDATE product SET login_id='$login_id',product_name='$name',category_id='$category_name',product_image='$image',product_type='$type',percentage='$percentage' WHERE product_id='$product_id'";
        if($conn->query($sql)==TRUE){

            $sql4 = "DELETE FROM product_addon WHERE product_id='$product_id'";
            $conn->query($sql4);

            $sql4 = "DELETE FROM product_timing WHERE product_id='$product_id'";
            $conn->query($sql4);

            $sql4 = "DELETE FROM product_variation WHERE product_id='$product_id'";
            $conn->query($sql4);
            
            for($i=0;$i < count($_POST['timing']);$i++){
                $my_timing = $_POST['timing'][$i];

                $sql = "INSERT INTO product_timing (timing_id,login_id,product_id) VALUES ('$my_timing','$login_id','$product_id')";
                $conn->query($sql);
            }
            for($i=0;$i < count($_POST['addon']);$i++){
                $my_addon = $_POST['addon'][$i];

                $sql = "INSERT INTO product_addon (addon_id,login_id,product_id) VALUES ('$my_addon','$login_id','$product_id')";
                $conn->query($sql);
            }
            for($i=0;$i < count($_POST['variation_name']);$i++){
                $variation_name = $_POST['variation_name'][$i];
                $demo_amt = $_POST['demo_amt'][$i];
                $purchasing_amt = $_POST['purchasing_amt'][$i];
                $unit_name = $_POST['unit_name'][$i];

                $selling_amt = ($percentage / 100 * $purchasing_amt) + $purchasing_amt;

                $sql = "INSERT INTO product_variation (product_variation_name,product_variation_unit_id,variation_demo_amount,variation_purchasing_amount,variation_selling_amt,product_id) VALUES ('$variation_name','$unit_name','$demo_amt','$purchasing_amt','$selling_amt','$product_id')";
                $conn->query($sql);
            }

            header("Location: add-product.php?msg=Updated");
        }else{
            header("Location: add-product.php?msg=Failed");
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
                                        <h4>Edit Product</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <form method="post" class="p-4">

                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <label for="owner">Product Name</label>
                                            <input type="text" name="name" id="name" placeholder="Product Name" class="form-control" value="<?php echo $product['product_name'];?>">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="category">Category Name</label>
                                            <select name="category_name" id="" class="form-control">
                                                <option value="" selected value disabled>Select Category</option>
                                                <?php
                                                    $sql = "SELECT * FROM category WHERE login_id='$login_id'";
                                                    $result = $conn->query($sql);
                                                    while($row = $result->fetch_assoc())
                                                    {
                                                        $selected_category = "";

                                                        if($row['category_id'] == $product['category_id']){
                                                            $selected_category = "selected";
                                                        }
                                                        ?>
                                                            <option value="<?php echo $row['category_id'];?>" <?php echo $selected_category;?>><?php echo $row['category_name'];?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                   
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <label for="image">Image</label>
                                            <input type="text" name="image" id="image" placeholder="Image" class="form-control" value="<?php echo $product['product_image'];?>" autocomplete="off" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="type">Type</label>
                                            <select name="type" id="" class="form-control">
                                                <?php
                                                    if($product['product_type']== "Veg"){
                                                        $veg = "selected";
                                                    }else{
                                                        $nonVeg = "selected";
                                                    }
                                                ?>
                                                <option value="" selected value disabled>Selecte Type</option>
                                                <option value="Veg" <?php echo $veg;?>>Veg</option>
                                                <option value="Non Veg" <?php echo $nonVeg;?>>Non-Veg</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <label for="timing">Timing</label>
                                            <select name="timing[]" id="timing" class="form-control selectpicker" data-live-search="true">
                                                <option value="">Select Timing</option>
                                                <?php
                                                    $sql = "SELECT * FROM timing WHERE login_id='$login_id'";
                                                    $result = $conn->query($sql);
                                                    while($row = $result->fetch_assoc())
                                                    {
                                                        $timing_id = $row['timing_id'];
                                                        $selected_timing = "";

                                                        $sql3 = "SELECT * FROM product_timing WHERE timing_id='$timing_id' AND product_id='$product_id'";
                                                        $result3 = $conn->query($sql3);
                                                        if($result3->num_rows > 0){
                                                            $selected_timing = "selected";
                                                        }
                                                        ?>
                                                            <option value="<?php echo $row['timing_id'];?>" <?php echo $selected_timing;?>><?php echo $row['timing_name'];?> - <?php echo $row['intime'].' - '. $row['outtime'];?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="addon">Add-On</label>
                                            <select name="addon[]" id="addon" class="form-control selectpicker" data-live-search="true">
                                                <option value="">Select Addon</option>
                                                <?php
                                                    $sql = "SELECT * FROM addon WHERE login_id='$login_id'";
                                                    $result = $conn->query($sql);
                                                    while($row = $result->fetch_assoc())
                                                    {
                                                        $addon_id = $row['addon_id'];
                                                        $selected_addon = "";

                                                        $sql3 = "SELECT * FROM product_addon WHERE addon_id='$addon_id' AND product_id='$product_id'";
                                                        $result3 = $conn->query($sql3);
                                                        if($result3->num_rows > 0){
                                                            $selected_addon = "selected";
                                                        }
                                                        ?>
                                                            <option value="<?php echo $row['addon_id'];?>" <?php echo $selected_addon;?>><?php echo $row['addon_name'];?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="addon">Percentage</label>
                                            <input type="text" name="percentage" class="form-control" value="<?php echo $product['percentage'];?>" placeholder="Percentage" autocomplete="off" required>
                                        </div>
                                    </div>

                                    <div class="form-group" id="duplicate">
                                        <?php
                                            $sql2 = "SELECT * FROM product_variation WHERE product_id='$product_id'";
                                            $result2 = $conn->query($sql2);
                                            $num_variation = $result2->num_rows;
                                            $count = 0;
                                            if($num_variation > 0){
                                                while($row2 = $result2->fetch_assoc()){
                                                    if($count == 0){
                                                        ?>
                                                            <div class="row mb-3">
                                                                <div class="col-sm-2">
                                                                    <label for="variation_name">Variation</label>
                                                                    <input type="text" name="variation_name[]" id="variation_name" class="form-control" placeholder="Variation Name" autocomplete="off" value="<?php echo $row2['product_variation_name'];?>" required>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <label for="variation_name">Unit</label>
                                                                    <select name="unit_name[]" id="unit_name" class="form-control" required>
                                                                        <option value="" selected value disabled>Select Unit</option>
                                                                        <?php
                                                                            $sql5 = "SELECT * FROM unit ORDER BY unit_name ASC";
                                                                            $result5 = $conn->query($sql5);
                                                                            while($row5 = $result5->fetch_assoc()){
                                                                                $selected_unit = "";
                                                                                if($row5['unit_id'] == $row2['product_variation_unit_id'])
                                                                                {
                                                                                    $selected_unit = "selected";
                                                                                }
                                                                                ?>
                                                                                    <option value="<?php echo $row5['unit_id'];?>" <?php echo $selected_unit;?>><?php echo $row5['unit_name'];?></option>
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <label for="demo_amt">Demo Amount</label>
                                                                    <input type="number" name="demo_amt[]" id="demo_amt" class="form-control" placeholder="Demo Amount" autocomplete="off" value="<?php echo $row2['variation_demo_amount'];?>" required>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <label for="purchasing_amt">Purchasing Amount</label>
                                                                    <input type="number" name="purchasing_amt[]" id="purchasing_amt" class="form-control" placeholder="Purchasing Amount" autocomplete="off" value="<?php echo $row2['variation_purchasing_amount'];?>" required>
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    <button type="button" style="margin-top: 35px;" name="add" id="add" class="btn btn-success">+</button>
                                                                </div>
                                                            </div>
                                                        <?php
                                                    }else{
                                                        ?>
                                                            <div class="row">
                                                                <div class="col-sm-2">
                                                                    <input type="text" name="variation_name[]" id="variation_name<?php echo $count;?>" class="form-control" placeholder="Variation Name" autocomplete="off" value="<?php echo $row2['product_variation_name'];?>" required>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <select name="unit_name[]" id="unit_name" class="form-control" required>
                                                                        <option value="" selected value disabled>Select Unit</option>
                                                                        <?php
                                                                            $sql5 = "SELECT * FROM unit ORDER BY unit_name ASC";
                                                                            $result5 = $conn->query($sql5);
                                                                            while($row5 = $result5->fetch_assoc()){
                                                                                $selected_unit = "";
                                                                                if($row5['unit_id'] == $row2['product_variation_unit_id']){
                                                                                    $selected_unit = "selected";
                                                                                }
                                                                                ?>
                                                                                    <option value="<?php echo $row5['unit_id'];?>" <?php echo $selected_unit;?>><?php echo $row5['unit_name'];?></option>
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <input type="number" name="demo_amt[]" id="demo_amt<?php echo $count;?>" class="form-control" placeholder="Demo Amount" autocomplete="off" value="<?php echo $row2['variation_demo_amount'];?>" required>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <input type="number" name="purchasing_amt[]" id="purchasing_amt<?php echo $count;?>" class="form-control" placeholder="Purchasing Amount" autocomplete="off" value="<?php echo $row2['variation_purchasing_amount'];?>" required>
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    <button type="button" name="remove" class="btn btn-danger btn_remove" id="<?php echo $count;?>">X</button>
                                                                </div>
                                                            </div>
                                                        <?php
    
                                                    }
                                                    $count++;
                                                }
                                            }else{
                                                ?>
                                                    <div class="row mb-3">
                                                        <div class="col-sm-2">
                                                            <label for="variation_name[]">Variation</label>
                                                            <input type="text" name="variation_name" id="variation_name" class="form-control" placeholder="Variation Name" autocomplete="off" value="<?php echo $row2['product_variation_name'];?>" required>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label for="variation_name">Unit</label>
                                                            <select name="unit_name[]" id="unit_name" class="form-control" required>
                                                                <option value="" selected value disabled>Select Unit</option>
                                                                <?php
                                                                    $sql5 = "SELECT * FROM unit ORDER BY unit_name ASC";
                                                                    $result5 = $conn->query($sql5);
                                                                    while($row5 = $result5->fetch_assoc()){
                                                                        $selected_unit = "";
                                                                        if($row5['unit_id'] == $row2['product_variation_unit_id']){
                                                                            $selected_unit = "selected";
                                                                        }
                                                                        ?>
                                                                            <option value="<?php echo $row5['unit_id'];?>"><?php echo $row5['unit_name'];?></option>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label for="demo_amt[]">Demo Amount</label>
                                                            <input type="number" name="demo_amt" id="demo_amt" class="form-control" placeholder="Demo Amount" autocomplete="off" value="<?php echo $row2['variation_demo_amount'];?>" required>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label for="purchasing_amt[]">Purchasing Amount</label>
                                                            <input type="number" name="purchasing_amt" id="purchasing_amt" class="form-control" placeholder="Purchasing Amount" autocomplete="off" value="<?php echo $row2['variation_purchasing_amount'];?>" required>
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <button type="button" style="margin-top: 35px;" name="add" id="add" class="btn btn-success">+</button>
                                                        </div>
                                                    </div>
                                                <?php
                                            }
                                            
                                        ?>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <label id="Error" style="color: red"></label>
                                        </div>
                                        <div class="col-sm-12">
                                            <input type="submit" name="save" value="Update" class="btn btn-primary float-right" onclick="return shopCheck()">
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
    <script src="plugins/bootstrap-select/bootstrap-select.min.js"></script>
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
        var i = <?php echo $count;?>;
        $('#add').click(function(){
            i++;
            $('#duplicate').append(
                '<div class="row m-t5" id="duplicate'+ i+'" style="margin-top: 10px;"><div class="col-sm-4"><input type="text" name="variation_name[]" id="variation_name'+ i+'" class="form-control" placeholder="Variation Name" autocomplete="off" required></div><div class="col-sm-4"><input type="number" name="demo_amt[]" id="demo_amt'+ i+'" class="form-control" placeholder="Demo Amount" autocomplete="off" required></div><div class="col-sm-3"><input type="number" name="purchasing_amt[]" id="purchasing_amt'+ i+'" class="form-control" placeholder="Purchasing Amount" autocomplete="off" required></div><div class="col-sm-1"><button type="button" name="remove" class="btn btn-danger btn_remove" id="'+i+'">X</button></div></div>'
            );
        });
        $(document).on('click', '.btn_remove', function(){  
            var button_id = $(this).attr("id");   
            $('#duplicate'+button_id+'').remove();  
        });
    </script>
</body>
</html>