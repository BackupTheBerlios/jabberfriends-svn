<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
include('classes/jforg_cite.php');
include('classes/jforg_cleanurl.php');
$user = new jforg_user();
if (in_array($_GET['lang'],$config['languages'])) {
    $language = $_GET['lang'];
} else {
    die('Language ist nicht bekannt');
}
$id = $_GET['id'];
$id = $id + 0;
if (!is_int($id)) {
    die("Invalid ID - $id");
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
$template->replace('META_TITLE','{LANG_CITE}');
$template->replace('FULLPAGE_HEADER','{LANG_CITE}');
$zitat = $cite->get_by_id($id);
$content = $template->highlight_cite($zitat['zitat']).'<br /><br />{LANG_ADDEDBY} <a href="/'.$language.'/'.$member_link.'/'.$zitat['user'].'-'.cleanurl($user->get_nick($zitat['user'])).'.htm">'.$user->get_nick($zitat['user']).'</a> {LANG_ON} '.date('d.m.Y',$zitat['datetime']);
print_r($zitat);
$template->replace('FULLPAGE_TEXT',$content);
$template->highlight_cite($random_zitat);
$template->translate($language);
include('includes/links.php');
$template->write();
?>