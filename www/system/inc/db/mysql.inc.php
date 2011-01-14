<?php
# strawberry
if (!defined("str_adm")) { header("Location: ../../../index.php"); exit; }

/**
 * @package Private
 * @access private
 */
 
### Выводим два класса подключения.
include_once databases_directory.'/mysql.class.php';

### Это новый тип подключения к базе (где-то, что-то тихо спер - называется нашел...)
$db = new sql_db($config['dbserver'], $config['dbuser'], $config['dbpassword'], $config['dbname'], false);


if (!$db->db_connect_id) {
  $sql_error_out = "mysql";
} else {

  if (!empty($config['dbcharset'])) {
    mysql_query("SET NAMES '".$config['dbcharset']."'");
    mysql_query("SET CHARACTER SET '".$config['dbcharset']."'");
  }
  if (!empty($config['cl_sql'])) {
    ### Стандартное подключение стравбери базы
    ### Что бы избавиться от предупреждений во время ошибки, класс не будет работать...
    $sql = new MySQL();
    $sql->connect($config['dbuser'], $config['dbpassword'], $config['dbserver'], $config['dbcharset']);
    $sql->selectdb($config['dbname'], $config['dbprefix']);
  }
}
?>