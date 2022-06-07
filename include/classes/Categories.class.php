<?php
require_once "Table.class.php";
class Category extends Table {
    protected $data=array(
        "id"=>0,
        "category_name"=>"",
        "parent_id"=>0
     );

    public static function getAllCategories($condition=1,$limit=0,$start=0,$order="id"){
        $conn=self::connect();
        if ($limit > 0) $limiter = "LIMIT $start, $limit";
        else $limiter = " ";
        $query = "SELECT * FROM `categories` WHERE $condition ORDER BY $order DESC $limiter";
        $result=$conn->query($query);
        if($result->num_rows){
            $cats=[];
           $rows=$result->fetch_all(MYSQLI_ASSOC) ;
            foreach ($rows as $row){
                $cats[]=new Category($row);
            }
            $ret=$cats;
        }
        else
            $ret=false;
        self::disconnect($conn);
        return $ret;
    }
    public static function getCategoryById($id){
        $conn=self::connect();
        $query="SELECT * FROM `categories` WHERE `id`=$id";
        $result=$conn->query($query);
        if($result->num_rows)
            $ret=new Category($result->fetch_assoc());
        else
            $ret=false;
        self::disconnect($conn);
        return $ret;

    }
    public static function getCategoriesByParentId($parentId){
        $conn=self::connect();
        $query="SELECT * FROM `categories` WHERE `parent_id`=$parentId ORDER BY `id`";
        $result=$conn->query($query);
        if($result->num_rows) {
            $cat = [];
            foreach ($result->fetch_all(MYSQLI_ASSOC) as $row)
                array_push($cat, new Category($row));
            $ret = $cat;
        }
        else
            $ret=false;
        self::disconnect($conn);
        return $ret;
    }
    public static function delSelectCat($id){
        $conn=self::connect();
        $query=("DELETE FROM `categories` WHERE `id`=$id");
        if($conn->query($query)) {
            $query = ("DELETE FROM `product` WHERE `product_cat`=$id");
            if ($conn->query($query)) {
                $ret = true;
            }
        }
        else $ret=false;
        self::disconnect($conn);
        return $ret;
    }
    public static function delAllCats($check,$count){
        $conn=self::connect();
        for ($i=0;$i<$count;$i++){
            $userCheckId=$check[$i];
            $query=("DELETE FROM `categories` WHERE `id`=$userCheckId");
            if($conn->query($query)){
                $query = ("DELETE FROM `product` WHERE `product_cat`=$userCheckId");
                if ($conn->query($query)) {
                    $ret = true;
                }
            }
            else $ret=false;
        }
        self::disconnect($conn);
        return $ret;
    }
    public static function insertCat($category_name,$parent_id){
        $conn=self::connect();
        $query="INSERT INTO `categories`(category_name,parent_id) VALUES ('$category_name',$parent_id)";
        if($conn->query($query)){
            self::disconnect($conn);
            return true;
        }
        return false;
    }
    public static function updateCat($id,$parent_id,$category_name){
        $conn=self::connect();
        $query="UPDATE `categories` SET `parent_id`=$parent_id ,`category_name`='$category_name' WHERE `id`=$id";
        if($conn->query($query)){
            self::disconnect($conn);
            return true;
        }
        return false;
    }


}