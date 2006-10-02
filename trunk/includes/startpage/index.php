<?php
include('classes/jforg_news.php');
include('classes/jforg_usersearch.php');
include('functions/jforg_cleanurl.php');
include('functions/jforg_gettext.php');
if ($language=='de') {
    $news_link = 'neuigkeiten';
} else {
    $news_link = 'news';
}
$usersearch = new jforg_usersearch();
$news = new jforg_news();
$row = $usersearch->get_random();
$template->set_frame('startpage');
$details_match = '<b><a href="{LINK_MEMBERS}'.$row['nick'].'.htm">'.$row['nick'].'</a>:</b> ';
$details_match .= $template->format_userdetails($user->get_details($row['id']),10);
$search = '<form action="{LINK_SEARCH}" method="post"><input name="search" type="text" /><br /><br /><input class="submit" name="submit" type="submit" value="{LANG_SEARCH}" /></form>';
$template->replace('RANDOMMEMBER',$details_match);
$template->replace('MEMBERSEARCH',$search);
$template->replace('DEVELOPER',get_text(3,$language));
$lastnews = $news->get_latest();
$template->replace('NEWSHEAD',htmlentities($lastnews['title']));
$template->replace('NEWSDATE',date('d.m.Y H:i',$lastnews['datetime']));
$news_absatz = explode("\n",$lastnews['text']);
$template->replace_wiki('NEWSTEXT',$news_absatz[0]);
$template->replace('LINK_GERMAN','/de/');
$template->replace('LINK_ENGLISH','/en/');
$template->replace('META_TITLE','JabberFriends.org');
$template->replace('LINK_NEWS','/'.$language.'/'.$news_link.'/'.$lastnews['id'].'-'.cleanurl($lastnews['title']).'.htm');
$template->replace('LINK_ALLNEWS','/'.$language.'/'.$news_link.'/');
$template->replace('RSS_NEWS','/'.$language.'/rss/'.$news_link.'.xml');
?> 
