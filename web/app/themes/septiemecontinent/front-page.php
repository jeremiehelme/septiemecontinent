<?php

$context = Timber::context();

$timber_post     = Timber::get_post();
$context['post'] = $timber_post;

// Hero
$context['hero_data'] = get_field('hero');

// ADN
$adn_posts = [];
$pushs_adn = get_field('pushs_adn');
if (!empty($pushs_adn) && !empty($pushs_adn['pushs'])) {
    foreach ($pushs_adn['pushs'] as $key => $push) {
        $push->thumbnail = get_the_post_thumbnail($push->ID, '392x240x1');
        $push->excerpt = get_the_excerpt($push->ID);
        $push->permalink = get_permalink($push->ID);
        $adn_posts[] = $push;
    }
}
$context['adn_posts'] = $adn_posts;

// Text / Image
$text_image = get_field('text_image');
$text_image['imagesize'] = 'banner';
$text_image['margin'] = 'none';
$context['text_image'] = $text_image;

// Statistiques
$stats = get_field('stats');
$context['stats'] = ['stats' => $stats]; // c'est moche mais requis pour avoir la même structure que le contenu flexible

// Text / Image 2
$text_image_2 = get_field('text_image_2');
$text_image_2['imagesize'] = '465';
$context['text_image_2'] = $text_image_2;

// Text / Image 3
$text_image_3 = get_field('text_image_3');
$text_image_3['imagesize'] = '440';
$text_image_3['background'] = 'white';
$text_image_3['order'] = 'reverse';
$context['text_image_3'] = $text_image_3;

// Actus
$latest_posts = Timber::get_posts([
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 3
]);
$context['latest_posts'] = $latest_posts;

Timber::render( 'front-page.twig', $context );
