<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
$user = new jforg_user();
if (in_array($_GET['lang'],$config['languages'])) {
    $language = $_GET['lang'];
} else {
    die('Language ist nicht bekannt');
}
$template = new jforg_template();
$user = new jforg_user();
$template->set_path('design');
$template->set_frame('fullpage','green');
$template->hover_on('green');
SESSION_START();
if (!$user->login($_SESSION['nick'],$_SESSION['passwd'])) {
    die('You are not logged in');  
}
$content = '<a href="{LINK_CHANGEPW}">{LANG_CHANGEPW}</a><br />
<a href="{LINK_CHANGEDETAILS}">{LANG_CHANGEDETAILS}</a><br />
<a href="{LINK_CHANGETAGS}">{LANG_CHANGETAGS}</a><br /><br />
<a href="{LINK_RMACC}">{LANG_RMACC}</a>';
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
$template->translate($language);
include('includes/links.php');
$template->write();
?>