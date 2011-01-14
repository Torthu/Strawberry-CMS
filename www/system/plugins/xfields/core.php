<?php
#_strawberry
if (!defined("str_adm")) {
header("Location: ../../../index.php");
exit;
}

/**
 * @package Plugins
 * @access private
 */

////////////
// XFields, module for CuteNews (http://www.cutephp.com).
// Written by SMKiller2 (smk2@xs4all.nl)

if (!empty($xfieldssubactionadd) and $xfieldssubactionadd == "add") {
  $xfieldssubaction = $xfieldssubactionadd;
}

if (!isset($_SERVER)) {$_SERVER = $HTTP_SERVER_VARS;}
if (!isset($rowstyle1)) {$rowstyleodd = "class=\"alternate\"";}
if (!isset($rowstyle2)) {$rowstyleeven = "";}


if ((!empty($xf_inited) and $xf_inited !== true) or empty($xf_inited)) { // Prevent "Cannot redeclare" error
  ////////////
  // Save XFields to a file, used when you modify it in the Options section.
  if (!function_exists('xfieldssave')){
	  function xfieldssave($data) {
	  global $cutepath;

	    $data = array_values($data);
	    foreach ($data as $index => $value) {
	      $value = array_values($value);
	      foreach ($value as $index2 => $value2) {
	        $value2 = stripslashes($value2);
	        $value2 = str_replace("|", "&#124;", $value2);
	        $value2 = str_replace("\r\n", "__NEWL__", $value2);
	        $filecontents .= $value2 . ($index2 < count($value) - 1 ? "|" : "");
	      }
	      $filecontents .= ($index < count($data) - 1 ? "\r\n" : "");
	    }

	    $filehandle = fopen("$cutepath/data/xfields.txt", "w+");
	    if (empty($filehandle))
	    msg("error", "Ошибка модуля", "Невозможно сохранить файл \"{$GLOBALS["cutepath"]}/data/xfields.txt\", проверьте существование файла и выставленные на него права.");
	    fwrite($filehandle, $filecontents);
	    fclose($filehandle);
	    header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] .
	        "?plugin=xfields&action=xfields&xfieldsaction=configure");
	    exit;
	  }

	  ////////////
	  // Load XFields from a file, used when you do anything in the Options section.
	  function xfieldsload() {
	  $path = ($GLOBALS["cutepath"] ? $GLOBALS["cutepath"] : '.') . '/data/xfields.txt';
	  $filecontents = file($path);

	    if (!is_array($filecontents))
	    msg("error", "Ошибка модуля", "Невозможно загрузить файл \"{$GLOBALS["cutepath"]}/data/xfields.txt\". Проверьте существование файла и выставленные на него права на чтение.");

	    foreach ($filecontents as $name => $value) {
	      $filecontents[$name] = explode("|", trim($value));
	      foreach ($filecontents[$name] as $name2 => $value2) {
	        $value2 = str_replace("&#124;", "|", $value2);
	        $value2 = str_replace("__NEWL__", "\r\n", $value2);
	        $filecontents[$name][$name2] = $value2;
	      }
	    }
	    return $filecontents;
	  }

	  ////////////
	  // Save XFields Data to a file, used when a news item is added/edited
	  function xfieldsdatasave($data) {
	    global $cutepath;
	    foreach ($data as $id => $xfieldsdata) {
	      foreach ($xfieldsdata as $xfielddataname => $xfielddatavalue) {
	        if ($xfielddatavalue == "") { unset($xfieldsdata[$xfielddataname]); continue;}
	        $xfielddataname = stripslashes($xfielddataname);
	        $xfielddatavalue = stripslashes($xfielddatavalue);
	        $xfielddataname = str_replace("|", "&#124;", $xfielddataname);
	        $xfielddataname = str_replace("\r\n", "__NEWL__", $xfielddataname);
	        $xfielddatavalue = str_replace("|", "&#124;", $xfielddatavalue);
	        $xfielddatavalue = str_replace("\r\n", "__NEWL__", $xfielddatavalue);
	        $filecontents[$id][] = "$xfielddataname|$xfielddatavalue";
	      }
	      $filecontents[$id] = "$id|>|" . implode("||", $filecontents[$id]);
	    }
	    $filecontents = @implode("\r\n", $filecontents);

	    $filehandle = fopen("$cutepath/data/xfields-data.txt", "w");
	    if (!$filehandle)
	    msg("error", "Ошибка модуля", "Невозможно загрузить файл \"{$GLOBALS["cutepath"]}/data/xfields.txt\". Проверьте существование файла и выставленные на него права.");
	    fwrite($filehandle, $filecontents);
	    fclose($filehandle);
	  }
	  ////////////
	  // Load XFields Data from a file, used when a your news is displayed or when you edit a news item.
	  function xfieldsdataload() {
	    $filecontents = file("{$GLOBALS["cutepath"]}/data/xfields-data.txt");
	    if (!is_array($filecontents))
	    msg("error", "Ошибка модуля", "Невозможно загрузить файл \"{$GLOBALS["cutepath"]}/data/xfields-data.txt\", проверьте, существует ли файл и выставлены ли на него необходимые права.");
$data = array();
	    foreach ($filecontents as $name => $value) {
	      list($id, $xfieldsdata) = explode("|>|", trim($value), 2);
	      $xfieldsdata = explode("||", $xfieldsdata);
	      foreach ($xfieldsdata as $xfielddata) {
	        list($xfielddataname, $xfielddatavalue) = explode("|", $xfielddata);
	        $xfielddataname = str_replace("&#124;", "|", $xfielddataname);
	        $xfielddataname = str_replace("__NEWL__", "\r\n", $xfielddataname);
	        $xfielddatavalue = str_replace("&#124;", "|", $xfielddatavalue);
	        $xfielddatavalue = str_replace("__NEWL__", "\r\n", $xfielddatavalue);
	        $data[$id][$xfielddataname] = $xfielddatavalue;
	      }
	    }
	    return $data;
	  }

	  ////////////
	  // Move an array item
	  function array_move(&$array, $index1, $dist) {
	    $index2 = $index1 + $dist;
	    if ($index1 < 0 or
	        $index1 > count($array) - 1 or
	        $index2 < 0 or
	        $index2 > count($array) - 1) {
	      return false;
	    }
	    $value1 = $array[$index1];

	    $array[$index1] = $array[$index2];
	    $array[$index2] = $value1;

	    return true;
	  }
	}

  $xf_inited = true;
}

$xfields = xfieldsload();
$xfieldsaction = !empty($xfieldsaction) ? $xfieldsaction : "";
switch ($xfieldsaction) {
  case "configure":
  
$xfieldssubaction = !empty($xfieldssubaction) ? $xfieldssubaction : "";
  
    switch ($xfieldssubaction) {
      case "delete":
        if (!isset($xfieldsindex)) {
          msg("error", "Ошибка модуля", "Выберите поле, которое хотите удалить!<br><br><a href=\"javascript:history.go(-1)\">Вернуться назад</a>");
        }
        msg("options", "Удаление поля", "Вы действительно хотите удалить выбранное поле?<br /><br /><a href=\"$PHP_SELF?plugin=xfields&amp;action=xfields&amp;xfieldsaction=configure&amp;xfieldsindex=$xfieldsindex&amp;xfieldssubaction=delete2\">[Да]</a>&nbsp;&nbsp;<a href=\"$PHP_SELF?plugin=xfields&amp;action=xfields&amp;xfieldsaction=configure\">[Нет]</a>");
        break;
      case "delete2":
        if (!isset($xfieldsindex)) {
          msg("error", "Ошибка модуля", "Выберите поле, которое хотите удалить!<br><br><a href=\"javascript:history.go(-1)\">Вернуться назад</a>");
        }
        unset($xfields[$xfieldsindex]);
        @xfieldssave($xfields);
        break;
      case "moveup":
        if (!isset($xfieldsindex)) {
          msg("error", "Ошибка модуля", "Выберите поле, которое хотите сдвинуть!<br><br><a href=\"javascript:history.go(-1)\">Вернуться назад</a>");
        }
        array_move($xfields, $xfieldsindex, -1);
        @xfieldssave($xfields);
        break;
      case "movedown":
        if (!isset($xfieldsindex)) {
          msg("error", "Ошибка модуля", "Выберите поле, которое хотите сдвинуть!<br><br><a href=\"javascript:history.go(-1)\">Вернуться назад</a>");
        }
        array_move($xfields, $xfieldsindex, -1);
        @xfieldssave($xfields);
        break;
      case "add":
        $xfieldsindex = count($xfields);
        // Fall trough to edit
      case "edit":
        if (!isset($xfieldsindex)) {
          msg("error", "Ошибка модуля", "Выберите поле, которое хотите редактировать!<br><br><a href=\"javascript:history.go(-1)\">Вернуться назад</a>");
        }

        if (empty($editedxfield)) {
          $editedxfield = !empty($xfields[$xfieldsindex]) ? $xfields[$xfieldsindex] : array();
        } elseif (strlen(trim($editedxfield[0])) > 0 and
            strlen(trim($editedxfield[1])) > 0) {
          foreach ($xfields as $name => $value) {
            if ($name != $xfieldsindex and $value[0] == $editedxfield[0]) {
              msg("error", "Ошибка модуля", "Поля с таким названием уже существует!<br><br><a href=\"javascript:history.go(-1)\">Вернуться назад</a>");
            }
          }
          $editedxfield[0] = strtolower(trim($editedxfield[0]));
          if ($editedxfield[2] == "custom") {
            $editedxfield[2] = $editedxfield["2_custom"];
          }
          settype($editedxfield[2], "string");
          if ($editedxfield[3] == "select") {
            $options = array();
            foreach (explode("\r\n", $editedxfield["4_select"]) as $name => $value) {
              $value = trim($value);
              if (!in_array($value, $options)) {
                $options[] = $value;
              }
            }
            if (count($options) < 2) {
            msg("error", "Ошибка модуля", "Если Вы выбираете список, то Вы должны вписать два или более пункта с различным значением!<br><br><a href=\"javascript:history.go(-1)\">Вернуться назад</a>");
            }
            $editedxfield[4] = implode("\r\n", $options);
          } else {
            $editedxfield[4] = $editedxfield["4_{$editedxfield[3]}"];
          }
          unset($editedxfield["2_custom"], $editedxfield["4_text"], $editedxfield["4_textarea"], $editedxfield["4_select"]);
          if (!empty($editedxfield[3]) and $editedxfield[3] == "select") {
            $editedxfield[5] = 0;
          } else {
            $editedxfield[5] = ($editedxfield[5] == "on" ? 1 : 0);
          }
          ksort($editedxfield);

          $xfields[$xfieldsindex] = $editedxfield;
          ksort($xfields);
          @xfieldssave($xfields);
          break;
        } else {
          msg("error", "Ошибка модуля", "Введите название и описание поля!<br><br><a href=\"javascript:history.go(-1)\">Вернуться назад</a>");
        }
        echoheader("options", (($xfieldssubaction == "add") ? "Добавить новое" : "Редактировать") . " поле");
        $checked = (!empty($editedxfield[5]) ? " checked" : "");
?>
    <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="get" name="xfieldsform">
      <script language="javascript">
      function ShowOrHideEx(id, show) {
        var item = null;
        if (document.getElementById) {
          item = document.getElementById(id);
        } else if (document.all) {
          item = document.all[id];
        } else if (document.layers){
          item = document.layers[id];
        }
        if (item && item.style) {
          item.style.display = show ? "" : "none";
        }
      }
      function onTypeChange(value) {
        ShowOrHideEx("default_text", value == "text");
        ShowOrHideEx("default_textarea", value == "textarea");
        ShowOrHideEx("select_options", value == "select");
        ShowOrHideEx("optional", value != "select");
      }
      function onCategoryChange(value) {
        ShowOrHideEx("category_custom", value == "custom");
      }
      </script>
      <input type="hidden" name="plugin" value="xfields">
      <input type="hidden" name="action" value="xfields">
      <input type="hidden" name="xfieldsaction" value="configure">
      <input type="hidden" name="xfieldssubaction" value="edit">
      <input type="hidden" name="xfieldsindex" value="<?php echo $xfieldsindex?>">
      <p>
        </b>Название поля (по-английски)</b><br />
        <input style="width: 400px;" type="text" name="editedxfield[0]" value="<?php echo (!empty($editedxfield[0]) ? htmlspecialchars($editedxfield[0]) : ""); ?>" />
      <br /><br />
        </b>Описание поля (по-русски)</b><br />
        <input style="width: 400px;" type="text" name="editedxfield[1]" value="<?php echo (!empty($editedxfield[1]) ? htmlspecialchars($editedxfield[1]) : ""); ?>" />
      <br /><br />
<?php
		/*
        $all_cats = c_array('categories');
        $cat_options = "";
        $cat_selected = false;
        foreach($all_cats as $cat_line)
        {
          $cat_arr = explode("|", $cat_line);
          $cat_arr[1] = stripslashes(preg_replace(array("'\"'", "'\''"), array("&quot;", "&#039;"), $cat_arr[1]));
          if ($cat_arr[0] == $editedxfield[2]) {
            $cat_selected = true;
            $cat_options .= "<option value=\"$cat_arr[0]\" selected>$cat_arr[1]</option>";
          } else {
            $cat_options .= "<option value=\"$cat_arr[0]\">$cat_arr[1]</option>";
          }
        }
        if ($cat_options != "") {
?>

	</b>Категория:</b>
          <br />
          <select style="width: 400px; border: 0;" name="editedxfield[2]" id="category" onchange="onCategoryChange(this.value)">
            <option value=""<?php echo ($editedxfield[2] == "") ? " selected" : ""?>>Все</option>
            <?php echo $cat_options?>
            <option value="custom"<?php echo ($editedxfield[2] != "" and !$cat_selected) ? " selected" : ""?>>Выбрать...</options>
          </select>
        <br />
        <span id="category_custom" <?php echo ($editedxfield[2] == "" or $cat_selected) ? " style=\"display: none;\"" : ""?>>
          <input type="text" style="width: 400px;" name="editedxfield[2_custom]" value="<?php echo htmlspecialchars($editedxfield[2])?>" /><br />
          <font style="font-size: 9px;">впишите ID категорий через запятую</font>
          <br />
        </span>
        <noscript>
        <span style="color: red;">Внимание! Для нормальной работы необходимо включить JavaScript!</span>
        </noscript>
      <br /><br />
<?php
        }
        */
?>
      </b>Тип поля:</b>
        <br />
        <select style="width: 400px;" name="editedxfield[3]" id="type" onchange="onTypeChange(this.value)" />
          <option value="text"<?php echo (!empty($editedxfield[3]) and $editedxfield[3] != "textarea") ? " selected" : ""?>>Одна строка</option>
          <option value="textarea"<?php echo (!empty($editedxfield[3]) and $editedxfield[3] == "textarea") ? " selected" : ""?>>Несколько строк</option>
          <option value="select"<?php echo (!empty($editedxfield[3]) and $editedxfield[3] == "select") ? " selected" : ""?>>Список</option>
        </select>
      </p>
      <p id="default_text">
        </b>Значение по умолчанию:</b>
        <br />
        <input style="width: 400px;" type="text" name="editedxfield[4_text]" value="<?php echo (!empty($editedxfield[4]) and !empty($editedxfield[3]) and $editedxfield[3] == "text") ? htmlspecialchars($editedxfield[4]) : ""?>" />
      </p>
      <p id="default_textarea">
        </b>Значение по умолчанию:</b>
        <br />
        <textarea style="width: 400px; height: 150px;" name="editedxfield[4_textarea]"><?php echo ($editedxfield[3] == "textarea") ? htmlspecialchars($editedxfield[4]) : ""?></textarea>
      </p>
      <p id="select_options">
        </b>Значения по умолчанию:</b>
        <br />
        <textarea style="width: 400px; height: 100px;" name="editedxfield[4_select]"><?php echo ($editedxfield[3] == "select") ? htmlspecialchars($editedxfield[4]) : ""?></textarea>
        <br /><font style="font-size: 9px;">на одной строке одно значение</font>
      </p>
      <p id="optional">
      <span style="width: 400px;">
      <input type="checkbox" name="editedxfield[5]"<?php echo $checked?> id="editxfive" />
    <label for="editxfive"> Использовать при желании <font style="font-size: 9px;">(можно оставить поле пуcтым)</label></span>
      </p>
      <p>
        <input style="width: 100px;" type="submit" accesskey="s" value=" Сохранить " />
      </p>
    </form>
    <script type="text/javascript">
    <!--
      var item_type = null;
      var item_category = null;
      if (document.getElementById) {
        item_type = document.getElementById("type");
        item_category = document.getElementById("category");
      } else if (document.all) {
        item_type = document.all["type"];
        item_category = document.all["category"];
      } else if (document.layers) {
        item_type = document.layers["type"];
        item_category = document.layers["category"];
      }
      if (item_type) {
        onTypeChange(item_type.value);
        onCategoryChange(item_category.value);
      }
    // -->
    </script>
<?php
        echofooter();
        break;
      default:
        echoheader("options", "Дополнительные поля");
?>

<table cellspacing=0 cellpadding=0><tr><td width=25 align=middle><img border=0 src=admin/images/help_small.gif></td>
<td>&nbsp;<a onClick="javascript:Help('xfields')" href="#">Что такое дополнительные поля?</a></td></tr></table>

<table border=0 cellpadding=0 cellspacing=0 width=645>

<tr><td width=654 height=11><img height=20 border=0 src=skins/images/blank.gif width=1></tr><tr><td width=654 height=1>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="get" name="xfieldsform">
<input type="hidden" name="plugin" value="xfields">
<input type="hidden" name="action" value="xfields">
<input type="hidden" name="xfieldsaction" value="configure">
<input type="hidden" name="xfieldssubactionadd" value="">
<table border="0" cellspacing="2" cellpadding="2" width=100%>

<tr>
<td class="panel"></b>Название</b></td>
<td class="panel"></b>Категории</b></td>
<td class="panel"></b>Тип</b></td>
<td class="panel"></b>При желании</b></td>
<td class="panel" width=10>&nbsp;</td>
</tr>

<?php
        if (count($xfields) == 0) {
          echo "<tr><td colspan=\"5\" align=\"center\"><br /><br />Дополнительных полей нет!</td></tr>";
        } else {
          foreach ($xfields as $name => $value) {
            $rowstyle = ($name % 2) ? $rowstyleodd : $rowstyleeven;
?>
        <tr>
          <td <?php echo $rowstyle?>>
            <?php echo htmlspecialchars($value[0])?>
          </td>
          <td <?php echo $rowstyle?>>
            <?php echo (trim($value[2]) ? htmlspecialchars($value[2]) : "Все")?>
          </td>
          <td <?php echo $rowstyle?>>
            <?php echo (($value[3] == "text") ? "Одна строка" : "")?>
            <?php echo (($value[3] == "textarea") ? "Несколько строк" : "")?>
            <?php echo (($value[3] == "select") ? "Список" : "")?>
          </td>
          <td <?php echo $rowstyle?>>
            <?php echo ($value[5] != 0 ? "Да" : "Нет")?>
          </td>
          <td <?php echo $rowstyle?>>
            <input type="radio" name="xfieldsindex" value="<?php echo $name?>">
          </td>
        </tr>
<?php
          }
        }
?>
      <tr>
        <td colspan="5" style="text-align: right; padding-top: 10px;">
          <?php if (count($xfields) > 0) { ?>
          Выберите действие:
          <select name="xfieldssubaction">
            <option value="edit">Редактировать</option>
            <option value="delete">Удалить</option>
            <option value="moveup">Сдвинуть вверх</option>
            <option value="movedown">Сдвинуть вниз</option>
          </select>
          <input type="submit" value="OK" onclick="document.forms['xfieldsform'].xfieldssubactionadd.value = '';" />
          <?php } ?>
          <input type="submit" value=" Добавить поле " onclick="document.forms['xfieldsform'].xfieldssubactionadd.value = 'add';" />
        </td>
      </tr>
    </table>
  </form>
</table>
<?php
      echofooter();
    }
    break;
    
    
  case "list":
    $xfieldsdata = xfieldsdataload();
    foreach ($xfields as $name => $value) {
      $fieldname = htmlspecialchars($value[0]);
      if (!$xfieldsadd) {
        $fieldvalue = $xfieldsdata[$xfieldsid][$value[0]];
        $fieldvalue = replace_news("admin", $fieldvalue);
        $fieldvalue = htmlspecialchars($fieldvalue);
      } elseif ($value[3] != "select") {
        $fieldvalue = htmlspecialchars($value[4]);
      }

      $holderid = "xfield_holder_$fieldname";

      if ($value[3] == "textarea") {
      $output = <<<HTML
<fieldset id="$holderid"><legend>$value[1][if-optional] (при желании)[/if-optional]</legend>
<textarea name="xfield[$fieldname]" id="xf_$fieldname">$fieldvalue</textarea>
</fieldset>
HTML;
      } elseif ($value[3] == "text") {
        $output = <<<HTML
<fieldset id="$holderid"><legend>$value[1][if-optional] (при желании)[/if-optional]</legend>
<input type="text" name="xfield[$fieldname]" id="xfield[$fieldname]" value="$fieldvalue">
</fieldset>
HTML;
      } elseif ($value[3] == "select") {
        $output = <<<HTML
<fieldset id="$holderid"><legend>$value[1]</legend>
<select name="xfield[$fieldname]">
HTML;
        foreach (explode("\r\n", $value[4]) as $index => $value) {
          $value = htmlspecialchars($value);
          $output .= "<option value=\"$index\"" . ($fieldvalue == $value ? " selected" : "") . ">$value</option>\r\n";
        }
        $output .= "</select></fieldset>";
      }
      $output = preg_replace("'\\[if-optional\\](.*?)\\[/if-optional\\]'s", $value[5] ? "\\1" : "", $output);
      $output = preg_replace("'\\[not-optional\\](.*?)\\[/not-optional\\]'s", $value[5] ? "" : "\\1", $output);
      $output = preg_replace("'\\[if-add\\](.*?)\\[/if-add\\]'s", ($xfieldsadd) ? "\\1" : "", $output);
      $output = preg_replace("'\\[if-edit\\](.*?)\\[/if-edit\\]'s", (!$xfieldsadd) ? "\\1" : "", $output);
      echo $output;
    }
    /*
    echo <<<HTML
<script type="text/javascript">
<!--
  var item = null;
  if (document.getElementById) {
    item = document.getElementById("category");
  } else if (document.all) {
    item = document.all["category"];
  } else if (document.layers) {
    item = document.layers["category"];
  }
  if (item) {
    onCategoryChange(item.value);
  }
// -->
</script>
HTML;
*/
    break;
  case "init":
    $postedxfields = !empty($_POST["xfield"]) ? $_POST["xfield"] : "";
    $newpostedxfields = array();
    foreach ($xfields as $name => $value) {
      if ($value[2] != "" and
          !in_array($category, explode(",", $value[2]))) {
        continue;
      }
      if ($value[5] == 0 and
          $postedxfields[$value[0]] == "") {
        msg("error", "XFields Error", "You should fill in all required fields.<br /><a href=\"javascript:history.go(-1)\">go back</a>");
      }
      if ($value[3] == "select") {
        $options = explode("\r\n", $value[4]);
        $postedxfields[$value[0]] = $options[$postedxfields[$value[0]]];
      }
      $newpostedxfields[$value[0]] = replace_news("add", $postedxfields[$value[0]], $n_to_br, $use_html);
    }
    $postedxfields = $newpostedxfields;
    break;
  case "save": // Make sure it is first initialized
    if (!empty($postedxfields)) {
      $xfieldsdata = xfieldsdataload();
      $xfieldsdata[$xfieldsid] = $postedxfields;
      @xfieldsdatasave($xfieldsdata);
    }
    break;
  case "delete":
    $xfieldsdata = xfieldsdataload();
    unset($xfieldsdata[$xfieldsid]);
    @xfieldsdatasave($xfieldsdata);
    break;
  case "templatereplace":
    $xfieldsdata = xfieldsdataload();
    $xfieldsoutput = $xfieldsinput;

    foreach ($xfields as $value) {
      $preg_safe_name = preg_quote($value[0], "'");

      if ($value[5] != 0) {
        if (empty($xfieldsdata[$xfieldsid][$value[0]])) {
          $xfieldsoutput = preg_replace("'\\[xfgiven_{$preg_safe_name}\\].*?\\[/xfgiven_{$preg_safe_name}\\]'is", "", $xfieldsoutput);
        } else {
          $xfieldsoutput = preg_replace("'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "\\1", $xfieldsoutput);
        }
      }
      $xfieldsoutput = preg_replace("'\\[xfvalue_{$preg_safe_name}\\]'i", $xfieldsdata[$xfieldsid][$value[0]], $xfieldsoutput);
    }
    break;
  case "templatereplacepreview":
    $xfieldsoutput = $xfieldsinput;

    foreach ($xfields as $value) {
      $preg_safe_name = preg_quote($value[0], "'");

      if ($value[3] == "select") {
        $options = explode("\r\n", $value[4]);
        $xfield[$value[0]] = $options[$xfield[$value[0]]];
      }
      $xfield[$value[0]] = replace_news("add", $xfield[$value[0]], $n_to_br, $use_html);

      if ($value[5] != 0) {
        if (empty($xfield[$value[0]])) {
          $xfieldsoutput = preg_replace("'\\[xfgiven_{$preg_safe_name}\\].*?\\[/xfgiven_{$preg_safe_name}\\]'is", "", $xfieldsoutput);
        } else {
          $xfieldsoutput = preg_replace("'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "\\1", $xfieldsoutput);
        }
      }
      $xfieldsoutput = preg_replace("'\\[xfvalue_{$preg_safe_name}\\]'i", $xfield[$value[0]], $xfieldsoutput);
    }
    break;
  case "categoryfilter":
    echo <<<HTML
  <script type="text/javascript">
  function ShowOrHideEx(id, show) {
    var item = null;

    if (document.getElementById) {
      item = document.getElementById(id);
    } else if (document.all) {
      item = document.all[id];
    } else if (document.layers){
      item = document.layers[id];
    }
    if (item && item.style) {
      item.style.display = show ? "" : "none";
    }
  }
  function xfInsertText(text, element_id) {
    var item = null;
    if (document.getElementById) {
      item = document.getElementById(element_id);
    } else if (document.all) {
      item = document.all[element_id];
    } else if (document.layers){
      item = document.layers[element_id];
    }
    if (item) {
      item.focus();
      item.value = item.value + " " + text;
      item.focus();
    }
  }
  function onCategoryChange(value) {

HTML;
    foreach ($xfields as $value) {
      $categories = str_replace(",", "||value==", $value[2]);
      if ($categories) {
        echo "ShowOrHideEx(\"xfield_holder_{$value[0]}\", value == $categories);\r\n";
      }
    }
    echo "  }\r\n</script>";
    break;
  default:
  if (function_exists('msg'))
    msg("error", "Ошибка модуля", "Недействительный запрос, возможно ошибка в модуле");
}
?>