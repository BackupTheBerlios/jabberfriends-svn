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
$zitat = $cite->get_by_id($id);
if ($language=='de') {
    $member_link = 'mitglieder';
    $cite_link = 'zitat';
} else {
    $member_link = 'members';
    $cite_link = 'cite';
}
$template->replace('LINK_GERMAN','/de/portal/zitat-'.$id.'.htm');
$template->replace('LINK_ENGLISH','/en/portal/cite-'.$id.'.htm');
$content = $template->highlight_cite($zitat['zitat']).'<br /><br />{LANG_ADDEDBY} <a href="/'.$language.'/'.$member_link.'/'.$zitat['user'].'-'.cleanurl($user->get_nick($zitat['user'])).'.htm">'.$user->get_nick($zitat['user']).'</a> {LANG_ON} '.date('d.m.Y',$zitat['datetime']);
$template->replace('FULLPAGE_TEXT',$content);
$template->highlight_cite($random_zitat);
?>