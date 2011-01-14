<?php
### STRAWBERRY 1.2.x
### Mr.Miksar (c) 2009

### проверяем на наличие админ панели
$ap = !empty($ap) ? 1 : 0;

### что бы не запустили системный модуль. // blocked systems files with out this define
define("str_define", true);
### определим текущее положение // where we are?
define('root_directory', str_replace("\\", "/", dirname(__FILE__)));

### защищаем наши адреса от всякой фигни, типа XSS атак. // hack attempt
if (strpos($_SERVER['REQUEST_URI'], 'http') or strpos($_SERVER['REQUEST_URI'], '..') or strpos($_SERVER['REQUEST_URI'], '//')) {
@header('HTTP/1.1 301 Moved Permanently');
@header("Location: http://".$_SERVER['SERVER_NAME']."");
exit();
}
if (!empty($_FILES['userfile']['size']) and !intval($_FILES['userfile']['size']) and !stristr(getenv("HTTP_REFERER"), "http://".$_SERVER['SERVER_NAME'])) {
@header('HTTP/1.1 301 Moved Permanently');
@header("Location: http://".$_SERVER['SERVER_NAME']."");
exit();
}

### установка локальной кодировки // set locale
$strw_sl = setlocale(LC_ALL, 'ru_RU.CP1251', 'ru_RU.cp1251', 'ru_RU', 'RU', 'koi8-r', 'ru_RU.KOI8-R', 'UTF-8', 'ru_RU.UTF-8', 'utf-8', 'ru_RU.utf-8', 'rus_RUS.CP1251', 'Russian_Russia.1251', 'russian');
if (!empty($strw_sl)) setlocale(LC_CTYPE, $strw_sl); // используйте, если у вас проблемы с русскими буквами. // it`s for russians ;) 
unset($strw_sl);

### обработка ошибок // error reporting
 error_reporting(E_ALL); // Самый честный режим // true mode! I`m use it! And you mast too for writing you modifications!
# error_reporting(E_ALL & ~E_NOTICE); // выводит только предупреждения (для отладки модулей и блоков) // only notice. borring!
# error_reporting(E_ALL & ~E_DEPRECATED); // same else
# error_reporting(E_ERROR | E_PARSE); // and same else, i`m not use it. Only E_ALL!
# error_reporting(0); // блокирование всех ошибок // block all notice and error. use this in final!

/****************************************
; E_ALL             - All errors and warnings (doesn't include E_STRICT)
; E_ERROR           - fatal run-time errors
; E_RECOVERABLE_ERROR  - almost fatal run-time errors
; E_WARNING         - run-time warnings (non-fatal errors)
; E_PARSE           - compile-time parse errors
; E_NOTICE          - run-time notices (these are warnings which often result from a bug in your code, but it's possible that it was intentional (e.g., using an uninitialized variable and relying on the fact it's automatically initialized to an empty string)
; E_STRICT          - run-time notices, enable to have PHP suggest changes to your code which will ensure the best interoperability and forward compatibility of your code
; E_CORE_ERROR      - fatal errors that occur during PHP's initial startup
; E_CORE_WARNING    - warnings (non-fatal errors) that occur during PHP's initial startup
; E_COMPILE_ERROR   - fatal compile-time errors
; E_COMPILE_WARNING - compile-time warnings (non-fatal errors)
; E_USER_ERROR      - user-generated error message
; E_USER_WARNING    - user-generated warning message
; E_USER_NOTICE     - user-generated notice message
****************************************/

### подключаем основные функции системы // include our system
include_once root_directory.'/inc/defined.inc.php';

### подгружаем плагины // load a plugins
LoadActivePlugins();
run_actions('head');

?>