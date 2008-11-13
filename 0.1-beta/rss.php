<?php
include('config.php');
include('lib/atom.class.inc');
include('classes/jforg_news.php');
include('functions/jforg_cleanurl.php');
if (in_array($_GET['lang'],$config['languages'])) {
    $language = $_GET['lang'];
} else {
    die('Language ist nicht bekannt');
}
if ($language=='de') {
    $news_link = 'neuigkeiten';
} else {
    $news_link = 'news';
}
$news = new jforg_news();
$feed = new AtomFeed('JForg :: Neuigkeiten', 'http://www.jabberfriends.org/de/rss/neuigkeiten.xml', $config['url']);
$lastnews = $news->get_latest(10);
foreach($lastnews as $news) {
    $news_absatz = explode("\n",$news['text']);
    $feed->addEntry(new Entry($news['title'],$config['url'].$language.'/'.$news_link.'/'.$news['id'].'-'.cleanurl($news['title']).'.htm',$news['datetime'],$news['datetime'], 'Daniel Gultsch',$news_absatz[0],$news['text']));
}
$feed->setEncoding("utf-8");
$feed->generate();
?>