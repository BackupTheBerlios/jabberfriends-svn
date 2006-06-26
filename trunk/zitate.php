<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
include('classes/jforg_cite.php');
$user = new jforg_user();
if (in_array($_GET['lang'],$config['languages'])) {
    $language = $_GET['lang'];
} else {
    die('Language ist nicht bekannt');
}
$template = new jforg_template();
$user = new jforg_user();
$cite = new jforg_cite();
$template->set_path('design');
$template->set_frame('fullpage','lila');
$template->hover_on('lila');
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
$template->replace('LINK_GERMAN','/de/');
$template->replace('LINK_ENGLISH','/en/');
$template->replace('META_TITLE','JabberFriends.org');
$random_zitat = $cite->get_random($language);
$template->replace('FULLPAGE_TEXT',$template->highlight_cite($random_zitat));
$template->highlight_cite($random_zitat);
$template->translate($language);
include('includes/links.php');
$template->write();
?>