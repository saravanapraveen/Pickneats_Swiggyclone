<?php
    ini_set('display_errors','off');
    include('include/connection.php');
    include('../api/password.php');

    $pageName = 'Admin';
    $adminActivation = 'active';
    $adminToggle = 'true';

    if(isset($_POST['add'])){

        $admin_name = $_POST['admin_name'];
        $user_name = $_POST['user_name'];
        $admin_phone = $_POST['admin_phone'];
        $city = $_POST['city'];
        $password = $_POST['password'];
        $address = $_POST['address'];

        $responce = json_decode(generatePass($conn,$password));

        if($responce->status){
            $decrypted = $responce->password;
            $cipher = $responce->cipher;

            $sql1 = "INSERT INTO login (login_name,username,password,cipher,login_phone_number,address,control,city_id) VALUES ('$admin_name','$user_name','$decrypted','$cipher','$admin_phone','$address','1','$city')";
            if($conn->query($sql1) === TRUE){
                header('Location: admin.php?msg=admin added!');
            }else{
                header('Location: admin.php?err=Internal server error!');
            }
        }
    }

    if(isset($_POST['edit'])){
        $admin_name = $_POST['admin_name'];
        $user_name = $_POST['user_name'];
        $admin_phone = $_POST['admin_phone'];
        $city = $_POST['city'];
        $password = $_POST['password'];
        $address = $_POST['address'];
        $login_id = $_POST['login_id'];

        $sql = "SELECT * FROM login WHERE city_id='$city' AND login_id!='$login_id'";
        $result = $conn->query($sql);
        if($result->num_rows < 1){
            $sql1 = "UPDATE login SET login_name='$admin_name',username='$user_name',login_phone_number='$admin_phone',address='$address' WHERE login_id='$login_id'";
            if($conn->query($sql1)==TRUE){
                header('Location: admin.php?msg=updated!');
            }else{
                header('Location: admin.php?msg=updation failed!');
            }
        }else{
            header('Location: admin.php?msg=Admin already added for this city');
        }
    }

    if(isset($_POST['delete'])){
        $login_id = $_POST['login_id'];

        $sql = "DELETE from login WHERE login_id='$login_id'";
        if($conn->query($sql)==TRUE){
            header('Location: admin.php?msg=admin deleted!');
        }else{
            header('Location: admin.php?msg=admin deletion failed!');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Admin | Pickneats</title>
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
                                        <h4>All Admin</h4>
                                    </div>
                                    <div class="col-xl-2 col-md-2 col-sm-2 col-2">
                                        <a class="btn btn-primary float-right mt-2" data-toggle="modal" data-target="#cityModal">Add New</a>
                                        <form method="post">
                                            <div class="modal fade" id="cityModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Add Admin</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <label for="name" class="col-form-label">Admin Name</label>
                                                                    <input type="text" name="admin_name" id="name" class="form-control" placeholder="Admin Name" autocomplete="off">
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label for="name" class="col-form-label">User Name</label>
                                                                    <input type="text" name="user_name" id="user_name" class="form-control" placeholder="User Name" autocomplete="off" onkeyup="checkUsername(this.value)">
                                                                    <p id="ErrorMes" class="ErrorMes"></p>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label for="name" class="col-form-label">Phone Number</label>
                                                                    <input type="text" name="admin_phone" id="phone" class="form-control" placeholder="Phone Number" autocomplete="off" onkeyup="checkPhone(this.value,'')">
                                                                    <p id="PhoneErrorMes" class="ErrorMes"></p>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label for="name" class="col-form-label">City</label>
                                                                    <select name="city" id="city" class="form-control">
                                                                        <option value="" selected value disabled>Select City</option>
                                                                        <?php
                                                                            $sql = "SELECT * FROM city ORDER BY city_name ASC";
                                                                            $result = $conn->query($sql);
                                                                            while($row = $result->fetch_assoc()){
                                                                                $city_id = $row['city_id'];

                                                                                $sql1 = "SELECT * FROM login WHERE city_id='$city_id' AND control='1'";
                                                                                $result1 = $conn->query($sql1);
                                                                                if($result1->num_rows == 0){
                                                                        ?>
                                                                                    <option value="<?php echo $row['city_id'];?>"><?php echo $row['city_name'];?></option>
                                                                        <?php
                                                                                }
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label for="name" class="col-form-label">Password</label>
                                                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="off">
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label for="name" class="col-form-label">Re Password</label>
                                                                    <input type="password" name="re_password" id="re_password" class="form-control" placeholder="Phone Number" autocomplete="off">
                                                                    <p id="PasswordError" class="ErrorMes"></p>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <label for="name" class="col-form-label">Address</label>
                                                                    <textarea name="address" id="address" class="form-control" placeholder="Address"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                                            <button type="submit" name="add" class="btn btn-primary" onclick="return adminValidation()">Add</button>
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
                                            <th>Name</th>
                                            <th>User Name</th>
                                            <th>City Name</th>
                                            <th>Phone Number</th>
                                            <th>Address</th>
                                            <th class="no-content">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sql = "SELECT * FROM login WHERE control='1' ORDER BY login_name ASC";
                                            $result = $conn->query($sql);
                                            $count = 1;
                                            while($row = $result->fetch_assoc()){

                                                $city_id = $row['city_id'];

                                                $sql1 = "SELECT * FROM city WHERE city_id='$city_id'";
                                                $result1 = $conn->query($sql1);
                                                $row1 = $result1->fetch_assoc();
                                        ?>
                                                <tr>
                                                    <td><?php echo $count++ ?></td>
                                                    <td><?php echo $row['login_name'] ?></td>
                                                    <td><?php echo $row['username'] ?></td>
                                                    <td><?php echo $row1['city_name'] ?></td>
                                                    <td><?php echo $row['login_phone_number'] ?></td>
                                                    <td><?php echo $row['address'] ?></td>
                                                    <td>
                                                        <ul class="table-controls">
                                                            <li>
                                                                <a data-toggle="modal" data-target="#cityModal<?php echo $row['login_id'] ?>">
                                                                    <svg style="width: 28px;height: 28px" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 p-1 br-6 mb-1">
                                                                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                                    </svg>
                                                                </a>
                                                                <form method="post">
                                                                    <div class="modal fade" id="cityModal<?php echo $row['login_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="cityLabel<?php echo $row['login_id'] ?>" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="cityLabel<?php echo $row['login_id'] ?>">Edit Admin</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                <div class="row">
                                                                                    <div class="col-sm-6">
                                                                                        <label for="name" class="col-form-label">Admin Name</label>
                                                                                        <input type="text" name="admin_name" id="name<?php echo $row['login_id'];?>" class="form-control" placeholder="Admin Name" autocomplete="off" value="<?php echo $row['admin_name'];?>">
                                                                                    </div>
                                                                                    <div class="col-sm-6">
                                                                                        <label for="name" class="col-form-label">User Name</label>
                                                                                        <input type="text" name="user_name" id="user_name<?php echo $row['login_id'];?>" class="form-control" placeholder="User Name" autocomplete="off" onkeyup="checkUsername(this.value, <?php echo $row['login_id'];?>)" value="<?php echo $row['username'];?>"> 
                                                                                        <p id="ErrorMes<?php echo $row['login_id'];?>" class="ErrorMes"></p>
                                                                                    </div>
                                                                                    <div class="col-sm-6">
                                                                                        <label for="name" class="col-form-label">Phone Number</label>
                                                                                        <input type="text" name="admin_phone" id="phone<?php echo $row['login_id'];?>" class="form-control" placeholder="Phone Number" autocomplete="off" onkeyup="checkPhone(this.value, <?php echo $row['login_id'];?>)" value="<?php echo $row['login_phone_number'];?>">
                                                                                        <p id="PhoneErrorMes<?php echo $row['login_id'];?>" class="ErrorMes"></p>
                                                                                    </div>
                                                                                    <div class="col-sm-6">
                                                                                        <label for="name" class="col-form-label">City</label>
                                                                                        <select name="city" id="city<?php echo $row['login_id'];?>" class="form-control">
                                                                                            <option value="" selected value disabled>Select City</option>
                                                                                            <?php
                                                                                                $sql2 = "SELECT * FROM city ORDER BY city_name ASC";
                                                                                                $result2 = $conn->query($sql2);
                                                                                                while($row2 = $result2->fetch_assoc())
                                                                                                {
                                                                                                    $selected_city = "";
                                                                                                    if($city_id == $row2['city_id']){
                                                                                                        $selected_city = "selected";
                                                                                                    }
                                                                                                    ?>
                                                                                                        <option value="<?php echo $row2['city_id'];?>" <?php echo $selected_city;?>><?php echo $row2['city_name'];?></option>
                                                                                                    <?php
                                                                                                }
                                                                                            ?>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-sm-12">
                                                                                        <label for="name" class="col-form-label">Address</label>
                                                                                        <textarea name="address" id="address<?php echo $row['login_id'];?>" class="form-control" placeholder="Address"><?php echo $row['address'];?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <input type="hidden" name="login_id" value="<?php echo $row['login_id'] ?>">
                                                                                <div class="modal-footer">
                                                                                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                                                                    <button type="submit" name="edit" class="btn btn-primary" onclick="return adminUpdateValidation(<?php echo $row['login_id'];?>)">Save</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
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
                                                                                    <h5 class="modal-title" id="cityDeleteLabel<?php echo $row['login_id'] ?>">Delete City</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <p class="text-center">Are you sure to delete <?php echo $row['username'] ?>!</p>
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