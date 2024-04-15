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

if ($post->ID == ID_PAGE_COMPRENDRE) { // PAGE COMPRENDRE
    $themes = Timber::get_posts([
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_parent' => ID_PAGE_COMPRENDRE
    ]);
    $context['themes'] = $themes;
}
if ($post->post_parent == ID_PAGE_COMPRENDRE) { // PAGES ENFANTS COMPRENDRE
    $timber_post->pre_title = __('Les thématiques scientifiques', 'septiemecontinent');
    $timber_post->page_header = get_field('page_header', ID_PAGE_COMPRENDRE);
    $timber_post->page_header['title'] = get_the_title(ID_PAGE_COMPRENDRE);
    $timber_post->page_header['thumbnail_id'] = get_post_thumbnail_id(ID_PAGE_COMPRENDRE);
    $links = [];
    $themes = get_posts([
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_parent' => ID_PAGE_COMPRENDRE,
        'exclude' => [$post->ID]
    ]);
    foreach ($themes as $theme) {
        $links[] = [
            'title' => $theme->post_title,
            'link' => get_permalink($theme)
        ];
    }
    $context['sidebar'] = Timber::get_sidebar('partial/sidebar.twig', [
        'title' => __('Autres thématiques', 'septiemecontinent'),
        'links' => $links
    ]);
} else {
    $timber_post->page_header = get_field('page_header');
    $context['flexible_content'] = get_field('flexible_content');
    $context['team'] = get_field('team');
}

$context['post'] = $timber_post;

Timber::render( array( 'page-' . $timber_post->post_name . '.twig', 'page.twig' ), $context );
