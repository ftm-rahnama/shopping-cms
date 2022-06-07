<?php
require_once "Table.class.php";
class Pay_cart extends Table{
protected $data=array(
    "id"=>0,
    "order_id"=>0,
    "product_id"=>0,
    "user_id"=>0,
    "first_name"=>"",
    "last_name"=>"",
    "address"=>"",
    "mobile"=>"",
    "qty"=>0
);
public static function getPay_cartByOrderId($order_id){
    $conn=self::connect();
    $query=("SELECT pay_cart.*,`first_name`,`last_name`,`address`,`mobile` FROM `pay_cart`,`customers`  WHERE pay_cart.user_id=customers.id AND pay_cart.order_id=$order_id");
    $result=$conn->query($query);
    if($result->num_rows){
        $pay_carts=array();
        $rows=$result->fetch_all(MYSQLI_ASSOC);
        foreach($rows as $row){
            array_push($pay_carts,new Pay_cart($row));
        }
        $ret=$pay_carts;
    }
    else $ret=false;
    self::disconnect($conn);
    return $ret;
}
public static function insertPay_cart($order_id,$product_id,$user_id)
{
    $qty=1;
    $conn = self::connect();
        $query = "INSERT INTO `pay_cart` (order_id,product_id,user_id,qty) VALUES ($order_id,$product_id,$user_id,$qty)";
       
        if ($conn->query($query)) {
            self::disconnect($conn);
            return true;
        }
        return false;
    
}


}

