<?php
#_strawberry
 if (!defined("str_setup")) die("Access dinaed");


$abort = @ignore_user_abort(1);
if (!ini_get('safe_mode')){ @set_time_limit(0); }
$PHP_SELF = way('setup.php');

$that = '-'; // чем замен€ть пробел в ”–Ћах
$the_way = root_directory."/system/setup/db/cn02x";


//echo "---- up _____ <br>";

function check_writable(){
global $the_way;
$the_way = root_directory."/system/setup/db/cn02x";
	$handle = opendir($the_way);
	while ($file = readdir($handle)){
	    if (strtolower(end(explode('.', $file))) == "tmp") {
	    	if (is_file($the_way."/".$file)) {
echo '<li><font color="'.(is_writable($the_way.'/'.$file) ? 'green' : 'red').'">'.$file.'</font> - '.(is_writable($the_way.'/'.$file) ? _WRITE_CAN : _WRITE_CANT).'</li>';
	    	}
	    }
	}
}



function not_null($file){
global $the_way;
$the_way = root_directory."/system/setup/db/cn02x";
	if (file_exists($the_way.'/'.$file.'.tmp')){
		if (filesize($the_way.'/'.$file.'.tmp')){
			return true;
		} else {
			return false;
	   	}
	} else {
		return false;
	}
}






function write_and_go($file, $text = 'ok'){
global $PHP_SELF, $the_way;
$the_way = root_directory."/system/setup/db/cn02x";
    @chmod($the_way.'/'.$file, 0777);
	@file_write($the_way.'/'.$file.'.tmp', $text);
#	@header('Location: '.$PHP_SELF);
echo "go-Go-GOOO!!!";
}






if (not_null('news')){
	foreach (file($the_way.'/news.tmp') as $fo){
		$fo_arr              = explode('|', $fo);
		$post_id[$fo_arr[0]] = $fo_arr[1];
	}
}






if ($fp = file($the_way.'/data/counter.txt')){
	foreach ($fp as $fo){
		$fo_arr              = explode('|', $fo);
		$counter[$fo_arr[0]] = $fo_arr[1];
	}
}


//echo "---- under action - rip _____ <br>";

if ($_GET['rip'] == 'users'){
	$fp = file($the_way.'/data/users.db.php');
	foreach ($fp as $fo){
		$fo_arr = explode('|', $fo);
/*
        echo "<hr>there are users report start<hr>";
        print_r($fo_arr);
        echo "<hr>there are users report end<hr>";
*/
/**/ 		
if (!$sql->update(array(
        'table'  => 'users',
        'where'  => array("username = $fo_arr[2]"),
        'values' => array(
        			'date'         => $fo_arr[0],
        			'usergroup'    => $fo_arr[1],
                    'name'         => $fo_arr[4],
                    'mail'         => $fo_arr[5],
                    'publications' => $fo_arr[6],
                    'hide_mail'    => $fo_arr[7],
                    'avatar'       => $fo_arr[8],
                    'last_visit'   => $fo_arr[9],
                    'homepage'     => $fo_arr[10],
                    'icq'          => $fo_arr[11],
                    'location'     => $fo_arr[12],
                    'about'        => $fo_arr[13],
                    'lj_username'  => $fo_arr[14]
                    )
        )) and $fo_arr[2]){
        
        

        
        
	        $sql->insert(array(
	        'table'  => 'users',
	        'values' => array(
	                    'date'         => $fo_arr[0],
	                    'usergroup'    => $fo_arr[1],
	                    'username'     => $fo_arr[2],
	                    'password'     => md5($fo_arr[3]),
	                    'name'         => $fo_arr[4],
	                    'mail'         => $fo_arr[5],
	                    'publications' => $fo_arr[6],
	                    'hide_mail'    => $fo_arr[7],
	                    'avatar'       => $fo_arr[8],
	                    'last_visit'   => $fo_arr[9],
	                    'homepage'     => $fo_arr[10],
	                    'icq'          => $fo_arr[11],
	                    'location'     => $fo_arr[12],
	                    'about'        => $fo_arr[13],
	                    'lj_username'  => $fo_arr[14]
	                    )
	        ));
	      
	        
	        
        } 
	}

	write_and_go('users');
}



if ($_GET['rip'] == 'categories'){
	$fp = file($the_way.'/data/category.db.php');
	foreach ($fp as $fo){
		$fo_arr = explode('|', $fo);

 /*       
        echo "<hr>there are users cat start<hr>";
        print_r($fo_arr);
        echo "<hr>there are users cat end<hr>";
  */      
        
		$sql->insert(array(
		'table'  => 'categories',
		'values' => array(
					'id'     => $fo_arr[0],
					'name'   => $fo_arr[1],
					'icon'   => $fo_arr[2],
					'url'    => ($fo_arr[3] ? $fo_arr[3] : totranslit($fo_arr[1], $that)),
					'parent' => $fo_arr[4]
					)
		));
		
		
		
		
		
	}

    write_and_go('categories');
}



if ($_GET['rip'] == 'news'){
	$news_arr[] = $the_way.'/data/news.txt';
	$fdir = opendir($the_way.'/data/archives');
	while ($file = readdir($fdir)){
	    $file_arr = explode('.', $file);

        if (is_numeric($file_arr[0]) and $file_arr[1] == 'news'){
            $news_arr[] = $the_way.'/data/archives/'.$file;
        }
	}

	foreach ($news_arr as $file){
		foreach (file($file) as $fo){
			$all_news_arr[] = $fo;
		}
	}

	sort($all_news_arr);

	for ($i = 0; $i < sizeof($all_news_arr); $i++){
		$fo_arr = explode('|', $all_news_arr[$i]);

   /*     
        echo "<hr>there are users news start<hr>";
        print_r($fo_arr);
        echo "<hr>there are users news end<hr>";
    */    
        
		$sql->insert(array(
		'table'  => 'news',
		'values' => array(
					'date'     => $fo_arr[0],
					'author'   => $fo_arr[1],
					'title'    => $fo_arr[2],
					'c_short'    => strlen($fo_arr[3]),
					'c_full'     => ($fo_arr[4] ? strlen($fo_arr[4]) : 0),
					'avatar'   => $fo_arr[5],
					'views'    => $counter[$fo_arr[0]],
					'category' => $fo_arr[6],
					'url'      => ($fo_arr[7] ? straw_namespace($fo_arr[7]) : straw_namespace(totranslit($fo_arr[2], $that)))
					)
		));
		

		if ($config['database'] == 'txtsql'){
			$last_insert_id = ($sql->last_insert_id('news', '', 'id') + $i);
		} else {
			$last_insert_id = $sql->last_insert_id('news', '', 'id');
		}

        $write .= $fo_arr[0].'|'.$last_insert_id."\r\n";

   /*     
        echo "<hr>there are story report start<hr>";
        print_r($fo_arr);
        echo "<hr>there are story report end<hr>";
  */      
        
        $sql->insert(array(
        'table'  => 'story',
        'values' => array(
        			'post_id' => $last_insert_id,
        			'short'   => $fo_arr[3],
        			'full'    => $fo_arr[4]
        			)
        ));
        
        
    }

    write_and_go('news', $write);
}






if ($_GET['rip'] == 'comments'){
	$comm_arr[] = $the_way.'/data/comments.txt';
	$fdir = opendir($the_way.'/data/archives');
	while ($file = readdir($fdir)){
	    $file_arr = explode('.', $file);

        if (is_numeric($file_arr[0]) and $file_arr[1] == 'comments'){
            $comm_arr[] = $the_way.'/data/archives/'.$file;
        }
    }

	foreach ($comm_arr as $file){
		foreach (file($file) as $fo){
			$all_comm_arr[] = $fo;
		}
	}

	sort($all_comm_arr);

    foreach ($all_comm_arr as $comment_line){
        $comment_arr_1 = explode('|>|', $comment_line);
        $comment_arr_2 = explode('||', $comment_arr_1[1]);

        foreach ($comment_arr_2 as $fo){
        	$fo_arr = explode('|', $fo);

        	if ($fo_arr[2] and $post_id[$comment_arr_1[0]]){
	           
/*	               
        echo "<hr>there are comm report start<hr>";
        print_r($fo_arr);
        echo "<hr>there are comm report end<hr>";
     */   
             
	             
	               $sql->insert(array(
	            'table'  => 'comments',
	            'values' => array(
	                        'date'    => $fo_arr[0],
	                        'author'  => $fo_arr[1],
	                        'mail'    => $fo_arr[2],
	                        'ip'      => $fo_arr[3],
	                        'comment' => $fo_arr[4],
	                        'reply'   => $fo_arr[5],
	                        'post_id' => $post_id[$comment_arr_1[0]],
	                        )
	            ));
	            

       /* 
        echo "<hr>there are news comm update start<hr>";
        print_r($fo_arr);
        echo "<hr>there are news comm update end<hr>";
        */
        
	            $sql->update(array(
	            'table'  => 'news',
	            'where'  => array('id = '.$post_id[$comment_arr_1[0]]),
	            'values' => array('comments' => count($comment_arr_2) - 1)
	            ));
	
	
	
	        }
        }
    }

	write_and_go('comments');
}






if ($_GET['rip'] == 'xfields'){
	function xz($id){
	global $post_id, $repl1;

    	$repl1 = str_replace("\r\n", '', $post_id[$id]);

	return $repl1;
	}

	$file1 = file($the_way.'/data/plugins/xfields-data.php');
	$file2 = file($the_way.'/data/xfieldsdata.txt');
	$file3 = file_read($the_way.'/data/xfields.txt');

    foreach ($file1 as $fo1){
    	$replace1_tmp = preg_replace('/([0-9]{10})/ie', "xz('\\1')", $fo1);

        if ($repl1){
        	$replace1 .= $replace1_tmp;
        }
    }

    foreach ($file2 as $fo2){
    	$fo_arr2 = explode('|>|', $fo2);

    	if ($replace2_tmp = str_replace("\r\n", '', $post_id[$fo_arr2[0]])){
	    	$replace2 .= $replace2_tmp.'|>|'.$fo_arr2[1];
	    }
	}
echo $cutepath;
  #  file_write($cutepath.'/data/xfields-data.php', "<?php\r\n\$array = array (\r\n".$replace1);
  #  file_write($cutepath.'/data/xfields-data.txt', $replace2);
  #  file_write($cutepath.'/data/xfields.txt', $file3);

	write_and_go('xfields');
}



//echo "---- bottom rip _____ <br>";

?>




<b><?php echo _CHECK_CHMOD; ?></b>
<?php echo check_writable(); ?>

<br>
<b><?php echo _MOVE; ?></b>

<?php
if (!not_null('users')){
?>

<li><a href="<?php echo $PHP_SELF; ?>?mod=no111&amp;act=02x&amp;rip=users"><?php echo _USERS; ?>*</a></li>

<?php
}

if (!not_null('categories')){
?>

<li><a href="<?php echo $PHP_SELF; ?>?mod=no111&amp;act=02x&amp;rip=categories"><?php echo _CATS; ?></a></li>

<?php
}

if (!not_null('news')){
?>

<li><a href="<?php echo $PHP_SELF; ?>?mod=no111&amp;act=02x&amp;rip=news"><?php echo _NEWS; ?></a></li>

<?php
}

if (not_null('news')){
	if (!not_null('comments')){
?>

<li><a href="<?php echo $PHP_SELF; ?>?mod=no111&amp;act=02x&amp;rip=comments"><?php echo _COMMS; ?></a></li>

<?php
	}

    if (!not_null('xfields')){
?>

<li><a href="<?php echo $PHP_SELF; ?>?mod=no111&amp;act=02x&amp;rip=xfields"><?php echo _XFLDS; ?></a></li>

<?php
	}
} else {
?>

<li><?php echo _COMMS; ?>**</li>
<li><?php echo _XFLDS; ?>**</li>

<?php
}
?>
<br>
<p>* <?php echo _MOVE_USERS_MESS; ?></p>
<p>** <?php echo _DB_NEWS_BEFORE; ?></p>
