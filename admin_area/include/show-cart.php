<?php
if(isset($_GET["confirm_order"])){
     $id = $_GET["confirm_order"];
    $confirmOrder = Order::verifiedOrder($id);
    if ($confirmOrder) {
        echo '<script>
$(function (){
    swal({
    title:"باتشکر",text:"سفارش انتخابی تایید شد",icon:"success",buttons:"بستن",timer:3000
    });
})
</script>';
    }
}
?>
<div class="card">
            <div class="card-header ">
                <h4 class="card-title text-center ">سفارشات کاربران</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills nav-fill bg-transparent">
                    <li class="nav-item text-secondary">
                        <a class="nav-link active bg" href="./?show-cart">همه</a>
                    </li>
                    <li class="nav-item text-secondary">
                        <a class="nav-link text-secondary" href="./?show-cart=confirmed">تایید شده</a>
                    </li>
                    <li class="nav-item text-secondary">
                        <a class="nav-link text-secondary" href="./?show-cart=wait">انتظار تایید</a>
                    </li>
                </ul>
            </div>
        </div>
<div class="card">
    <div class="card-body">
        <table class="table table-striped table-bordered text-center">
            <thead>
            <tr>
                <th scope="col"><a href="./?show-cart=id" class="hover color">شناسه سفارش<i class="fa fa-chevron-down px-1"></i></a></th>
                <th scope="col"><a href="./?show-cart=time" class="hover color">زمان سفارش<i class="fa fa-chevron-down px-1"></i></a></th>
                <th scope="col"><a href="./?show-cart=price" class="hover color">مبلغ سفارش<i class="fa fa-chevron-down px-1"></i></a></th>
                <th scope="col">ایمیل کاربر</th>
                <th scope="col">وضعیت سفارش</th>
                <th scope="col">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(isset($_GET["show-cart"])and $_GET["show-cart"]>0){
                $section=$_GET["show-cart"];
            }
            else $section=1;
            if(isset($_GET["show-cart"])and ($_GET["show-cart"])=="confirmed"){
                    $condition="order_verified='true'";
                }
            else if(isset($_GET["show-cart"])and ($_GET["show-cart"])=="wait"){
                    $condition="order_verified='false'";
                }
             else $condition=1;
            if(isset($_GET["show-cart"])and ($_GET["show-cart"])=="id"){
                    $order="id";
                }else if(isset($_GET["show-cart"])and ($_GET["show-cart"])=="time"){
                $order="order_time";
                }else if(isset($_GET["show-cart"])and ($_GET["show-cart"])=="price"){
                $order="order_total_price";
                }
            else $order="id";

            $start=($section-1)* MAX_POST;
                $orders=Order::getAllOrders($condition,MAX_POST,$start,$order);
            if($orders){
                foreach ($orders as $order): ?>
                    <tr>
                        <th><?php echo $order->id?></th>
                        <th><?php
                             $creation = convertDate($order->order_time);
                            echo "تاریخ: " . $creation["day"] . "/" . $creation["month_name"] . "/" . $creation["year"];
                            echo " زمان: " . $creation["hour"] . ":" . $creation["minute"] . ":" . $creation["second"];
                            ?></th>
                        <th><?php echo $order->order_total_price." تومان"?></th>
                        <th><?php echo $order->customer_email?></th>
                        <th><?php
                            switch ($order->order_verified){
                                case 'false': echo '<span class="badge badge-warning p-1">انتظار تایید</span>';
                                    break;
                                case 'true': echo '<span class="badge badge-success p-1">تایید شده</span>';
                                    break;
                            }
                            ?></th>
                        <th>
                            <?php if($order->order_verified=='false'): ?>
                            <p class="d-block"><a  href="./?confirm_order=<?php echo $order->id; ?>" class="text-success"> تایید سفارش<i class="fa fa-check"></i></a></p><?php endif;?>
                            <a href="?show-detail=<?php echo $order->id; ?>" class="text-info"> جزئیات سفارش <i class="fa fa-info"></i></a>
                        </th>
                    </tr>
                <?php endforeach; }
            else{ echo "<div class='alert alert-warning'>متاسفانه کاربری برای نمایش وجود ندارد</div>"; } ?>
            </tbody>
        </table>
    </div>
</div>
<?php
if($orders){
$totalProduct=count(Order::getAllOrders($condition));
$totalSection=ceil($totalProduct/MAX_POST);?>
<div class="col-lg-12">
    <div class="paging">
        <p> صفحه ی <?php echo $section?> از <?php echo $totalSection?></p>
    <ul id="paging">
        <?php
        for ($index=1;$index<=$totalSection;$index++){
            if($index==$section)$class="class=active";
            else $class="";
            echo "<li><a href=\"./?show-cart=$index\" $class>$index</a></li>";
        }
        ?>
    </ul>
</div>
    <?php }?>


