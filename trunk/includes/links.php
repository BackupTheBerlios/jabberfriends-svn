<?php
if ($language=="de") {
    $template->replace('LINK_STARTPAGE','/de/');
    $template->replace('LINK_MEMBERS','/de/mitglieder/');
    $template->replace('LINK_PORTAL','/de/portal/');
    $template->replace('LINK_WIKI','/de/wiki/');
    $template->replace('LINK_DEVELOPERS','http://developers.jabberfriends.org');
    $template->replace('LINK_LOGIN','/de/anmelden.htm');
    $template->replace('LINK_REGISTER','/de/registrieren.htm');
    $template->replace('LINK_LOGOUT','/de/abmelden.htm');
    $template->replace('LINK_OPTIONS','/de/optionen.htm');
    $template->replace('LINK_CHANGEDETAILS','/de/angaben_aendern.htm');
}
if ($language=="en") {
    $template->replace('LINK_STARTPAGE','/en/');
    $template->replace('LINK_MEMBERS','/en/members/');
    $template->replace('LINK_PORTAL','/en/portal/');
    $template->replace('LINK_WIKI','/en/wiki/');
    $template->replace('LINK_DEVELOPERS','http://developers.jabberfriends.org');
    $template->replace('LINK_LOGIN','/en/login.htm');
    $template->replace('LINK_REGISTER','/en/register.htm');
    $template->replace('LINK_LOGOUT','/en/logout.htm');
    $template->replace('LINK_OPTIONS','/en/options.htm');
}
?> 
