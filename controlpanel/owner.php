<?php
    include('include/connection.php');
    include('../api/password.php');

    $pageName = 'Owner';
    $ownerActivation = 'active';
    $ownerToggle = 'true';

    if(isset($_POST['add'])){
        $name = $_POST['name'];
        $username = $_POST['username'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];

        $responce = json_decode(generatePass($conn,$password));

        if($responce->status){
            $decrypted = $responce->password;
            $cipher = $responce->cipher;

            $sql = "INSERT INTO login (login_name,username,password,cipher,login_phone_number,control) VALUES ('$name','$username','$decrypted','$cipher','$phone','4')";
            if($conn->query($sql) === TRUE){
                header("Location: owner.php?msg=Owner added!");
            }
        }
    }
    if(isset($_POST['edit'])){
        $login_id = $_POST['login_id'];
        $name = $_POST['name'];
        $username = $_POST['username'];
        $phone = $_POST['phone'];

        $sql = "UPDATE login SET login_name='$name',username='$username',login_phone_number='$phone' WHERE login_id='$login_id'";
        if($conn->query($sql) === TRUE){
            header("Location: owner.php?msg=Owner updated!");
        }
    }
    if(isset($_POST['delete'])){
        $login_id = $_POST['login_id'];

        $sql = "DELETE FROM login WHERE login_id='$login_id'";
        if($conn->query($sql) === TRUE){
            header("Location: owner.php?msg=Owner deleted!");
        }
    }
    if(isset($_POST['changePassword'])){
        $login_id = $_POST['login_id'];
        $password = $_POST['password'];

        $responce = json_decode(generatePass($conn,$password));

        if($responce->status){
            $decrypted = $responce->password;
            $cipher = $responce->cipher;

            $sql = "UPDATE login SET password='$decrypted',cipher='$cipher' WHERE login_id='$login_id'";
            if($conn->query($sql) === TRUE){
                header("Location: owner.php?msg=Owner password changed!");
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
    <title>Owner | Pickneats</title>
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
                                        <h4>Owner</h4>
                                    </div>
                                    <div class="col-xl-2 col-md-2 col-sm-2 col-2">
                                        <a class="btn btn-primary float-right mt-2" data-toggle="modal" data-target="#cityModal">Add New</a>
                                        <form method="post">
                                            <div class="modal fade" id="cityModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Add Owner</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row mb-3">
                                                                <div class="col-sm-12">
                                                                    <label for="name" class="col-form-label">Owner Name</label>
                                                                    <input type="text" name="name" id="name" class="form-control" placeholder="Owner Name" autocomplete="off">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-sm-6">
                                                                    <label for="username" class="col-form-label">User Name</label>
                                                                    <input type="text" name="username" id="username" class="form-control" placeholder="User Name" autocomplete="off" onkeyup="checkUsername(this.value)">
                                                                    <label id="ErrorMes" style="padding: 10px 0px 0px 10px;"></label>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label for="phone" class="col-form-label">Phone Number</label>
                                                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone Number" autocomplete="off" onkeyup="checkPhone(this.value)">
                                                                    <label id="PhoneErrorMes" style="padding: 10px 0px 0px 10px;"></label>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-sm-6">
                                                                    <label for="password" class="col-form-label">Password</label>
                                                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="off">
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label for="retypepassword" class="col-form-label">Retype Password</label>
                                                                    <input type="password" name="retypepassword" id="retypepassword" class="form-control" placeholder="Retype Password" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                                            <input type="submit" name="add" class="btn btn-primary" onclick="return addOwner()" value="Add">
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
                                            <th>Username</th>
                                            <th>Phone Number</th>
                                            <th class="no-content">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $count = 1;
                                            $sql = "SELECT * FROM login WHERE control='4' ORDER BY login_name ASC";
                                            $result = $conn->query($sql);
                                            while($row = $result->fetch_assoc()){
                                        ?>
                                                <tr>
                                                    <td><?php echo $count++ ?></td>
                                                    <td><?php echo $row['login_name'] ?></td>
                                                    <td><?php echo $row['username'] ?></td>
                                                    <td><?php echo $row['login_phone_number'] ?></td>
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
                                                                                    <h5 class="modal-title" id="cityLabel<?php echo $row['login_id'] ?>">Edit City</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="row">
                                                                                        <div class="col-sm-12">
                                                                                            <label for="name<?php echo $row['login_id'] ?>" class="col-form-label">Owner Name</label>
                                                                                            <input type="text" name="name" id="name<?php echo $row['login_id'] ?>" class="form-control" placeholder="Owner Name" autocomplete="off" value="<?php echo $row['login_name'] ?>">
                                                                                            <input type="hidden" name="login_id" value="<?php echo $row['login_id'] ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row mb-3">
                                                                                        <div class="col-sm-6">
                                                                                            <label for="username<?php echo $row['login_id'] ?>" class="col-form-label">Username</label>
                                                                                            <input type="text" name="username" id="username<?php echo $row['login_id'] ?>" class="form-control" placeholder="Username" autocomplete="off" value="<?php echo $row['username'] ?>" onkeyup="checkUsername(this.value,<?php echo $row['login_id'] ?>)">
                                                                                            <label id="ErrorMes<?php echo $row['login_id'] ?>" style="padding: 10px 0px 0px 10px;"></label>
                                                                                        </div>
                                                                                        <div class="col-sm-6">
                                                                                            <label for="phone<?php echo $row['login_id'] ?>" class="col-form-label">Phone Number</label>
                                                                                            <input type="text" name="phone" id="phone<?php echo $row['login_id'] ?>" class="form-control" placeholder="Phone Number" autocomplete="off" value="<?php echo $row['login_phone_number'] ?>" onkeyup="checkPhone(this.value,<?php echo $row['login_id'] ?>)">
                                                                                            <label id="PhoneErrorMes<?php echo $row['login_id'] ?>" style="padding: 10px 0px 0px 10px;"></label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                                                                    <button type="submit" name="edit" class="btn btn-primary" onclick="return addOwner(<?php echo $row['login_id'] ?>)">Save</button>
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
                                                                                    <h5 class="modal-title" id="cityDeleteLabel<?php echo $row['login_id'] ?>">Delete Owner</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <p class="text-center">Are you sure to delete <?php echo $row['login_name'] ?>!</p>
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
                                                                <a data-toggle="modal" data-target="#changePasswordModal<?php echo $row['login_id'] ?>">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-unlock">
                                                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                                                        <path d="M7 11V7a5 5 0 0 1 9.9-1"></path>
                                                                    </svg>
                                                                </a>
                                                                <form method="post">
                                                                    <div class="modal fade" id="changePasswordModal<?php echo $row['login_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel<?php echo $row['login_id'] ?>" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="changePasswordModalLabel<?php echo $row['login_id'] ?>">Change Password</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="row">
                                                                                        <div class="col-sm-6">
                                                                                            <label for="password<?php echo $row['login_id'] ?>">New Password</label>
                                                                                            <input type="password" name="password" id="password<?php echo $row['login_id'] ?>" class="form-control" placeholder="New Password">
                                                                                        </div>
                                                                                        <div class="col-sm-6">
                                                                                            <label for="retypePassword<?php echo $row['login_id'] ?>">Retype Password</label>
                                                                                            <input type="password" name="retypePassword" id="retypePassword<?php echo $row['login_id'] ?>" class="form-control" placeholder="Retype Password">
                                                                                        </div>
                                                                                    </div>
                                                                                    <input type="hidden" name="login_id" value="<?php echo $row['login_id'] ?>">
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                                                                    <button type="submit" name="changePassword" class="btn btn-primary" onclick="return changePasswordLogin(<?php echo $row['login_id'] ?>)">Change</button>
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