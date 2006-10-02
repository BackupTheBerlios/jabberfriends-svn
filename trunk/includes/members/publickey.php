<?php
$template->set_frame('fullpage','green');
if (!$user->nick_exists($_GET['id'])) {
    $content = 'Such user and public key doesn\'t exists';
    $template->replace('FULLPAGE_HEADER','There is no '.$_GET['id']);
}else{
$user_id = $user->get_id($_GET['id']);
$user_details = $user->get_details($user_id);
if ($language=="de") {
    $link = "mitglieder";
}
if ($language=="en") {
    $link = "members";
}
$content = '<a href="/'.$language.'/'.$link.'/'.$user->get_nick($user_id).'.htm">{LANG_SHOW_USER_PAGE_OF} '.$user->get_nick($user_id).'</a><br /><br />';
$content = $content.str_replace("\n","<br />",$user_details['PUBLICKEY']);
$template->replace('FULLPAGE_HEADER','{LANG_PUBLICKEY_OF} '.$user->get_nick($user_id));
}
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('META_TITLE','{LANG_PUBLICKEY_OF} '.$user->get_nick($user_id));
$template->replace('LINK_GERMAN','/de/publickeys/'.$user->get_nick($user_id));
$template->replace('LINK_ENGLISH','/en/publickeys/'.$user->get_nick($user_id));
?>
