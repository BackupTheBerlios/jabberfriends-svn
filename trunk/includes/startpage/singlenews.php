<?php
include('classes/jforg_news.php');
include('functions/jforg_cleanurl.php');
$news = new jforg_news();
$id = $_GET['id'];
$id = $id + 0;
if (!is_int($id)) {
    die("Invalid ID - $id");
}
$template->set_frame('fullpage','tuerkis');
$news = $news->get_by_id($id);
$template->replace('FULLPAGE_HEADER',htmlentities($news['title']));
if ($user->is_admin($_SESSION['nick'],$_SESSION['passwd'])) {
    $content = '<br /><br />[<a href="/'.$language.'/news_editor/'.$id.'.htm">{LANG_EDIT}</a>]';
}
$template->replace('FULLPAGE_TEXT','<i>'.date('d.m.Y H:i',$news['datetime']).'</i> '.str_replace("\n","<br />",htmlentities($news['text'])).$content);
$template->replace('LINK_GERMAN','/de/neuigkeiten/'.$id.'-'.cleanurl($news['title']).'.htm');
$template->replace('LINK_ENGLISH','/en/news/'.$id.'-'.cleanurl($news['title']).'.htm');
$template->replace('META_TITLE','{LANG_NEWS}');
?>