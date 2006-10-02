<?php
$template->set_frame('fullpage','green');
if (!$user->nick_exists($_GET['id'])) {
    $content = 'Such username doesn\'t exists';
    $template->replace('FULLPAGE_HEADER','There is no '.$_GET['id']);
}else{
$user_id = $user->get_id($_GET['id']);
$content = '<table cellpadding="0" cellspacing="2" border="0">';
$user_details = $user->get_details($user_id);
$user_details = array_map('htmlentities',$user_details);
if ($user_details['BIRTHDATE']!=0) {
    $var = explode('-',$user_details['BIRTHDATE']);
    $bdate=date('d. F Y', mktime(0, 0, 0, $var[1], $var[2], $var[0]));
} else {
    $bdate = "";
}
$content .= '<tr><td class="left" valign="top">{LANG_NICK}</td><td valign="top">'.$user->get_nick($user_id).'</td><td style="width: 150px;" rowspan="7" valign="top">';
if (file_exists('upload/userpics/'.$user_id.'.jpg')) {
    $content .= '<img src="/upload/userpics/'.$user_id.'.jpg" width="150" />';
} else {
    $content .= '&nbsp;';
}
$content .= '</td></tr>';
$content .= '<tr><td valign="top">{LANG_JID}</td><td valign="top">'.$user->get_jid($user_id).'</td></tr>';
$content .= '<tr><td colspan="2"><br /><h2>{LANG_ABOUT} '.$user->get_nick($user_id).'</h2></td></tr>';
$content .= '<tr><td valign="top">{LANG_REALNAME}</td><td valign="top">'.$user_details['REALNAME'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_SEX}</td><td valign="top">'.$user_details['SEX'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_BIRTHDATE}</td><td valign="top">'.$bdate.'</td></tr>';
$content .= '<tr><td valign="top">{LANG_COUNTRY}</td><td valign="top">'.$user_details['COUNTRY'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_CITY}</td><td valign="top" colspan="2">'.$user_details['CITY'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_ORIGINAL_FROM}</td><td valign="top" colspan="2">'.$user_details['ORIGINAL_FROM'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_LANGUAGES}</td><td valign="top" colspan="2">'.$user_details['LANGUAGES'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_HOBBYS}</td><td valign="top" colspan="2">'.$user_details['HOBBYS'].'</td></tr>';
$content .= '<tr><td colspan="3"><br /><h2>{LANG_COMPUTER_OF} '.$user->get_nick($user_id).'</h2></td></tr>';
$content .= '<tr><td valign="top">{LANG_WEBSITE}</td><td valign="top" colspan="2"><a href="http://'.str_replace('http://','',$user_details['WEBSITE']).'">'.str_replace('http://','',$user_details['WEBSITE']).'</a></td></tr>';
$content .= '<tr><td class="left" valign="top">{LANG_COMPUTER}</td><td class="right" valign="top" colspan="2">'.$user_details['COMPUTER'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_COMPUTER_OS}</td><td valign="top" colspan="2">'.$user_details['COMPUTER_OS'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_GEEKCODE}</td><td valign="top" colspan="2">'.str_replace("\n","<br />",$user_details['GEEKCODE']).'</td></tr>';
$content .= '<tr><td valign="top">{LANG_PUBLICKEY}</td><td valign="top" colspan="2">';
if ($user_details['PUBLICKEY']!="") { $content .= '<a href="/'.$language.'/publickeys/'.$user_id.'-'.$user->get_nick($user_id).'.htm">{LANG_SHOW_PUBLICKEY}</a>'; }
$content .= '</td></tr>';
$content .= '<tr><td colspan="3"><br /><h2>{LANG_FAVORITES} '.$user->get_nick($user_id).'</h2></td></tr>';
$content .= '<tr><td valign="top">{LANG_FAVORITE_FILM}</td><td valign="top" colspan="2">'.$user_details['FAVORITE_FILM'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_FAVORITE_SERIES}</td><td valign="top" colspan="2">'.$user_details['FAVORITE_SERIES'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_FAVORITE_MUSIK}</td><td valign="top" colspan="2">'.$user_details['FAVORITE_MUSIK'].'</td></tr>';
$content .= '<tr><td valign="top">{LANG_FAVORITE_BOOK}</td><td valign="top" colspan="2">'.$user_details['FAVORITE_BOOK'].'</td></tr>';
$content .= '<tr><td colspan="3"><br /><h2>Tags:</h2></td></tr>
<tr><td></td><td colspan="2">';
$content .= $template->generate_cloud($language,$user_id);
$content .= '</td></tr>';
$content .= '</table>';
$template->replace('FULLPAGE_HEADER','{LANG_USER_PAGE_OF} '.$user->get_nick($user_id));
}
$template->replace('META_TITLE','{LANG_USER_PAGE_OF} '.$user->get_nick($user_id));
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('LINK_GERMAN','/de/mitglieder/'.$user->get_nick($user_id).'.htm');
$template->replace('LINK_ENGLISH','/en/members/'.$user->get_nick($user_id).'.htm');
?>
