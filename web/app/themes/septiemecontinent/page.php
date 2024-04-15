<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/templates/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();
$timber_post     = Timber::get_post();

if ($post->post_parent == 13) {
    $timber_post->page_header = get_field('page_header', 13);
    $timber_post->page_header['title'] = get_the_title(13);
    $timber_post->page_header['thumbnail_id'] = get_post_thumbnail_id(13);
    $themes = get_posts([
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_parent' => 13
    ]);
    $context['sidebar'] = Timber::get_sidebar('partial/sidebar.twig', [
        'title' => __('Autres thématiques', 'septiemecontinent'),
        'posts' => $themes
    ]);
} else {
    $timber_post->page_header = get_field('page_header');
    $context['flexible_content'] = get_field('flexible_content');
    $context['team'] = get_field('team');
}

$context['post'] = $timber_post;

Timber::render( array( 'page-' . $timber_post->post_name . '.twig', 'page.twig' ), $context );
