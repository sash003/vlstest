<?php

/**
* 
* 
* 
* @package classes
* @author = sash 
*/
class DBCore {
    
    protected $db;
    
    protected $tablePhones = 'task1';
    protected $rightPhonesTable = 'rightPhones';
    
    protected $tableOrders = 'task2';
    
    /**
    * Конструктор класса, устанавливает соединение с бд и его параметры
    * @return null
    */
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
        
       //echo $this->checkTableExists(); //
        
    }
    
    
    protected function checkTableExists($table){
        
        $exists = $this->db->query("SELECT * FROM `$table` LIMIT 1");
        if($exists){
          return true;   
        }
    }
    
    
    
    
    /**
    * @return array список телефонов
    */
    public function get_phones(){
        
        $sql = "SELECT `Phone` FROM `$this->tablePhones` WHERE `Phone` REGEXP '[0-9]+'";
        
        $result = $this->db->query($sql);
        
        if($result){
            
            // прверка существования таблицы
            $exists = $this->checkTableExists($this->rightPhonesTable);
            // создание таблицы с правилльными номерами телефонов
            $this->createTable($this->rightPhonesTable,
                               'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                Phone VARCHAR(12),
                                INDEX(Phone(12))',
                                'MyISAM');
                    
            $phones = array();
            
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {

               $phone = preg_replace('/[^\d]/', '', $row['Phone']);
               if(preg_match('/^\d{11}$/', $phone)){
                   $righthoneNum = "+$phone";
                   if(!$exists){
                       $this->_insert_($this->rightPhonesTable, 'Phone', $righthoneNum);
                   }                   
                   $phones[] = $righthoneNum;
               }
               
            }
            
            return $phones;
            
        }
    }
    
    public function get_order(){
        
        $sql = "SELECT * FROM `$this->tableOrders` order by `phone`";
        
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
    
    
    /**
    * 
    * Функция разбивает переданные в виде строк параметры $fields и $values по запятой в массивы. если количества массивов не совпадают, генерируется ушибка. Далее готовится строка подготовленного запроса, данные обезвреживаются и запрос выполняется
    * 
    * @param string $tableName
    * @param string $fields
    * @param string $values
    * 
    * @return результат выполнения, true или false
    */
    protected function _insert_($tableName, $fields, $values){
        $fields_ = explode(',', $fields);
        $fields_len = count($fields_);
        $values_ = explode(',', $values);
        $values_len = count($values_);
        if($fields_len !== $values_len){
            throw new Exception('Количество полей и значений не совпадает');
        }
        $prepare = str_repeat('?,', $fields_len);
        $prepares = substr($prepare, 0, mb_strlen($prepare)-1);
        $sql = "INSERT INTO `$tableName` ($fields) VALUES ($prepares)";
        $stmt = $this->db->prepare($sql);
         // обезвреживание данных
        for($i = 0; $i < $values_len; $i++){
            $value = trim($values_[$i]);
            if(preg_match('/^\d+$/', $value)){
                $stmt->bindValue($i+1, $value, \PDO::PARAM_INT);
            }
            else{
                 $stmt->bindValue($i+1, $value, \PDO::PARAM_STR);
                }   
        }
        return $stmt->execute();
    }
    
    
    public function _update_($fields, $values, $condition){
    
        $fields_ = explode(',', $fields);
        $fields_len = count($fields_);
        $values_ = explode(',', $values);
        $values_len = count($values_);
        if($fields_len !== $values_len){
            exit('Количество полей и значений не совпадает');
        }
        $prepare = '';
        for($i = 0; $i < $fields_len; $i++){
            $prepare  .= $fields_[$i]."=?,";
        }
        $prepares = substr($prepare, 0, mb_strlen($prepare)-1);
        $sql = "UPDATE `".$this->tableName."` SET $prepares WHERE $condition";
            $stmt = $this->db->prepare($sql);
    // обезвреживание данных
        for($i = 0; $i < $values_len; $i++){
            $value = trim($values_[$i]);
            if(preg_match('/^\d+$/', $value)){
                $stmt->bindValue($i+1, $value, PDO::PARAM_INT);
            }else{
                 $stmt->bindValue($i+1, $value, PDO::PARAM_STR);
                 }   
     }
        return $stmt->execute();
    }
    
    
    /**
    * 
    * Создаёт новую таблицу, если она не существует
    * 
    * @param string $name - имя таблицы
    * @param string $query - запрос 
    * @param string $engine - тип таблицы
    * 
    * @return true
    */
    protected function createTable($name, $query, $engine){
        $result = $this->db->query("CREATE TABLE IF NOT EXISTS $name($query) ENGINE=$engine CHARACTER SET=UTF8");
        if($result){
            return true;
        }
        return false;
    }
    
    /**
    * Закрывает соединение с бд 
    */
    function __destruct(){
        $this->db = null;
    }
    
}
