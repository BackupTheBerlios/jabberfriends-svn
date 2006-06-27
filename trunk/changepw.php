<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
$template = new jforg_template();
$user = new jforg_user();
$template->set_path('design');
$template->set_frame('fullpage','green');
$template->hover_on('green');
if (in_array($_GET['lang'],$config['languages'])) {
    $language = $_GET['lang'];
} else {
    die('Language ist nicht bekannt');
}
$user = new jforg_user();
$user_id = $user->get_id($_SESSION['nick']);
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
$content .= '<form action="{FORM_ACTION}" method="post">
                <table cellpadding="0" cellspacing="2" border="0">';

if (!empty($_POST['submit'])) {
    $counter = 0;
    //Stimmt das alte PW
    if ($user->login($_SESSION['nick'],$_POST['old_pw'])) {
        $counter++;
    } else {
        $pwold_comment = '<tr><td>&nbsp;</td><td><em>{LANG_PWWRONG}</em></td></tr>';
    }
    //Stimmen die PWs überein
    if ($_POST['new_pw']==$_POST['new_pw2']) {
        $counter++;
    } else {
        $pw_comment = '<tr><td>&nbsp;</td><td><em>{LANG_PW_NOT_SAME}</em></td></tr>';
    }
    //Vailides PW?
    if (preg_match('/[-._+\d\w]{6,}/i',$_POST['new_pw'])) {
        $counter++;
    } else {
        $pw_comment = '<tr><td>&nbsp;</td><td><em>{LANG_ONLY_LETTERS_6}</em></td></tr>';
    }
    if ($counter==3) {
        if($user->change_password($_SESSION['nick'], $_POST['new_pw'])){
            $pwold_comment = '<tr><td></td><td><em>{LANG_CHANGEPW_SUCCES}</em></td></tr>';
            $_SESSION['passwd'] = $_POST['new_pw'];
        }
    }
}

$content .= $pwold_comment;
$content .= '<tr><td>{LANG_OLD_PW}</td><td><input type="password" name="old_pw" value=""></td>';
$content .= $pw_comment;
$content .= '<tr><td>{LANG_NEW_PW}</td><td><input type="password" name="new_pw" value=""></td></tr><tr><td>{LANG_PW_AGAIN}</td><td><input type="password" name="new_pw2" value=""></td></tr><tr><td>&nbsp;</td><td><br /><input class="submit" value="{LANG_CHANGE_PW}" name="submit" type="submit" /></td></tr></table></form>';

$template->replace('FULLPAGE_TEXT',$content);
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('LINK_CHANGEPW','{LINK_CHANGEPW}');
$template->replace('LINK_REGISTER','{LANG_OPTIONS}');
$template->replace('FORM_ACTION','');
$template->replace('LINK_GERMAN','/de/passwortaendern.htm');
$template->replace('LINK_ENGLISH','/en/changepassword.htm');
$template->replace('FULLPAGE_HEADER','{LANG_CHANGEPW}');
$template->replace('META_TITLE','{LANG_CHANGEPW}');
$template->translate($language);
include('includes/links.php');
$template->write();
?>
