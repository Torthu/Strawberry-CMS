<?php
#_strawberry
if (!defined("str_adm")) { header("Location: ../../index.php"); exit; }
/**
 * @package Cache
 * @access public
 */

class cache {

	/**
	 * @param bool $cache Создавать кэш или нет
	 * @param string $dir Директория, куда будет сохранятся кэш
	 * @param int $lifetime Время жизни (3600 = 1 час)
	 * @return void
	 * @access public
	 */
	function cache($cache = false, $dir = 'cache', $lifetime = 3600){
		$this->dir      = (!empty($dir) ? $dir.'/' : 'cache/');
		$this->cache    = $cache;
		$this->lifetime = $lifetime;
	}

	/**
	 * Получает кэш
	 * @param string $file Имя файла
	 * @param string $uniqid Уникальное имя
	 * @param string $ext Дополнительное расширение
	 * @return string Если фаил существует читает его
	 * @access public
	 */
	function get($file, $uniqid = '', $ext = ''){
            if ($this->cache){
	        $this->file = $this->dir.$this->_get_folder($file).($uniqid ? '('.$uniqid.')' : '').($ext ? '.'.$ext : '').'.tmp';

	        if (file_exists($this->file) and file_exists($this->file.'.md5') and (time() - @filemtime($this->file)) < $this->lifetime){
	        	$cache = file_get_contents($this->file);
	        	$md5   = file_get_contents($this->file.'.md5');

	        	if ($md5 == md5($cache)){
	            	return $cache;
	            }
	        }
	    }
	}

	/**
	 * Создаёт кэш
	 * @param string $output Что угодно для кэширования
	 * @return string $output
	 * @access public
	 */
	function put($output){
		if ($this->cache){
	        $this->_write($this->file, $output);
	        $this->_write($this->file.'.md5', md5($output));
                }

        return $output;
	}

	/**
	 * Получает кэш
	 * @param string $file Имя файла
	 * @param string $uniqid Уникальное имя
	 * @param string $ext Дополнительное расширение
	 * @return array Если фаил существует читает его
	 * @access public
	 */
	function unserialize($file, $uniqid = '', $ext = ''){
		if ($cache = $this->get($file, $uniqid, $ext)){
			return unserialize($cache);
		} else {
		        return;
		}
	}

	/**
	 * Создаёт кэш
	 * @param string $output Что угодно для кэширования
	 * @return string $output
	 * @access public
	 */
	function serialize($output){
		if ($this->cache){
	        $this->_write($this->file, serialize($output));
	        $this->_write($this->file.'.md5', md5(serialize($output)));
        }

        return $output;
	}

	/**
	 * Удаляет кэш
	 * @param string $id Если указано, то будет удалён кэш только с таким $id
	 * @return void
	 * @access public
	 */
	function delete($id = ''){
		$this->_remove_directory($this->dir);
	}

	/**
	 * Считает количество вызовов самой себя
	 * @return int
	 * @access public
	 */
function touch_this(){
static $touch_this;
$touch_this++;
return ((!empty($touch_this) and $touch_this == 1) ? '' : $touch_this);
}





	/**
	 * @access private
	 */
	 
function _write($fopen = '', $fwrite = '', $clear = true, $chmod = 0777){
    if ($fopen and $fopen != ".md5") {	
	 if (!empty($clear)){
	    $fwrite = str_replace('  ', '', str_replace("\r\n", '', $fwrite));
	 }
	    $fp = fopen($fopen, 'wb+');
	    fwrite($fp, $fwrite);
	    @chmod($fopen, $chmod);
	    fclose($fp);
    }
}
	
	
	
	

	/**
	 * @access private
	 */
	function _chicken_dick($chicken, $dick = '/'){
	    $chicken = preg_replace('/^(['.preg_quote($dick, '/').']+)/', '', $chicken);
	    $chicken = preg_replace('/(['.preg_quote($dick, '/').']+)/', $dick, $chicken);
	    $chicken = preg_replace('/(['.preg_quote($dick, '/').']+)$/', '', $chicken);
	return $chicken;
	}

	/**
	 * @access private
	 */
	function _get_folder($filename){
		$result = array(
				  'a' => substr($filename, 0, 1),
				  'b' => substr($filename, 1, 1),
				  'c' => substr($filename, 2, 1),
				  'z' => $filename
				  );

	    if (!is_dir($this->dir.$result['a'])){
	        @mkdir($this->dir.$result['a'], 0777);
	    }

	    if (!is_dir($this->dir.$result['a'].'/'.$result['b'])){
	        @mkdir($this->dir.$result['a'].'/'.$result['b'], 0777);
	    }

	    if (!is_dir($this->dir.$result['a'].'/'.$result['b'].'/'.$result['c'])){
	        @mkdir($this->dir.$result['a'].'/'.$result['b'].'/'.$result['c'], 0777);
	    }

	    return join('/', $result);
	}

	/**
	 * @access private
	 */
	function _remove_directory($dir){
	    $handle = opendir($dir);
	    while (($file = readdir($handle)) !== false){
	        if ($file != '.' and $file != '..' and $file != '.htaccess' and $file != '.html'){
	            if (is_dir($dir.$file)){
	                $this->_remove_directory($dir.$file.'/');
	            } else {
	            	@unlink($dir.$file);
	            }
	        }
	    }
	}
}
?>