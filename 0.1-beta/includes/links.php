<?php
if ($language=="de") {
    $template->replace('LINK_STARTPAGE','/de/');
    $template->replace('LINK_MEMBERS','/de/mitglieder/');
    $template->replace('LINK_PORTAL','/de/portal/');
    $template->replace('LINK_WIKI','/de/wiki/');
    $template->replace('LINK_DEVELOPERS','http://developer.berlios.de/projects/jabberfriends');
    $template->replace('LINK_LOGIN','/de/anmelden.htm');
    $template->replace('LINK_REGISTER','/de/registrieren.htm');
    $template->replace('LINK_LOGOUT','/de/abmelden.htm');
    $template->replace('LINK_OPTIONS','/de/optionen.htm');
    $template->replace('LINK_CHANGEDETAILS','/de/angaben_aendern.htm');
    $template->replace('LINK_CHANGETAGS','/de/tags_aendern.htm');
    $template->replace('LINK_SEARCH','/de/mitglieder/suche/');
    $template->replace('LINK_CHANGEPW', '/de/passwortaendern.htm');
    $template->replace('LINK_RMACC', '/de/loesche_account.htm');
    $template->replace('LINK_UPLOADUSERPIC', '/de/benutzerbild_hochladen.htm');
    $template->replace('LINK_ABOUT','/de/wiki/7-about.htm');
    $template->replace('LINK_WIKISEARCH','/de/wiki/suche/');
}
if ($language=="en") {
    $template->replace('LINK_STARTPAGE','/en/');
    $template->replace('LINK_MEMBERS','/en/members/');
    $template->replace('LINK_PORTAL','/en/portal/');
    $template->replace('LINK_WIKI','/en/wiki/');
    $template->replace('LINK_DEVELOPERS','http://developer.berlios.de/projects/jabberfriends');
    $template->replace('LINK_LOGIN','/en/login.htm');
    $template->replace('LINK_REGISTER','/en/register.htm');
    $template->replace('LINK_LOGOUT','/en/logout.htm');
    $template->replace('LINK_OPTIONS','/en/options.htm');
    $template->replace('LINK_CHANGETAGS','/en/change_tags.htm');
    $template->replace('LINK_SEARCH','/en/members/search/');
    $template->replace('LINK_CHANGEDETAILS','/en/change_details.htm');
    $template->replace('LINK_CHANGEPW', '/en/changepassword.htm');
    $template->replace('LINK_RMACC', '/en/remove_account.htm');
    $template->replace('LINK_UPLOADUSERPIC', '/en/upload_userpic.htm');
    $template->replace('LINK_ABOUT','/en/wiki/7-about.htm');
    $template->replace('LINK_WIKISEARCH','/en/wiki/search/');
}
?>  
