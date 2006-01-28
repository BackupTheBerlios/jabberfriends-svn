<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
if (in_array($_GET['lang'],$config['languages'])) {
    $language = $_GET['lang'];
} else {
    die('Language ist nicht bekannt');
}
$template = new jforg_template();
$user = new jforg_user();
$template->set_path('design');
$template->set_frame('formpage','green');
$template->hover_on('green');
session_start();
if ($user->login($_SESSION['nick'],$_SESSION['passwd'])) {
    die('You are already logged in');
} else {
    $template->replace('LOGIN','{LANG_LOGIN}');
    $template->replace('REGISTER','{LANG_REGISTER}');
}
if ($_POST['submit']!="") {
    if ((preg_match('/[-._+\d\w]{3,}/i',$_POST['nick']))&&(preg_match('/[-._+\d\w]{6,}/i',$_POST['passwd']))) {
        $nick = $_POST['nick'];
        $passwd = $_POST['passwd'];
        if ($user->login($nick,$passwd)) {
            session_destroy();
            session_start();
            session_register('nick','passwd');
            $_SESSION['nick'] = $_POST['nick'];
            $_SESSION['passwd'] = $_POST['passwd'];
            HEADER("Location: /$language/");
        } else {
            $pw_coment = "<em>{LANG_PWUSER}</em>";
        }
    } else {
        $pw_coment = "<em>{LANG_PWUSER}</em>";
    }
}
$content='<tr><td class="left">{LANG_NICK}</td><td class="right">'.$pw_coment.'<input value="'.$_POST['nick'].'" type="text" name="nick" /></td>
    </tr>
    <tr>
    <td>{LANG_PW}</td><td><input type="password" name="passwd" /></td>
    </tr>
    <tr><td>&nbsp;</td><td><br /><input class="submit" value="{LANG_LOGIN}" name="submit" type="submit" /></td></tr>';
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('FULLPAGE_HEADER','{LANG_LOGIN}');
$template->replace('META_TITLE','{LANG_LOGIN}');
$template->replace('FORM_ACTION','');
$template->replace('LINK_GERMAN','/de/anmelden.htm');
$template->replace('LINK_ENGLISH','/en/login.htm');
$template->translate($language);
include('includes/links.php');
$template->write();
?>