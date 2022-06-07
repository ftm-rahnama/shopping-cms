<?php
if(isset($_GET["show-product"]) and $_GET["show-product"]=="updated"){
    echo '<script>
$(function (){
    swal({
    title:"باتشکر",text:"اطلاعات محصول به روز رسانی شد",icon:"success",buttons:"بستن",timer:3000
    });
})
</script>';
}
$deleteAllProduct=null;
$delSelectProduct=null;
if (isset($_POST["del-all"])) {
    if (isset($_POST["checkbox"])) {
        $count = count($_POST["checkbox"]);
        $check = $_POST["checkbox"];
        $delAllProduct = Product::delAllProducts($check, $count);
        if ($delAllProduct) {
            echo '<script>
$(function (){
    swal({
    title:"باتشکر",text:"اطلاعات با موفقیت حذف گردید",icon:"success",buttons:"بستن",timer:3000
    });
})
</script>';
        } else {
            echo '<script>
       $(function (){
    swal({
    title:"متسفانه",text:"گزینه برای حذف موجود نیست",icon:"error",buttons:"بستن",timer:3000
    });
})
</script>';
        }
    }
}
if(isset($_GET["del_id"])) {
    $id = $_GET["del_id"];
    $delSelectProduct = Product::delSelectProduct($id);
    if ($delSelectProduct) {
        echo '<script>
$(function (){
    swal({
    title:"باتشکر",text:"محصول انتخابی حذف شد",icon:"info",buttons:"بستن",timer:3000
    });
})
</script>';
    }
}
?> 
<div class="card">
            <div class="card-header ">
                <h4 class="card-title text-center ">اطلاعات محصولات</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills nav-fill bg-transparent">
                    <li class="nav-item text-secondary">
                        <a class="nav-link active bg" href="./?show-product">همه</a>
                    </li>
                    <?php
                            $rootCats=Category::getCategoriesByParentId(0);
                            foreach ($rootCats as $rootCat) {
                                $childCats = Category::getCategoriesByParentId($rootCat->id);
                    foreach ($childCats as $childCat) { ?>
                    <li class="nav-item text-secondary">
                        <a class="nav-link text-secondary" href="./?show-product=<?php echo $childCat->id ;?>">
                        <?php echo $childCat->category_name; ?></a>
                    </li>
                    <?php }}?>
                    <li class="nav-item">
                        <form action="#" method="post">
                            <button class="btn btn-outline-danger btn-sm "  name="del-all"><i class="fa fa-trash-alt"></i>حذف کلی</button>
                            <a href="./?add-product" class="btn btn-outline-success btn-sm" ><i class="fa fa-plus "></i> افزودن محصول</a>

                    </li>
                </ul>
            </div>
        </div>
<div class="card">
    <div class="card-body">
        <table class="table table-striped table-bordered text-center">
            <thead>
            <tr>
                <th scope="col"><input type="checkbox" class="select-all" name="checkbox"></th>
                <th scope="col"><a href="./?show-product=id" class="hover color">محصول id <i class="fa fa-chevron-down px-1"></i></a></th>
                <th scope="col">گروه بندی</th>
                <th scope="col">برند</th>
                <th scope="col">عنوان محصول</th>
                <th scope="col"><a href="./?show-product=price" class="hover color">  قیمت (تومان)<i class="fa fa-chevron-down px-1"></i></a></th>
                <th scope="col"><a href="./?show-product=size" class="hover color">  سایز<i class="fa fa-chevron-down px-1"></i></a></th>
                <th scope="col">توضیحات</th>
                <th scope="col">عکس</th>
                <th scope="col">اجازه کامنت</th>
                <th scope="col">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(isset($_GET["show-product"])and $_GET["show-product"]<0){
                $section=$_GET["show-product"]*-1;
            }
            else $section=1;
            if(isset($_GET["show-product"])and ($_GET["show-product"])>0){
                $cats=Category::getCategoriesByParentId($_GET["show-product"]);
                if(is_array($cats)){
                    $condition="";
                    foreach ($cats as $cat){
                        $condition.="product_cat=".$cat->id." or ";
                    }
                    $condition=substr($condition,0,strlen($condition)-3);
                }
                else {
                    $condition ="product_cat=" . $_GET["show-product"];
                }
            }
            else $condition=1;
            if(isset($_GET["show-product"])and $_GET["show-product"]=="price"){
                $order="product_price";
            }
            else if(isset($_GET["show-product"])and $_GET["show-product"]=="id"){
                $order="id";
            }
            else if(isset($_GET["show-product"])and $_GET["show-product"]=="size"){
                $order="product_size";
            }
            else $order="id";
            $start=($section-1)* MAX_POST;
                $products=Product::getAllProducts($condition,MAX_POST,$start,$order);
            if($products){
                foreach ($products as $product): ?>
                    <tr>
                        <th><input type="checkbox" class="checkbox" name="checkbox[]" value="<?php echo $product->id ?>"></th>
                        <th><?php echo $product->id?></th>
                        <th><?php
                            $cat=Category::getCategoryById($product->product_cat);
                            echo $cat->category_name; ?>
                            </p></th>
                        <th><?php
                            $brand=Brand::getBrandyById($product->product_brand);
                            echo $brand->brand_title; ?></th>
                        <th><?php echo $product->product_title?></th>
                        <th><?php echo $product->product_price?></th>
                        <th><?php echo $product->product_size?></th>
                        <th> <?php
                            $descs=explode("\n",$product->product_desc);
                            foreach ($descs as $desc) {
                                echo $desc;
                                ?>
                                <br>
                            <?php }?></th>
                        <th><img src="<?php echo "../".$product->product_img?>" alt=""></th>
                        <th><?php
                            switch ($product->allow_comment){
                                case 0: echo '<span class="badge badge-danger">غیر مجاز</span>';
                                    break;
                                case 1: echo '<span class="badge badge-success">مجاز</span>';
                                    break;
                            }
                            ?></th>
                        <th>
                            <a  href="./?update_product_id=<?php echo $product->id; ?>" class=" text-info"><i class="fa fa-edit"></i></a>
                            <a href="?del_id=<?php echo $product->id; ?>" class="text-danger px-1"><i class="fa fa-trash-alt"></i></a>
                        </th>
                    </tr>
                <?php endforeach; }
            else{ echo "<div class='alert alert-warning'>متاسفانه محصولی برای نمایش وجود ندارد</div>"; } ?>
            </tbody>
        </table>
    </div>
</div>
<?php
if($products){
$totalProduct=count(Product::getAllProducts($condition));
$totalSection=ceil($totalProduct/MAX_POST);?>
<div class="col-lg-12">
    <div class="paging">
        <p> صفحه ی <?php echo $section?> از <?php echo $totalSection?></p>
    <ul id="paging">
        <?php
        for ($index=1;$index<=$totalSection;$index++){
            if($index==$section)$class="class=active";
            else $class="";
            $sec=$index*-1;
            echo "<li><a href=\"./?show-product=$sec\" $class>$index</a></li>";
        }
        ?>
    </ul>
</div>
    <?php }?>


