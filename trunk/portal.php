<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
include('classes/jforg_cite.php');
include('classes/jforg_cleanurl.php');
$user = new jforg_user();
if (in_array($_GET['lang'],$config['languages'])) {
    $language = $_GET['lang'];
} else {
    die('Language ist nicht bekannt');
}
$template = new jforg_template();
$user = new jforg_user();
$cite = new jforg_cite();
$template->set_path('design');
$template->set_frame('fullpage','lila');
$template->hover_on('lila');
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
$template->replace('LINK_GERMAN','/de/portal/');
$template->replace('LINK_ENGLISH','/en/portal/');
$template->replace('META_TITLE','Portal');
$template->replace('FULLPAGE_HEADER','Portal');
if ($language=='de') {
    $member_link = 'mitglieder';
    $cite_link = 'zitat';
} else {
    $member_link = 'members';
    $cite_link = 'cite';
}
$content = '<a href="">{LANG_ADDCITE}</a>';
$random_zitat = $cite->get_random();
$content .= '<h2>{LANG_RANDOMECITE}</h2>'.$template->highlight_cite($random_zitat['zitat']).'<br /><br />{LANG_ADDEDBY} <a href="/'.$language.'/'.$member_link.'/'.$random_zitat['user'].'-'.cleanurl($user->get_nick($random_zitat['user'])).'.htm">'.$user->get_nick($random_zitat['user']).'</a> {LANG_ON} '.date('d.m.Y',$random_zitat['datetime']).' <a href="/'.$language.'/portal/'.$cite_link.'-'.$random_zitat['id'].'.htm">Zitat URL</a>';
$template->replace('FULLPAGE_TEXT',$content);
$template->highlight_cite($random_zitat);
$template->translate($language);
include('includes/links.php');
$template->write();
?>