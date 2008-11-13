<?php
$template->set_frame('fullpage','green');
session_start();
if ($user->login($_SESSION['nick'],$_SESSION['passwd'])) {
    die('You are already logged in');
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
$content='<form action="{FORM_ACTION}" method="post">
                <table cellpadding="0" cellspacing="2"><tr><td class="left">{LANG_NICK}</td><td class="right">'.$pw_coment.'<input value="'.$_POST['nick'].'" type="text" name="nick" /></td>
    </tr>
    <tr>
    <td>{LANG_PW}</td><td><input type="password" name="passwd" /></td>
    </tr>
    <tr><td>&nbsp;</td><td><br /><input class="submit" value="{LANG_LOGIN}" name="submit" type="submit" /></td></tr></table></form>';
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('FULLPAGE_HEADER','{LANG_LOGIN}');
$template->replace('META_TITLE','{LANG_LOGIN}');
$template->replace('FORM_ACTION','');
$template->replace('LINK_GERMAN','/de/anmelden.htm');
$template->replace('LINK_ENGLISH','/en/login.htm');
?>