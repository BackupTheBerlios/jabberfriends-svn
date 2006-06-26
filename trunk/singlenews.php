<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
include('classes/jforg_news.php');
$user = new jforg_user();
$news = new jforg_news();
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
$template->set_path('design');
$template->set_frame('fullpage','tuerkis');
$template->hover_on('tuerkis');
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
$news = $news->get_by_id($id);
$template->replace('FULLPAGE_HEADER',htmlentities($news['title']));
$template->replace('FULLPAGE_TEXT','<i>'.date('d.m.Y H:i',$news['datetime']).'</i> '.htmlentities($news['text']));
$template->replace('LINK_GERMAN','/de/neuigkeiten/');
$template->replace('LINK_ENGLISH','/en/news/');
$template->replace('META_TITLE','{LANG_NEWS}');
$template->translate($language);
include('includes/links.php');
$template->write();
?>