<?php
class Table{
    protected $data=array();
    public function __construct($data){
        foreach ($data as $key=>$value){
            if(array_key_exists($key,$this->data)){
                if(is_numeric($value))$this->data[$key]=(int)$value;
                else $this->data[$key]=$value;
            }
        }
    }
    public function __get($property){
        if(array_key_exists($property,$this->data))
            return $this->data[$property];
         else die("Invalid property");
    }
    protected static function connect(){
        $conn=new mysqli(HOST_NAME,DB_USER,DB_PASS,DB_NAME);
        $conn->set_charset("utf8");
        return $conn;
    }
    protected static function disconnect($conn){
        unset($conn);
}

}
