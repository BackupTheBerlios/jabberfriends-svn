<?php
include('classes/jforg_cite.php');
include('functions/jforg_cleanurl.php');
$cite = new jforg_cite();
$template->set_frame('fullpage','lila');
$template->replace('LINK_GERMAN','/de/portal/');
$template->replace('LINK_ENGLISH','/en/portal/');
$template->replace('META_TITLE','Portal');
$template->replace('FULLPAGE_HEADER','Portal');
if ($language=='de') {
    $member_link = 'mitglieder';
    $cite_link = 'zitat';
    $creat_link = 'zitat-hinzufuegen';
} else {
    $member_link = 'members';
    $cite_link = 'cite';
    $creat_link = 'add-cite';
}
$content = '<a href="/'.$language.'/portal/'.$creat_link.'.htm">{LANG_ADDCITE}</a>';
$random_zitat = $cite->get_random();
$content .= '<h2>{LANG_RANDOMECITE}</h2>'.$template->highlight_cite($random_zitat['zitat']).'<br /><br />{LANG_ADDEDBY} <a href="/'.$language.'/'.$member_link.'/'.$random_zitat['user'].'-'.cleanurl($user->get_nick($random_zitat['user'])).'.htm">'.$user->get_nick($random_zitat['user']).'</a> {LANG_ON} '.date('d.m.Y',$random_zitat['datetime']).' <a href="/'.$language.'/portal/'.$cite_link.'-'.$random_zitat['id'].'.htm">Zitat URL</a>';
$template->replace('FULLPAGE_TEXT',$content);
$template->highlight_cite($random_zitat);
?>