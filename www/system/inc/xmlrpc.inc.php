<?php
#_strawberry
if (!defined("str_adm")) { header("Location: ../../index.php"); exit; }

/**
 * @package Private
 * @access private
 */

/*
An XML-RPC implementation by Keith Devens, version 2.2.1.
http://www.keithdevens.com/software/xmlrpc/

History:
.95 - initial version
.98 5/30/02
	- improved the XML parser and serializer to support attributes
	  and changed how the parsers work a little.
	- Importantly, now uses the Connection: close header to avoid persistent
	  connections in HTTP 1.1.
.99 6/1/02 - Fixed XML_unserialize, which was broken. Now it works in all cases!
.99b 6/1/02 - I was wrong :) Fixed one more bug in XML_unserialize
1.0 6/9/02
	- Combined all "generation" functions (XMLRPC_makeArray, XMLRPC_makeStruct, and XMLRPC_makeScalar) into
	  one XMLRPC_prepare function that "does the right thing"
	- Also added a getBase64 function.
	- The only thing that's not currently easy to do is to create a base64 or boolean. That'll be fixed for 1.01.
	- Ok, fixed for 1.01 :) 6/9/02
1.02 6/9/02
	- fixed up the code a bit, removed some things that were technically wrong but still worked
	  because my library is just *so* flexible, and most importantly -- **documented** it!
1.03 6/12/02
	- changed XMLRPC_getParams so that the $params returned would always be an array
	  even if there was only one parameter
	- Added the functions XMLRPC_getArray and XMLRPC_getStruct to complete the set of accessor functions
	- Modified the XMLRPC_getBoolean function to cast the return value to a (bool)
	- Fixed a bug in XMLRPC_prepare where the $type wasn't reset between elements
2.0 6/12/02
	- Overhaul of code, even though it's only a few days old :)
	- Basically, it's now pretty much symmetric, where you serialize PHP data structures to send your message
	  and you 'deserialize' the message when you get it back. All the XMLRPC_getWhatever's go away.
	  Much nicer. I also realized you can detect the boolean type, so I added that to XMLRPC_prepare
2.0b 6/20/02
	- Very minor change in XMLRPC_request to avoid some string copying
2.0c 7/6/02
	- Very small change to count_numeric_items so it'll return zero if it's passed something that isn't an array
	- Also, so that it won't give you ugly error messages in case you break its precondition of passing it an array :)
2.1 7/12/02
	- Very small change to XMLRPC_parse to hopefully avoid some string copying by passing a reference

	- Made it possible to make an XMLRPC_request with an empty parameter list by passing either
	  an empty array or NULL for the $params.
	- Made XMLRPC_getParams handle the similar case where there are no parameters passed.
	  (This takes care of the receiving end as well as the sending end.)
	- Made XMLRPC_adjustValue handle the cases of an array with no elements and a struct with no members.
	Thanks to Elan for pointing these cases out.

	- Neatened up these comments a bit
2.1.1 7/15/02
	- You can now specify the port for XMLRPC_request to connect to by specifying the $site in the form
	  of "www.keithdevens.com:8080". Defaults to port 80
	- in XMLRPC_request the $params now defaults to NULL, whereas previously it didn't have a default.
2.1.2 7/16/02
	- Pretty important bug fix in XMLRPC_adjustValue (where arrays of one element got "clobbered").
	Thanks again to Elan.
2.2 7/18/02
	- One more bugfix to XMLRPC_adjustValue (described here http://www.keithdevens.com/weblog/?id2439)
	- Changed the return of XMLRPC_request in the case of a connection failure from false into a structure
	  that mimics an XML-RPC error so you can deal with all errors in a uniform way. This may break your code
	  if you tested for a connection failure separately before.
	- Added debugging system (finally).
2.2.1 7/23/02
	- Bug fix to XMLRPC_prepare to correctly handle empty arrays and empty structs
2.2.1b 7/23/02
	- Changes to many functions to try to avoid copying data whereever possible.
2.2.1c 7/23/02
	- Ok, backed off a few of those changes. You'll have to pass things by reference yourself to functions like
	  XMLRPC_response, etc. if you want to avoid copying.

Note: this code requires version 4.10 or higher of PHP.

*/

function & XML_serialize(&$data, $level = 0, $prior_key = NULL){
global $config;
	#assumes a hash, keys are the variable names
	$xml_serialized_string = "";
	while(list($key, $value) = each($data)){
		$inline = false;
		$numeric_array = false;
		$attributes = "";
		#echo "My current key is '$key', called with prior key '$prior_key'<br>";
		if(!strstr($key, " attr")){ #if it's not an attribute
			if(array_key_exists("$key attr", $data)){
				while(list($attr_name, $attr_value) = each($data["$key attr"])){
					#echo "Found attribute $attribute_name with value $attribute_value<br>";
					$attr_value = &htmlspecialchars($attr_value, ENT_QUOTES);
					$attributes .= " $attr_name=\"$attr_value\"";
				}
			}

			if(is_numeric($key)){
				#echo "My current key ($key) is numeric. My parent key is '$prior_key'<br>";
				$key = $prior_key;
			}else{
				#you can't have numeric keys at two levels in a row, so this is ok
				#echo "Checking to see if a numeric key exists in data.";
				if(is_array($value) and array_key_exists(0, $value)){
				#	echo " It does! Calling myself as a result of a numeric array.<br>";
					$numeric_array = true;
					$xml_serialized_string .= XML_serialize($value, $level, $key);
				}
				#echo "<br>";
			}

			if(!$numeric_array){
				$xml_serialized_string .= str_repeat("\t", $level) . "<$key$attributes>";

				if(is_array($value)){
					$xml_serialized_string .= "\r\n" . XML_serialize($value, $level+1);
				}else{
					$inline = true;
					$xml_serialized_string .= $value;
				}

				$xml_serialized_string .= (!$inline ? str_repeat("\t", $level) : "") . "</$key>\r\n";
			}
		}else{
			#echo "Skipping attribute record for key $key<bR>";
		}
	}
	if($level == 0){
		$xml_serialized_string = "<?xml version=\"1.0\" encoding=\"$config[charset]\" ?>\r\n" . $xml_serialized_string;
		return $xml_serialized_string;
	}else{
		return $xml_serialized_string;
	}
}

function & XML_unserialize(&$xml){
	$values = array();
	$indexes = array();
	$xml_parser = xml_parser_create();
	xml_parser_set_option ($xml_parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parse_into_struct($xml_parser, $xml, &$values, &$indexes);
	xml_parser_free($xml_parser);

	$root = array();
	$current_parent = &$root;
	$value_count = count($values);
	for($n = 0; $n<$value_count; $n++){
		$value = $values[$n];
		$level = $value['level'];
		$type = $value['type'];
		$tag = $value['tag'];
		$val = $value['value'];
		$attributes = $value['attributes'];

		$attribute_string = '';
		if(($type == 'open' or $type == 'complete')){
			#echo "At $type tag $tag, level $level. <b>Value is '$val'</b><br>\n";
			if(array_key_exists($tag, $current_parent)){
				#echo "There's already an instance of '$tag' at the current level ($level)<br>\n";
				if(is_array($current_parent[$tag]) and array_key_exists(0, $current_parent[$tag])){ #if the keys are numeric
					#need to make sure they're numeric (account for attributes)
					$key = count_numeric_items($current_parent[$tag]);
					#echo "There are $key instances: the keys are numeric.<br>\n";
				}else{
					#echo "There is only one instance. Shifting everything around<br>\n";
					$temp = &$current_parent[$tag];
					unset($current_parent[$tag]);
					$current_parent[$tag][0] = &$temp;
					if(array_key_exists("$tag attr", $current_parent)){
						$temp = &$current_parent["$tag attr"];
						unset($current_parent["$tag attr"]);
						$current_parent[$tag]["0 attr"] = &$temp;
					}
					$key = 1;
				}
				$current_parent = &$current_parent[$tag];
			}else{
				$key = $tag;
			}
			if($attributes){
				$current_parent["$key attr"] = $attributes;
			}
		}
		if($type == 'open'){
			$current_parent[$key] = array();
			$current_parent = &$current_parent[$key];
			if(!is_numeric($key)){
				$current_level_parent[$level-1] = &$current_parent;
			}
		}elseif($type == 'complete'){
			#echo "Parsing: key is $key and val is " . show($val, var_dump, true) . "<br>\n";
			$current_parent[$key] = $val;
			if(is_numeric($key)){
				$current_parent = &$current_level_parent[$level-2];
			}
		}elseif($type == 'close'){
			if($level > 1){
				$current_parent = &$current_level_parent[$level-2];
			}else{
				$current_parent = &$root;
			}
		}
	}
	return $root;
}

function & XMLRPC_prepare($data, $type = NULL){
	if(is_array($data)){
		$num_elements = count($data);
		if((array_key_exists(0, $data) or !$num_elements) and $type != 'struct'){ #it's an array
			if(!$num_elements){ #if the array is empty
				$returnvalue =  array('array' => array('data' => NULL));
			}else{
				$returnvalue['array']['data']['value'] = array();
				$temp = &$returnvalue['array']['data']['value'];
				$count = count_numeric_items($data);
				for($n=0; $n<$count; $n++){
					$type = NULL;
					if(array_key_exists("$n type", $data)){
						$type = $data["$n type"];
					}
					$temp[$n] = XMLRPC_prepare(&$data[$n], $type);
				}
			}
		}else{ #it's a struct
			if(!$num_elements){ #if the struct is empty
				$returnvalue = array('struct' => NULL);
			}else{
				$returnvalue['struct']['member'] = array();
				$temp = &$returnvalue['struct']['member'];
				while(list($key, $value) = each($data)){
					if(substr($key, -5) != ' type'){ #if it's not a type specifier
						$type = NULL;
						if(array_key_exists("$key type", $data)){
							$type = $data["$key type"];
						}
						$temp[] = array('name' => $key, 'value' => XMLRPC_prepare(&$value, $type));
					}
				}
			}
		}
	}else{ #it's a scalar
		if(!$type){
			if(is_int($data)){
				$returnvalue['int'] = $data;
				return $returnvalue;
			}elseif(is_float($data)){
				$returnvalue['double'] = $data;
				return $returnvalue;
			}elseif(is_bool($data)){
				$returnvalue['boolean'] = $data;
				return $returnvalue;
			}elseif(preg_match('/\d{8}T\d{2}:\d{2}:\d{2}/', $data, $matches)){ #it's a date
				$returnvalue['dateTime.iso8601'] = $data;
				return $returnvalue;
			}elseif(is_string($data)){
				$returnvalue['string'] = htmlspecialchars($data);
				return $returnvalue;
			}
		}else{
			$returnvalue[$type] = $data;
		}
	}
	return $returnvalue;
}

function & XMLRPC_adjustValue(&$current_node){
	if(is_array($current_node)){
		if(isset($current_node['array'])){
			if(!is_array($current_node['array']['data'])){
				#If there are no elements, return an empty array
				return array();
			}else{
				#echo "Getting rid of array -> data -> value<br>\n";
				$temp = &$current_node['array']['data']['value'];
				if(is_array($temp) and array_key_exists(0, $temp)){
					$count = count($temp);
					for($n=0;$n<$count;$n++){
						$temp2[$n] = &XMLRPC_adjustValue(&$temp[$n]);
					}
					$temp = &$temp2;
				}else{
					$temp2 = &XMLRPC_adjustValue(&$temp);
					$temp = array(&$temp2);
					#I do the temp assignment because it avoids copying,
					# since I can put a reference in the array
					#PHP's reference model is a bit silly, and I can't just say:
					# $temp = array(&XMLRPC_adjustValue(&$temp));
				}
			}
		}elseif(isset($current_node['struct'])){
			if(!is_array($current_node['struct'])){
				#If there are no members, return an empty array
				return array();
			}else{
				#echo "Getting rid of struct -> member<br>\n";
				$temp = &$current_node['struct']['member'];
				if(is_array($temp) and array_key_exists(0, $temp)){
					$count = count($temp);
					for($n=0;$n<$count;$n++){
						#echo "Passing name {$temp[$n][name]}. Value is: " . show($temp[$n][value], var_dump, true) . "<br>\n";
						$temp2[$temp[$n]['name']] = &XMLRPC_adjustValue(&$temp[$n]['value']);
						#echo "adjustValue(): After assigning, the value is " . show($temp2[$temp[$n]['name']], var_dump, true) . "<br>\n";
					}
				}else{
					#echo "Passing name $temp[name]<br>\n";
					$temp2[$temp['name']] = &XMLRPC_adjustValue(&$temp['value']);
				}
				$temp = &$temp2;
			}
		}else{
			$types = array('string', 'int', 'i4', 'double', 'dateTime.iso8601', 'base64', 'boolean');
			$fell_through = true;
			foreach($types as $type){
				if(array_key_exists($type, $current_node)){
					#echo "Getting rid of '$type'<br>\n";
					$temp = &$current_node[$type];
					#echo "adjustValue(): The current node is set with a type of $type<br>\n";
					$fell_through = false;
					break;
				}
			}
			if($fell_through){
				$type = 'string';
				#echo "Fell through! Type is $type<br>\n";
			}
			switch ($type){
				case 'int': case 'i4': $temp = (int)$temp;    break;
				case 'string':         $temp = (string)$temp; break;
				case 'double':         $temp = (double)$temp; break;
				case 'boolean':        $temp = (bool)$temp;   break;
			}
		}
	}else{
		$temp = (string)$current_node;
	}
	return $temp;
}

function XMLRPC_getParams($request){
	if(!is_array($request['methodCall']['params'])){
		#If there are no parameters, return an empty array
		return array();
	}else{
		#echo "Getting rid of methodCall -> params -> param<br>\n";
		$temp = &$request['methodCall']['params']['param'];
		if(is_array($temp) and array_key_exists(0, $temp)){
			$count = count($temp);
			for($n = 0; $n < $count; $n++){
				#echo "Serializing parameter $n<br>";
				$temp2[$n] = &XMLRPC_adjustValue(&$temp[$n]['value']);
			}
		}else{
			$temp2[0] = &XMLRPC_adjustValue($temp['value']);
		}
		$temp = &$temp2;
		return $temp;
	}
}

function XMLRPC_getMethodName($methodCall){
	#returns the method name
	return $methodCall['methodCall']['methodName'];
}

function XMLRPC_request($site, $location, $methodName, $params = NULL, $user_agent = NULL){
	list($site, $port) = explode(':', $site);
	if(!is_numeric($port)){
		$port = 80;
	}
	$data["methodCall"]["methodName"] = $methodName;
	$param_count = count($params);
	if(!$param_count){
		$data["methodCall"]["params"] = NULL;
	}else{
		for($n = 0; $n<$param_count; $n++){
			$data["methodCall"]["params"]["param"][$n]["value"] = $params[$n];
		}
	}
	$data = XML_serialize($data);

	XMLRPC_debug('XMLRPC_request', "<p>Received the following parameter list to send:</p>" . XMLRPC_show($params, print_r, true));
	$conn = fsockopen ($site, $port); #open the connection
	if(!$conn){ #if the connection was not opened successfully
		if(defined('XMLRPC_DEBUG') and XMLRPC_DEBUG){
			XMLRPC_debug('XMLRPC_request', "<p>Connection failed: Couldn't make the connection to $site.</p>");
		}
		return array(false, array('faultCode'=>10532, 'faultString'=>"Connection failed: Couldn't make the connection to $site."));
	}else{
		$headers =
			"POST $location HTTP/1.0\r\n" .
			"Host: $site\r\n" .
			"Connection: close\r\n" .
			($user_agent ? "User-Agent: $user_agent\r\n" : '') .
			"Content-Type: text/xml\r\n" .
			"Content-Length: " . strlen($data) . "\r\n\r\n";

		fputs($conn, "$headers");
		fputs($conn, $data);

		if(defined('XMLRPC_DEBUG') and XMLRPC_DEBUG){
			XMLRPC_debug('XMLRPC_request', "<p>Sent the following request:</p>\n\n" . XMLRPC_show($headers . $data, print_r, true));
		}

		#socket_set_blocking ($conn, false);
		$response = "";
		while(!feof($conn)){
			$response .= fgets($conn, 1024);
		}
		fclose($conn);
		#strip headers off of response

		$data = XML_unserialize(substr($response, strpos($response, "\r\n\r\n")+4));

		if(defined('XMLRPC_DEBUG') and XMLRPC_DEBUG){
			XMLRPC_debug('XMLRPC_request', "<p>Received the following response:</p>\n\n" . XMLRPC_show($response, print_r, true) . "<p>Which was serialized into the following data:</p>\n\n" . XMLRPC_show($data, print_r, true));
		}
		if(isset($data['methodResponse']['fault'])){
			$return =  array(false, XMLRPC_adjustValue(&$data['methodResponse']['fault']['value']));
			if(defined('XMLRPC_DEBUG') and XMLRPC_DEBUG){
				XMLRPC_debug('XMLRPC_request', "<p>Returning:</p>\n\n" . XMLRPC_show($return, var_dump, true));
			}
			return $return;
		}else{
			$return = array(true, XMLRPC_adjustValue(&$data['methodResponse']['params']['param']['value']));
			if(defined('XMLRPC_DEBUG') and XMLRPC_DEBUG){
				XMLRPC_debug('XMLRPC_request', "<p>Returning:</p>\n\n" . XMLRPC_show($return, var_dump, true));
			}
			return $return;
		}
	}
}

function XMLRPC_response($return_value, $server = NULL){
	$data["methodResponse"]["params"]["param"]["value"] = &$return_value;
	$return = XML_serialize(&$data);

	if(defined('XMLRPC_DEBUG') and XMLRPC_DEBUG){
		XMLRPC_debug('XMLRPC_response', "<p>Received the following data to return:</p>\n\n" . XMLRPC_show($return_value, print_r, true));
	}

	header("Connection: close");
	header("Content-Length: " . strlen($return));
	header("Content-Type: text/xml");
	header("Date: " . date("r"));
	if($server){
		header("Server: $server");
	}

	if(defined('XMLRPC_DEBUG') and XMLRPC_DEBUG){
		XMLRPC_debug('XMLRPC_response', "<p>Sent the following response:</p>\n\n" . XMLRPC_show($return, print_r, true));
	}
	echo $return;
}

function XMLRPC_error($faultCode, $faultString, $server = NULL){
	$array["methodResponse"]["fault"]["value"]["struct"]["member"] = array();
	$_this = &$array["methodResponse"]["fault"]["value"]["struct"]["member"];
	$_this[0]["name"] = "faultCode";
	$_this[0]["value"]["int"] = $faultCode;
	$_this[1]["name"] = "faultString";
	$_this[1]["value"]["string"] = $faultString;

	$return = XML_serialize($array);

	header("Connection: close");
	header("Content-Length: " . strlen($return));
	header("Content-Type: text/xml");
	header("Date: " . date("r"));
	if($server){
		header("Server: $server");
	}
	if(defined('XMLRPC_DEBUG') and XMLRPC_DEBUG){
		XMLRPC_debug('XMLRPC_error', "<p>Sent the following error response:</p>\n\n" . XMLRPC_show($return, print_r, true));
	}
	echo $return;
}

function XMLRPC_convert_timestamp_to_iso8601($timestamp){
	#takes a unix timestamp and converts it to iso8601 required by XMLRPC
	#an example iso8601 datetime is "20010822T03:14:33"
	return date("Ymd\TH:i:s", $timestamp);
}

function XMLRPC_convert_iso8601_to_timestamp($iso8601){
	return strtotime($iso8601);
}

function XMLRPC_parse(&$request){
	return XML_unserialize(&$request);
}

function count_numeric_items(&$array){
	return is_array($array) ? count(array_filter(array_keys($array), is_numeric)) : 0;
}

function XMLRPC_debug($function_name, $debug_message){
	$GLOBALS['XMLRPC_DEBUG_INFO'][] = array($function_name, $debug_message);
}

function XMLRPC_debug_print(){
	if($GLOBALS['XMLRPC_DEBUG_INFO']){
		echo "<table border=\"1\" width=\"100%\">\n";
		foreach($GLOBALS['XMLRPC_DEBUG_INFO'] as $debug){
			echo "<tr><th style=\"vertical-align: top\">$debug[0]</th><td>$debug[1]</td></tr>\n";
		}
		echo "</table>\n";
		unset($GLOBALS['XMLRPC_DEBUG_INFO']);
	}else{
		echo "<p>No debugging information available yet.</p>";
	}
}

function XMLRPC_show($data, $func = "print_r", $return_str = false){
	ob_start();
	$func($data);
	$output = ob_get_contents();
	ob_end_clean();
	if($return_str){
		return "<pre>" . htmlspecialchars($output) . "</pre>\n";
	}else{
		echo "<pre>", htmlspecialchars($output), "</pre>\n";
	}
}

?>
