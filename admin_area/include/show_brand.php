<?php
if(isset($_GET["show-brand"]) and $_GET["show-brand"]=="updated"){
    echo '<script>
$(function (){
    swal({
    title:"باتشکر",text:"تغییرات برند ثبت شد",icon:"success",buttons:"بستن",timer:3000
    });
})
</script>';
}
if(isset($_GET["del_brand"])) {
    $id = $_GET["del_brand"];
    $delSelectBrand = Brand::delSelectBrand($id);
    if ($delSelectBrand) {
        echo '<script>
    $(function (){
        swal({
            title:"باتشکر",text:"برند انتخابی حذف شد",icon:"info",buttons:"بستن",timer:3000
        });
    })
</script>';
    }
}
if (isset($_POST["del-all"])) {
    if (isset($_POST["checkbox"])) {
        $count = count($_POST["checkbox"]);
        $check = $_POST["checkbox"];
        $delAllbrand =Brand::delAllBrands($check, $count);
        if ($delAllbrand) {
            echo '<script>
$(function (){
    swal({
    title:"باتشکر",text:"اطلاعات با موفقیت حذف گردید",icon:"success",buttons:"بستن",timer:3000
    });
})
</script>';
        }
        else {
            echo '<script>
       $(function (){
    swal({
    title:"متسفانه",text:"گزینه برای حذف موجود نیست",icon:"error",buttons:"بستن",timer:3000
    });
})
</script>';
        }
    }
}?>
<div class="card">
    <div class="card-header ">
        <h4 class="card-title text-center ">برند های های محصولات</h4>
    </div>
    <div class="card-body">
        <ul class="nav nav-pills nav-fill bg-transparent">
        <li class="nav-item">
            <form action="#" method="post">
                <button class="btn btn-outline-danger btn-sm "  name="del-all"><i class="fa fa-trash-alt"></i>حذف کلی</button>
                <a href="./?add-brand" class="btn btn-outline-success btn-sm" ><i class="fa fa-plus "></i> افزودن برند</a>
        </li>
        </ul>
        <table class="table table-striped table-bordered text-center mt-3">
            <thead>
            <tr>
                <th scope="col"><input type="checkbox" class="select-all" name="checkbox"></th>
                <th scope="col"><a href="./?show-brand=id" class="hover color"> برند id<i class="fa fa-chevron-down px-1"></i></a></th>
                <th scope="col">نام برند</th>
                <th scope="col">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(isset($_GET["show-brand"])and $_GET["show-brand"]>0){
                $section=$_GET["show-brand"];
            }
            else $section=1;
            $start=($section-1)* MAX_POST;
            $brands=Brand::getAllBrands(MAX_POST,$start);
            if($brands){
                foreach ($brands as $brand): ?>
                    <tr>
                        <th><input type="checkbox" class="checkbox" name="checkbox[]" value="<?php echo $brand->id ?>"></th>
                        <th><?php echo $brand->id?></th>
                        <th><?php echo $brand->brand_title ?></th>
                        <th>
                            <a href="?update_brand_id=<?php echo  $brand->id ?>" class="text-info px-1"><i class="fa fa-edit"></i></a>
                            <a href="?del_brand=<?php echo $brand->id ?>" class="text-danger"><i class="fa fa-trash-alt"></i></a>
                        </th>
                    </tr>
                <?php endforeach; }
            else{ echo "<div class='alert alert-warning'>متاسفانه برندی برای نمایش وجود ندارد</div>"; } ?>
            </tbody>
        </table>
    </div>
</div>
<?php
if($brands){
$totalCat=count(Brand::getAllBrands());
$totalSection=ceil($totalCat/MAX_POST);?>
<div class="col-lg-12">
    <div class="paging">
        <p> صفحه ی <?php echo $section?> از <?php echo $totalSection?></p>
        <ul id="paging">
            <?php
            for ($index=1;$index<=$totalSection;$index++){
                if($index==$section)$class="class=active";
                else $class="";
                echo "<li><a href=\"./?show-cat=$index\" $class>$index</a></li>";
            }
            ?>
        </ul>
    </div>

    <?php }?>
