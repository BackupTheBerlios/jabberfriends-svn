<?php
if (is_int($_GET['id']+0)) {
    $user_id = $_GET['id'];
} else {
    die('ID is not an int'.$_GET['id']);
}
$template->set_frame('fullpage','green');
$user_details = $user->get_details($user_id);
if ($language=="de") {
    $link = "mitglieder";
}
if ($language=="en") {
    $link = "members";
}
$content = '<a href="/'.$language.'/'.$link.'/'.$user->get_nick($user_id).'.htm">{LANG_SHOW_USER_PAGE_OF} '.$user->get_nick($user_id).'</a><br /><br />';
$content = $content.str_replace("\n","<br />",$user_details['PUBLICKEY']);
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('FULLPAGE_HEADER','{LANG_PUBLICKEY_OF} '.$user->get_nick($user_id));
$template->replace('META_TITLE','{LANG_PUBLICKEY_OF} '.$user->get_nick($user_id));
$template->replace('LINK_GERMAN','/de/publickeys/'.$user_id.'-'.$user->get_nick($user_id).'.htm');
$template->replace('LINK_ENGLISH','/en/publickeys/'.$user_id.'-'.$user->get_nick($user_id).'.htm');
?>
