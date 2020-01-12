<?php
/*
===============================================================================================================
One ARK - template-tags.php
===============================================================================================================
This is the most generic template file in a WordPress theme and is one of the two required files 
for a theme (the other being functions.php). This template-tags.php template file allows you to 
add additional features and functionality to a WordPress theme which is stored in the includes 
folder. The primary template file functions.php contains the main features and functionality to 
the WordPress theme which is stored in the root of the theme's directory.

@package        One ARK WordPress Theme
@copyright      Copyright (C) 2018. Benjamin Lu
@license        GNU General Public License v2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
@author         Benjamin Lu (https://www.getbenonit.com/)
===============================================================================================================
*/

/*
===============================================================================================================
Table of Content
===============================================================================================================
 1.0 - Author Avatar Size
 2.0 - Author Byline
 2.0 - Entry Time Stamp Setup
 3.0 - Entry Posted On Setup
 4.0 - Entry Taxonomies Setup
===============================================================================================================
*/

/*
===============================================================================================================
 1.0 - Author Avatar Size
===============================================================================================================
*/
function one_ark_author_avatar_size_setup() {
    $author_avatar_size = apply_filters('one_ark_author_avatar_size', 75);

    printf('%1$s', get_avatar(get_the_author_meta('user_email'), $author_avatar_size));
}

/*
===============================================================================================================
 1.0 - Author Byline
===============================================================================================================
*/
function one_ark_author_byline_setup() {
    $byline = '<span class="author vcard"><span>' . sprintf(esc_html__('by ', 'one-ark')) . '</span><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>';
    echo one_ark_entry_time_stamp_setup();
    echo '<span class="byline"> ' . $byline . '</span>';
}

function one_ark_add_comment_setup() {
    if ( !is_page() && !post_password_required() && (comments_open() || get_comments_number())) {
        echo '<span class="entry-comments">';
            comments_popup_link(sprintf(esc_html__('Add a Comment', 'one-ark')));
        echo '</span>';
    }
}

/*
===============================================================================================================
 2.0 - Entry Time Stamp Setup
===============================================================================================================
*/
function one_ark_entry_time_stamp_setup() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if (get_the_time('U') !== get_the_modified_time('U')) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr(get_the_date('c')),
		esc_html(get_the_date()),
		esc_attr(get_the_modified_date('c')),
		esc_html(get_the_modified_date())
    );
    
    return sprintf('<a href="'. esc_url(get_permalink()) .'">'. $time_string .'</a>');
}

/*
===============================================================================================================
 3.0 - Entry Posted On Setup
===============================================================================================================
*/
function one_ark_entry_posted_on_setup() {
    one_ark_author_byline_setup();
    one_ark_add_comment_setup();
}

/*
===============================================================================================================
 4.0 - Entry Taxonomies Setup
===============================================================================================================
*/
function one_ark_entry_taxonomies_setup() {
    $cat_list = get_the_category_list(esc_html__(' | ', 'one-ark'));
    $tag_list = get_the_tag_list('', esc_html(' | ', 'one-ark'));

    if ($cat_list) {
        printf('<div class="cat-link"><i class="fa fa-folder-open-o"></i> %1$s <span class="cat-list">%2$s</span></div>',
        esc_html__('Posted On', 'one-ark'),
        $cat_list
        );
    }

    if ($tag_list) {
        printf('<div class="tag-link"><i class="fa fa-tags"></i> %1$s <span class="tag-list">%2$s</span></div>',
        esc_html__('Tagged', 'one-ark'),
        $tag_list
        );
    }
}