<?php
include('classes/jforg_usersearch.php');
$template->set_frame('fullpage','green');
$max_per_search = 5;
$usersearch = new jforg_usersearch();
$content = '<br /><form action="{LINK_SEARCH}" method="post"><input name="search" type="text" />&nbsp;<input class="submit" name="submit" type="submit" value="{LANG_SEARCH}" /></form>';
$content = $content.'<br /><br /><b>{LANG_LAST_MEMBERS}</b><ol>';
$array = $usersearch->search_last(5);
foreach($array as $row) {
    $details_match = $template->format_userdetails($user->get_details($row['id']));
    $content = $content.'<li><b><a href="'.$row['id'].'-'.$row['nick'].'.htm">'.$row['nick'].'</a></b><br />'.$details_match.'</li>';
}
$content = $content.'</ol><b>{LANG_POP_TAGS}</b><br /><br />';
$content .= $template->generate_cloud($language);
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('LINK_GERMAN','/de/mitglieder/');
$template->replace('LINK_ENGLISH','/en/members/');
$template->replace('META_TITLE','{LANG_MEMBERS}');
$template->replace('FULLPAGE_HEADER','{LANG_MEMBERS}');
$template->replace('FULLPAGE_TEXT',$content);
?>
