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
class Pgm_Settings_Login {
	protected $plugin_name;
	protected $version;

	public function __construct($plugin_name, $version) {
        $this->version = $version;
		$this->plugin_name = $plugin_name;
	}
    public function logo_css() {
        ?>
            <style type="text/css">
                #login h1 a, .login h1 a {
                    background-image: url('<?php echo get_site_icon_url() ?>');
                    height:100px;
                    width:100px;
                    background-size: 100px 100px;
                    background-repeat: no-repeat;
                    padding-bottom: 10px;
                }
                div#login {
                    width: 360px;
                }
                #loginform {
                    border: none;
                    border-radius: 8px;
                    padding: 35px 40px 39px;
                }
                #nav, #backtoblog {
                    text-align: center;
                }
            </style>
        <?php
    }
    public function logo_url() {
        return home_url();
    }
    public function logo_url_title() {
        return get_bloginfo('name');
    }
	public function get_plugin_name() {
		return $this->plugin_name;
	}
	public function get_version() {
		return $this->version;
	}
}
