<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
include('classes/jforg_tags.php');
$user = new jforg_user();

// set the language
if (in_array($_GET['lang'],$config['languages'])) {
    $language = $_GET['lang'];
} else {
    die('Language ist nicht bekannt');
}

$template = new jforg_template();
$user = new jforg_user();
$tags = new jforg_tags();
$template->set_path('design');
$template->set_frame('fullpage','green');
$template->hover_on('green');
SESSION_START();
$user_id = $user->get_id($_SESSION['nick']);

// check if the user is logged in
if (!$user->login($_SESSION['nick'],$_SESSION['passwd'])) {
    die('You are not logged in');  
}



$content = '<form action="{FORM_ACTION}" method="post">
                <table cellpadding="0" cellspacing="2" border="0">';
// add tag to a user
if (!empty($_POST['submit'])) {
    $_POST['tags'] = strip_tags( $_POST['tags_add']);
    $tags->add_tag($_POST['tags'], $user_id);
}

// remove the given tag_id from the user
if (is_int(intval(ereg_replace('[^0-9]','',$_GET['id'])))) {
    $tags_del = $_GET['tag_id'];
    $tags->remove_tag((int) $tags_del, $user_id);
} else {
    die('ID is not an int'.$_GET['tag_id']);
}

$user_tags 	= $tags->get_user_tags($user_id);
$content .= '<tr><td colspan="2"><a href="{LINK_OPTIONS}">{LANG_BACK_TO_OPTIONS}</a><br /><br /></td></tr>';
$content .= '<tr><td class="left">{LANG_NICK}</td><td class="right">'.$user->get_nick($user_id).'</td></tr>';
$content .= '<tr><td>{LANG_JID}</td><td>'.$user->get_jid($user_id).'</td></tr>';
$content .= '<tr><td><br />{LANG_AKTUAL_TAGS}</td><td><br />';
$content .= '<table cellpadding="0" cellspacing="2" border="0">';

// purint the tags
foreach ($user_tags as $user_tags_content) {
        $tag_id  = $tags->get_tag_id($user_tags_content);
		$content .= '<tr><td>'.$user_tags_content.'</td><td><a href="../../'.$language.'/{LANG_DEL_TAGS}/'.$tag_id.'.htm">{LANG_DEL_TAG}</a>, <br /></tr>';
}

$content .= '</table>';
$content .= '</td></tr>';
$content .= '<tr><td valign="top"><br />{LANG_ADD_TAGS_TEXT}</td><td><br /><input type="text" name="tags_add" value="">';
$content .= '	</td></tr>';
$content .= '</td></tr>';
$content .= '<tr><td>&nbsp;</td><td><br /><input class="submit" value="{LANG_ADD_TAGS}" name="submit" type="submit" /></td></tr></table></form>';
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('LOGIN','{LANG_LOGOUT}');
$template->replace('REGISTER','{LANG_OPTIONS}');
$template->replace('LINK_LOGIN','{LINK_LOGOUT}');
$template->replace('LINK_REGISTER','{LINK_OPTIONS}');
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
if ($language=='de') {
    $template->replace('FORM_ACTION','/de/tags_aendern.htm');
} else {
    $template->replace('FORM_ACTION','/en/change_tags.htm');
}
$template->replace('FORM_ACTION','');
$template->replace('LINK_GERMAN','/de/tags_aendern.htm');
$template->replace('LINK_ENGLISH','/en/change_tags.htm');
$template->replace('META_TITLE','{LANG_CHANGETAGS}');
$template->replace('FULLPAGE_HEADER','{LANG_CHANGETAGS}');
$template->translate($language);
include('includes/links.php');
$template->write();
?>
