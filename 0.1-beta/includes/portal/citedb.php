<?php
include('classes/jforg_cite.php');
include('functions/jforg_cleanurl.php');
$cite = new jforg_cite();
$template->set_frame('fullpage','lila');
$template->replace('LINK_GERMAN','/de/portal/citedb/');
$template->replace('LINK_ENGLISH','/en/portal/citedb');
$template->replace('META_TITLE','Portal - citeDataBase');
$template->replace('FULLPAGE_HEADER','citeDataBase');
if ($language=='de') {
    $member_link = 'mitglieder';
    $cite_link = 'zitat';
    $creat_link = 'zitat-hinzufuegen';
} else {
    $member_link = 'members';
    $cite_link = 'cite';
    $creat_link = 'add-cite';
}
$content = '<a href="/'.$language.'/portal/citedb/'.$creat_link.'.htm">{LANG_ADDCITE}</a>';

$content .= '<h2>{LANG_TOPCITE}</h2>';
$top_zitate = $cite->get_top(1);
foreach($top_zitate as $top_zitat) {
    $this_cite_url = $language.'/portal/citedb/'.$cite_link.'-'.$top_zitat['id'];
    $content .= $template->highlight_cite($top_zitat['zitat']).'<br /><br />{LANG_ADDEDBY} <a href="/'.$language.'/'.$member_link.'/'.cleanurl($user->get_nick($top_zitat['user'])).'.htm">'.$user->get_nick($top_zitat['user']).'</a> {LANG_ON} '.date('d.m.Y H:i',$top_zitat['datetime']).' <a href="/'.$this_cite_url.'.htm">Zitat URL</a> - <b>{LANG_RATE}: <a href="/'.$this_cite_url.'.htmmm">--</a> <a href="/'.$this_cite_url.'.htmm">-</a> <a href="/'.$this_cite_url.'.htmp">+</a> <a href="/'.$this_cite_url.'.htmpp">++</a></b>';
}
$content .= '<br /><br /><h2>{LANG_LASTCITE}</h2>';
//Last
$last_zitate = $cite->get_last(1);
foreach($last_zitate as $last_zitat) {
    $this_cite_url = $language.'/portal/citedb/'.$cite_link.'-'.$last_zitat['id'];
    $content .= $template->highlight_cite($last_zitat['zitat']).'<br /><br />{LANG_ADDEDBY} <a href="/'.$language.'/'.$member_link.'/'.cleanurl($user->get_nick($last_zitat['user'])).'.htm">'.$user->get_nick($last_zitat['user']).'</a> {LANG_ON} '.date('d.m.Y H:i',$last_zitat['datetime']).' <a href="/'.$this_cite_url.'.htm">Zitat URL</a> - <b>{LANG_RATE}: <a href="/'.$this_cite_url.'.htmmm">--</a> <a href="/'.$this_cite_url.'.htmm">-</a> <a href="/'.$this_cite_url.'.htmp">+</a> <a href="/'.$this_cite_url.'.htmpp">++</a></b>';
}
//Zufall
$content .= '<br /><br /><h2>{LANG_RANDOMECITE}</h2><a href="">{LANG_VIEW5RANDOM}</a><br />';
$random_zitate = $cite->get_random(1);
foreach($random_zitate as $random_zitat) {
    $this_cite_url = $language.'/portal/citedb/'.$cite_link.'-'.$random_zitat['id'];
    $content .= $template->highlight_cite($random_zitat['zitat']).'<br /><br />{LANG_ADDEDBY} <a href="/'.$language.'/'.$member_link.'/'.cleanurl($user->get_nick($random_zitat['user'])).'.htm">'.$user->get_nick($random_zitat['user']).'</a> {LANG_ON} '.date('d.m.Y',$random_zitat['datetime']).' <a href="/'.$this_cite_url.'.htm">Zitat URL</a> - <b>{LANG_RATE}: <a href="/'.$this_cite_url.'.htmmm">--</a> <a href="/'.$this_cite_url.'.htmm">-</a> <a href="/'.$this_cite_url.'.htmp">+</a> <a href="/'.$this_cite_url.'.htmpp">++</a></b>';
}
$template->replace('FULLPAGE_TEXT',$content);
$template->highlight_cite($random_zitat);
?>
