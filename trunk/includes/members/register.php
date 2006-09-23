<?php
$counter = 0;
$content = $content.'<form action="{FORM_ACTION}" method="post">
                <table cellpadding="0" cellspacing="2">';
if ($_POST['submit']!="") {
    if (!preg_match('/[-._+\d\w]{3,}/i',$_POST['nick'])) {
        $nick_comment = '<tr><td>&nbsp;</td><td><em>{LANG_ONLY_LETTERS_3}</em></td></tr>';
    } else {
        if ($user->nick_exists($_POST['nick'])) {
            $nick_comment = '<tr><td>&nbsp;</td><td><em>{LANG_NICK_EXISTS}</em></td></tr>';
        } else {
            $counter++;
        }
    }
    if (!preg_match('/.+[@].{4,}/i',$_POST['jid'])) {
        $jid_comment = '<tr><td>&nbsp;</td><td><em>{LANG_UNACCEPT_JID}</em></td></tr>';
    } else {
        if ($user->jid_exists($_POST['jid'])) {
            $jid_comment = '<tr><td>&nbsp;</td><td><em>{LANG_JID_EXISTS}</em></td></tr>';
        } else {
            $counter++;
        }
    }
    if (!preg_match('/[-._+\d\w]{6,}/i',$_POST['passwd1'])) {
        $pw_comment = '<tr><td>&nbsp;</td><td><em>{LANG_ONLY_LETTERS_6}</em></td></tr>';
    } else {
        if ($_POST['passwd1']!=$_POST['passwd2']) {
            $pw_comment = '<tr><td>&nbsp;</td><td><em>{LANG_PW_NOT_SAME}</em></td></tr>';
        } else {
            $counter++;
        }
    }
}
$content = $content.$nick_comment.'<tr><td class="left">{LANG_NICK}</td><td class="right"><input value="'.$_POST['nick'].'" type="text" name="nick" /></td></tr>';
$content = $content.$jid_comment.'<tr><td>{LANG_JID}</td><td><input value="'.$_POST['jid'].'" type="text" name="jid" /></td></tr>';
$content = $content.$pw_comment.'<tr><td>{LANG_PW}</td><td><input type="password" name="passwd1" /></td></tr>
<tr><td>{LANG_PW_AGAIN}</td><td><input type="password" name="passwd2" /></td></tr>
<tr><td>&nbsp;</td><td><br /><br /><input class="submit" value="{LANG_REGISTER}" name="submit" type="submit" /></td></tr></table></form>';
if ($counter==3) {
    $user->create_new_user($_POST['jid'],$_POST['nick'],$_POST['passwd1']);
    $template->set_frame('fullpage','green');
    $content = "Sie haben sich erfolgreich angemeldet. Sie können nun in ihrem Profil weitere Angaben zu ihrer Person machen";
    $template->replace('FULLPAGE_TEXT',$content);
    $template->replace('FULLPAGE_HEADER','{LANG_SUCCESS_REG}');
    $template->replace('META_TITLE','{LANG_SUCCESS_REG}');
} else {
    $template->set_frame('fullpage','green');
    $template->replace('FULLPAGE_TEXT',$content);
    $template->replace('FULLPAGE_HEADER','{LANG_REGISTER}');
    $template->replace('META_TITLE','{LANG_REGISTER}');
}
$template->replace('FORM_ACTION','');
$template->replace('LINK_GERMAN','/de/registrieren.htm');
$template->replace('LINK_ENGLISH','/en/register.htm');
?>