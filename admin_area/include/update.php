<?php
if(isset($_GET["update_product_id"])) {
    $errors=array();
    $hasError=false;
    $id = $_GET["update_product_id"];
    if (isset($_POST["update_product"])) {
        $updateProduct = null;

    if (!empty($_POST["product_title"]) and !empty($_POST["product_cat"]) and !empty($_POST["product_brand"]) and !empty($_POST["product_price"]) and !empty($_POST["product_size"])) {
        $product_img_name=$_FILES['product_img']['name'];
        $product_img_tmp=$_FILES['product_img']['tmp_name'];
        $product_address_img='images/'.$product_img_name;
        move_uploaded_file($product_img_tmp,"../".$product_address_img);
        $allow_comment=0;
        if(isset($_POST["allow_comment"])){
            $allow_comment=1;
        }
$updateProduct =Product::updateProduct($id,$_POST["product_cat"],$_POST["product_title"],$_POST["product_brand"], $_POST["product_price"], $_POST["product_size"], $_POST["product_desc"],$product_address_img,$allow_comment);
        if ($updateProduct) {
echo '<script>window.location.href = "http://localhost/backend/shopping/admin_area/?show-product=updated";</script>';}
    }
    else {
        if (empty($_POST["product_title"])) {
            $hasError = true;
            $errors[] = "لطفا نام محصول را وارد نمایید";
        }
        if (empty($_POST["product_cat"])) {
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
?> <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h4 class="card-title text-center">ادیت محصول</h4>
                        </div>
                        <div class="card-body">
                            <form method="post" id="insert_product" enctype="multipart/form-data">
                                <?php
                                $findUserById=null;
                                $product=Product::getProductById($id);?>
                                <div class="form-group">
                                    <label for="">نام محصول</label>
                                    <input type="text" class="form-control" value="<?php echo $product->product_title;?>" id="" name="product_title" placeholder="نام محصول">
                                </div>
                                <div class="form-group">
                                    <label for="">دسته بندی محصول</label>
                                    <select name="product_cat" class="form-control" id="">
                                        <?php $rootCats=Category::getCategoriesByParentId(0);
                                        foreach ($rootCats as $rootCat) {
                                            $childCats = Category::getCategoriesByParentId($rootCat->id);
                                            foreach ($childCats as $childCat) { ?>
                                                <option value="<?php echo $childCat->id?>"> <?php echo $childCat->category_name; ?></option>
                                            <?php }}?>
                                    </select>
                                    <script>
                                        $(function (){
                                            $("select option").each(function (index,element){
                                                if($(element).attr("value")==<?php echo $product->product_cat?>){
                                                    $(element).attr("selected","selected")
                                                }
                                            })
                                        })
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="">برند محصول</label>
                                    <select name="product_brand" class="form-control" id="">
                                        <?php $brands=Brand::getAllBrands();
                                        foreach ($brands as $brand) {?>
                                            <option value="<?php echo $brand->id?>"> <?php echo $brand->brand_title; ?></option>
                                        <?php }?>
                                    </select>
                                    <script>
                                        $(function (){
                                            $("select option").each(function (index,element){
                                                if($(element).attr("value")==<?php echo $product->product_brand?>){
                                                    $(element).attr("selected","selected")
                                                }
                                            })
                                        })
                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="">قیمت محصول به تومان</label>
                                    <input type="text" class="form-control" id="" name="product_price" value="<?php echo $product->product_price;?>" placeholder="قیمت">
                                </div>
                                <div class="form-group">
                                    <label for="">سایز کفش</label>
                                    <input type="text" class="form-control" id="" name="product_size" value="<?php echo $product->product_size;?>" placeholder="سایز">
                                </div>
                                <div class="form-group">
                                    <label for="">توصیف محصول</label>
                                    <textarea class="form-control" id="" name="product_desc" rows="5"><?php echo $product->product_desc;?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">عکس محصول</label>
                                    <input type="file" name="product_img" class="form-control" id="">
                                </div>
                                <div class="form-group form-check">
                                    <label class="" for="allow">محصول اجازه کامنت دارد؟</label>
                                    <input type="checkbox" class="" id="allow" name="allow_comment[]" value="1">
                                </div>
                                <input type="submit" class="btn btn-dark btn-block" form="insert_product" name="update_product" value="بروز رسانی">
                            </form>
                        </div>

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
<?php }
if(isset($_GET["update_comment_id"])) {
    $errors="";
    $hasError=false;
    $comment_id= $_GET["update_comment_id"];
    $root_comment=Comment::getCommentById($comment_id);
    if (isset($_POST["submit_reply"])) {
        $replyComment = null;
        if (!empty($_POST["comment"])) {
            $replyComment =Comment::insertComment("ادمین سایت", "", $_POST["comment"], $root_comment->product_id, $comment_id, $_SERVER["REMOTE_ADDR"]);
            if ($replyComment) {
                echo '<script>window.location.href = "http://localhost/backend/shopping/admin_area/?show-comment=replied";</script>';}
        }
        else {
            if (empty($_POST["comment"])) {
                $hasError = true;
                $errors = "لطفا پاسخ خود را وارد نمایید";
            }
        }
    }
    ?>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card bg-white">
                <div class="card-header">
                    <h5 class="card-title color">پاسخ خود را بنویسید</h5>
                </div>
                <div class="card-body">
                    <form method="post" id="reply">
                        <div class="form-group">
                            <label for="">پاسخ در جواب <?php echo "<span class='color'>".$root_comment->full_name."</span>";?></label>
                            <textarea class="form-control" id="" name="comment" rows="5"></textarea>
                        </div>
                        <input type="submit" class="btn btn-dark float-left px-4" form="reply" name="submit_reply" value="ارسال">
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php }
if(isset($_GET["update_cat_id"])) {
    $errors="";
    $hasError=false;
    $cat_id= $_GET["update_cat_id"];
    if (isset($_POST["update_cat"])) {
        $updateCat = null;
        if (!empty($_POST["category_name"])) {
            $updateCat=Category::updateCat($cat_id,$_POST["parent_id"],$_POST["category_name"]);
            if ($updateCat) {
                echo '<script>window.location.href = "http://localhost/backend/shopping/admin_area/?show-cat=updated";</script>';}
        }
        else {
            if (empty($_POST["category_name"])) {
                $hasError = true;
                $errors = "لطفا نام گروه را وارد نمایید";
            }
        }
    }
    ?>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="card-title text-center">ادیت محصول</h4>
                </div>
                <div class="card-body">
                    <form method="post" id="cat">
                        <?php  $cat=Category::getCategoryById($cat_id);

                        ?>
                        <div class="form-group">
                            <label for="">دسته اصلی</label>
                            <select name="parent_id" class="form-control" id="">
                                <option value=1>مردانه</option>
                                <option value=2>زنانه</option>
                            <script>
                                $(function (){
                                    $("select option").each(function (index,element){
                                        if($(element).attr("value")==<?php echo $cat->parent_id?>){
                                            $(element).attr("selected","selected")
                                        }
                                    })
                                })
                            </script>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">نام گروه</label>
                            <input type="text" class="form-control" id="" name="category_name" value="<?php echo $cat->category_name;?>">
                        </div>
                        <input type="submit" class="btn btn-dark btn-block" form="cat" name="update_cat" value="بروز رسانی">
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php }
if(isset($_GET["update_brand_id"])) {
   
    $errors="";
    $hasError=false;
    $brand_id= $_GET["update_brand_id"];
    if (isset($_POST["update_brand"])) {
        $updateCat = null;
        if (!empty($_POST["brand_title"])) {
            $updateBrand=Brand::updateBrand($brand_id,$_POST["brand_title"]);
            if ($updateBrand) {
                echo '<script>window.location.href = "http://localhost/backend/shopping/admin_area/?show-brand=updated";</script>';}
        }
        else {
            if (empty($_POST["brand_title"])) {
                $hasError = true;
                $errors = "لطفا نام برند را وارد نمایید";
            }
        }
    }
    ?>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="card-title text-center">ادیت برند</h4>
                </div>
                <div class="card-body">
                    <form method="post" id="brand">
                        <?php  $brand=Brand:: getBrandyById($brand_id); ?>
                        <div class="form-group">
                            <label for="">نام برند</label>
                            <input type="text" class="form-control" id="" name="brand_title" value="<?php echo $brand->brand_title;?>">
                        </div>
                        <input type="submit" class="btn btn-dark btn-block" form="brand" name="update_brand" value="بروز رسانی">
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php }
if(isset($_GET["update_customer_id"])) {
    $errors=array();
    $hasError=false;
    $id = $_GET["update_customer_id"];
    if (isset($_POST["update_customer"])) {
        $updateCustomer = null;
        if(!empty($_POST["first_name"]) and !empty($_POST["last_name"]) and !empty($_POST["email"]) and !empty($_POST["user_name"]) and !empty($_POST["password"]) and !empty($_POST["mobile"]) and !empty($_POST["address"])) {

            $updateCustomer =Customer::updateCustomer($id,$_POST["first_name"],$_POST["last_name"],$_POST["user_name"],$_POST["email"],$_POST["password"],$_POST["mobile"],$_POST["address"]);
            if ($updateCustomer) {
                echo '<script>window.location.href = "http://localhost/backend/shopping/admin_area/?show-customer=updated";</script>';}
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
    ?> <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="card-title text-center">ادیت کاربر</h4>
                </div>
                <div class="card-body">
                    <form method="post" id="customer">
                        <?php
                        $customer=Customer::getCustomerById($id);?>
                        <div class="form-group">
                            <label for="">نام</label>
                            <input type="text" class="form-control" id="" name="first_name" value="<?php echo $customer->first_name?>" >

                        </div>
                        <div class="form-group">
                            <label for="">نام خانوادگی</label>
                            <input type="text" class="form-control" id="" name="last_name" value="<?php echo $customer->last_name?>" >
                        </div>
                        <div class="form-group">
                            <label for="">نام کاربری</label>
                            <input type="text" name="user_name" class="form-control" id="" value="<?php echo $customer->user_name?>"">
                        </div>
                        <div class="form-group">
                            <label for="">ایمیل</label>
                            <input type="text" name="email" class="form-control" id="" value="<?php echo $customer->email;?>">
                        </div>
                        <div class="form-group">
                            <label for="">رمز عبور</label>
                            <input type="password" name="password" class="form-control" id="" value="<?php echo $customer->password?>">
                        </div>
                        <div class="form-group">
                            <label for="">موبایل</label>
                            <input type="text" name="mobile" class="form-control" id="" value="<?php echo $customer->mobile?>">
                        </div>
                        <div class="form-group">
                            <label for="">آدرس</label>
                            <input type="text" name="address" class="form-control" id="" value="<?php echo $customer->address?>">
                        </div>
                        <input type="submit" class="btn btn-dark btn-block" form="customer" name="update_customer" value="بروز رسانی">
                    </form>
                </div>
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
<?php }
?>
