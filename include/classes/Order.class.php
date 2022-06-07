<?php
require_once "Table.class.php";
class Order extends Table{
protected $data=array(
    "id"=>0,
    "order_time"=>"",
    "order_total_price"=>0,
    "order_verified"=>"",
    "customer_email"=>"",
);
public static function insertOrder($order_total_price,$customer_email)
{
    $conn = self::connect();
    $order_time = time();
    $query = "INSERT INTO `orders` (order_total_price,customer_email ,order_time) VALUES ($order_total_price,'$customer_email',$order_time)";
    $result = $conn->query($query);
    if ($result) {
        $query = ("SELECT * FROM `orders` WHERE `customer_email`='$customer_email' AND `order_time`=$order_time");
        $result = $conn->query($query);
        if ($result->num_rows) {
            $row=$result->fetch_assoc();
            $ret=new Order($row);
        }
    }
    else $ret = false;
    self::disconnect($conn);
    return $ret;
}
    public static function verifiedOrder($id){
        $conn=self::connect();
        $query=("UPDATE `orders` SET `order_verified` ='true' WHERE `id`=$id");
        if($conn->query($query)){
            $ret=true;
        }
        else $ret=false;
        self::disconnect($conn);
        return $ret;
    }
    public static function getAllOrders($condition=1,$limit=0,$start=0,$order="id"){
        $conn=self::connect();
        if($limit>0) $limiter="LIMIT $start, $limit";
        else $limiter=" ";
        $query="SELECT * FROM `orders` WHERE $condition ORDER BY $order DESC $limiter";
        $result=$conn->query($query);
        if($result->num_rows){
            $orders=array();
            $rows=$result->fetch_all(MYSQLI_ASSOC);
            foreach($rows as $row){
                array_push($orders,new Order($row));
            }
            $ret=$orders;
        }
        else $ret=false;
        self::disconnect($conn);
        return $ret;
    }

}

