<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>CrowdRock</title>
        <link href="/application/images/favicon.ico" rel="icon" type="image/x-icon" />
        <link type="text/css" rel="stylesheet" href="/application/stylesheets/style.css"/>
        <script type='text/javascript' src="/application/javascripts/jquery-1.6.4.js"></script>
    </head>

    <body>
        <div class="body">
            <div class="header">
                <div class="logo">
                    <!--img src="/application/images/logo.jpg" height="50px" alt="Logo"/-->
                    <a href="/">Crowdrock App</a>
                </div>
                <div class="links">
                    <?php
                    if ($this->ion_auth->logged_in()) {
                        echo anchor('/profile/show/' . $this->session->userdata('user_id'), $this->session->userdata('username'));
                        echo " &middot; ";
                        echo "<a href='/auth/change_password'>Change password</a>";
                        echo " &middot; ";
                        echo "<a href='/auth/logout'>Logout</a>";
                        //echo "<a href='/songs'>All Songs</a>";
                    }else{
                        //echo "<a href='/auth/login'>Log in</a>";
                    }
                    ?>
                </div>
                <div style="clear: right"></div>
                <div class="menu">
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="http://crowdrockapp.wordpress.com/about/">About</a></li>
                        <li><a href="http://crowdrockapp.wordpress.com/contact/">Contact</a></li>
                        <?php
                    if (!$this->ion_auth->logged_in()) {
                        echo "<li><a href='/auth/login'>Log in</a></li>";
                        echo "<li><a href='/auth/create_user'>Sign up</a></li>";
                    } else {
                        echo "<li><a href='/parties'>Parties</a></li>";
                    }
                    ?>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <div class='mainInfo'>
                <div id="infoMessage"><?php echo $this->session->flashdata('message'); ?></div>
                <div id="nav-menu">
                    <?php
                    if ($this->ion_auth->logged_in()) {
                        echo "<a href='/parties'>My Parties</a> ";
                        if (isset($party) && $party->id != null) {
                            echo "<a href='/parties/show/" . $party->id . "'>Email My Playlist</a> ";
                            echo "<a href='/partysongs/song_list/" . $party->id . "'>My Songs</a> ";
                            echo "<a href='/partysongs/show/" . $party->id . "'>DJ Panel</a> ";
                        }
                    }
                    ?>
                </div>
                <div style="clear: both;"></div>
                <div style="float: left; width: 100%;">
