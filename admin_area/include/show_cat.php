<?php
if(isset($_GET["show-cat"]) and $_GET["show-cat"]=="updated"){
    echo '<script>
$(function (){
    swal({
    title:"باتشکر",text:"تغییرات گروه ثبت شد",icon:"success",buttons:"بستن",timer:3000
    });
})
</script>';
}
if(isset($_GET["del_cat"])) {
    $id = $_GET["del_cat"];
    $delSelectCat = Category::delSelectCat($id);
    if ($delSelectCat) {
        echo '<script>
    $(function (){
        swal({
            title:"باتشکر",text:"گروه انتخابی حذف شد",icon:"info",buttons:"بستن",timer:3000
        });
    })
</script>';
    }
}
if (isset($_POST["del-all"])) {
    if (isset($_POST["checkbox"])) {
        $count = count($_POST["checkbox"]);
        $check = $_POST["checkbox"];
        $delAllCat =Category:: delAllcats($check, $count);
        if ($delAllCat) {
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
        <h4 class="card-title text-center ">گروه های محصولات</h4>
    </div>
    <div class="card-body">
        <ul class="nav nav-pills nav-fill bg-transparent">
            <li class="nav-item text-secondary">
                <a class="nav-link active bg" href="./?show-cat">همه</a>
            </li>
            <li class="nav-item text-secondary">
                <a class="nav-link text-secondary" href="./?show-cat=male">
                    مردانه</a>
            </li>
            <li class="nav-item text-secondary">
                <a class="nav-link text-secondary" href="./?show-cat=female">
                    زنانه</a>
            </li>
            <li class="nav-item">
                <form action="#" method="post">
                    <button class="btn btn-outline-danger btn-sm "  name="del-all"><i class="fa fa-trash-alt"></i>حذف کلی</button>
                    <a href="./?add-cat" class="btn btn-outline-success btn-sm" ><i class="fa fa-plus "></i> افزودن گروه</a>
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
                <th scope="col"><a href="./?show-cat=id" class="hover color"> گروه id<i class="fa fa-chevron-down px-1"></i></a></th>
                <th scope="col">نام گروه</th>
                <th scope="col">گروه اصلی</th>
                <th scope="col">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(isset($_GET["show-cat"])and $_GET["show-cat"]>0){
                $section=$_GET["show-comment"];
            }
            else $section=1;
            if(isset($_GET["show-cat"])and ($_GET["show-cat"])=="male"){
                $condition="parent_id=1";
            }
            elseif(isset($_GET["show-cat"])and ($_GET["show-cat"])=="female"){
                $condition="parent_id=2";
            }
            else $condition="id>2";
             $order="id";
            $start=($section-1)* MAX_POST;
            $categories=Category::getAllCategories($condition,MAX_POST,$start,$order);
            if($categories){
                foreach ($categories as $category): ?>
                    <tr>
                        <th><input type="checkbox" class="checkbox" name="checkbox[]" value="<?php echo $category->id ?>"></th>
                        <th><?php echo $category->id?></th>
                        <th><?php echo $category->category_name ?></th>
                        <th><?php
                            if($category->parent_id==1) echo '<span class="badge badge-primary p-2">مردانه</span>';
                            elseif ($category->parent_id==2) echo '<span class="badge badge-success p-2">زنانه</span>';
                            ?></th>
                        <th>
                            <a href="?update_cat_id=<?php echo  $category->id ?>" class="text-info px-1"><i class="fa fa-edit"></i></a>
                            <a href="?del_cat=<?php echo $category->id ?>" class="text-danger"><i class="fa fa-trash-alt"></i></a>
                        </th>
                    </tr>
                <?php endforeach; }
            else{ echo "<div class='alert alert-warning'>متاسفانه گروهی برای نمایش وجود ندارد</div>"; } ?>
            </tbody>
        </table>
    </div>
</div>
<?php
if($categories){
$totalCat=count(Category::getAllCategories($condition));
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