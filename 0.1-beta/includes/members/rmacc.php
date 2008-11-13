<?php
include('functions/jforg_gettext.php');
$template->set_frame('fullpage','green');
if (!$user->login($_SESSION['nick'],$_SESSION['passwd'])) {
    die('You are not logged in');  
}
if ($_POST['submit']!='') {
    if ($user->login($_SESSION['nick'],$_POST['pw'])) {
        SESSION_DESTROY();
        $user->delete_user($_SESSION['nick']);
        HEADER("Location: /$language/");
    } else {
        $error = '<em>{LANG_WRONGPW}</em><br />';
    }
}
$content .= '<form action="" method="post"><table cellpadding="0" cellspacing="2" border="0">';
$content .= '<tr><td class="left">{LANG_PW}</td><td class="right">'.$error.'<input value="" type="password" name="pw" /></td></tr>';
$content .= '<tr><td>&nbsp;</td><td><br /><br /><input class="submit" value="{LANG_RMACC}" name="submit" type="submit" /></td></tr>';
$content .= '</table></form>'; 
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('LOGIN','{LANG_LOGOUT}');
$template->replace('REGISTER','{LANG_OPTIONS}');
$template->replace('LINK_LOGIN','{LINK_LOGOUT}');
$template->replace('LINK_REGISTER','{LINK_OPTIONS}');
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('LINK_GERMAN','/de/loesche_account.htm');
$template->replace('LINK_ENGLISH','/en/remove_account.htm');
$template->replace('META_TITLE','{LANG_RMACC}');
$template->replace('FULLPAGE_HEADER','{LANG_RMACC}');
?>
