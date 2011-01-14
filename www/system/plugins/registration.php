<?php
#_strawberry
if (!defined("str_plug")) {
header("Location: ../../index.php");
exit;
}

/*
Plugin Name:    Регистрация
Plugin URI:     http://cutenews.ru
Description:   Плагин позволяет посетителям вашего сайта самостоятельно зарегистрироваться в системе. Возможно подключение с разными шаблонами. Установка уровня регистрации (Админ, Редактор, Журналист и Комментатор) и защиты от флуда регистрациями. + Корректные даты визита. + Защита от регистрации ботов. + Правила проекта.
Version:        1.6
Author:         Пашка, MrMiksar
Author URI:     mailto:pashka.89@mail.ru
Application:     Strawberry
*/





class userRegistration
{



    function userRegistration() {
    global $xfields;
        if(!is_a($xfields, 'XFieldsData')) {
          $xfields = new XFieldsData();
        }
        $this -> lang = straw_lang('plugins/registration');
        $this -> settings = new PluginSettings('registration');
    }


    function showForm($tpl = 'default') {
    global $sql, $xfields, $config, $_FILES, $allowed_extensions;
    $that_time = time;
    $step = (!empty($_POST['step']) and is_numeric($_POST['step'])) ? intval($_POST['step']) : 1;
        switch($step)
        {
        
        
            default:
//////////////////////////////////////////////////////////////////////////////


            
//////////////////////////////////////////////////////////////////////////////
            case 1:
            
$tpl = file_exists(stpl.'/registration/'.$tpl.'/form.tpl') ? GetContents(stpl.'/registration/'.$tpl.'/form.tpl') : GetContents(stpl.'/registration/default/form.tpl');
$res = file_read(rule_file);
$config['terms'] = !empty($config['terms']) ? $config['terms'] : t("для продолжения регистрации, вам нужно поставить галочку ниже этого поля.");
$formterm = "<div class=\"text\">&nbsp; ".t("Правила проекта").":<br><textarea style=\"width: 100%; height: 100px;\" name=\"register[rule]\">".replace_comment('admin', $res)."</textarea><div class=\"description\"><input type=\"checkbox\" name=\"register[agree]\" value=\"true\"> ".t("Правила прочитал(а) и согласен(на).")."</div></div>";
$aarw = $this -> settings -> settings[getip()];

                    $replaces = array(
                        '{lang.RegNewUser}' => $this -> lang['regNewUser'],
                        '{lang.Login}'      => $this -> lang['regLogin'],
                        '{lang.Passw}'      => $this -> lang['regPassw'],
                        '{lang.Re}'         => $this -> lang['regRe'],
                        '{lang.Nick}'       => $this -> lang['regNick'],
                        '{lang.EMail}'      => $this -> lang['regEmail'],
                        '{lang.AutoLogin}'  => $this -> lang['regAutoLogin'],
                        '{reg.pin}' => (!empty($config['pin_auth']) ? pin_cod_auth('reg', 'reg') : ''),
                        '{lang.terms}' => (!empty($config['uterms']) ? $formterm : ''),
                    );
                    

                    if(!empty($aarw))
                    {
                        $this -> settings -> settings[getip()] = array('warns' => 0);
                    }
                break;
//////////////////////////////////////////////////////////////////////////////










//////////////////////////////////////////////////////////////////////////////
            case 2:
                    if(($config['preventRegFlood'] === 1 && (time() - $this -> settings -> settings[$_SERVER['REMOTE_ADDR']]['LastRegTime']) < $config['RegDelay']) or (pin_check('reg') and $config['pin_auth']))
                    {
                        $tpl = file_exists(stpl.'/registration/'.$tpl.'/regError.tpl') ? GetContents(stpl.'/registration/'.$tpl.'/regError.tpl') : GetContents(stpl.'/registration/default/regError.tpl');
                        $replaces = array(
                            '{lang.Error}'     => $this -> lang['regError'],
                            '{lang.ErrorText}' => $this -> lang['regErrorFlood'],
                            '{lang.ErrorPin}' => ((pin_check('reg') and !empty($config['pin_auth'])) ? ($this -> lang['regErrorPin']) : '')
                        );

                        $this -> settings -> settings[$_SERVER['REMOTE_ADDR']]['warns']++;

                        if($this -> settings -> settings[$_SERVER['REMOTE_ADDR']]['warns'] >= $config['banOnWarns'])
                        {
                            if(!$sql -> select(array('table' => 'ipban', 'where' => array('ip = '.$_SERVER['REMOTE_ADDR'])))){
                                $sql -> insert(
                                    array(
                                        'table'  => 'ipban',
                                        'values' => array(
                                            'ip' => $_SERVER['REMOTE_ADDR']
                                        )
                                    )
                                );
                            }
                        }

                        break;
                    }
//////////////////////////////////////////////////////////////////////////////









//////////////////////////////////////////////////////////////////////////////
                    if($_POST['register']['passw1'] != $_POST['register']['passw2'] || $_POST['register']['passw1'] != mysql_escape_string($_POST['register']['passw1']))
                    {
                        $tpl = file_exists(stpl.'/registration/'.$tpl.'/regError.tpl') ? GetContents(stpl.'/registration/'.$tpl.'/regError.tpl') : GetContents(stpl.'/registration/default/regError.tpl');
                        $replaces = array(
                            '{lang.Error}'     => $this -> lang['regError'],
                            '{lang.ErrorText}' => $this -> lang['regErrorPasswords'],
                            '{lang.ErrorPin}' => ((pin_check('reg') and !empty($config['pin_auth'])) ? ($this -> lang['regErrorPin']) : '')
                        );

                        break;
                    }
//////////////////////////////////////////////////////////////////////////////






//////////////////////////////////////////////////////////////////////////////
			        if(empty($_POST['register']['agree']) and !empty($config['uterms']))
			        {
			            $tpl = file_exists(stpl.'/registration/'.$tpl.'/regError.tpl') ? GetContents(stpl.'/registration/'.$tpl.'/regError.tpl') : GetContents(stpl.'/registration/default/regError.tpl');
			            $replaces = array(
			            	'{lang.Error}'     => $this -> lang['regError'],
			            	'{lang.ErrorText}' => $this -> lang['regErrorRule'],
			            	'{lang.ErrorPin}' => ((pin_check('reg') and !empty($config['pin_auth'])) ? ($this -> lang['regErrorPin']) : '')
						);

						break;
				}
//////////////////////////////////////////////////////////////////////////////
				
				
				
				
				
//////////////////////////////////////////////////////////////////////////////
                    if(!preg_match('/^[\.a-z0-9_\-]+[@][a-z0-9_\-]+([.][a-z0-9_\-]+)+[a-z]{1,4}$/i', $_POST['register']['email']))
                    {
                        $tpl = file_exists(stpl.'/registration/'.$tpl.'/regError.tpl') ? GetContents(stpl.'/registration/'.$tpl.'/regError.tpl') : GetContents(stpl.'/registration/default/regError.tpl');
                        $replaces = array(
                            '{lang.Error}'     => $this -> lang['regError'],
                            '{lang.ErrorText}' => $this -> lang['regErrorMail'],
                            '{lang.ErrorPin}' => ((pin_check('reg') and !empty($config['pin_auth'])) ? ($this -> lang['regErrorPin']) : '')
                        );

                        break;
                    }
//////////////////////////////////////////////////////////////////////////////





//////////////////////////////////////////////////////////////////////////////
                    if($sql -> select(array('table' => 'users', 'where' => array('username = '.mysql_escape_string($_POST['register']['login']), 'or', 'name = '.mysql_escape_string($_POST['register']['nick'])))))
                    {
                        $tpl = file_exists(stpl.'/registration/'.$tpl.'/regError.tpl') ? GetContents(stpl.'/registration/'.$tpl.'/regError.tpl') : GetContents(stpl.'/registration/default/regError.tpl');
                        $replaces = array(
                            '{lang.Error}'     => $this -> lang['regError'],
                            '{lang.ErrorText}' => $this -> lang['regErrorName'],
                            '{lang.ErrorPin}' => ((pin_check('reg') and !empty($config['pin_auth'])) ? ($this -> lang['regErrorPin']) : '')
                        );

                        break;
                    }
//////////////////////////////////////////////////////////////////////////////





//////////////////////////////////////////////////////////////////////////////
                    $this -> settings -> settings[$_SERVER['REMOTE_ADDR']]['LastRegTime'] = time();

                    $sql -> insert(
                        array(
                            'table'  => 'users',
                            'values' => array(
                                'date'      => $that_time,
                                'usergroup' => $config['regLevel'],
                                'username'  => mysql_escape_string($_POST['register']['login']),
                                'password'  => md5x($_POST['register']['passw1']),
                                'name'      => ($_POST['register']['nick'] ? mysql_escape_string($_POST['register']['nick']) : mysql_escape_string($_POST['register']['login'])),
                                'mail'      => $_POST['register']['email'],
                                'hide_mail' => 1,
                           'last_visit' => $that_time
                            )
                        )
                    );

                    $tpl = file_exists(stpl.'/registration/'.$tpl.'/regOk.tpl') ? GetContents(stpl.'/registration/'.$tpl.'/regOk.tpl') : GetContents(stpl.'/registration/default/regOk.tpl');

                    $replaces = array(
                        '{lang.Ok}' => $this -> lang['regOk'],
                        '{reg.pin}' => (!empty($config['pin_auth']) ? pin_cod_auth("login", "auth") : ''),
                    );
//////////////////////////////////////////////////////////////////////////////






//////////////////////////////////////////////////////////////////////////////
                    if(!empty($_POST['register']['autologin']))
                    {
                        $replaces = array(
                            '{lang.Ok}' => $this -> lang['regOkAndLogined'],
                            '{reg.pin}' => (!empty($config['pin_auth']) ? pin_cod_auth("login", "auth") : ''),
                        );

                        if(session)
                        {
                            $_SESSION['username']      = mysql_escape_string($_POST['register']['login']);
                            $_SESSION['md5_password']  = md5x($_POST['register']['passw1']);
                            $_SESSION['ip']            = $_SERVER['REMOTE_ADDR'];
                            # $_SESSION['login_referer'] = $_SERVER['HTTP_REFERER'];
                        }

                        $sql->update(
                            array(
                                'table'  => 'users',
                                'where'  => array('username = '.mysql_escape_string($_POST['register']['login'])),
                                'values' => array('last_visit' => $that_time)
                            )
                        );
                    }
                break;
        }
//////////////////////////////////////////////////////////////////////////////







        foreach($replaces as $from => $to)
        {
            $tpl = str_replace($from, $to, $tpl);
        }

        $this -> settings -> save();

        return $tpl;
    }
}




//////////////////////////////////////////////////////////////////////////////
add_action('head', 'regInit', 21);

function regInit() {
global $registration;
$registration = new userRegistration();
}



//////////////////////////////////////////////////////////////////////////////
function regForm($template) {
global $registration;
return $registration -> showForm($template);
}



//////////////////////////////////////////////////////////////////////////////
add_filter('help-sections', 'regAdminHelp', 21);

function regAdminHelp($help_sections) {
$lang['regmod'] = straw_lang('plugins/registration');
$help_sections['regmod'] = $lang['regmod']['regHelp'];
return $help_sections;
}



?>