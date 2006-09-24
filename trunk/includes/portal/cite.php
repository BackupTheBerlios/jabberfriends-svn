<?php
include('classes/jforg_cite.php');
include('functions/jforg_cleanurl.php');
$id = $_GET['id'];
$id = $id + 0;
if (!is_int($id)) {
    die("Invalid ID - $id");
}
$cite = new jforg_cite();
$template->set_frame('fullpage','lila');
$template->replace('META_TITLE','{LANG_CITE}');
$template->replace('FULLPAGE_HEADER','{LANG_CITE}');
$content = '<a href="/'.$language.'/portal/citedb/">{LANG_BACKTOCITEDB}</a><br /><br />';
if (isset($_GET['rating'])) {
    if (!$user->login($_SESSION['nick'],$_SESSION['passwd'])) {
        die('You are not logged in');
    }
    $is_rated = $cite->rate($id,$user->get_id($_SESSION['nick']),$_GET['rating']);
    if ($is_rated) {
        $content .= '<em>{LANG_WASRATED}</em><br /><br />';
    } else {
        $content .= '<em>{LANG_WASNOTRATED}</em><br /><br />';
    }
}
$zitat = $cite->get_by_id($id);
if ($language=='de') {
    $member_link = 'mitglieder';
    $cite_link = 'zitat';
} else {
    $member_link = 'members';
    $cite_link = 'cite';
}
$template->replace('LINK_GERMAN','/de/portal/citedb/zitat-'.$id.'.htm');
$template->replace('LINK_ENGLISH','/en/portal/citedb/cite-'.$id.'.htm');
$this_cite_url = $language.'/portal/citedb/'.$cite_link.'-'.$zitat['id'];
$content .= $template->highlight_cite($zitat['zitat']).'<br /><br />{LANG_ADDEDBY} <a href="/'.$language.'/'.$member_link.'/'.$zitat['user'].'-'.cleanurl($user->get_nick($zitat['user'])).'.htm">'.$user->get_nick($zitat['user']).'</a> {LANG_ON} '.date('d.m.Y H:i',$zitat['datetime']).' - <b>{LANG_RATE}: <a href="/'.$this_cite_url.'.htmmm">--</a> <a href="/'.$this_cite_url.'.htmm">-</a> <a href="/'.$this_cite_url.'.htmp">+</a> <a href="/'.$this_cite_url.'.htmpp">++</a></b>';
$template->replace('FULLPAGE_TEXT',$content);
$template->highlight_cite($random_zitat);
?>