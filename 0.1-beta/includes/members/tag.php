<?php
include('classes/jforg_tags.php');
$tags = new jforg_tags();
$template->set_frame('fullpage','green');
$content = '<form action="{FORM_ACTION}" method="post">
                <table cellpadding="0" cellspacing="2" border="0">';

$tag_value = $_GET['tag_value'];
$users 	= $tags->list_users($tag_value);
if (!empty($_POST['submit'])) {
    $_POST['tag'] = $_POST['search_tagged_users'];
    $url =$_POST['tag'].'.htm';
    if($url != ".htm"){
        header("location:".$url);
    }
}
    $content .= '<tr><td class="left">{LANG_TAG}</td><td class="right">'.$tag_value.'</td></tr><tr><td>{LANG_USERS}:</td>';
    foreach ($users as $tagged_users) {
	    	$content .= '<td><a href="{LINK_MEMBERS}'.$user->get_nick($tagged_users).'.htm">'.$user->get_nick($tagged_users).'</a></td></tr><tr><td></td>';
    }

$content .= '<td></td></tr>';
$content .= '<tr><td valign="top"><br />{LANG_SEARCH_WHO_HAVE_TAG}</td><td><br /><input type="text" name="search_tagged_users" value="">';
$content .= '	</td></tr>';
$content .= '</td></tr>';
$content .= '<tr><td>&nbsp;</td><td><br /><input class="submit" value="{LANG_SEARCH}" name="submit" type="submit" /></td></tr></table></form>';
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('LOGIN','{LANG_LOGOUT}');
$template->replace('REGISTER','{LANG_OPTIONS}');
$template->replace('LINK_LOGIN','{LINK_LOGOUT}');
$template->replace('LINK_REGISTER','{LINK_OPTIONS}');
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('FORM_ACTION','');
$template->replace('LINK_GERMAN','/de/tag/'.$tag_value.'.htm');
$template->replace('LINK_ENGLISH','/en/tag/'.$tag_value.'.htm');
$template->replace('META_TITLE','{LANG_ALL_WHO_HAVE_TAG}');
$template->replace('FULLPAGE_HEADER','{LANG_ALL_WHO_HAVE_TAG}');
?>
