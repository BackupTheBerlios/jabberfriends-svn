<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
include('classes/jforg_news.php');
include('classes/jforg_usersearch.php');
include('classes/jforg_cleanurl.php');
include('classes/jforg_gettext.php');
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
$usersearch = new jforg_usersearch();
$row = $usersearch->get_random();
print_r($array);
$details_match = '<b><a href="{LINK_MEMBERS}'.$row['id'].'-'.$row['nick'].'.htm">'.$row['nick'].'</a>:</b> ';
$details_match .= $template->format_userdetails($user->get_details($row['id']),10);
$search = '<form action="{LINK_SEARCH}" method="post"><input name="search" type="text" /><br /><br /><input class="submit" name="submit" type="submit" value="{LANG_SEARCH}" /></form>';
$template->replace('RANDOMMEMBER',$details_match);
$template->replace('MEMBERSEARCH',$search);
$template->replace('DEVELOPER',get_text(3,$language));
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