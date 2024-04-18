<?php
include_once(__DIR__ . '/../config/constants.php');
use Timber\Site;

/**
 * Class StarterSite
 */
class StarterSite extends Site
{
	public function __construct()
	{
		// setup
		add_action('after_setup_theme', array($this, 'theme_supports'));
		add_action('after_setup_theme', array($this, 'textdomain'));
		add_action('after_setup_theme', array($this, 'add_image_sizes'));
		add_action('init', array($this, 'register_post_types'));
		add_action('init', array($this, 'register_taxonomies'));

		// acf
		add_filter('acf/settings/save_json', array($this, 'my_acf_json_save_point'));
		add_filter('acf/settings/load_json', array($this, 'my_acf_json_load_point'));
		add_filter('acf/format_value/name=stats', array($this, 'stats_format_value'), 10, 3);

		// rewrite rules
		// add_action('init', array($this, 'custom_rewrite_rule'), 10, 0);

		// admin
		add_action('admin_init', array($this, 'add_editor_styles'));
		// add_action('admin_menu', array($this, 'custom_admin_menu'));

		// timber
		add_filter('timber/context', array($this, 'add_to_context'));
		add_filter('timber/twig', array($this, 'add_to_twig'));
		add_filter('timber/twig/environment/options', [$this, 'update_twig_environment_options']);

		// scripts / styles
		add_action('wp_enqueue_scripts', array($this, 'add_theme_scripts'));

		parent::__construct();
	}

	/**
	 * This is where you can register custom post types.
	 */
	public function register_post_types()
	{
		register_post_type('partenaires', [
				'labels'      => $this->get_custom_entity_labels('partenaire', 'partenaires', 'm'),
				'public'      => true,
				'has_archive' => true,
				'menu_icon'   => 'dashicons-share-alt',
				'supports'    => ['title', 'editor', 'thumbnail']
		]);
	}

	/**
	 * This is where you can register custom taxonomies.
	 */
	public function register_taxonomies()
	{
		register_taxonomy( 'type', 'partenaires', [
			'labels'      => $this->get_custom_entity_labels('type', 'types', 'm'),
			'hierarchical' => true
		]);
	}

	public function add_theme_scripts()
	{
		// CSS
		$cssFileURI = get_template_directory_uri() . '/assets/build/css/main.min.css';
		wp_enqueue_style('main_css', $cssFileURI);

		// JS
		$jsFileURI = get_template_directory_uri() . '/assets/build/js/index.min.js';
		wp_enqueue_script('main_js', $jsFileURI, null, null, true);
	}

	/**
	 * This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context($context)
	{
		$context['menu']  = Timber::get_menu();
		$context['site']  = $this;
		$context['is_front_page'] = is_front_page();

		return $context;
	}

	public function theme_supports()
	{
		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		// add_theme_support(
		// 	'post-formats',
		// 	array(
		// 		'aside',
		// 		'image',
		// 		'video',
		// 		'quote',
		// 		'link',
		// 		'gallery',
		// 		'audio',
		// 	)
		// );

		add_theme_support('menus');
		add_post_type_support( 'page', 'excerpt' );
	}

	public function textdomain()
	{
		load_theme_textdomain('septiemecontinent', get_template_directory() . '/languages');
	}

	/**
	 * This is where you can add your own functions to twig.
	 *
	 * @param Twig\Environment $twig get extension.
	 */
	public function add_to_twig($twig)
	{
		/**
		 * Required when you want to use Twig’s template_from_string.
		 * @link https://twig.symfony.com/doc/3.x/functions/template_from_string.html
		 */
		// $twig->addExtension( new Twig\Extension\StringLoaderExtension() );

		$twig->addFunction(new Twig\TwigFunction('svg', [$this, 'get_inline_svg']));
		$twig->addFunction(new Twig\TwigFunction('get_attachment_image', [$this, 'get_attachment_image']));

		return $twig;
	}

	/**
	 * Updates Twig environment options.
	 *
	 * @link https://twig.symfony.com/doc/2.x/api.html#environment-options
	 *
	 * \@param array $options An array of environment options.
	 *
	 * @return array
	 */
	function update_twig_environment_options($options)
	{
		// $options['autoescape'] = true;

		return $options;
	}

	public function add_image_sizes()
	{
		// width x height x crop
		$image_sizes = [
			'9999x567x0',
			'425x260x1',
			'465x9999x0',
			'440x9999x0',
			'9999x360x0',
			'345x345x1',
			'769x9999x0',
			'80x80x1',
			'130x130x0',
			'215x215x1',
			'440x440x1',
		];

		foreach ($image_sizes as $image_size) {
			list($width, $height, $crop) = explode('x', $image_size);
			$crop = ($crop == '1');
			add_image_size($image_size, $width, $height, $crop);
		}
	}

	public function custom_rewrite_rule()
	{
		add_rewrite_tag('%newtag%', '([^&]+)');
		add_rewrite_rule('^newpage/([^&]+)/?', 'index.php?page_id=1234&newtag=$matches[1]', 'top');
	}

	public function add_editor_styles()
	{
		$cssFileURI = get_template_directory_uri() . '/assets/build/css/editor.min.css';
		add_editor_style($cssFileURI);
	}

	public function custom_admin_menu()
	{
		remove_menu_page('edit.php');
		remove_menu_page('wpseo_dashboard');
		remove_menu_page('wpseo_workouts');
	}

	/**
	 * Return html of an svg for inline embedding
	 *
	 * @param string $filename of svg in static assets folder.
	 */
	public function get_inline_svg($filename)
	{
		$html = '';
		$file = get_template_directory_uri() . '/assets/build/img/svg/' . $filename . '.svg';
		$content = @file_get_contents($file);
		$className = 'svg-%name';

		if ($content === FALSE) {
			return $html;
		} else {
			$uid = uniqid();
			$content = preg_replace('/<svg/', '<svg role="presentation"', $content);
			preg_match('/([^\\/\\\\]+)\.svg$/i', $file, $matches);

			if (count($matches) === 2) {
				$content = preg_replace('/\sid=(["\'])([^"\']+)(["\'])/', ' id=$1' . $matches[1] . '-$2-' . $uid . '$3', $content);
				$content = preg_replace('/url\(#([^)]+)\)/', 'url(#' . $matches[1] . '-$1-' . $uid . ')', $content);
				$content = preg_replace('/\sxlink:href=(["\'])#([^"\']+)(["\'])/', ' xlink:href=$1#' . $matches[1] . '-$2-' . $uid . '$3 ', $content);

				$content = preg_replace('/<svg/', '<svg class="' . preg_replace('/%name/', $matches[1], $className) . '"', $content);
			}
			$html = $content;
		}

		return $html;
	}

	public function get_attachment_image($attachment_id, $size = 'thumbnail', $icon = false)
	{
		if (is_array($attachment_id)) {
			$attachment_id = $attachment_id['ID'];
		}
		if (is_object($attachment_id)) {
			$attachment_id = $attachment_id->ID;
		}
		return wp_get_attachment_image($attachment_id, $size, $icon);
	}

	public function my_acf_json_save_point( $path ) {
		return get_stylesheet_directory() . '/acf-json';
	}
	public function my_acf_json_load_point( $paths ) {
		$paths[] = get_stylesheet_directory() . '/acf-json';
		return $paths;    
	}

	// Generate labels for register_post_type and register_taxonomy functions
	public function get_custom_entity_labels($singular, $plural, $genre = 'm', $overwrite = array()) {
		$un_une = "un".($genre=='f'?'e':'');
		$tous_toutes = "tou".($genre=='f'?'te':'')."s";
	 
	 
		if (in_array($singular[0], array('a', 'e', 'é', 'è', 'i', 'o', 'u'))) {
			$le_la_l = "l'";
		} else {
			if ($genre == 'f') {
				$le_la_l = "la ";
			} else {
				$le_la_l = "le ";
			}
		}
	 
	 
		if ($genre == 'f') {
			$nouveau_nouvelle_nouvel = "nouvelle";
		} else {
			if (in_array($singular[0], array('a', 'e', 'é', 'è', 'i', 'o', 'u'))) {
				$nouveau_nouvelle_nouvel = "nouvel";
			} else {
				$nouveau_nouvelle_nouvel = "nouveau";
			}
		}
	 
	 
		$labels = array(
			'name' => ucfirst($plural),
			'singular_name' => ucfirst($singular),
			'menu_name' => ucfirst($plural),
			'name_admin_bar' => "Ajouter ".$un_une." $singular",
			'all_items' => ucfirst($tous_toutes)." les $plural",
			'add_new' => 'Ajouter',
			'add_new_item' => "Ajouter ".$un_une." $singular",
			'edit_item' => "Modifier ".$le_la_l."$singular",
			'new_item' => ucfirst($nouveau_nouvelle_nouvel)." $singular",
			'view_item' => "Voir ".$le_la_l."$singular",
			'search_items' => "Rechercher ".$un_une." $singular",
			'not_found' => "Aucun".($genre=='f'?'e':'')." $singular trouvé",
			'not_found_in_trash' => "Aucun".($genre=='f'?'e':'')." $singular dans la corbeille",
			'parent_item_colon' => "Parent",
		);
		$labels = $overwrite + $labels;
	 
	 
		return $labels;
	}	 

	public function stats_format_value($value, $post_id, $field) {
		foreach ($value as $key => $stat) {
			$title = $stat['title'];
			$title = str_replace('[', '<small>', $title);
			$title = str_replace(']', '</small>', $title);
			$value[$key]['title'] = $title;
		}
		return $value;
	}
}
