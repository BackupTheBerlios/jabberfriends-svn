<?php
include('functions/jforg_cleanurl.php');
include('classes/jforg_wikisearch.php');
if( isset($_GET['searchword']) AND !isset( $_POST['searchword'] ) )
{
    $_POST['searchword'] = $_GET['searchword'];
}
if (isset($_POST['searchword'])) {
    $wikisearch = new jforg_wikisearch();
    $wikisearch->set_language($language);
    $wikisearch->set_searchword($_POST['searchword']);
    $direct_id = $wikisearch->get_direct_id();
    if ($direct_id!=0) {
        $url = '/'.$language.'/wiki/'.$direct_id.'-'.cleanurl($_POST['searchword']).'.htm';
        header("Location: $url");
    }
    $content = '<form action="{LINK_WIKISEARCH}" method="post"><input value="'.$_POST['searchword'].'" name="searchword" type="text" />&nbsp;<input class="submit" name="submit" type="submit" value="{LANG_SEARCH}" /></form>';
    if (preg_match('/.{3,}/',$_POST['searchword'])) {
        $results = $wikisearch->get_results();
        $content .= '<br /><br /><b>'.count($results).' {LANG_MATCHES_FOR} '.$_POST['searchword'].'</b><ol>';
        foreach($results as $entry) {
            $content .= '<li><a href="/'.$language.'/wiki/'.$entry['wiki_id'].'-'.cleanurl($entry['title']).'.htm">'.$entry['title'].'</a></li>';
        }
    } else {
        $content = $content."<br /><br /><b><em>{LANG_3CHAR}</em></b><br /><br />";
    }
    $template->replace('LINK_GERMAN','/de/wiki/suche/'.$_POST['searchword']);
    $template->replace('LINK_ENGLISH','/en/wiki/search/'.$_POST['searchword']);
} else {
    $content = '<form action="{LINK_WIKISEARCH}" method="post"><input name="searchword" type="text" />&nbsp;<input class="submit" name="submit" type="submit" value="{LANG_SEARCH}" /></form>';
    $template->replace('LINK_GERMAN','/de/wiki/suche/');
    $template->replace('LINK_ENGLISH','/en/wiki/search/');
}
$template->set_frame('fullpage','red');
$template->replace('META_TITLE','Wiki {LANG_SEARCH}');
$template->replace('FULLPAGE_HEADER','Wiki {LANG_SEARCH}');
$template->replace('FULLPAGE_TEXT',$content);
$template->highlight_cite($random_zitat);
?>
