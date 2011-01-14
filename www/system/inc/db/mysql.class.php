<?php
# strawberry
if (!defined("str_adm")) { header("Location: ../../../index.php"); exit; }
define("_SEC", t('секунд'));

/**
 * Parse txtSQL query to MySQL
 *
 * Все комментарии на английском, это copy-paste комментариев из класса txtSQL.
 *
 * Обратите внимание, что всё работает на соплях и писалось под нужды Strawberry,
 * если какой-то функции нет или она плохо работает значит мне эта функция не нужна.
 * Не нужна в данный момент и моменты до него :).
 *
 * @package MySQL
 * @access public
 */
class MySQL {

	/**
	 * Connects a user to the database
	 * @param string $username The username of the user
	 * @param string $password The corressponding password of the user
	 * @param string $server The database server (usually "localhost")
	 * @return void
	 * @access public
	 */
	function connect($username = 'root', $password = '', $server = 'localhost', $charset = ''){
		$this->username = $username;
		$this->password = $password;
		$this->server   = $server;
		$this->link     = mysql_connect($this->server, $this->username, $this->password);
	

		if (!empty($charset)){
			mysql_query('set names '.$charset.';', $this->link);
			mysql_query("SET CHARACTER SET '".$charset."' ");		
		}
	}

	/**
	 * Disconnects a user from the database
	 * @return void
	 * @access public
	 */
	function disconnect(){
		mysql_close($this->link);
	}

	/**
	 * Turns strict property of MySQL off/on
	 * @param bool $error The value of the strict property
	 * @return void
	 * @access public
	 */
	function strict($error = false){
	}

	/**
	 * To select a database for mysql to use as a default
	 * @param string $database The name of the database that is to be selected
	 * @param string $prefix The table prefix (e.g.: straw_)
	 * @return void
	 * @access public
	 */
	function selectdb($database, $prefix = ''){
		$this->database = $database;
		$this->prefix   = $prefix;
		mysql_select_db($this->database, $this->link);
	}

	/**
	 * To return the last error that occurred
	 * @return string $error The last error
	 * @access public
	 */
	function get_last_error(){
		return mysql_error($this->link);
	}

	/**
	 * To print the last error that occurred
	 * @return void
	 * @access public
	 */
	function last_error(){
		echo '<pre>'.mysql_error($this->link).'</pre>';
	}

	/**
	 * Alters a database by working with its columns
	 * @param mixed $arg The arguments in form of "[$key] => $value"
	 *                         where $key can be 'db', 'table', 'action',
	 *                         'name', and 'values'
	 * @return void
	 * @access public
	 */
	function altertable($arg){
       	$query = 'alter table '.$this->prefix.$arg['table']."\n";

        if ($arg['action'] == 'insert'){
        	$query .= 'add '.$arg['name'].' '.$this->_values($arg);
        } elseif ($arg['action'] == 'rename table'){
        	$query .= 'rename '.$arg['name'];
        } elseif ($arg['action'] == 'rename col' and !$arg['values']['permanent']){
        	$result = array();
	        $list   = mysql_query('select * from '.$this->prefix.$arg['table'], $this->link);
	        for ($i = 0; $i < mysql_num_fields($list); $i++){
	        	if (mysql_field_name($list, $i) == $arg['name']){
	            	$result[] = mysql_field_type($list, $i).' '.mysql_field_flags($list, $i);
	            }
	        }

        	$query .= 'change `'.$arg['name'].'` `'.$arg['values']['name'].'` '.join('', $result)."\n";
        } elseif ($arg['action'] == 'modify' and !$arg['values']['permanent']){
        	$result = array();
	        $list   = mysql_query('select * from `'.$this->prefix.$arg['table'].'`', $this->link);
	        for ($i = 0; $i < mysql_num_fields($list); $i++){
	        	if (mysql_field_name($list, $i) == $arg['name']){
	        		$result[] = str_replace('string', 'varchar(255)', str_replace('bool', 'tinyint(1)', mysql_field_type($list, $i))).' '.mysql_field_flags($list, $i);
	            }
	        }

        	$query .= 'change `'.$arg['name'].'` `'.$arg['name'].'` '.(!$arg['values']['type'] ? join('', $result).' ' : '').$this->_values($arg).' not null'."\n";
        } elseif ($arg['action'] == 'addkey'){
        	$query .= 'add primary key('.$arg['name'].')'."\n";
        } elseif ($arg['action'] == 'drop'){
        	if ($arg['name']){
        		$query .= 'drop '.$arg['name']."\n";
        	} else {
        		$query = 'drop table '.$this->prefix.$arg['table']."\n";
        	}
        } elseif ($arg['values']['permanent']){
        	$query .= 'add unique('.$arg['name'].')'."\n";
        }

		return mysql_query($query, $this->link);
	}

	/**
	 * Returns an array containing a list of the columns, and their
	 * corresponding properties
	 * @param mixed arg The arguments that are passed to the MySQL as an array.
	 * @return mixed cols An array populated with details on the fields in a table
	 * @access private
	 */
	function describe($arg){
		if (!$arg['db']){
			$arg['db']    = $this->database;
			$arg['table'] = $this->prefix.$arg['table'];
		}

        $query = mysql_db_query($arg['db'], 'show fields from '.$arg['table'], $this->link);
	    while ($row = mysql_fetch_assoc($query)){
	    	$row = array_change_key_case($row);

			if (substr($row['key'], 0, 3) == 'PRI'){
            	$result['primary'] = $row['field'];
            }

			if (substr($row['key'], 0, 3) == 'UNI'){
            	$result['permanent'] = $row['field'];
            }

            if (substr($row['type'], 0, 4) == 'enum'){
            	$row['type'] = preg_replace('/^enum\((.*?)\)$/i', '\\1', $row['type']);
            	preg_match_all('/\'(.*?)\'/i', $row['type'], $matches);
            	$row['type']     = 'enum';
            	$row['enum_val'] = $matches[1];
            }

            $tables[] = $row;
	    }

        foreach ($tables as $row){
			$row['type'] = str_replace('varchar(255)', 'string', $row['type']);
			$row['type'] = str_replace('tinyint(1)', 'bool', $row['type']);
			$row['type'] = str_replace('int(11)', 'int', $row['type']);
	    	$result[$row['field']] = array(
	    	'permanent'      => (substr($row['key'], 0, 3) == 'PRI' ? 1 : 0),
	    	'auto_increment' => ($row['extra'] == 'auto_increment' ? 'auto_increment' : 0),
	    	'max'            => 0,
	    	'type'           => $row['type'],
	    	'default'        => $row['default'],
	    	'autocount'      => ($this->last_insert_id($arg['table'], $arg['db']) > 0 ? $this->last_insert_id($arg['table'], $arg['db']) : 0),
	    	'enum_val'       => $row['enum_val'],
	    	);
	    }

		return $result;
	}

	/**
	 * Returns a list containing the current valid MySQL databases
	 * @return mixed $databases A list containing the databases
	 * @access public
	 */
	function showdbs($arg){
	    $list = mysql_list_dbs($this->link);
	    for ($i = 0; $i < mysql_num_rows($list); $i++){
	        $result[] = mysql_db_name($list, $i);
	    }

	    return $result;
	}

	/**
	 * Creates a new database
	 * @param mixed $arg The arguments in form of "[$key] => $value"
	 *                         where $key can be 'db'
	 * @return void
	 * @access public
	 */
	function createdb($arg){
		mysql_create_db($arg['db'], $this->link);
	}

	/**
	 * Drops a database
	 * @param mixed $arg The arguments in form of "[$key] => $value"
	 *                         where $key can be 'db'
	 * @return void
	 * @access public
	 */
	function dropdb($arg){
		mysql_drop_db($arg['db'], $this->link);
	}

	/**
	 * Creates a new table with the given criteria inside a database
	 * @param mixed $arg The arguments in form of "[$key] => $value"
	 *                         where $key can be 'db', 'table', 'columns'
	 * @return int $deleted The number of rows deleted
	 * @access public
	 */
	function createtable($arg){
		foreach ($arg['columns'] as $column => $arg['values']){
			$query[] = '`'.$column.'` '.$this->_values($arg);

			foreach($arg['values'] as $k => $v){
                if ($k == 'primary' and $v){
                    $primary = ','."\n".'primary key ('.$column.')';
                }

                if ($k == 'permanent' and $v){
                    $unique = ','."\n".'unique('.$column.')'."\n";
                }
			}
		}

        $query = join(', ', $query).$primary.$unique;
		$query = 'create table '.$this->prefix.$arg['table']." (\n".$query."\n)\n";
		return mysql_query($query, $this->link);
	}

	/**
	 * Drops a table from a database
	 * @param mixed $arguments The arguments in form of "[$key] => $value"
	 *                         where $key can be 'db', 'table'
	 * @return void
	 * @access public
	 */
	function droptable($arg){
		$query = 'drop table '.$this->prefix.$arg['table']."\n";
		return mysql_query($query, $this->link);
	}

	/**
	 * To check whether a table exists or not
	 * @param string $table The name of the table
	 * @param string $database The name of the database the table is in
	 * @return bool Whether the db exists or not
	 * @access public
	 */
	function table_exists($table, $db = ''){
//	    $list = mysql_list_tables((!empty($db) ? $db : $this->database), $this->link);
$list = mysql_query("SHOW TABLES FROM ".(!empty($db) ? $db : $this->database)."", $this->link);
	    for ($i = 0; $i < mysql_num_rows($list); $i++){
	        if (mysql_tablename($list, $i) == $this->prefix.$table){
	            return true;
	        }
	    }

		return false;
	}

	/**
	 * To return the number of queries sent to MySQL
	 * @return int
	 * @access public
	 */
	function query_count(){
		return (!empty($this->query) ? $this->query : 0);
	}

	/**
	 * To retrieve the number of records inside of a table
	 * @param string $table The name of the table
	 * @param string $database The database the table is inside of (optional)
	 * @return int The number of records in the table
	 * @access public
	 */
	function table_count($table, $database = ''){
		$query = 'show table status like \''.$this->prefix.$table.'\'';
		$query = mysql_query($query, $this->link);
		$row   = mysql_fetch_array($query);
		return $row['Rows'];
	}

	/**
	 * To retrieve the last ID generated by an auto_increment field in a table
	 * @param string $table The name of the table
	 * @param string $database The database the table is inside of (optional)
	 * @return string Get the last ID generated by this column instead of the priamry key (optional)
	 * @access public
	 */
	function last_insert_id($table = '', $database = '', $select = ''){
	    $query = 'show table status like \''.$this->prefix.$table.'\'';
	    $query = mysql_query($query, $this->link);
	    $row   = mysql_fetch_array($query);
	    return ($row['Auto_increment'] - 1);
	}

	/**
	 * Inserts a new row into a table with the given information
	 * @param mixed $arg The arguments in form of "[$key] => $value"
	 *                         where $key can be 'db', 'table', 'values'
	 * @return int The number of rows inserted into the table
	 * @access public
	 */
	function insert($arg){
	$query = "";
        foreach ($arg['values'] as $k => $v){
        	$insert[] = '`'.$k.'`';
        	$values[] = '\''.mysql_escape_string($v).'\'';
        }

       	$query .= 'insert into '.$this->prefix.$arg['table']."\n";
        $query .= '('.join(', ', $insert).')'."\n";
        $query .= 'values ('.join(', ', $values).')'."\n";
		return mysql_query($query, $this->link);
	}

	/**
	 * Deletes a row from a table that matches a 'where' clause
	 * @param mixed $arg The arguments in form of "[$key] => $value"
	 *                         where $key can be 'db', 'table', 'where', 'limit'
	 * @return int The number of rows deleted
	 * @access public
	 */
	function delete($arg){
	$query = "";
       	$query .= 'delete from '.$this->prefix.$arg['table']."\n";
        $query .= $this->_where($arg)."\n";
        $query .= ($arg['limit'] ? 'limit '.join(', ', $arg['limit']) : '')."\n";
		return mysql_query($query, $this->link);
	}

	/**
	 * Updates a row that matches a 'where' clause, with new information
	 * @param mixed $arg The arguments in form of "[$key] => $value"
	 *                         where $key can be 'db', 'table', 'where', 'limit',
	 *                         and 'values'
	 * @return int The number of rows updated
	 * @access public
	 */
	function update($arg){
	$query = "";
        foreach ($arg['values'] as $k => $v){
        	$update[] = '`'.$k.'` = \''.mysql_escape_string($v).'\'';
        }

       	$query .= 'update '.$this->prefix.$arg['table']."\n";
        $query .= 'set '.join(', ', $update)."\n";
        $query .= $this->_where($arg)."\n";
        $query .= (!empty($arg['limit']) ? 'limit '.join(', ', $arg['limit']) : '')."\n";
		return mysql_query($query, $this->link);
	}

	/**
	 * Selects rows of information from a selected database and a table
	 * that fits the given 'where' clause
	 * @param mixed $arg The arguments in form of "[$key] => $value"
	 *                         where $key can be 'db', 'table', 'select', 'where', 'limit'
	 *                         and 'orderby'
	 * @return mixed An array that MySQL returns that matches the given criteria
	 * @access public
	 */
	function select($arg){
		if (!empty($arg['orderby'])){
			if (is_array($arg['orderby'][0])){
				foreach ($arg['orderby'] as $array){
					$orderby[] = '`'.$array[0].'` '.$array[1];
				}
			} else {
				$orderby[] = '`'.$arg['orderby'][0].'` '.$arg['orderby'][1];
			}
		}
$query = "";
		$this->query++;
        $query .= 'select '.$this->_select($arg)."\n";
       	$query .= 'from `'.$this->prefix.$arg['table'].'`'."\n";
       	$query .= (!empty($arg['join']) ? 'inner join `'.$this->prefix.$arg['join']['table'].'` on '.$arg['join']['where'] : '')."\n";
        $query .= $this->_where($arg)."\n";
        $query .=  (!empty($orderby) ? 'order by '.join(', ', $orderby) : '')."\n";
        $query .= (!empty($arg['limit']) ? 'limit '.join(', ', $arg['limit']) : '')."\n";
		$query  = mysql_query($query, $this->link);
		$result = array();

		while ($row = @mysql_fetch_assoc($query)){
			$result[] = $row;
		}

		return $result;
	}

	function count($arg){
	$query = "";
		$this->query++;
        $query .= 'select count('.$this->_select($arg).')'."\n";
       	$query .= 'from `'.$this->prefix.$arg['table'].'`'."\n";
        $query .= $this->_where($arg)."\n";
        $query .= (!empty($arg['limit']) ? 'limit '.join(', ', $arg['limit']) : '')."\n";
		$query  = mysql_query($query, $this->link);
		$result = @mysql_fetch_array($query);

		return (!empty($result) ? reset($result) : '0');
	}

	/**
	 * @access private
	 */
    function _select($arg){
    	if (empty($arg['select'])){
    		$result[] = '*';
    	} else {
	        foreach ($arg['select'] as $k => $v){
	        	$result[] = '`'.$v.'`';
	        }
        }

        return join(', ', $result);
	}

	/**
	 * @access private
	 */
    function _values($arg, $separator_in = ' ', $separator_out = ' '){
        foreach ($arg['values'] as $k => $v){
            if ($k != 'primary' and $v != 'enum' and $k != 'permanent' and $k != 'max'){
                if ($k == 'type' or ($k == 'name' and $arg['action'])){
                    $result[] = $v.' not null';
                } elseif ($k == 'auto_increment'){
                    $result[] = $k;
                } else {
                    if ($k == 'enum_val' and is_array($v)){
                        foreach ($v as $enum){
                            $enum_var[] = '\''.$enum.'\'';
                        }

                        $result[] = 'enum('.join(', ', $enum_var).') not null';
                    } else {
                        $result[] = $k.$separator_in.'\''.mysql_escape_string($v).'\'';
                    }
                }
            }
        }

        $result = @join($separator_out, $result);
		$result = str_replace('string', 'varchar(255)', $result);
		$result = str_replace('bool', 'tinyint(1)', $result);

        return $result;
	}

	/**
	 * @access private
	 */
	function _where($arg, $separator = ' '){
	$s="";
	$v="";
	$e="";
	
	
        if (!empty($arg['where'])){
        	$op = '(=|!=|<|<=|>|>=|=~|!~)';

	        foreach ($arg['where'] as $k => $v){
            	if (preg_match('/(.*)\[(.*)\]/i', $v, $match)){
            		if (preg_match('/(.*) (\?|!\?) (.*)/i', $v, $regexp)){
            			$result[] = $regexp[1].($regexp[2] == '!?' ? ' not' : '').' regexp \'[[:<:]]('.$match[2].')[[:>:]]\''."\n";
            		} else {
	                    foreach (explode('|', $match[2]) as $or){
	                        $where[] = $match[1].'\''.$or.'\'';
	                    }

	        			$result[] = '('.join(' or ', $where).')'."\n";
	        		}
                } elseif ($v != 'and' and $v != 'or' and $v != 'xor'){
	                if (substr($v, 0, 1) == '('){
	                    $v = substr($v, 1);
	                    $s = true;
	                }

	                if (substr($v, (strlen($v) - 1)) == ')'){
	                    $v = substr($v, 0, (strlen($v) - 1));
	                    $e = true;
	                }

	                $result[] = preg_replace('/(.*?) '.$op.' (.*)/i', '`\\1` \\2 \'\\3\'', $v);
	        	} else {
	        		$result[] = $v."\n";
	        	}
	        }

        	return 'where '.($s ? '(' : '').str_replace(array('!~', '=~'), array('not like', 'like'), join($separator, $result)).($e ? ')' : '');
        }
	}
}




















#### А ТЕПЕРЬ ВВОДИМ ВТОРОЙ КЛАСС. PHP NUKE RULEZZzz!

class sql_db {
	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
	var $total_time_db = 0;
	var $time_query = "";

	function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true) {
		$this->db_connect_id = !empty($persistency) ? @mysql_pconnect($sqlserver, $sqluser, $sqlpassword) : @mysql_connect($sqlserver, $sqluser, $sqlpassword);
		if ($this->db_connect_id) {
			if ($database != "" && !@mysql_select_db($database)) {
				@mysql_close($this->db_connect_id);
				$this->db_connect_id = false;
			}
			return $this->db_connect_id;
		} else {
			return false;
		}
	}

	function sql_close() {
		if ($this->db_connect_id) {
			if ($this->query_result) @mysql_free_result($this->query_result);
			$result = @mysql_close($this->db_connect_id);
			return $result;
		} else {
			return false;
		}
	}

	function sql_query($query = "", $transaction = false) {
		unset($this->query_result);
		if ($query != "") {
			$st = array_sum(explode(" ", microtime()));
			$this->query_result = @mysql_query($query, $this->db_connect_id);
			$total_tdb = round(array_sum(explode(" ", microtime())) - $st, 5);
			$this->total_time_db += $total_tdb;
			$this->time_query .= "".$total_tdb > 0.01."" ? "<font color=\"red\"><b>".$total_tdb."</b></font> "._SEC.". - [".$query."]<br><br>" : "<font color=\"green\"><b>".$total_tdb."</b></font> "._SEC.". - [".$query."]<br><br>";
		}
		if ($this->query_result) {
			$this->num_queries += 1;
			unset($this->row[$this->query_result]);
			unset($this->rowset[$this->query_result]);
			return $this->query_result;
		} else {
			return ($transaction == 'END_TRANSACTION') ? true : false;
		}
	}

	function sql_numrows($query_id = 0) {
		if (!$query_id) $query_id = $this->query_result;
		if ($query_id) {
			$result = @mysql_num_rows($query_id);
			return $result;
		} else {
			return false;
		}
	}

	function sql_affectedrows() {
		if ($this->db_connect_id) {
			$result = @mysql_affected_rows($this->db_connect_id);
			return $result;
		} else {
			return false;
		}
	}

	function sql_numfields($query_id = 0) {
		if (!$query_id) $query_id = $this->query_result;
		if ($query_id) {
			$result = @mysql_num_fields($query_id);
			return $result;
		} else {
			return false;
		}
	}

	function sql_fieldname($offset, $query_id = 0) {
		if (!$query_id) $query_id = $this->query_result;
		if ($query_id) {
			$result = @mysql_field_name($query_id, $offset);
			return $result;
		} else {
			return false;
		}
	}

	function sql_fieldtype($offset, $query_id = 0) {
		if (!$query_id) $query_id = $this->query_result;
		if($query_id) {
			$result = @mysql_field_type($query_id, $offset);
			return $result;
		} else {
			return false;
		}
	}

	function sql_fetchrow($query_id = 0) {
		if (!$query_id) $query_id = $this->query_result;
		if ($query_id) {
			$this->row[$query_id] = @mysql_fetch_array($query_id);
			return $this->row[$query_id];
		} else {
			return false;
		}
	}

	function sql_fetchrowset($query_id = 0) {
		if (!$query_id) $query_id = $this->query_result;
		if ($query_id) {
			unset($this->rowset[$query_id]);
			unset($this->row[$query_id]);
			while ($this->rowset[$query_id] = @mysql_fetch_array($query_id)) {
				$result[] = $this->rowset[$query_id];
			}
			return $result;
		} else {
			return false;
		}
	}

	function sql_fetchfield($field, $rownum = -1, $query_id = 0) {
		if (!$query_id) $query_id = $this->query_result;
		if ($query_id) {
			if ($rownum > -1) {
				$result = @mysql_result($query_id, $rownum, $field);
			} else {
				if (empty($this->row[$query_id]) && empty($this->rowset[$query_id])) {
					if ($this->sql_fetchrow()) {
						$result = $this->row[$query_id][$field];
					}
				} else {
					if ($this->rowset[$query_id]) {
						$result = $this->rowset[$query_id][0][$field];
					} else if ($this->row[$query_id]) {
						$result = $this->row[$query_id][$field];
					}
				}
			}
			return $result;
		} else {
			return false;
		}
	}

	function sql_rowseek($rownum, $query_id = 0) {
		if (!$query_id) $query_id = $this->query_result;
		if ($query_id) {
			$result = @mysql_data_seek($query_id, $rownum);
			return $result;
		} else {
			return false;
		}
	}

	function sql_nextid() {
		if ($this->db_connect_id) {
			$result = @mysql_insert_id($this->db_connect_id);
			return $result;
		} else {
			return false;
		}
	}

	function sql_freeresult($query_id = 0){
		if (!$query_id) $query_id = $this->query_result;
		if ($query_id) {
			unset($this->row[$query_id]);
			unset($this->rowset[$query_id]);
			@mysql_free_result($query_id);
			return true;
		} else {
			return false;
		}
	}

	function sql_error($query_id = 0) {
		$result["message"] = @mysql_error($this->db_connect_id);
		$result["code"] = @mysql_errno($this->db_connect_id);
		return $result;
	}
}


?>