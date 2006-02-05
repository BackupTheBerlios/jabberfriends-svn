<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
$user = new jforg_user();
if (in_array($_GET['lang'],$config['languages'])) {
    $language = $_GET['lang'];
} else {
    die('Language ist nicht bekannt');
}
$template = new jforg_template();
$user = new jforg_user();
$template->set_path('design');
$template->set_frame('fullpage','green');
$template->hover_on('green');
SESSION_START();
$user_id = $user->get_id($_SESSION['nick']);
if (!$user->login($_SESSION['nick'],$_SESSION['passwd'])) {
    die('You are not logged in');  
}
$content = '<form action="{FORM_ACTION}" method="post">
                <table cellpadding="0" cellspacing="2">';
if ($_POST['submit']!="") {
    $_POST['birthdate'] = $_POST['birthdate_year'].'-'.$_POST['birthdate_month'].'-'.$_POST['birthdate_date'];
    $user->set_details($user_id,$_POST);
}
$user_details = $user->get_details($user_id);
$brithvar = $user_details['BIRTHDATE']{8}.$user_details['BIRTHDATE']{9};
$birthdate_date_select = "";
if ($birtvar==0) {
    $birthdate_date_select = "$birthdate_date_select<option value=\"0\" selected=\"selected\">--</option>";
}
for($i=01;$i<=31; $i++) {
    if ($i==$brithvar) {
        $birthdate_date_select = "$birthdate_date_select<option value=\"$i\" selected=\"selected\">$i</option>";
    } else {
        $birthdate_date_select = "$birthdate_date_select<option value=\"$i\" >$i</option>";
    }
}
$yearvar = $user_details['BIRTHDATE']{0}.$user_details['BIRTHDATE']{1}.$user_details['BIRTHDATE']{2}.$user_details['BIRTHDATE']{3};
$birthdate_year_select = "";
if ($yearvar==0) {
    $birthdate_year_select = "$birthdate_year_select<option value=\"0\" selected=\"selected\">----</option>";
}
for($i=date('Y')-80;$i<=date('Y'); $i++) {
    if ($i==$yearvar) {
        $birthdate_year_select = "$birthdate_year_select<option value=\"$i\" selected=\"selected\">$i</option>";
    } else {
        $birthdate_year_select = "$birthdate_year_select<option value=\"$i\" >$i</option>";
    }
}
$monthvar = $user_details['BIRTHDATE']{5}.$user_details['BIRTHDATE']{6};
if ($monthvar==0) { $birthdate_month_select = "$birthdate_month_select<option value=\"0\" selected=\"selected\">---</option>"; } else { $birthdate_month_select = "$birthdate_month_select<option value=\"0\">---</option>"; }
if ($monthvar==1) { $birthdate_month_select = "$birthdate_month_select<option value=\"1\" selected=\"selected\">January</option>"; } else { $birthdate_month_select = "$birthdate_month_select<option value=\"1\">January</option>"; }
if ($monthvar==2) { $birthdate_month_select = "$birthdate_month_select<option value=\"2\" selected=\"selected\">February</option>"; } else { $birthdate_month_select = "$birthdate_month_select<option value=\"2\">February</option>"; }
if ($monthvar==3) { $birthdate_month_select = "$birthdate_month_select<option value=\"3\" selected=\"selected\">March</option>"; } else { $birthdate_month_select = "$birthdate_month_select<option value=\"3\">March</option>"; }
if ($monthvar==4) { $birthdate_month_select = "$birthdate_month_select<option value=\"4\" selected=\"selected\">April</option>"; } else { $birthdate_month_select = "$birthdate_month_select<option value=\"4\">April</option>"; }
if ($monthvar==5) { $birthdate_month_select = "$birthdate_month_select<option value=\"5\" selected=\"selected\">May</option>"; } else { $birthdate_month_select = "$birthdate_month_select<option value=\"5\">May</option>"; }
if ($monthvar==6) { $birthdate_month_select = "$birthdate_month_select<option value=\"6\" selected=\"selected\">June</option>"; } else { $birthdate_month_select = "$birthdate_month_select<option value=\"6\">June</option>"; }
if ($monthvar==7) { $birthdate_month_select = "$birthdate_month_select<option value=\"7\" selected=\"selected\">July</option>"; } else { $birthdate_month_select = "$birthdate_month_select<option value=\"7\">July</option>"; }
if ($monthvar==8) { $birthdate_month_select = "$birthdate_month_select<option value=\"8\" selected=\"selected\">August</option>"; } else { $birthdate_month_select = "$birthdate_month_select<option value=\"8\">August</option>"; }
if ($monthvar==9) { $birthdate_month_select = "$birthdate_month_select<option value=\"9\" selected=\"selected\">September</option>"; } else { $birthdate_month_select = "$birthdate_month_select<option value=\"9\">September</option>"; }
if ($monthvar==10) { $birthdate_month_select = "$birthdate_month_select<option value=\"10\" selected=\"selected\">October</option>"; } else { $birthdate_month_select = "$birthdate_month_select<option value=\"10\">October</option>"; }
if ($monthvar==11) { $birthdate_month_select = "$birthdate_month_select<option value=\"11\" selected=\"selected\">November</option>"; } else { $birthdate_month_select = "$birthdate_month_select<option value=\"11\">November</option>"; }
if ($monthvar==12) { $birthdate_month_select = "$birthdate_month_select<option value=\"12\" selected=\"selected\">Dezember</option>"; } else { $birthdate_month_select = "$birthdate_month_select<option value=\"12\">Dezember</option>"; }
$content = $content.'<tr><td colspan="2"><a href="{LINK_OPTIONS}">{LANG_BACK_TO_OPTIONS}</a><br /><br /></td></tr>';
$content = $content.'<tr><td class="left">{LANG_NICK}</td><td class="right">'.$user->get_nick($user_id).'</td></tr>';
$content = $content.'<tr><td>{LANG_JID}</td><td>'.$user->get_jid($user_id).'</td></tr>';
$content = $content.'<tr><td colspan="2"><br /><b>{LANG_ABOUT_YOU}</b></td></tr>';
$content = $content.$realname_warning;
$content = $content.'<tr><td>{LANG_REALNAME}</td><td><input type="text" name="realname" value="';
if ($_POST['realname']=="") { $content = $content.$user_details['REALNAME']; } else { $content = $content.$_POST['realname']; }
$content = $content.'" /></td></tr>';
$content = $content.'<tr><td>{LANG_BIRTHDATE}</td><td><select name="birthdate_date">'.$birthdate_date_select.'</select> <select name="birthdate_month">'.$birthdate_month_select.'</select> <select name="birthdate_year">'.$birthdate_year_select.'</select></td></tr>';
$content = $content.'<tr><td>{LANG_COUNTRY}</td><td><input type="text" name="country" value="'.$user_details['COUNTRY'].'" /></td></tr>';
$content = $content.'<tr><td>{LANG_CITY}</td><td><input type="text" name="city" value="'.$user_details['CITY'].'" /></td></tr>';
$content = $content.'<tr><td valign="top">{LANG_ORIGINAL_FROM}</td><td><input type="text" name="original_from" value="'.$user_details['ORIGINAL_FROM'].'" /></td></tr>';
$content = $content.'<tr><td valign="top">{LANG_LANGUAGES}</td><td><input type="text" name="languages" value="'.$user_details['LANGUAGES'].'" /></td></tr>';
$content = $content.'<tr><td valign="top">{LANG_HOBBYS}</td><td><input type="text" name="hobbys" value="'.$user_details['HOBBYS'].'" /></td></tr>';
$content = $content.'<tr><td colspan="2"><br /><b>{LANG_YOUR_COMPUTER}</b></td></tr>';
$content = $content.'<tr><td valign="top">{LANG_COMPUTER}</td><td><input type="text" name="computer" value="'.$user_details['COMPUTER'].'" /></td></tr>';
$content = $content.'<tr><td valign="top">{LANG_COMPUTER_OS}</td><td><input type="text" name="computer_os" value="'.$user_details['COMPUTER_OS'].'" /></td></tr>';
$content = $content.'<tr><td valign="top">{LANG_GEEKCODE}</td><td><textarea cols="200" rows="3" name="geekcode">'.$user_details['GEEKCODE'].'</textarea></td></tr>';
$content = $content.'<tr><td valign="top">{LANG_PUBLICKEY}</td><td><textarea cols="200" rows="8" name="publickey">'.$user_details['PUBLICKEY'].'</textarea></td></tr>';
$content = $content.'<tr><td colspan="2"><br /><b>{LANG_YOUR_FAVORITES}</b></td></tr>';
$content = $content.'<tr><td valign="top">{LANG_FAVORITE_FILM}</td><td><input type="text" name="favorite_film" value="'.$user_details['FAVORITE_FILM'].'" /></td></tr>';
$content = $content.'<tr><td valign="top">{LANG_FAVORITE_SERIES}</td><td><input type="text" name="favorite_series" value="'.$user_details['FAVORITE_SERIES'].'" /></td></tr>';
$content = $content.'<tr><td valign="top">{LANG_FAVORITE_MUSIK}</td><td><input type="text" name="favorite_musik" value="'.$user_details['FAVORITE_MUSIK'].'" /></td></tr>';
$content = $content.'<tr><td valign="top">{LANG_FAVORITE_BOOK}</td><td><input type="text" name="favorite_book" value="'.$user_details['FAVORITE_BOOK'].'" /></td></tr>';
$content = $content.'<tr><td>&nbsp;</td><td><br /><input class="submit" value="{LANG_CHANGE}" name="submit" type="submit" /></td></tr></table></form>';
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('LOGIN','{LANG_LOGOUT}');
$template->replace('REGISTER','{LANG_OPTIONS}');
$template->replace('LINK_LOGIN','{LINK_LOGOUT}');
$template->replace('LINK_REGISTER','{LINK_OPTIONS}');
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('FORM_ACTION','');
$template->replace('LINK_GERMAN','/de/angaben_aendern.htm');
$template->replace('LINK_ENGLISH','/en/change_details.htm');
$template->replace('META_TITLE','{LANG_CHANGEDETAILS}');
$template->replace('FULLPAGE_HEADER','{LANG_CHANGEDETAILS}');
$template->translate($language);
include('includes/links.php');
$template->write();
?>