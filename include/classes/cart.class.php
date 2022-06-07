<?php
require_once "Table.class.php";
class cart extends Table{
protected $data=array(
    "id"=>0,
    "product_title"=>"",
    "product_size"=>0,
    "product_img"=>"",
    "product_price"=>0,
    "product_id"=>0,
    "user_ip"=>"",
    "qty"=>0
);
public static function getCartByIp($user_ip){
    $conn=self::connect();
    $query=("SELECT * FROM `carts` WHERE `user_ip`='$user_ip'");
    $result=$conn->query($query);
    if($result->num_rows){
        $carts=array();
        $rows=$result->fetch_all(MYSQLI_ASSOC);
        foreach($rows as $row){
            array_push($carts,new Cart($row));
        }
        $ret=$carts;
    }
    else $ret=false;
    self::disconnect($conn);
    return $ret;
}
public static function insertCart($product_id,$user_ip,$qty=1)
{
    $conn = self::connect();
    $query = ("SELECT * FROM `carts` WHERE `product_id`=$product_id AND `user_ip`='$user_ip'");
    $result = $conn->query($query);
    if ($result->num_rows) {
        return false;
    } else {
        $query = "INSERT INTO carts (product_id,user_ip,qty) VALUES ($product_id,'$user_ip',$qty)";
        if ($conn->query($query)) {
            self::disconnect($conn);
            return true;
        }
        return false;
    }
}
    public static function delCartByProductId($product_id,$user_ip){
        $conn=self::connect();
        $query=("DELETE  FROM `carts` WHERE `user_ip`='$user_ip' AND `product_id`=$product_id");
        $result=$conn->query($query);
        if($result){

            $ret= true;
        }
        else $ret=false;
        self::disconnect($conn);
        return $ret;
    }
    public static function delCartByIp($user_ip){
        $conn=self::connect();
        $query=("DELETE  FROM `carts` WHERE `user_ip`='$user_ip'");
        $result=$conn->query($query);
        if($result){
            $ret= true;
        }
        else $ret=false;
        self::disconnect($conn);
        return $ret;
    }
/*public static function getAllProducts($condition=1,$limit=0,$start=0){
    $conn=self::connect();
    if($limit>0) $limiter="LIMIT $start, $limit";
    else $limiter=" ";
    $query="SELECT * FROM `product` WHERE $condition ORDER BY `id` DESC $limiter";
    $result=$conn->query($query);
    if($result->num_rows){
        $products=array();
        $rows=$result->fetch_all(MYSQLI_ASSOC);
        foreach($rows as $row){
            array_push($products,new Product($row));
        }
        $ret=$products;
    }
    else $ret=false;
    self::disconnect($conn);
    return $ret;
}*/
}

