<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title><?php echo SITE_TITLE.':  '.$current_page;?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="<?php echo base_url();?>css/global.css">
    <script type="text/javascript" src="<?php echo base_url();?>js/jquery-2.0.3.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/main.js"></script>

    <!--IE fix for Sticky Footer template--!>
    <!--[if !IE 7]>
    <style type="text/css">
        #wrap { display:table; height:100% }
    </style>
    <![endif]-->
</head>
<body>
    <div id="wrap">
        <div id="main">

            <div id="site_head">

                <div id="site_title">
                    <a href='<?php echo base_url();?>'><img src="<?php echo base_url();?>img/logo2.png" alt="GitAsterisk site logo"></a>
                </div>

                <div id="head_menu">
                    <ul>
                        <li><a href="<?php base_url();?>/"                 <?php if($current_page == 'Main') echo 'class="current_item"';?>            >Main</a></li>
                        <li><a href="<?php base_url();?>/search_project"   <?php if($current_page == 'Search project') echo 'class="current_item"';?>  >Search project</a></li>
                        <?php if($current_page == 'Project details'):?>
                            <li><a href="<?php base_url();?>/project_details"  class="current_item">Project details</a></li>
                        <?php endif;?>
                        <?php if($current_page == 'User details'):?>
                            <li><a href="<?php base_url();?>/user_details" class="current_item">User details</a></li>
                        <?php endif;?>
                    </ul>
                </div>

            </div>
