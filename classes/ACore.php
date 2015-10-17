<?php

/**
* @package classes
*/
class ACore {
    
    protected $db;
    protected $tablePhones = 'task1';
    
    public function __construct(){
        
       require_once ROOT . 'config.php';
       
 	   try{
			$this->db=new PDO("mysql: host=" . $arrConfig['dbhost'] . "; dbname=" . $arrConfig['dbname'], $arrConfig['dbuser'], $arrConfig['dbpass']);
		} catch  (PDOException $err) { 
    echo 'Ошибка соединения ' . $err->getMessage(). '<br> 
          в файле '.$err->getFile().", строка ".$err->getLine(); 
    exit; 
		}
		$this->db->exec('SET NAMES ' . $arrConfig['dbcharset'] . ' COLLATE ' . $arrConfig['dbcollation']);
    }
    
    public function get_phones(){
        
        $sql = "SELECT `Phone` FROM `$this->tablePhones` WHERE `Phone` REGEXP '[0-9]+'";
        
        $result = $this->db->query($sql);
        
        if($result){
            
            $phones = array();
            
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {

               $phone = preg_replace('/[^\d]/', '', $row['Phone']);
               if(preg_match('/^\d{11}$/', $phone)){
                   $phones[] = "+$phone";
               }
               
            }
            
            return $phones;
            
        }
    }
    
    public function get_order(){
        
        $sql = "SELECT * FROM `task2` order by `phone`";
        
        $result = $this->db->query($sql);
        
        $orders  = array();
        
        if($result){
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {

               $orders[] = $row;
               
            }
            
        $orders = array_reverse($orders);
        
        return $orders;
            
        }
        
    }
    
    function __destruct(){
        $this->db = null;
    }
    
}
