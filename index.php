<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>مرکز خرید کفش</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
    <script src="node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="style/new.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <?php require_once "include/include.php"?>
</head>
<body>
<div class="container my-3">
    <div class="col-lg-12 wrapper">
    <header id="top" class="p-5">
        <img src="images/logo.png" alt="">
         <h3>مرکز خرید کفش <i class="fa fa-shoe-prints"></i></h3>
        <div class="shopping-cart">
            <?php if (isset($_GET["del"]) and is_numeric($_GET["del"])){
                $delcart= Cart::delCartByProductId($_GET["del"], $_SERVER["REMOTE_ADDR"]);
            }
                if(isset($_GET["buy"])and is_numeric($_GET["buy"])) {
                $product_id = $_GET["buy"];
                $addcart = Cart::insertCart($product_id, $_SERVER["REMOTE_ADDR"]);
                if(!$addcart){ ?>
                    <script>
                        swal({
                            title:"خطایی رخ داده",text:'محصول در سبد شما موجود است',icon:"error",buttons:"بستن",timer: 3000
                        })

                    </script>
          <?php  }}
            $priceAll=0;
            ?>
            <div class="cart-icon">
                       <i class="fa fa-shopping-cart"></i>
                    <span class="num"><?php $carts=Cart::getCartByIp($_SERVER["REMOTE_ADDR"]);
                    $num=0;
                        if($carts) $num=count($carts);
                        echo $num;
                        ?></span>
            </div>
            <div class="cart">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <?php if(isset($_SESSION["userInfo"]["fullName"])){
                            $name=$_SESSION["userInfo"]["fullName"]." عزیز";
                            $spanText="پرداخت";
                            $url="./?action=pay";
                        }
                        else {
                            $name = "کاربر گرامی";
                            $spanText= "ورود و پرداخت";
                            $url="./?action=login";
                        }
                            ?>
                    <h6 class="text-muted"><?php echo $name?> سبد خرید شما </h6>
                    </div>
                    <?php if($carts){?>
                    <div class="card-body">
                <?php foreach ($carts as $cart) {
                        $product=Product::getProductById($cart->product_id);
                        ?>
                    <div class="row">
                        <div class="col-lg-6 image">
                            <img src="<?php echo $product->product_img; ?>" alt="">
                        </div>
                        <div class="col-lg-6">
                            <h6 class="text-muted"> <?php echo $product->product_title; ?></h6>
                            <p class="text-muted">
                            سایز: <?php echo $product->product_size; ?>
                            </p>
                            <span class="text-muted">
                                قیمت :
                             <p class="text-dark">
                                 <?php $priceAll += $product->product_price;
                                 echo  "  تومان  ".$product->product_price; ?>
                                  <a href="./?del=<?php echo $product->id;?>" class="trash"><i class="fa fa-trash"></i></a>
                             </p>
                        </span>
                        </div>
                    </div>

                <?php }?>
                    </div>
                    <?php }
                    $_SESSION["userInfo"]["cartPrice"]=$priceAll;?>
                <div class="card-footer">
                      <span class="text-muted d-inline-block">
                               مبلغ قابل پرداخت :
                             <p class="text-dark">
                                <?php echo " تومان ".$_SESSION["userInfo"]["cartPrice"]; ?>
                             </p>
                      </span><a href="<?php echo $url?>" class="btn btn-info float-left order"><?php echo $spanText?>
                    </a>

                </div>
                </div>
            </div>

        </div>

    </header>
    <br>
    <nav id="menu">
        <ul>
        <li><a href="./">صفحه‌ اصلی</a></li>
        <li><a href="./?action=signup">ثبت نام</a></li>
        <li><a href="./?">تماس با ما</a></li>
        <li><a href="./?">درباره ما</a></li>
        </ul>
    </nav>
    <div class="row p-3">
    <div class="col-lg-3 mt-3">
        <aside id="sidebar">
            <?php if(isset($_SESSION["userInfo"]["fullName"])):?>
                    <div class="card p-1 mb-3 bg-transparent" style="max-width: 540px;">
                        <div class="row no-gutters">
                                <div class="card-body">
                                    <h5 class="card-title text-center"><i class=" fa fa-tachometer-alt tachometer-custom"></i> پروفایل کاربری </h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-12">
                                            <dl>
                                                <div class="row  text-muted">
                                                    <div class="col-5">
                                                        <dt>نام  </dt>
                                                    </div>
                                                    <div class="col-7">
                                                        <dd><?php echo $_SESSION["userInfo"]["fullName"] ?></dd>
                                                    </div>
                                                    <div class="col-5">
                                                        <dt>مبلغ سبد</dt>
                                                    </div>
                                                    <div class="col-7">
                                                        <dd><?php echo $_SESSION["userInfo"]["cartPrice"]." تومان " ?></dd>
                                                    </div>
                                                </div>
                                            </dl>
                                        </div>
                                    </div>
                                    <hr>
                                    <h6 class="pr-3"><a href="http://localhost/backend/shopping/include/logout.php" class="text-danger "><i class="fa fa-power-off"></i> خروج</a></h6>
                                </div>
                            </div>
                    </div>
            <?php endif; ?>
            <section id="categories">
                <header>
                    گروه‌ها
                </header>
                <div>
                    <ul>
                        <li>
                            <?php
                            $rootCats=Category::getCategoriesByParentId(0);
                            foreach ($rootCats as $rootCat) {?>
                        <li>
                            <a href="./?cat=<?php echo $rootCat->id;?>">
                                <?php echo $rootCat->category_name;?>
                            </a>
                            <ul>
                                <?php $childCats=Category::getCategoriesByParentId($rootCat->id);
                                foreach ($childCats as $childCat) {?>
                                <li><a href="./?cat=<?php echo $childCat->id ;?>">
                                        <?php echo $childCat->category_name; ?></a>
                                </li>
                           <?php }?>
                            </ul>
                        </li>
                        <?php }?>
                    </ul>
                </div>
            </section>
            <section id="brand" class="list">
                <header>
                    برند محصولات
                </header>
                <div>
                    <ul>
                        <?php $brands=Brand::getAllBrands();
                        foreach ($brands as $brand) {?>
                        <li>
                            <a href="./?brand=<?php echo $brand->id;?>"><?php echo $brand->brand_title; ?></a>
                        </li>
                        <?php }?>
                    </ul>
                </div>

            </section>
            <section class="list " id="last-Product">
                <header>
                    آخرین محصولات
                </header>
                <div>
                    <ul>
                        <?php
                        $lastProducts=Product::getAllProducts(1,MAX_LAST_POST);
                        foreach ($lastProducts as $product):?>
                            <li>
                                <a href="./?product=<?php echo $product->id;?>"><?php echo $product->product_title;?></a>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </section>
        </aside>
    </div>
        <div class="col-lg-9">
            <section id="content">
                <?php
                $myquery=null;
                if(isset($_GET["activationKey"]) and !empty($_GET["activationKey"])){
                    $myquery=Customer::isActivationKey($_GET["activationKey"]);
                    if($myquery){
                        $myquery=Customer::activeUserAcc($_GET["activationKey"]);
                        echo '<script>
            swal({
                title: "باتشکر",
                text: "اکنون میتوانید از سایت استفاده نماید",
                icon: "success",
                button: "بستن",
                timer: 3000
        })
    </script>';}
                }
                    if(isset($_GET["payed"])){
                       echo '<script>
            swal({
                title: "باتشکر از خریدتان",
                text: "سفارش شما ثبت شد",
                icon: "success",
                button: "بستن",
                timer: 3000
        })
    </script>';}
                if(isset($_GET["product"]) and is_numeric($_GET["product"])){
                    include_once "include/showProduct.php";
                }
                elseif (isset($_GET["action"])){
                    include_once "include/login.php";
                }
                else require_once "include/showAll.php";?>
            </section>


    </div>
</div>
    <footer id="bottom">
            © تمامی حقوق برای ftm_rahnama محفوظ می باشد.
    </footer>
</div>
</div>
</body>
</html>