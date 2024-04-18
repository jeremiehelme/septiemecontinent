<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.gimond.fr
 * @since      1.0.0
 *
 * @package    Pgm_Settings
 * @subpackage Pgm_Settings/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Pgm_Settings
 * @subpackage Pgm_Settings/includes
 * @author     Gimond <hello@gimond.fr>
 */
class Pgm_Settings_Tools {
	protected $plugin_name;
	protected $version;

	public function __construct($plugin_name, $version) {
        $this->version = $version;
		$this->plugin_name = $plugin_name;
	}
    public function disable_checked_term_on_top()
    {
        add_filter('wp_terms_checklist_args', function ($args, $post_id) {
            $args['checked_ontop'] = false;
            return $args;
        }, 1, 2);
    }

    public function disallow_file_edit() {
        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
    }

    public function disable_comments() {
        // Removes from admin menu
        add_action( 'admin_menu', function() {
            remove_menu_page( 'edit-comments.php' );
        } );

        // Removes from post and pages
        add_action('admin_init', function() {
            // Redirect any user trying to access comments page
            global $pagenow;

            if ($pagenow === 'edit-comments.php') {
                wp_safe_redirect(admin_url());
                exit;
            }

            // Remove comments metabox from dashboard
            remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

            $post_types = get_post_types();
            foreach ($post_types as $post_type) {
                if (post_type_supports($post_type, 'comments')) {
                    remove_post_type_support($post_type, 'comments');
                    remove_post_type_support($post_type, 'trackbacks');
                }
            }
        }, 100);

        // Hide existing comments
        add_filter('comments_array', '__return_empty_array', 10, 2);

        // Removes from admin bar
        add_action( 'wp_before_admin_bar_render', function() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('comments');
        } );


        // Close comments on the front-end
        add_filter('comments_open', '__return_false', 20, 2);
        add_filter('pings_open', '__return_false', 20, 2);
    }
    public function disable_resource_hints() {
        add_action( 'init', function() {
            remove_action( 'wp_head', 'wp_resource_hints', 2);
        } );
    }
    public function remove_emojis() {
        // REMOVE EMOJI ICONS
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    }
    public function remove_acf_style_on_front() {
        add_action( 'wp_enqueue_style', function() {
            if( ! is_admin() ) {
                wp_deregister_style( 'acf' );
                wp_deregister_style( 'acf-field-group' );
                wp_deregister_style( 'acf-global' );
                wp_deregister_style( 'acf-input' );
                wp_deregister_style( 'acf-datepicker' );
            }
        }, 100 );
    }
    public function remove_generator() {
        add_action( 'init', function() {
            // not sure what this does, but it displays WordPress verion, which we don't want
            remove_action('wp_head', 'wp_generator');
        });
    }
    public function remove_feeds() {
        add_action( 'init', function() {
            // Category Feeds
            remove_action('wp_head', 'feed_links_extra', 3);
            // Post and Comment Feeds
            remove_action('wp_head', 'feed_links', 2);
            // EditURI link
            remove_action('wp_head', 'rsd_link');
        });
        add_action( 'after_theme_support', function() {
            remove_theme_support( 'automatic-feed-links' );
        });
    }
    public function remove_head_posts_links() {
        add_action( 'init', function() {
            remove_action('wp_head', 'index_rel_link'); // remove link to index page
            remove_action('wp_head', 'wlwmanifest_link'); // remove wlwmanifest.xml (needed to support windows live writer)

            remove_action('wp_head', 'start_post_rel_link', 10, 0); // remove random post link
            remove_action('wp_head', 'parent_post_rel_link', 10, 0); // remove parent post link
            remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // remove the next and previous post links
            remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
        });
    }
    public function disable_admin_bar_for_all() {
        show_admin_bar(false);
    }
    public function remove_embed() {
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
    }
    public function limit_revisions() {
        if (!defined('WP_POST_REVISIONS')) {
            define('WP_POST_REVISIONS', 5);
        }
    }
    public function footer_text()
    {
        add_filter('admin_footer_text', function() {
            $text = get_option('pgm_footer_text');
            if (!empty($text)) {
                echo $text;
            }
            return;
        });

//        add_filter('update_footer', function() {
//            echo 'Version ' . get_bloginfo('version');
//            return;
//        }, 999);
    }
    public function remove_admin_bar_wordpress_logo() {
        add_action( 'admin_bar_menu', function(){
            global $wp_admin_bar;
            $wp_admin_bar->remove_node( 'wp-logo' );
        }, 999);
    }
    public function disable_api() {
        add_action( 'after_setup_theme', function() {
            add_filter('rest_enabled', '__return_false');
            add_filter('rest_jsonp_enabled', '__return_false');

            // Remove the REST API lines from the HTML Header
            remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
            remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

            // Remove the REST API endpoint.
            remove_action( 'rest_api_init', 'wp_oembed_register_route' );
        } );
    }
    public function disable_xml_rpc() {
        // Disable use XML-RPC
        add_filter( 'xmlrpc_enabled', '__return_false' );

        // Disable X-Pingback to header
        add_filter( 'wp_headers', function($headers) {
            unset( $headers['X-Pingback'] );
            return $headers;
        } );
    }
    public function remove_version_in_script_style() {
        /** Remove Version Query Strings from Scripts/Styles **/
        add_filter('script_loader_src', function($src) {
            $parts = explode('?ver', $src);
            return $parts[0];
        }, 15, 1);
        add_filter('style_loader_src', function($src) {
            $parts = explode('?ver', $src);
            return $parts[0];
        }, 15, 1);
    }
    public function clean_admin_metaboxes() {
        add_action('admin_init', function() {
            remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
            remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
            remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
            remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');//since 3.8
        });
    }
    public function yoast_remove_dashboard_overview(){
        add_filter('wp_dashboard_setup', function(){
            remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'side' );
        });
    }
    public function yoast_move_metabox_below(){
        add_filter( 'wpseo_metabox_prio', function(){
            return 'low';
        });
    }
    public function yoast_remove_code_comments() {
        add_filter('wpseo_debug_markers', '__return_false');
    }
	public function get_plugin_name() {
		return $this->plugin_name;
	}
	public function get_version() {
		return $this->version;
	}
}
