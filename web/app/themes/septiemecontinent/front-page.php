<?php

$context = Timber::context();

$timber_post     = Timber::get_post();
$context['post'] = $timber_post;

// Hero
$context['hero_data'] = get_field('hero');

// ADN
$adn_posts = get_posts([
    'post_type' => 'page',
    'post__in' => [13, 15, 17],
    'orderby' => 'post__in'
]);
foreach ($adn_posts as $key => $post) {
    $adn_posts[$key]->thumbnail = get_the_post_thumbnail($post->ID, '392x240x1');
    $adn_posts[$key]->excerpt = get_the_excerpt($post->ID);
}
$context['adn_posts'] = $adn_posts;

Timber::render( 'front-page.twig', $context );
