<?php
require_once "Table.class.php";
class Product extends Table{
protected $data=array(
    "id"=>0,
    "product_cat"=>0,
    "product_brand"=>0,
    "product_title"=>"",
    "product_price"=>0,
    "product_size"=>0,
    "product_color"=>"",
    "product_desc"=>"",
    "product_img"=>"",
    "allow_comment"=>0,
);
public static function getProductById($id){
    $conn=self::connect();
    $query=("SELECT * FROM `product` WHERE `id`=$id");
    $result=$conn->query($query);
    if($result->num_rows){
        $row=$result->fetch_assoc();
        $ret=new Product($row);
    }
    else $ret=false;
    self::disconnect($conn);
    return $ret;
}
public static function insertProduct($product_cat,$product_title,$product_brand,$product_price,$product_size,$product_desc,$product_img,$allow_comment){
    $conn=self::connect();

    $query="INSERT INTO product (product_cat,product_brand,product_title,product_price,product_size,product_desc,product_img,allow_comment) VALUES ($product_cat,$product_brand,N'$product_title',$product_price,$product_size,N'$product_desc',N'$product_img',$allow_comment)";
    if($conn->query($query)){
        self::disconnect($conn);
        return true;
    }
    return false;
}
public static function getAllProducts($condition=1,$limit=0,$start=0,$order="id"){
    $conn=self::connect();
    if($limit>0) $limiter="LIMIT $start, $limit";
    else $limiter=" ";
    $query="SELECT * FROM `product` WHERE $condition ORDER BY $order DESC $limiter";
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
}
public static function delAllProducts($check,$count){
    $conn=self::connect();
    for ($i=0;$i<$count;$i++){
        $userCheckId=$check[$i];
        $query=("DELETE FROM `product` WHERE `id`=$userCheckId");
        if($conn->query($query)){
            $ret=true;
        }
        else $ret=false;
    }
    self::disconnect($conn);
    return $ret;
}
public static function delSelectProduct($id){
    $conn=self::connect();
   
        $query=("DELETE FROM `product` WHERE `id`=$id");
        if($conn->query($query)){
            $ret=true;
        }
        else $ret=false;
    self::disconnect($conn);
    return $ret;
}
public static function updateProduct($id,$product_cat,$product_title,$product_brand,$product_price,$product_size,$product_desc,$product_img,$allow_comment){
        $conn=self::connect();
        $query="UPDATE product SET `product_cat`=$product_cat , `product_brand`=$product_brand , `product_title`=N'$product_title' , `product_price`=$product_price , `product_size`=$product_size ,`product_desc`=N'$product_desc', `product_img`=N'$product_img',`allow_comment`=$allow_comment WHERE `id`=$id";
        if($conn->query($query)){
            self::disconnect($conn);
            return true;
        }
        return false;
    }
}

