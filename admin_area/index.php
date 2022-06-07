<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>پنل ادمین</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="../style/new.css">
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <script src="../node_modules/jquery/dist/jquery.js"></script>
    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <script src="js/js.js"></script>
    <?php require_once "../include/include.php"?>

</head>
<body>
<div class="container-fluid my-3">
    <div class="col-lg-12 wrapper">
        <header id="top" >

            <img src="../images/logo.png" alt="">
            <h3>پنل ادمین مرکز خرید کفش <i class="fa fa-shoe-prints"></i></h3>
            <i class="fa fa-user fa-2x"></i>
        </header>
        <br>
        <nav id="menu">
            <ul>
                <li><a href="./">صفحه‌ اصلی</a></li>
                <li><a href="http://localhost/backend/shopping/index.php" target="_blank"><i class="fa fa-eye "></i> مشاهده سایت</a></li>
            </ul>
        </nav>
        <div class="row p-3">
            <div class="col-lg-2">
                <aside id="sidebar" class="list">
                    <header>
                        امکانات:
                    </header>
                    <ul>

                        <li><a href="./?add-product"><i class="fa fa-plus color"></i> وارد کردن محصول جدید</a></li>
                        <li><a href="./?show-product"><i class="fa fa-gift color"></i> ادیت و مشاهده ی محصولات</a></li>
                        <li><a href="./?add-comment"><i class="fa fa-plus color"></i> وارد کردن کامنت جدید</a></li>
                        <li><a href="./?show-comment"><i class="fa fa-envelope color"></i> پاسخ و مشاهده کامنت ها</a></li>
                        <li><a href="./?add-cat"><i class="fa fa-plus color"></i> وارد کردن گروه جدید</a></li>
                        <li><a href="./?show-cat"><i class="fa fa-object-group color"></i> ادیت و مشاهده گروه ها</a></li>
                        <li><a href="./?add-brand"> <i class="fa fa-plus color"></i> وارد کردن برند جدید</a></li>
                        <li><a href="./?show-brand"><i class="fa fa-bold color"></i> ادیت و مشاهده برند ها</a></li>
                        <li><a href="./?add-customer"><i class="fa fa-plus color"></i> وارد کردن کاربر جدید</a></li>
                        <li><a href="./?show-customer"><i class="fa fa-address-card color"></i> ادیت و مشاهده کاربران </a></li>
                        <li><a href="./?show-log"><i class="fa fa-users color"></i> مشاهده ورود و خروج کاربران </a></li>
                        <li><a href="./?show-cart"><i class="fa fa-shopping-cart color"></i> مشاهده و تایید سفارشات</a></li>
                      

                    </ul>
                </aside>
            </div>
            <div class="col-lg-10 ">
                <section id="content">

                    <?php
                    if(isset($_GET["activationKey"]) and !empty($_GET["activationKey"])){
                        $myquery=Customer::isActivationKey($_GET["activationKey"]);
                        if($myquery){
                            $myquery=Customer::activeUserAcc($_GET["activationKey"]);
                            echo '<script>
            swal({
                title: "باتشکر",
                text: "کاربر اکنون میتواند از سایت استفاده نماید",
                icon: "success",
                button: "بستن",
                timer: 3000
        })
    </script>';}
                    }
                    if (isset($_GET["add-product"]) or isset($_GET["add-comment"]) or isset($_GET["add-cat"])or isset($_GET["add-brand"])or isset($_GET["add-customer"])) {
                        require_once "include/add.php";
                    }
           elseif(isset($_GET["show-product"]) or isset($_GET["del_id"])){
                        require_once "include/show-product.php";
                    }
                    elseif (isset($_GET["update_product_id"]) or isset($_GET["update_comment_id"])or isset($_GET["update_cat_id"])or isset($_GET["update_brand_id"]) or isset($_GET["update_customer_id"])) require_once "include/update.php";
                     elseif (isset($_GET["show-comment"]) or isset($_GET["del_comm"])) {
                        require_once "include/show-comment.php";
                    }
                    elseif(isset($_GET["show-cat"]) or isset($_GET["del_cat"])){
                        require_once "include/show_cat.php";
                    }
                    elseif(isset($_GET["show-brand"]) or isset($_GET["del_brand"])){
                        require_once "include/show_brand.php";
                    }
                    elseif(isset($_GET["show-customer"]) or isset($_GET["del_customer"])){
                        require_once "include/show-customer.php";
                    }
                    elseif(isset($_GET["show-log"]) or isset($_GET["del_log"])){
                        require_once "include/show-log.php";
                    }
                    elseif(isset($_GET["show-cart"]) or isset($_GET["confirm_order"])){
                        require_once "include/show-cart.php";
                    }
                    elseif(isset($_GET["show-detail"])){
                        require_once "include/show-detail.php";
                    }
                    else{?>
                            <div class="col-lg-12 mt-5 pt-5">
                    <h3 class="text-muted text-center  mt-5 pt-5">به پنل ادمین خوش آمدید</h3>
                            </div>
                    <?php }?>
                </section>
            </div>
        </div>
        <footer id="bottom">
            © تمامی حقوق برای ftm_rahnama محفوظ می باشد.
        </footer>
    </div>
</body>
</html>