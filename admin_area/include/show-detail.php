<div class="card">
    <div class="card-header ">
        <h4 class="card-title text-center ">جزئیات سفارش</h4>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered text-center">
            <thead>
            <tr>
                <th scope="col">شناسه سفارش</a></th>
                <th scope="col">نام کاربر</th>
                <th scope="col">موبایل کاربر</th>
                <th scope="col">ادرس کاربر</th>
                <th scope="col">سفارشات</th>
                <th scope="col">مبلغ کل پرداختی</th>

            </tr>
            </thead>
            <tbody>
            <?php
            if(isset($_GET["show-detail"])){
                $order_id=$_GET["show-detail"];
            }
                $pay_carts=Pay_cart::getPay_cartByOrderId($order_id);
            if($pay_carts){
                foreach ($pay_carts as $pay_cart){
                $id=$pay_cart->order_id;
                $name=$pay_cart->first_name." ".$pay_cart->last_name;
                $mobile=$pay_cart->mobile;
                $address=$pay_cart->address;
                $products[]=Product::getProductById($pay_cart->product_id);
                }
                ?>
                    <tr>
                        <th><?php echo $id?></th>
                        <th><?php echo  $name?></th>
                        <th><?php echo  $mobile?></th>
                        <th><?php echo $address?></th>
                        <th><div class="row justify-content-center"><div class="col-lg-7">
                            <?php
                            $priceAll=0;
                            foreach ($products as $product){?>
                                    <span><?php echo $product->product_title?><img src="<?php echo "../".$product->product_img?>" alt="" class="my-3"> قیمت: <?php echo $product->product_price?> تومان</span>
                                    <hr>
                           <?php $priceAll+=$product->product_price;
                           }?></span></div></div></th>
                        <th><?php echo $priceAll." تومان "?></th>
                    </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
</div>
