<?php
    $lang_variable = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    $lang_code = $lang_variable{0}.$lang_variable{1};
    include('config.php');
    if (!in_array($lang_code,$config['languages'])) {
        $lang_code = 'en';
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<div xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>JabberFriends.org :: All Around The World</title>
        <link rel="shortcut icon" href="/favicon.ico">
        <style type="text/css">
            body{
                text-align: center;
                background-color: #4b72b0;
                color: #c7e0ff;
                font-size: 20pt;
                font-family: Verdana, Arial, sans-serif;
            }
            a {
                color: #c7e0ff;
                font-size: 18pt;
                font-family: Verdana, Arial, sans-serif;
                font-weight: bold;
                text-decoration: none;
            }
            h1 {
                color: #c7e0ff;
                font-size: 25pt;
                font-family: Verdana, Arial, sans-serif;
                font-weight: bold;
                text-decoration: none;
            }
        </style>
        <meta name="description" content="{META_DESCRIPTION}" />
        <meta name="auther" content="Daniel Gultsch, Bahtijar Gadimov" />
        <meta name="keywords" content="{META_KEYWORDS}" />
    </head>
    <body>
        <h1>JabberFriends.org - All Around The World</h1>
        <a href="/<? echo $lang_code?>/"><img border="0" src="gfx/jforg_enter.gif" /></a><br />
        <a href="/de/">Deutsch</a><br />
        <a href="/en/">English</a>
    </body>
</html>