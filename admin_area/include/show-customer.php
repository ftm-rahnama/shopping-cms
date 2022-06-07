<?php
if(isset($_GET["show-customer"]) and $_GET["show-customer"]=="updated"){
    echo '<script>
$(function (){
    swal({
    title:"باتشکر",text:"اطلاعات کاربر به روز رسانی شد",icon:"success",buttons:"بستن",timer:3000
    });
})
</script>';
}
$deleteAllCustomer=null;
$delSelectCustomer=null;
if (isset($_POST["del-all"])) {
    if (isset($_POST["checkbox"])) {
        $count = count($_POST["checkbox"]);
        $check = $_POST["checkbox"];
        $delAllCustomer = Customer::delAllCustomers($check, $count);
        if ($delAllCustomer) {
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
if(isset($_GET["del_customer"])) {
    $id = $_GET["del_customer"];
    $delSelectCustomer = Customer::delSelectCustomer($id);
    if ($delSelectCustomer) {
        echo '<script>
$(function (){
    swal({
    title:"باتشکر",text:"کاربر انتخابی حذف شد",icon:"info",buttons:"بستن",timer:3000
    });
})
</script>';
    }
}
?> 
<div class="card">
            <div class="card-header ">
                <h4 class="card-title text-center ">اطلاعات کاربران</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills nav-fill bg-transparent">
                    <li class="nav-item text-secondary">
                        <a class="nav-link active bg" href="./?show-customer">همه</a>
                    </li>
                    <li class="nav-item text-secondary">
                        <a class="nav-link text-secondary" href="./?show-customer=active">کاربران فعال</a>
                    </li>
                    <li class="nav-item text-secondary">
                        <a class="nav-link text-secondary" href="./?show-customer=inactive">کاربران غیر فعال</a>
                    </li>
                    <li class="nav-item">
                        <form action="#" method="post">
                            <button class="btn btn-outline-danger btn-sm "  name="del-all"><i class="fa fa-trash-alt"></i>حذف کلی</button>
                            <a href="./?add-customer" class="btn btn-outline-success btn-sm" ><i class="fa fa-plus "></i> افزودن کاربر</a>

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
                <th scope="col"><a href="./?show-customer=id" class="hover color">کاربر id <i class="fa fa-chevron-down px-1"></i></a></th>
                <th scope="col">نام</th>
                <th scope="col">ایمیل</th>
                <th scope="col">نام کاربری</th>
                <th scope="col">موبایل</th>
                <th scope="col">آدرس</th>
                <th scope="col">وضغیت کاربری</th>
                <th scope="col">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(isset($_GET["show-customer"])and $_GET["show-customer"]>0){
                $section=$_GET["show-customer"];
            }
            else $section=1;
            if(isset($_GET["show-customer"])and ($_GET["show-customer"])=="active"){
                    $condition="status=1";
                }
            elseif(isset($_GET["show-customer"])and ($_GET["show-customer"])=="inactive"){
                    $condition="status=0";
                }
            else $condition=1;
            $start=($section-1)* MAX_POST;
            
                $customers=Customer::getAllCustomers($condition,MAX_POST,$start);
            if($customers){
                foreach ($customers as $customer): ?>
                    <tr>
                        <th><input type="checkbox" class="checkbox" name="checkbox[]" value="<?php echo $customer->id ?>"></th>
                        <th><?php echo $customer->id?></th>
                        <th><?php echo $customer->first_name." ".$customer->last_name?></th>
                        <th><?php echo $customer->email?></th>
                        <th><?php echo $customer->user_name?></th>
                        <th><?php echo $customer->mobile?></th>
                        <th><?php echo $customer->address?></th>
                        <th><?php
                            switch ($customer->status){
                                case 0: echo '<span class="badge badge-danger">غیر فعال</span>';
                                    break;
                                case 1: echo '<span class="badge badge-success">فعال</span>';
                                    break;
                            }
                            ?></th>
                        <th>
                            <a  href="./?update_customer_id=<?php echo $customer->id; ?>" class=" text-info"><i class="fa fa-edit"></i></a>
                            <a href="?del_customer=<?php echo $customer->id; ?>" class="text-danger px-1"><i class="fa fa-trash-alt"></i></a>
                        </th>
                    </tr>
                <?php endforeach; }
            else{ echo "<div class='alert alert-warning'>متاسفانه کاربری برای نمایش وجود ندارد</div>"; } ?>
            </tbody>
        </table>
    </div>
</div>
<?php
if($customers){
$totalProduct=count(Customer::getAllCustomers($condition));
$totalSection=ceil($totalProduct/MAX_POST);?>
<div class="col-lg-12">
    <div class="paging">
        <p> صفحه ی <?php echo $section?> از <?php echo $totalSection?></p>
    <ul id="paging">
        <?php
        for ($index=1;$index<=$totalSection;$index++){
            if($index==$section)$class="class=active";
            else $class="";
            echo "<li><a href=\"./?show-product=$index\" $class>$index</a></li>";
        }
        ?>
    </ul>
</div>
    <?php }?>


