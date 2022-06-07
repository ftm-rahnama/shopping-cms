<?php
require_once "Table.class.php";
class Brand extends Table {
    protected $data=array(
        "id"=>0,
        "brand_title"=>""
     );
    public static function getAllBrands($limit=0,$start=0){
        $conn=self::connect();
        if ($limit > 0) $limiter = "LIMIT $start, $limit";
        else $limiter = " ";
        $query = "SELECT * FROM `brands` ORDER BY `id` DESC $limiter";
        $result=$conn->query($query);
        if($result->num_rows){
            $brands=[];
            $rows=$result->fetch_all(MYSQLI_ASSOC) ;
            foreach ($rows as $row){
                $brands[]=new Brand($row);
            }
            $ret=$brands;
        }
        else
            $ret=false;
        self::disconnect($conn);
        return $ret;
    }
    public static function getBrandyById($id){
        $conn=self::connect();
        $query="SELECT * FROM `brands` WHERE `id`=$id";
        $result=$conn->query($query);
        if($result->num_rows)
            $ret=new Brand($result->fetch_assoc());
        else
            $ret=false;
        self::disconnect($conn);
        return $ret;

    }
    public static function delSelectBrand($id){
        $conn=self::connect();
        $query=("DELETE FROM `brands` WHERE `id`=$id");
        if($conn->query($query)){
            $query = ("DELETE FROM `product` WHERE `product_brand`=$id");
            if ($conn->query($query)) {
                $ret = true;
            }
        }
        else $ret=false;
        self::disconnect($conn);
        return $ret;
    }
    public static function delAllBrands($check,$count)
    {
        $conn = self::connect();
        for ($i = 0; $i < $count; $i++) {
            $userCheckId = $check[$i];
            $query = ("DELETE FROM `brands` WHERE `id`=$userCheckId");
            if ($conn->query($query)) {
                $query = ("DELETE FROM `product` WHERE `product_brand`=$userCheckId");
                if ($conn->query($query)) {
                    $ret = true;
                }
            }
            else $ret=false;
        }
        self::disconnect($conn);
        return $ret;
    }
    public static function insertBrand($brand_title){
        $conn=self::connect();
        $query="INSERT INTO `brands`(brand_title) VALUES ('$brand_title')";
        if($conn->query($query)){
            self::disconnect($conn);
            return true;
        }
        return false;
    }
    public static function updateBrand($id,$brand_title){
        $conn=self::connect();
        $query="UPDATE `brands` SET `brand_title`='$brand_title' WHERE `id`=$id";
        if($conn->query($query)){
            self::disconnect($conn);
            return true;
        }
        return false;
    }

}