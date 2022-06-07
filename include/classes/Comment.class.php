<?php
require_once "Table.class.php";
class Comment extends Table{
protected $data=array(
    "id"=>0,
    "full_name"=>"",
    "email"=>"",
    "website"=>"",
    "comment"=>"",
    "comment_time"=>"",
    "user_ip"=>"",
    "product_id"=>0,
    "parent_id"=>0,
);
public static function getAllcomments($condition=1,$limit=0,$start=0,$order="id"){
    $conn=self::connect();
    if($limit>0) $limiter="LIMIT $start, $limit";
    else $limiter=" ";
    $query="SELECT * FROM `comments` WHERE $condition ORDER BY $order DESC $limiter";
     $result=$conn->query($query);
     if($result->num_rows){
         $comments=array();
         $rows=$result->fetch_all(MYSQLI_ASSOC);
         foreach($rows as $row){
             array_push($comments,new Comment($row));
         }
         $ret=$comments;
     }
     else $ret=false;
     self::disconnect($conn);
     return $ret;
}
public static function getCommentsByPost_id($product_id){
    $conn=self::connect();
    $query=("SELECT * FROM `comments` WHERE `product_id`=$product_id ORDER BY `comment_time` DESC");
    $result=$conn->query($query);
    if($result->num_rows){
        $comments=array();
        $rows=$result->fetch_all(MYSQLI_ASSOC);
        foreach ($rows as $row){
            array_push($comments,new Comment($row));
        }
        $ret=$comments;
    }
    else $ret=false;
    self::disconnect($conn);
    return $ret;
}
public static function getCommentById($id){
    $conn=self::connect();
    $query=("SELECT * FROM `comments` WHERE `id`=$id");
    $result=$conn->query($query);
    if($result->num_rows){
        $row=$result->fetch_assoc();
        $ret=new Comment($row);
    }
    else $ret=false;
    self::disconnect($conn);
    return $ret;
}
public static function insertComment($full_name,$email,$comment,$product_id,$parent_id,$user_ip){
    $ret=true;
    $conn=self::connect();
    $comment_time=time();
    $query="INSERT INTO comments(full_name,email,comment,product_id,parent_id,user_ip,comment_time) VALUES ('$full_name','$email','$comment',$product_id,$parent_id,'$user_ip',$comment_time)";
    if($conn->query($query)){
        self::disconnect($conn);
        return true;
    }
    return false;
}
    public static function getCommentByParentId($parentId){
        $conn=self::connect();
        $query="SELECT * FROM `comments` WHERE `parent_id`=$parentId ORDER BY `id`";
        $result=$conn->query($query);
        if($result->num_rows) {
            $comment = [];
            foreach ($result->fetch_all(MYSQLI_ASSOC) as $row)
                array_push($comment, new Comment($row));
            $ret = $comment;
        }
        else
            $ret=false;
        self::disconnect($conn);
        return $ret;
    }
    public static function delSelectComment($id){
        $conn=self::connect();
        $query=("DELETE FROM `comments` WHERE `id`=$id");
        if($conn->query($query)){
            $ret=true;
        }
        else $ret=false;
        self::disconnect($conn);
        return $ret;
    }
    public static function delAllcomments($check,$count){
        $conn=self::connect();
        for ($i=0;$i<$count;$i++){
            $userCheckId=$check[$i];
            $query=("DELETE FROM `comments` WHERE `id`=$userCheckId");
            if($conn->query($query)){
                $ret=true;
            }
            else $ret=false;
        }
        self::disconnect($conn);
        return $ret;
    }

}
