<?php
    $errors=array();
    $hasError=false;
    if(isset($_GET["add-product"]) and $_GET["add-product"]==true) {
        echo '<script>swal({
    title:"باتشکر",text:"محصول با موفقیت اضافه شد" ,icon:"success",buttons:"بستن",timer:3000
    });
</script>';}
    if (isset($_POST["submit"])) {
        if (!empty($_POST["product_title"]) and !empty($_POST["product_category"]) and !empty($_POST["product_brand"]) and !empty($_POST["product_price"]) and !empty($_POST["product_size"])) {
            $product_img_name = $_FILES['product_img']['name'];
            $product_img_tmp = $_FILES['product_img']['tmp_name'];
            $product_address_img = 'images/' . $product_img_name;
            move_uploaded_file($product_img_tmp, "../" . $product_address_img);
            $allow_comment = 0;
            if (isset($_POST["allow_comment"])) {
                $allow_comment = 1;
            }
            $product = Product::insertProduct($_POST["product_category"], $_POST['product_title'], $_POST["product_brand"], $_POST["product_price"], $_POST["product_size"], $_POST["product_desc"], $product_address_img, $allow_comment);
            if ($product) {
                echo '<script>window.location.href = "http://localhost/backend/shopping/admin_area/index.php?add-product=true";</script>';
            }
        }
        else {
            if (empty($_POST["product_title"])) {
                $hasError = true;
                $errors[] = "لطفا نام محصول را وارد نمایید";
            }
            if (empty($_POST["product_category"])) {
                $hasError = true;
                $errors[] = "لطفا گروه محصول را وارد نمایید";
            }
            if (empty($_POST["product_brand"])) {
                $hasError = true;
                $errors[] = "لطفا برند را وارد نمایید";
            }
            if (empty($_POST["product_price"])) {
                $hasError = true;
                $errors[] = "لطفا قیمت محصول را وارد نمایید";
            }
            if (empty($_POST["product_size"])) {
                $hasError = true;
                $errors[] = "لطفا سایز محصول را وارد نمایید";
            }
        }
    }
    if(isset($_GET["add-comment"]) and $_GET["add-comment"]==true) {
        echo '<script>swal({
    title:"باتشکر",text:"کامنت با موفقیت اضافه شد" ,icon:"success",buttons:"بستن",timer:3000
    });
</script>';}
    if (isset($_POST["submit_comment"])){
        $product=Product::getProductById($_POST["product_id"]);
        if ($product->allow_comment == 1 and !empty($_POST["comment"]) and !empty($_POST["product_id"])) {
            $comment = Comment::insertComment("ادمین سایت", "", $_POST["comment"],  $_POST["product_id"], 0, $_SERVER["REMOTE_ADDR"]);
            if ($comment) {
                echo '<script>window.location.href = "http://localhost/backend/shopping/admin_area/index.php?add-comment=true";</script>';
            }
        }
        else{
            if ($product->allow_comment != 1) {
                   echo '<script>swal({
    title:"خطا",text:"محصول اجازه ی کامنت ندارد" ,icon:"error",buttons:"بستن",timer:3000
    });
</script>';}
            if (empty($_POST["comment"])) {
                $hasError = true;
                $errors[] = "لطفا کامنت خود را وارد نمایید";
            } 
            if (empty($_POST["product_id"])) {
                $hasError = true;
                $errors[] = "لطفا محصول خود را انتخاب نمایید";
            }
            }
    }
    if(isset($_GET["add-cat"]) and $_GET["add-cat"]==true){
    echo '<script>swal({
    title:"باتشکر",text:"گروه با موفقیت اضافه شد" ,icon:"success",buttons:"بستن",timer:3000
    });
</script>';}
    if (isset($_POST["submit_cat"])){
        if(!empty($_POST["category_name"])) {
            $cat = Category::insertCat($_POST["category_name"], $_POST["parent_id"]);
            if ($cat) {
                echo '<script>window.location.href = "http://localhost/backend/shopping/admin_area/index.php?add-cat=true";</script>';
            }
        }
        else {
            $hasError = true;
            $errors[] = "لطفا نام گروه را وارد نمایید";
        }
}
if(isset($_GET["add-brand"]) and $_GET["add-brand"]==true){
    echo '<script>swal({
    title:"باتشکر",text:"برند با موفقیت اضافه شد" ,icon:"success",buttons:"بستن",timer:3000
    });
</script>';}
if (isset($_POST["submit_brand"])){
    if(!empty($_POST["brand_title"])) {
        $brand = Brand::insertBrand($_POST["brand_title"]);
        if ($brand) {
            echo '<script>window.location.href = "http://localhost/backend/shopping/admin_area/index.php?add-brand=true";</script>';
        }
    }
    else {
        $hasError = true;
        $errors[] = "لطفا نام برند را وارد نمایید";
    }
}
if(isset($_GET["add-customer"]) and $_GET["add-customer"]==true){
    echo '<script>swal({
    title:"باتشکر",text:"کاربر با موفقیت اضافه شد" ,icon:"success",buttons:"بستن",timer:3000
    });
</script>';}
if (isset($_POST["submit_customer"])){
    if(!empty($_POST["first_name"]) and !empty($_POST["last_name"]) and !empty($_POST["email"]) and !empty($_POST["user_name"]) and !empty($_POST["password"]) and !empty($_POST["mobile"]) and !empty($_POST["address"])) {
        $queryExist = Customer::isCustomerExist($_POST["userName"],$_POST["password"]);
        if ($queryExist) {
            echo '<script>
        $(function() {
            swal({
                title: "خطایی رخ داده",
                text: "کاربری با این              نام کاربری قبلا ثبت شده است",
                icon: "error",
                button: "بستن",
                timer: "3000"
            })
        })
    </script>';
        }
        else {
            $currentTime = microTime(true);
            $currentTime = str_replace(".", "", $currentTime);
            $token = hashData($_POST["userName"], "md5");
            $activationKey = $currentTime . $token;
            $customer = Customer::insertCustomer($_POST["first_name"],$_POST["last_name"],$_POST["user_name"],$_POST["email"],$_POST["password"],$_POST["mobile"],$_POST["address"],$activationKey);
            if ($customer) {
                $email_subject = "فعال سازی ایمیل کاربری";
                $email_body = ' <section style="width: 50%;min-height: 400px;box-sizing: border-box;background-color: #f5f5f5;border: 1px solid rgba(52,52,52,0.30);padding: 50px 0 ; box-shadow: 0 0 7px 3px rgba(70,70,70,0.20) ;margin: 30px auto">
        <h3 style="color: #666666; text-align: center;line-height: 36px">لینک فعالسازی حساب کاربری</h3>
        <hr color="silver" size="1" style="width: 70%" />
        <div style="width: 100%;height: 100%;padding: 30px;box-sizing: border-box;text-align: center">
            <a href="http://localhost/backend/shopping/admin_area?activationKey=' . $activationKey . '" style="display: block;font-size: 20px;text-decoration: none;color: tomato;">برای فعالسازی حساب کاربری خود روی لینک کلیک کنید</a>
            <p style="margin: 50px;line-height: 35px;font-size: 14px">درصورت ارسال اشتباه ایمیل لطفا آنرا نادیده بگیرید و پاک کنید</p>
        </div>
    </section> ';
                sendMail($email_subject, $email_body, $_POST["email"]);
                echo '<script>window.location.href = "http://localhost/backend/shopping/admin_area/index.php?add-customer=true";</script>';
            }
        }
    }
    else {
        if (empty($_POST["first_name"])) {
            $hasError = true;
            $errors[] = "لطفا نام کاربر را وارد نمایید";
        }
        if (empty($_POST["last_name"])) {
            $hasError = true;
            $errors[] = "لطفا نام خانوادگی کاربر را وارد نمایید";
        }
        if (empty($_POST["user_name"])) {
            $hasError = true;
            $errors[] = "لطفا نام کاربری را وارد نمایید";
        }
        if (empty($_POST["email"])) {
            $hasError = true;
            $errors[] = "لطفا ایمیل کاربر را وارد نمایید";
        }
        if (empty($_POST["password"])) {
            $hasError = true;
            $errors[] = "لطفا پسورد را وارد نمایید";
        }
        if (empty($_POST["mobile"])) {
            $hasError = true;
            $errors[] = "لطفا موبایل کاربر را وارد نمایید";
        } if (empty($_POST["address"])) {
            $hasError = true;
            $errors[] = "لطفا آدرس کاربر را وارد نمایید";
        }
    }
}
    ?>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card">
                    <?php  if (isset($_GET["add-product"])):?>
                        <div class="card-header bg-light">
                            <h4 class="card-title text-center">افزودن محصول</h4>
                        </div>
                        <div class="card-body">
                            <form method="post" id="insert_product" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="">نام محصول</label>
                                    <input type="text" class="form-control" id="" name="product_title" placeholder="نام محصول">
                                </div>
                                <div class="form-group">
                                    <label for="">دسته بندی محصول</label>
                                    <select name="product_category" class="form-control" id="">
                                        <?php $rootCats=Category::getCategoriesByParentId(0);
                                        foreach ($rootCats as $rootCat) {
                                            $childCats = Category::getCategoriesByParentId($rootCat->id);
                                            foreach ($childCats as $childCat) { ?>
                                                <option value="<?php echo $childCat->id?>"> <?php echo $childCat->category_name; ?></option>
                                            <?php }}?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">برند محصول</label>
                                    <select name="product_brand" class="form-control" id="">
                                        <?php $brands=Brand::getAllBrands();
                                        foreach ($brands as $brand) {?>
                                            <option value="<?php echo $brand->id?>"> <?php echo $brand->brand_title; ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">قیمت محصول به تومان</label>
                                    <input type="text" class="form-control" id="" name="product_price" placeholder="قیمت">
                                </div>
                                <div class="form-group">
                                    <label for="">سایز کفش</label>
                                    <input type="text" class="form-control" id="" name="product_size" placeholder="سایز">
                                </div>
                                <div class="form-group">
                                    <label for="">توصیف محصول</label>
                                    <textarea class="form-control" id="" name="product_desc" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">عکس محصول</label>
                                    <input type="file" name="product_img" class="form-control" id=""">
                                </div>
                                <div class="form-group form-check">
                                    <label class="" for="allow">محصول اجازه کامنت دارد؟</label>
                                    <input type="checkbox" class="" id="allow" name="allow_comment[]" value="1">
                                </div>
                                <input type="submit" class="btn btn-dark btn-block float-left px-4" form="insert_product" name="submit" value="ارسال">
                            </form>
                        </div>
                        <?php endif;?>
                      <?php  if (isset($_GET["add-comment"])):?>
                          <div class="card-header">
            <h5 class="card-title color">کامنت خود را بنویسید</h5>
        </div>
                           <div class="card-body">
            <form method="post" id="insert_product" >
                <div class="form-group">
                    <label for="">انتخاب محصول</label>
                    <select name="product_id" class="form-control" id="">
                        <?php $getProducts=Product::getAllProducts(1);
                        foreach ($getProducts as $getProduct) {?>
                            <option value="<?php echo $getProduct->id?>"> <?php echo $getProduct->product_title; ?></option>
                            <?php }?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">دیدگاه</label>
                    <textarea class="form-control" id="" name="comment" rows="5"></textarea>
                </div>
                <input type="submit" class="btn btn-dark float-left px-4" form="insert_product" name="submit_comment" value="ارسال">
            </form>
        </div>
                        <?php endif;?>
                        <?php  if (isset($_GET["add-cat"])):?>
                            <div class="card-header">
                                <h5 class="card-title color">افزودن گروه</h5>
                            </div>
                            <div class="card-body">
                                <form method="post" id="insert_cat" >
                                    <div class="form-group">
                                        <label for="">دسته اصلی</label>
                                        <select name="parent_id" class="form-control" id="">
                                                <option value="1">مردانه</option>
                                                <option value="2">زنانه</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">نام گروه</label>
                                        <input type="text" class="form-control" id="" name="category_name" placeholder="نام محصول">
                                    </div>
                                    <input type="submit" class="btn btn-dark float-left px-4" form="insert_cat" name="submit_cat" value="ارسال">
                                </form>
                            </div>
                        <?php endif;?>
                        <?php  if (isset($_GET["add-brand"])):?>
                            <div class="card-header">
                                <h5 class="card-title color">افزودن برند</h5>
                            </div>
                            <div class="card-body">
                                <form method="post" id="insert_brand" >
                                    <div class="form-group">
                                        <label for="">نام برند</label>
                                        <input type="text" class="form-control" id="" name="brand_title">
                                    </div>
                                    <input type="submit" class="btn btn-dark justify-content-left px-4" form="insert_brand" name="submit_brand" value="ارسال">
                                </form>
                            </div>
                        <?php endif;?>
                        <?php  if (isset($_GET["add-customer"])):?>
                            <div class="card-header bg-light">
                                <h4 class="card-title text-center">افزودن کاربر</h4>
                            </div>
                            <div class="card-body">
                                <form method="post" id="insert_customer">
                                    <div class="form-group">
                                        <label for="">نام</label>
                                        <input type="text" class="form-control" id="" name="first_name" placeholder="نام">

                                    </div>
                                    <div class="form-group">
                                        <label for="">نام خانوادگی</label>
                                        <input type="text" class="form-control" id="" name="last_name" placeholder="نام خانوادگی">
                                    </div>
                                    <div class="form-group">
                                        <label for="">نام کاربری</label>
                                        <input type="text" name="user_name" class="form-control" id="" placeholder="نام کاربری">
                                    </div>
                                    <div class="form-group">
                                        <label for="">ایمیل</label>
                                        <input type="text" name="email" class="form-control" id="" placeholder="ایمیل">
                                    </div>
                                    <div class="form-group">
                                        <label for="">رمز عبور</label>
                                        <input type="password" name="password" class="form-control" id="" placeholder="رمز عبور">
                                    </div>
                                    <div class="form-group">
                                        <label for="">موبایل</label>
                                        <input type="text" name="mobile" class="form-control" id="" placeholder="موبایل">
                                    </div>
                                    <div class="form-group">
                                        <label for="">آدرس</label>
                                        <input type="text" name="address" class="form-control" id="" placeholder="آدرس">
                                    </div>
                                    <input type="submit" class="btn btn-dark btn-block px-4" form="insert_customer" name="submit_customer" value="ثبت نام">
                                </form>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
                <?php if($hasError): ?>
                    <div class="col-5">
                        <div class="card border-0 bg-transparent">
                            <div class="card-body">
                                <?php foreach ($errors as $error): ?>
                                    <div class="alert alert-danger">
                                        <span class="close float-left" data-dismiss="alert">&times;</span>
                                        <p class="lead m-0"><?php echo $error; ?></p>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
