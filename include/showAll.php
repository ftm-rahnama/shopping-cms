<div class="col-lg-12">
    <div class="row">
<?php
if(isset($_GET["section"])and is_numeric($_GET["section"])){
    $section=$_GET["section"];
}
else $section=1;
if(isset($_GET["cat"]) and is_numeric($_GET["cat"])){
    $cats=Category::getCategoriesByParentId($_GET["cat"]);
    if(is_array($cats)){
        $conditon="";
        foreach ($cats as $cat){
            $conditon.="product_cat=".$cat->id." or ";
        }
        $conditon=substr($conditon,0,strlen($conditon)-3);
    }
    else {
        $conditon ="product_cat=" . $_GET["cat"];
    }
}
else if(isset($_GET["brand"]) and is_numeric($_GET["brand"])){
    $conditon="product_brand=".$_GET["brand"];
}
else $conditon=1;
    $start=($section-1)* MAX_POST;
    if($products=Product::getAllProducts($conditon,MAX_POST,$start)):?>
    <?php foreach ($products as $product) :?>
            <div class="card col-lg-4 product_card">
                <div class="card-body d-block">
                <a href="./?product=<?php echo $product->id?>" class="color hover">
                    <h5 class="card-title text-center"><?php echo $product->product_title;?>
                    </h5>

                    <img src="<?php echo $product->product_img; ?>" alt="">

                </a>
                </div>
                <div class="card-footer">
                    <span class="d-block"> قیمت:  <?php echo $product->product_price?> تومان </span>
                    <a href="./?buy=<?php echo $product->id?>" class="add_cart color hover">
                        <i class="fa fa-shopping-cart"></i>افزودن به سبد
                    </a>
                </div>
            </div>
    <?php endforeach;?>
    </div>
</div>
</div>
<?php
$totalProduct=count(Product::getAllProducts($conditon));
$totalSection=ceil($totalProduct/MAX_POST);
?>
<div class="col-lg-12">
    <div class="paging">
        <p> صفحه ی <?php echo $section?> از <?php echo $totalSection?></p>
        <ul id="paging">
            <?php
            for ($index=1;$index<=$totalSection;$index++){
                if($index==$section)$class="class=active";
                else $class="";
                echo "<li><a href=\"./?section=$index\" $class>$index</a></li>";
            }
            ?>
        </ul>
    </div>

<?php endif; ?>