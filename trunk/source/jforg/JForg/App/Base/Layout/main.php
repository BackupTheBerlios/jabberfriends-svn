<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>JForg :: <?php echo $this->escape($this->title); ?></title>
            <link rel="stylesheet" type="text/css" href="/css/main.css" />
            <style type="text/css">
                #strich {
                    position: fixed;
                    left: 150px;
                    top: 0px;
                    width: 1px;
                    height: 100%;
                    background-color: #4b72b0;
                } 
            </style>
    </head>
    <body>
        <div id="strich"></div>
        <div id="header">
            <a href="<?php echo $this->action('/'); ?>">
                <img src="/images/jforg_logo_header.gif" alt="JabberFriends.org"/><img src="/images/jforg_text_header.gif" alt="JabberFriends.org"/>
            </a>
        </div>
        <div id="leiste">
            <div id="login">
                <a 
                    href="<?php echo $this->action('login'); ?>">
                    <?php echo $this->getText('TEXT_LOGIN'); ?>
                </a>
                /
                <a
                    href="<?php echo $this->action('register'); ?>">
                    <?php echo $this->getText('TEXT_REGISTER'); ?>
                </a>
            </div>
            <div id="sprache">
                Select your language:
                <a
                    href="<?php echo $this->action('setup/language/de_DE'); ?>">
                    Deutsch
                </a>
                /
                <a
                    href="<?php echo $this->action('setup/language/de_DE'); ?>">
                    English
                </a>
            </div>
        </div>
        <div id="navigation">
            <a
                <?php if ($this->navi_highlight=='tuerkis') {
                    echo 'style="background-color: #4ba7b0;"';
                } ?>
                class="tuerkis"
                href="<?php echo $this->action('/'); ?>">
                <?php echo $this->getText('TEXT_STARTPAGE'); ?>
            </a>
            <a
                <?php if ($this->navi_highlight=='green') {
                    echo 'style="background-color: #4bb072;"';
                } ?>
                class="green"
                href="<?php echo $this->action('/search/'); ?>">
                <?php echo $this->getText('TEXT_MEMBERS'); ?>
            </a>
            <a
                <?php if ($this->navi_highlight=='lila') {
                    echo 'style="background-color: #864bb0;"';
                } ?>
                class="lila"
                href="<?php echo $this->action('/portal/'); ?>">
                <?php echo $this->getText('TEXT_PORTAL'); ?>
            </a>
            <a
                <?php if ($this->navi_highlight=='red') {
                    echo 'style="background-color: #b04b4b;"';
                } ?>
                class="red"
                href="<?php echo $this->action('/wiki/'); ?>">
                <?php echo $this->getText('TEXT_WIKI'); ?>
            </a>
            <a
                <?php if ($this->navi_highlight=='yellow') {
                    echo 'style="background-color: #b0ae4b;"';
                } ?>
                class="yellow"
                href="<?php echo $this->action('/developers/'); ?>">
                <?php echo $this->getText('TEXT_DEVELOPERS'); ?>
            </a>
        </div> 
        <?php echo $this->layout_content ?>  
    </body>
</html>