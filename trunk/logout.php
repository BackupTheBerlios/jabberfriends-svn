<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
include('classes/jforg_gettext.php');
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
$text = get_text(1,$language);
SESSION_START();
if ($user->login($_SESSION['nick'],$_SESSION['passwd'])) {
    SESSION_DESTROY();
    $template->replace('LOGIN','{LANG_LOGIN}');
    $template->replace('REGISTER','{LANG_REGISTER}');
    $template->replace('LOGIN','{LANG_LOGIN}');
    $template->replace('REGISTER','{LANG_REGISTER}');
    $template->replace('LINK_GERMAN','/de/abmelden.htm');
    $template->replace('LINK_ENGLISH','/en/logout.htm');
    $template->replace('META_TITLE','{LANG_LOGOUT}');
    $template->replace('FULLPAGE_HEADER','{LANG_LOGOUT}');
    $template->replace('FULLPAGE_TEXT',$text);
    $template->translate($language);
    include('includes/links.php');
    $template->write();
} else {
    die('You are not logged in');
}
?>