<?php
include('classes/jforg_news.php');
include('functions/jforg_cleanurl.php');
$news = new jforg_news();
$template->set_frame('fullpage','tuerkis');
if ($language=='de') {
    $news_link = 'neuigkeiten';
} else {
    $news_link = 'news';
}
$lastnews = $news->get_latest(15);
foreach($lastnews as $news) {
    $news_absatz = explode("\n",$news['text']);
    $content .= '<h2>'.htmlentities($news['title']).'</h2><i>'.date('d.m.Y H:i',$news['datetime']).'</i> '.substr($template->parse_wiki($news_absatz[0]),0, -6).' <a href="/'.$language.'/'.$news_link.'/'.$news['id'].'-'.cleanurl($news['title']).'.htm">{LANG_READMORE}</a>';
}
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('FULLPAGE_HEADER','{LANG_NEWS}');
$template->replace('LINK_GERMAN','/de/neuigkeiten/');
$template->replace('LINK_ENGLISH','/en/news/');
$template->replace('META_TITLE','{LANG_NEWS}');
?>
