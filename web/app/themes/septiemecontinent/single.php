<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context         = Timber::context();
$timber_post     = Timber::get_post();
$context['post'] = $timber_post;

// Sidebar
$latest_posts = Timber::get_posts([
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 3
]);
$context['sidebar'] = Timber::get_sidebar('partial/sidebar.twig', [
    'title' => __('Derniers articles', 'septiemecontinent'),
    'posts' => $latest_posts
]);

if ( post_password_required( $timber_post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array( 'single-' . $timber_post->ID . '.twig', 'single-' . $timber_post->post_type . '.twig', 'single-' . $timber_post->slug . '.twig', 'single.twig' ), $context );
}
