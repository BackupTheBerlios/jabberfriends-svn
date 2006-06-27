<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
$template = new jforg_template();
$user = new jforg_user();
$template->set_path('design');
$template->set_frame('fullpage','green');
$template->hover_on('green');
if (in_array($_GET['lang'],$config['languages'])) {
    $language = $_GET['lang'];
} else {
    die('Language ist nicht bekannt');
}
$user = new jforg_user();
$user_id = $user->get_id($_SESSION['nick']);
SESSION_START();
if ($user->login($_SESSION['nick'],$_SESSION['passwd'])) {
    $template->replace('LOGIN','{LANG_LOGOUT}');
    $template->replace('REGISTER','{LANG_OPTIONS}');
    $template->replace('LINK_LOGIN','{LINK_LOGOUT}');
    $template->replace('LINK_REGISTER','{LINK_OPTIONS}');
} else {
    $template->replace('LOGIN','{LANG_LOGIN}');
    $template->replace('REGISTER','{LANG_REGISTER}');
}
$content .= '<form action="{FORM_ACTION}" method="post">
                <table cellpadding="0" cellspacing="2" border="0">';

if (!empty($_POST['submit'])) {
    $succes = $user->login($_SESSION['nick'], mysql_real_escape_string($_POST['old_pw']) );
    echo $_SESSION['nick'];
    echo $_POST['old_pw'];
    if ($succes == true){
        if($_POST['new_pw'] == $_POST['new_pw2']){ 
            if($user->change_password($_SESSION['nick'], $_POST['new_pw'])){
                $content .='<tr><td></td><td>{LANG_CHANGEPW_SUCCES}</td></tr>';
                $_SESSION['passwd'] = $_POST['new_pw'];
            }
        }else{
            $content .='<tr><td></td><td>{LANG_PW_NOT_SAME}</td></tr>';
        }
    }else{
    $content .='<tr><td></td><td>{LANG_PWUSER}!</td></tr>';
    }

}


$content .= '<tr><td>{LANG_OLD_PW}</td><td><input type="text" name="old_pw" value=""></td>';
$content .= '<tr><td>{LANG_NEW_PW}</td><td><input type="text" name="new_pw" value=""></td></tr><tr><td>{LANG_PW_AGAIN}</td><td><input type="text" name="new_pw2" value=""></td></tr><tr><td>&nbsp;</td><td><br /><input class="submit" value="{LANG_CHANGE_PW}" name="submit" type="submit" /></td></tr></table></form>';

$template->replace('FULLPAGE_TEXT',$content);
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('LINK_CHANGEPW','{LINK_CHANGEPW}');
$template->replace('LINK_REGISTER','{LANG_OPTIONS}');
$template->replace('FORM_ACTION','');
$template->replace('LINK_GERMAN','/de/passwortaendern.htm');
$template->replace('LINK_ENGLISH','/en/changepassword.htm');
$template->replace('FULLPAGE_HEADER','{LANG_CHANGEPW}');
$template->replace('META_TITLE','{LANG_CHANGEPW}');
$template->translate($language);
include('includes/links.php');
$template->write();
?>
