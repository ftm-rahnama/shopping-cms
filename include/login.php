<script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
<?php

if($_GET["action"]=="login"){
    if(isset($_POST["btn-login"])) {
        $user = Customer::isCustomerExist($_POST["userName"], $_POST["password"]);
        if ($user) {
            $details = "ورود";
           $log=Log::insertLog($user->id, $_SERVER["REMOTE_ADDR"], $user->email, $user->user_name, $details);
           $carts=Cart::getCartByIp($_SERVER["REMOTE_ADDR"]);
            $userSession = array(
                "signInKey" => true,
                "userEmail" => $user->email,
                "id" => $user->id,
                "userName" => $user->user_name,
                "firstName" => $user->first_name,
                "lastName" => $user->last_name,
                "fullName" => $user->first_name . " " . $user->last_name,
                "status" => $user->status,
                "expireTime" => time() + 60,
            );
            $_SESSION["userInfo"] = $userSession;
            if($log){
                echo '<script>window.location.href = "http://localhost/backend/shopping/index.php?action=pay";</script>';}

        }
    }
     ?>
     <div class="container">
         <div class="row justify-content-center">
             <div class="col-md-7">
                 <div class="card">
                     <div class="card-header">
                         <h5 class="card-title text-center">  ورود به سایت </h5>
                     </div>
                     <div class="card-body">
                         <form method="post">
                             <div class="form-group">
                                 <label for="">نام کاربری</label>
                                 <input type="text" class="form-control" id="" name="userName" placeholder="نام کاربری" ">
                             </div>
                             <div class="form-group">
                                 <label for="">رمز عبور</label>
                                 <input type="password" name="password" class="form-control" id="" placeholder="رمز عبور">
                             </div>
                             <div class="form-group">
                                 <i class="fa fa-pencil-alt text-danger"></i>
                                 <a href="./?action=signup" class="text-dark"><small class="hover">تا به حال ثبت نام نکرده ام</small></a>
                             </div>

                             <div class="form-group">
                                 <input type="submit" class="btn btn-dark btn-block" name="btn-login" value="ورود">
                             </div>
                         </form>
                     </div>
                 </div>
             </div>
             

         </div>
     </div>
 <?php }
if ($_GET["action"]=="signup"){
if(isset($_POST["submit"])) {
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
    $currentTime=microTime(true);
    $currentTime=str_replace(".","",$currentTime);
    $token=hashData($_POST["userName"],"md5");
    $activationKey=$currentTime.$token;
    $query = Customer::insertCustomer($_POST["firstName"], $_POST["lastName"], $_POST["userName"], $_POST["email"], $_POST["password"], $_POST["mobile"], $_POST["address"],$activationKey);
    if ($query){
    $email_subject="فعال سازی ایمیل کاربری";
    $email_body=' <section style="width: 50%;min-height: 400px;box-sizing: border-box;background-color: #f5f5f5;border: 1px solid rgba(52,52,52,0.30);padding: 50px 0 ; box-shadow: 0 0 7px 3px rgba(70,70,70,0.20) ;margin: 30px auto">
        <h3 style="color: #666666; text-align: center;line-height: 36px">لینک فعالسازی حساب کاربری</h3>
        <hr color="silver" size="1" style="width: 70%" />
        <div style="width: 100%;height: 100%;padding: 30px;box-sizing: border-box;text-align: center">
            <a href="http://localhost/backend/shopping?activationKey='.$activationKey.'" style="display: block;font-size: 20px;text-decoration: none;color: tomato;">برای فعالسازی حساب کاربری خود روی لینک کلیک کنید</a>
            <p style="margin: 50px;line-height: 35px;font-size: 14px">درصورت ارسال اشتباه ایمیل لطفا آنرا نادیده بگیرید و پاک کنید</p>
        </div>
    </section> ';
    sendMail($email_subject,$email_body,$_POST["email"]);
    echo '<script>
            swal({
                title: "باتشکر",
                text: "اطلاعات با موفقیت ثبت                      گردید",
                icon: "success",
                button: "بستن",
                timer: 3000
            });
    </script>';
}}}?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-9">
            <div class="card">
                <div class="card-header"><h5 class="card-title">ثبت نام</h5></div>
                <div class="card-body">
                    <form method="post">
                        <div class="form-group">
                            <label for="">نام</label>
                            <input type="text" class="form-control" id="" name="firstName" placeholder="نام">

                        </div>
                        <div class="form-group">
                            <label for="">نام خانوادگی</label>
                            <input type="text" class="form-control" id="" name="lastName" placeholder="نام خانوادگی">
                        </div>
                        <div class="form-group">
                            <label for="">نام کاربری</label>
                            <input type="text" name="userName" class="form-control" id="" placeholder="نام کاربری">
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
                        <input type="submit" class="btn btn-dark btn-block" name="submit" value="ثبت نام">
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<?php }
if($_GET["action"]=="pay"){
    if(isset($_POST["pay"]) and $_SESSION["userInfo"]["cartPrice"]>0) {

        $order=Order::insertOrder($_SESSION["userInfo"]["cartPrice"],$_SESSION["userInfo"]["userEmail"]);
        $carts=Cart::getCartByIp($_SERVER["REMOTE_ADDR"]);
        foreach ($carts as $cart){
            $pay_cart=Pay_cart::insertPay_cart($order->id,$cart->product_id,$_SESSION["userInfo"]["id"]);
        }
        $delcart=cart::delCartByIp($_SERVER["REMOTE_ADDR"]);
         if($delcart){
                echo '<script>window.location.href = "http://localhost/backend/shopping/index.php?payed";</script>';

        }
    }?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-center">  پرداخت </h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="form-group">
                                <input type="submit" class="btn btn-dark btn-block" name="pay" value="پرداخت">
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
<?php } ?>