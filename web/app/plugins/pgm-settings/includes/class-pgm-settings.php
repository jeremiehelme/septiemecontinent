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
class Pgm_Settings {
	protected $plugin_name;
	protected $version;

	public function __construct() {
		$this->version = PGM_SETTINGS_VERSION;
		$this->plugin_name = 'pgm-settings';

		$this->load_dependencies();
		$this->set_locale();
		$this->init_tools();
		$this->init_custom_login();
	}
	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pgm-settings-login.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pgm-settings-tools.php';
	}

	private function set_locale() {
        add_action( 'plugins_loaded', function(){
            load_plugin_textdomain(
                'pgm-settings',
                false,
                dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
            );
        } );
	}
    private function init_tools() {
        $plugin_tools = new Pgm_Settings_Tools( $this->get_plugin_name(), $this->get_version() );
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins'));

        $plugin_tools->disable_checked_term_on_top();
        $plugin_tools->disallow_file_edit();
        $plugin_tools->disable_comments();
        $plugin_tools->disable_resource_hints();
        $plugin_tools->remove_emojis();
        $plugin_tools->remove_acf_style_on_front();
        $plugin_tools->remove_generator();
        $plugin_tools->remove_feeds();
        $plugin_tools->disable_admin_bar_for_all();
        $plugin_tools->remove_embed();
        $plugin_tools->limit_revisions();
        $plugin_tools->footer_text();
        $plugin_tools->remove_admin_bar_wordpress_logo();
        $plugin_tools->disable_api();
        $plugin_tools->remove_head_posts_links();
        $plugin_tools->disable_xml_rpc();
        $plugin_tools->remove_version_in_script_style();
        $plugin_tools->clean_admin_metaboxes();

        // YOAST
        if(
            in_array('wordpress-seo/wp-seo.php', $active_plugins) ||
            in_array('wordpress-seo-premium/wp-seo-premium.php', $active_plugins)
        ) {
            $plugin_tools->yoast_remove_dashboard_overview();
            $plugin_tools->yoast_move_metabox_below();
            $plugin_tools->yoast_remove_code_comments();
        }
    }
    public function init_custom_login() {
        $plugin_login = new Pgm_Settings_Login( $this->get_plugin_name(), $this->get_version() );
        add_action( 'login_enqueue_scripts', [$plugin_login, 'logo_css'] );
        add_filter( 'login_headerurl', [$plugin_login, 'logo_url'] );
        add_filter( 'login_headertext', [$plugin_login, 'logo_url_title'] );    
        add_filter( 'login_display_language_dropdown', '__return_false' );
    }
	public function get_plugin_name() {
		return $this->plugin_name;
	}
	public function get_version() {
		return $this->version;
	}
}
