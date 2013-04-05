<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>CrowdRock</title>
        <link type="text/css" rel="stylesheet" href="/application/stylesheets/style.css"/>
        <link type="text/css" rel="stylesheet" href="/application/stylesheets/jquery.mobile-1.0.1.min.css"/>
        <link type="text/css" rel="stylesheet" href="/application/stylesheets/mobile-override.css"/>
        <script type='text/javascript' src="/application/javascripts/jquery-1.6.4.js"></script>
        <script type='text/javascript' src="/application/javascripts/jquery-ui-1.8.16.js"></script>
        <script type='text/javascript' src="/application/javascripts/jquery.mobile-1.0.1.min.js"></script>
        <script type='text/javascript' src="/application/javascripts/mobile-override.js"></script>
        <link href="/application/images/favicon.ico" rel="icon" type="image/x-icon" />

        <script type="text/javascript">
            $('.sresult').live('vclick', function(event) {
                //alert('ok');
            });
        </script>

        <script type="text/javascript">
            $(document).ready(function(e) {
                $('li').removeClass('ui-corner-bottom');
                $('ul')
                .addClass('ui-corner-top')
                .removeClass('ui-corner-all')
                .sortable({
                    'containment': 'parent',
                    'opacity': 0.6,
                    update: function(event, ui) {
                        //alert("dropped");
                    }
                });
            });
        </script>

    </head>

    <body class="mobile">
        <div class="body">
            <!--div class="header">
                <div class="logo">
                    <img src="/application/images/logo.jpg" height="30px" alt="Logo"/>
                </div>
                <div class="title">
            <?php
            if (isset($page_title) && $page_title != NULL)
                echo $page_title;
            ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <div class='mainInfo'-->
            <div id="infoMessage"><?php echo $this->session->flashdata('message'); ?></div>
