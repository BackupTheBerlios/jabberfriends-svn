<?php
include('classes/jforg_tags.php');
if (is_int($_GET['id']+0)) {
    $user_id = $_GET['id'];
} else {
    die('ID is not an int'.$_GET['id']);
}
$tags = new jforg_tags();
$template->set_frame('fullpage','green');
$content = '<table cellpadding="0" cellspacing="2" border="0">';
$user_details = $user->get_details($user_id);
$user_details = array_map('htmlentities',$user_details);
if ($user_details['BIRTHDATE']!=0) {
    $var = explode('-',$user_details['BIRTHDATE']);
    $bdate=date('d. F Y', mktime(0, 0, 0, $var[1], $var[2], $var[0]));
} else {
    $bdate = "";
}
$content .= '<tr><td valign="top">{LANG_NICK}</td><td valign="top">'.$user->get_nick($user_id).'</td></tr>';
$content .= '<tr><td valign="top">{LANG_JID}</td><td valign="top">'.$user->get_jid($user_id).'</td></tr>';
$content .= '<tr><td colspan="2"><br /><h2>{LANG_ABOUT} '.$user->get_nick($user_id).'</h2></td></tr>';
$content .= '<tr><td valign="top" class="left">{LANG_REALNAME}</td><td valign="top" class="right">'.$user_details['REALNAME'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_SEX}</td><td valign="top">'.$user_details['SEX'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_BIRTHDATE}</td><td valign="top">'.$bdate.'</td></tr>';
$content .= '<tr><td valign="top">{LANG_COUNTRY}</td><td valign="top">'.$user_details['COUNTRY'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_CITY}</td><td valign="top">'.$user_details['CITY'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_ORIGINAL_FROM}</td><td valign="top">'.$user_details['ORIGINAL_FROM'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_LANGUAGES}</td><td valign="top">'.$user_details['LANGUAGES'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_HOBBYS}</td><td valign="top">'.$user_details['HOBBYS'].'</td></tr>';
$content .= '<tr><td colspan="2"><br /><h2>{LANG_COMPUTER_OF} '.$user->get_nick($user_id).'</h2></td></tr>';
$content .= '<tr><td valign="top">{LANG_WEBSITE}</td><td valign="top"><a href="http://'.str_replace('http://','',$user_details['WEBSITE']).'">'.str_replace('http://','',$user_details['WEBSITE']).'</a></td></tr>';
$content .= '<tr><td valign="top">{LANG_COMPUTER}</td><td valign="top">'.$user_details['COMPUTER'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_COMPUTER_OS}</td><td valign="top">'.$user_details['COMPUTER_OS'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_GEEKCODE}</td><td valign="top">'.str_replace("\n","<br />",$user_details['GEEKCODE']).'</td></tr>';
$content .= '<tr><td valign="top">{LANG_PUBLICKEY}</td><td valign="top">';
if ($user_details['PUBLICKEY']!="") { $content .= '<a href="/'.$language.'/publickeys/'.$user_id.'-'.$user->get_nick($user_id).'.htm">{LANG_SHOW_PUBLICKEY}</a>'; }
$content .= '</td></tr>';
$content .= '<tr><td colspan="2"><br /><h2>{LANG_FAVORITES} '.$user->get_nick($user_id).'</h2></td></tr>';
$content .= '<tr><td valign="top">{LANG_FAVORITE_FILM}</td><td valign="top">'.$user_details['FAVORITE_FILM'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_FAVORITE_SERIES}</td><td valign="top">'.$user_details['FAVORITE_SERIES'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_FAVORITE_MUSIK}</td><td valign="top">'.$user_details['FAVORITE_MUSIK'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_FAVORITE_BOOK}</td><td valign="top">'.$user_details['FAVORITE_BOOK'].'</td></tr>';
$user_tags 	= $tags->get_user_tags($user_id);
$content .= '<tr><td><br /><h2>Tags:</h2></td></tr><tr><td valign="top">';
foreach ($user_tags as $user_tags_content) {
	    $content .= '<a href="../../'.$language.'/tag/'.$user_tags_content.'.htm">'.$user_tags_content.'</a><br />';
}
$content .= '</td></tr>';
$content .= '</table>';
$template->replace('FULLPAGE_HEADER','{LANG_USER_PAGE_OF} '.$user->get_nick($user_id));
$template->replace('META_TITLE','{LANG_USER_PAGE_OF} '.$user->get_nick($user_id));
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('LINK_GERMAN','/de/mitglieder/'.$user_id.'-'.$user->get_nick($user_id).'.htm');
$template->replace('LINK_ENGLISH','/en/members/'.$user_id.'-'.$user->get_nick($user_id).'.htm');
?>