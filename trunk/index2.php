<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
include('classes/jforg_news.php');
include('classes/jforg_cleanurl.php');
$user = new jforg_user();
if (in_array($_GET['lang'],$config['languages'])) {
    $language = $_GET['lang'];
} else {
    die('Language ist nicht bekannt');
}
$template = new jforg_template();
$user = new jforg_user();
$news = new jforg_news();
$template->set_path('design');
$template->set_frame('startpage');
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
$lastnews = $news->get_latest();
$template->replace('NEWSHEAD',htmlentities($lastnews['title']));
$template->replace('NEWSDATE',date('d.m.Y H:i',$lastnews['datetime']));
$news_absatz = explode("\n",$lastnews['text']);
$template->replace('NEWSTEXT',htmlentities($news_absatz[0]));
$template->replace('LINK_GERMAN','/de/');
$template->replace('LINK_ENGLISH','/en/');
$template->replace('META_TITLE','JabberFriends.org');
$template->replace('LINK_NEWS','/'.$language.'/'.$news_link.'/'.$lastnews['id'].'-'.cleanurl($lastnews['title']).'.htm');
$template->replace('LINK_ALLNEWS','/'.$language.'/'.$news_link.'/');
$template->replace('RSS_NEWS','/'.$language.'/rss/'.$news_link.'.xml');
$template->translate($language);
include('includes/links.php');
$template->write();
?>