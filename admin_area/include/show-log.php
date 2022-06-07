<?php
/*$deleteAllLog=null;
$delSelectLog=null;
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
*/?>
<div class="card">
            <div class="card-header ">
                <h4 class="card-title text-center ">ورود و خروج کاربران</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills nav-fill bg-transparent">
                    <li class="nav-item text-secondary">
                        <a class="nav-link active bg" href="./?show-log">همه</a>
                    </li>
                    <li class="nav-item text-secondary">
                        <a class="nav-link text-secondary" href="./?show-log=login">ورود</a>
                    </li>
                    <li class="nav-item text-secondary">
                        <a class="nav-link text-secondary" href="./?show-log=logout">خروج</a>
                    </li>
                </ul>
            </div>
        </div>
<div class="card">
    <div class="card-body">
        <table class="table table-striped table-bordered text-center">
            <thead>
            <tr>
                <th scope="col"><a href="./?show-log=user_id" class="hover color">شناسه کاربری<i class="fa fa-chevron-down px-1"></i></a></th>
                <th scope="col"><a href="./?show-log=time" class="hover color">زمان رخداد<i class="fa fa-chevron-down px-1"></i></a></th>
                <th scope="col">IP کاربر</th>
                <th scope="col">ایمیل</th>
                <th scope="col">نام کاربری</th>
                <th scope="col">جزئیات</th>

            </tr>
            </thead>
            <tbody>
            <?php
            if(isset($_GET["show-log"])and $_GET["show-log"]>0){
                $section=$_GET["show-log"];
            }
            else $section=1;
            if(isset($_GET["show-log"])and ($_GET["show-log"])=="login"){
                    $condition ="details='ورود'";
            }
            elseif(isset($_GET["show-log"])and ($_GET["show-log"])=="logout"){
                    $condition ="details='خروج'";
            }
            else $condition=1;
            if(isset($_GET["show-log"])and $_GET["show-log"]=="user_id"){
                $order="user_id";
            }
            elseif(isset($_GET["show-log"])and $_GET["show-log"]=="time"){
                $order="time";
            }
            else $order="id";
            $start=($section-1)* 20;
                $logs=Log::getAllLogs($condition,20,$start,$order);
            if($logs){
                foreach ($logs as $log): ?>
                        <th><?php echo $log->user_id?></th>
                        <th><?php $creation=convertDate($log->time);
                            echo "تاریخ: ".$creation["day"]."/".$creation["month_name"]."/".$creation["year"];
                            echo " زمان: ".$creation["hour"].":".$creation["minute"].":".$creation["second"]; ?></th>
                        <th><?php echo $log->user_ip; ?></th>
                        <th><?php echo $log->email?></th>
                        <th><?php echo $log->user_name?></th>
                        <th><?php
                            switch ($log->details){
                                case 'خروج': echo '<span class="badge badge-danger p-2">خروج</span>';
                                    break;
                                case 'ورود': echo '<span class="badge badge-success p-2">ورود</span>';
                                    break;
                            }
                            ?></th>
                    </tr>
                <?php endforeach; }
            else{ echo "<div class='alert alert-warning'>متاسفانه محصولی برای نمایش وجود ندارد</div>"; } ?>
            </tbody>
        </table>
    </div>
</div>
<?php
if($logs){
$totalLog=count(Log::getAllLogs($condition));
$totalSection=ceil($totalLog/20);?>
<div class="col-lg-12">
    <div class="paging">
        <p> صفحه ی <?php echo $section?> از <?php echo $totalSection?></p>
    <ul id="paging">
        <?php
        for ($index=1;$index<=$totalSection;$index++){
            if($index==$section)$class="class=active";
            else $class="";
            echo "<li><a href=\"./?show-log=$index\" $class>$index</a></li>";
        }
        ?>
    </ul>
</div>
    <?php }?>


