<?php
$template->set_frame('fullpage','green');
if (!$user->login($_SESSION['nick'],$_SESSION['passwd'])) {
    die('You are not logged in');  
}
$content = '<h2>{LANG_SETTING}</h2><a href="{LINK_CHANGEPW}">{LANG_CHANGEPW}</a><br />
<a href="{LINK_CHANGEDETAILS}">{LANG_CHANGEDETAILS}</a><br />
<a href="{LINK_CHANGETAGS}">{LANG_CHANGETAGS}</a><br /><br />';
if ($user->is_admin($_SESSION['nick'],$_SESSION['passwd'])) {
    $content .= '<h2>{LANG_ADMIN}</h2>
    <a href="/'.$language.'/news_editor/neu.htm">{LANG_WRITENEWS}</a>';
}
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('LOGIN','{LANG_LOGOUT}');
$template->replace('REGISTER','{LANG_OPTIONS}');
$template->replace('LINK_LOGIN','{LINK_LOGOUT}');
$template->replace('LINK_REGISTER','{LINK_OPTIONS}');
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('LINK_GERMAN','/de/optionen.htm');
$template->replace('LINK_ENGLISH','/en/options.htm');
$template->replace('META_TITLE','{LANG_OPTIONS}');
$template->replace('FULLPAGE_HEADER','{LANG_OPTIONS}');
?>
