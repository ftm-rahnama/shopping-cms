<?php
require_once "Table.class.php";
class Customer extends Table{
protected $data=array(
    "id"=>0,
    "first_name"=>"",
    "last_name"=>"",
    "user_name"=>"",
    "email"=>"",
    "mobile"=>"",
    "password"=>"",
    "address"=>"",
    "activation_key"=>"",
   "reset_password_key"=>"",
    "status"=>0,
);
    public static function isCustomerExist($userName,$password){
        $conn=self::connect();
        $query=("SELECT * FROM `customers`  WHERE `user_name` ='$userName' AND `password`='$password'");
        $result=$conn->query($query);
        if($result->num_rows){
            $row=$result->fetch_assoc();
            $ret=new Customer($row);
        }
        else $ret=false;
        self::disconnect($conn);
        return $ret;
    }
    public static function insertCustomer($first_name,$last_name,$user_name,$email,$password,$phone,$address,$activationKey){
    $conn=self::connect();
    $query="INSERT INTO `customers` (`first_name`, `last_name`, `email`, `user_name`, `password`, `mobile`, `address`, `activation_key`) VALUES ('$first_name', '$last_name', '$email', '$user_name', '$password', '$phone', '$address', '$activationKey')";
    if($conn->query($query)){
        $ret=true;
    }
    else $ret=false;
    self::disconnect($conn);
    return $ret;
}
    public static function isActivationKey($activationKey){
       $conn=self::connect();
        $query=("SELECT `activation_key` FROM customers WHERE `activation_key`='$activationKey'");
       if($conn->query($query)){
           $ret=true;
       }
       else $ret=false;
       self::disconnect($conn);
       return $ret;
    }
    public static function activeUserAcc($activationKey){
       $conn=self::connect();
        $query=("UPDATE `customers` SET `status` =1 WHERE `activation_key`='$activationKey'");
       if($conn->query($query)){
           $ret=true;
       }
       else $ret=false;
       self::disconnect($conn);
       return $ret;
   }
    public static function getAllCustomers($condition=1,$limit=0,$start=0){
        $conn=self::connect();
        if($limit>0) $limiter="LIMIT $start, $limit";
        else $limiter=" ";
            $query="SELECT * FROM `customers` WHERE $condition ORDER BY `id` DESC $limiter";
        $result=$conn->query($query);
        if($result->num_rows){
            $customers=array();
            $rows=$result->fetch_all(MYSQLI_ASSOC);
            foreach($rows as $row){
                array_push($customers,new Customer($row));
            }
            $ret=$customers;
        }
        else $ret=false;
        self::disconnect($conn);
        return $ret;
    }
    public static function getCustomerById($id){
        $conn=self::connect();
        $query=("SELECT * FROM `customers` WHERE `id`=$id");
        $result=$conn->query($query);
        if($result->num_rows){
            $row=$result->fetch_assoc();
            $ret=new Customer($row);
        }
        else $ret=false;
        self::disconnect($conn);
        return $ret;
    }
    public static function delAllCustomers($check,$count){
        $conn=self::connect();
        for ($i=0;$i<$count;$i++){
            $userCheckId=$check[$i];
            $query=("DELETE FROM `customers` WHERE `id`=$userCheckId");
            if($conn->query($query)){
                $ret=true;
            }
            else $ret=false;
        }
        self::disconnect($conn);
        return $ret;
    }
    public static function delSelectCustomer($id){
        $conn=self::connect();

        $query=("DELETE FROM `customers` WHERE `id`=$id");
        if($conn->query($query)){
            $ret=true;
        }
        else $ret=false;
        self::disconnect($conn);
        return $ret;
    }
    public static function updateCustomer($id,$first_name,$last_name,$user_name,$email,$password,$phone,$address){
        $conn=self::connect();
        $query="UPDATE `customers` SET `first_name`='$first_name',`last_name`='$last_name',`user_name`='$user_name',`email`='$email',`password`='$password' ,`mobile`='$phone', `address`='$address' WHERE `id`=$id";
        if($conn->query($query)){
            self::disconnect($conn);
            return true;
        }
        return false;
    }
}

