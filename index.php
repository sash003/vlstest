<?php

/**
* Ну возьмите меня на работу, ну пожалуйста..
* Я изучу всё что надо (Laravel уже изучается) и буду очень стараться, честно.
*  Я уже устал искать работу.. 
*/

error_reporting(E_ALL);
ini_set('display_errors', 1);

  header ("Content-Type:text/html;charset=UTF8", false);
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s")." GMT");
  header("Cache-Control: no-cache, must-revalidate");
  header("Cache-Control: post-check=0,pre-check=0", false);
  header("Cache-Control: max-age=0", false);
  header("Pragma: no-cache");
  mb_internal_encoding('utf-8');

define('ROOT', __DIR__ . '/');

function autoload($class){
    require_once ROOT . "classes/$class.php";
}
spl_autoload_register('autoload');

$obj = new ACore();

?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  
  <style>
      input[type="text"]{
          width: 333px;
          margin: 5px;
          padding: 5px;
      }
      input[type="submit"]{
          padding: 11px;
      }
  </style>
  
</head>
<body style="padding: 33px;">

<table border="1">
<tr><td>Телефоны</td></tr>
<?php 
    $phones = $obj->get_phones();
    foreach($phones as $phone){
        echo "<tr><td>$phone</td></tr>";
    }
?>
</table>

<br><br>


Сортировка заказов
<table border="1">
<tr>
    <?php
    
     $orders = $obj->get_order();
     foreach($orders as $order){
         echo "<tr>
               <td>" . $order['id'] . "</td>
               <td>" . $order['Name'] . "</td>
               <td>" . $order['Date'] . "</td>
               <td>" . $order['Phone'] . "</td>
               </tr>";
     }
     
     ?>
</tr>
</table>

<br><br>

<span class="app_show" style="border: 2px solid green; border-radius: 5px; cursor: pointer;">Показать карту</span>
<form>
    <input type="text" id="first"/> Точка 1<br>
    <input type="text" id="second"/> Точка 2<br>
    Маршрут по <input style="width: 111px;" type="text" id="city" value="Москва"/><br>
    <input type="submit" id="show" value="Показать маршрут"/>
</form>



<div id="YMapsID" style="width:600px;height:400px; display: none; float: left;"></div>
<div id="route" style="widrh: 400px; float: left; padding-left: 11px;"></div>

<script src="https://api-maps.yandex.ru/1.1/index.xml" type="text/javascript"></script>
<script src="js/script.js" type="text/javascript"></script>

</body>
</html>

