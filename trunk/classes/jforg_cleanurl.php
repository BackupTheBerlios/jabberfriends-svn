<?php
function cleanurl($url) {
    $url = htmlentities($url);
    $url = strtolower($url);
    $url = str_replace(' ','_',$url);
    $url = str_replace(' ','_',$url);
    $url = str_replace('&auml;','ae',$url);
    $url = str_replace('&uuml;','ue',$url);
    $url = str_replace('&ouml;','oe',$url);
    $url = str_replace('&szlig;','ss',$url);
    return $url;
}
?>