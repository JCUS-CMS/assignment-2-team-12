<?php
/*
===========================================================================================================
One ARK - index.php
===========================================================================================================
This is the most generic template file in a WordPress theme and is one of the two required files for a 
theme (the other being style.css). The index.php template file is flexible. It can be used to include all 
references to the header, content, widget, footer and any other pages created in WordPress. Or it can be 
divided into modular template files, each taking on part of the workload. If you do not provide other 
template files, WordPress may have default files or functions to perform their jobs.

@package        One ARK WordPress Theme
@copyright      Copyright (C) 2018. Benjamin Lu
@license        GNU General Public License v2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
@author         Benjamin Lu (https://www.getbenonit.com/)
===========================================================================================================
*/
?>
<?php get_header(); ?>
    <?php if ('left-sidebar' == get_theme_mod('global_layout', 'left-sidebar')) { ?>
        <?php one_ark_content_setup(); ?>
    <?php } else if ('right-sidebar' == get_theme_mod('global_layout', 'right-sidebar')) { ?>
        <?php one_ark_content_setup(); ?>
    <?php } else { ?>
        <?php one_ark_content_setup(); ?>
    <?php } ?> 
<?php get_footer(); ?>