<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
include('classes/jforg_news.php');
include('classes/jforg_cleanurl.php');
$user = new jforg_user();
$news = new jforg_news();
if (in_array($_GET['lang'],$config['languages'])) {
    $language = $_GET['lang'];
} else {
    die('Language ist nicht bekannt');
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
if ($language=='de') {
    $news_link = 'neuigkeiten';
} else {
    $news_link = 'news';
}
$lastnews = $news->get_latest(15);
foreach($lastnews as $news) {
    $news_absatz = explode("\n",$news['text']);
    $content .= '<h2>'.htmlentities($news['title']).'</h2><i>'.date('d.m.Y H:i',$news['datetime']).'</i> '.htmlentities($news_absatz[0]).' <a href="/'.$language.'/'.$news_link.'/'.$news['id'].'-'.cleanurl($news['title']).'.htm">{LANG_READMORE}</a>';
}
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('FULLPAGE_HEADER','{LANG_NEWS}');
$template->replace('LINK_GERMAN','/de/neuigkeiten/');
$template->replace('LINK_ENGLISH','/en/news/');
$template->replace('META_TITLE','{LANG_NEWS}');
$template->translate($language);
include('includes/links.php');
$template->write();
?>