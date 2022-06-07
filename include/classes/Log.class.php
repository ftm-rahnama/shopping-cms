<?php
require_once "Table.class.php";
class Log extends Table{
protected $data=array(
    "id"=>0,
    "user_id"=>0,
    "time"=>"",
    "user_ip"=>"",
    "email"=>"",
    "user_name"=>"",
    "details"=>""
);
public static function insertLog($user_id,$user_ip,$email,$user_name,$details){
    $conn=self::connect();
    $time=time();
    $query="INSERT INTO `logs` (`user_id`, `user_ip`, `email`, `user_name`, `details`,`time`) VALUES ($user_id, '$user_ip', '$email','$user_name', '$details',$time)";
    if($conn->query($query)){
        $ret=true;
    }
    else $ret=false;
    self::disconnect($conn);
    return $ret;
}
public static function getAllLogs($condition=1,$limit=0,$start=0,$order="id"){
        $conn=self::connect();
        if($limit>0) $limiter="LIMIT $start, $limit";
        else $limiter=" ";
        $query="SELECT * FROM `logs` WHERE $condition ORDER BY $order DESC $limiter";
        $result=$conn->query($query);
        if($result->num_rows){
            $logs=array();
            $rows=$result->fetch_all(MYSQLI_ASSOC);
            foreach($rows as $row){
                array_push($logs,new Log($row));
            }
            $ret=$logs;
        }
        else $ret=false;
        self::disconnect($conn);
        return $ret;
    }
}

