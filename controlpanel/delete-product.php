<?php
    ini_set('display_errors','off');
    include('include/connection.php');
    include('../api/password.php');

    $product_id = $_REQUEST['product_id'];
    $login_id = $_REQUEST['login_id'];

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
?>