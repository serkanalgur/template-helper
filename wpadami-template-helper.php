<?php

/**
 * WPAdamı Template Helper
 *
 * @package   WPAdami_Template_Helper
 * @author    Serkan Algur <info@wpadami.com>
 * @license   GPL-3.0+
 * @link      https://github.com/serkanalgur/wpadami-template-helper
 * @copyright 2019 Serkan Algur, WPAdamı
 *
 * @wordpress-plugin
 * Plugin Name:       WPAdamı Template Helper
 * Plugin URI:        https://github.com/serkanalgur/wpadami-template-helper
 * Description:       Template helper by Serkan Algur
 * Version:           1.0.0
 * Author:            Serkan Algur
 * Author URI:        https://github.com/serkanalgur
 * Text Domain:       wpadami-template-helper
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/serkanalgur/wpadami-template-helper
 * GitHub Branch:     master
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Begin

if ( ! class_exists( 'WPAdami_Template_Helper', true ) ) {

	class WPAdami_Template_Helper {

		public function __construct() {

			add_filter( 'wp_handle_upload_prefilter', array( __CLASS__, 'correct_utf_chars_filename' ) );
			add_action( 'dashboard_glance_items', array( __CLASS__, 'cpt_to_dashboard_info' ) );
			add_action( 'wp_dashboard_setup', array( __CLASS__, 'disable_default_dashboard_widgets' ), 999 );
		}

		/**
		 * correct wrong chars on filenames
		 *
		 * @since 1.0.0
		*/

		public function correct_utf_chars_filename( $file = array() ) {

			include plugin_dir_path( __FILE__ ) . '/helpers/foreign_chars.php';

			$file['name'] = strtolower( preg_replace( array_keys( $foreign_characters ), array_values( $foreign_characters ), $file['name'] ) );

			return $file;
		}

		/**
		 * show post types info in to dashboard widget
		 *
		 * @since 1.0.0
		*/

		public function cpt_to_dashboard_info() {

			$post_types = get_post_types( array( '_builtin' => false ), 'objects' );
			if ( $post_types ) :
				foreach ( $post_types as $post_type ) {
					$num_posts       = wp_count_posts( $post_type->name );
					$num             = number_format_i18n( $num_posts->publish );
					$cptsingularname = $post_type->labels->singular_name;
					$cptname         = $post_type->labels->name;
					$text            = _n( $cptsingularname, $cptname, $num_posts->publish );
					if ( current_user_can( 'edit_posts' ) ) {
						$tt = '<a href="edit.php?post_type=' . $post_type->name . '">' . $num . ' ' . $text . '</a>';
					}
					echo '<li class="post-count">' . $tt . '</li>';

					if ( $num_posts->pending > 0 ) {
						$num  = number_format_i18n( $num_posts->pending );
						$text = _n( $cptsingularname . ' pending', $cptname . ' pending', $num_posts->pending );
						if ( current_user_can( 'edit_posts' ) ) {
							$tt = '<a href="edit.php?post_status=pending&post_type=' . $post_type->name . '">' . $num . ' ' . $text . '</a>';
						}
						echo '<li class="first b b-' . $post_type->name . 's">' . $tt . '</li>';
					}
				}
			endif;

		}

		/**
		 * remove unused or not needed dashboard widgets
		 *
		 * @since 1.0.0
		*/

		public function disable_default_dashboard_widgets() {
			global $wp_meta_boxes;
			// wp..
			unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity'] );
			unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'] );
			unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] );
			unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] );
			unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
			unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] );
			unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
			unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'] );
			// bbpress
			unset( $wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now'] );
			// yoast seo
			unset( $wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget'] );
			// gravity forms
			unset( $wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard'] );
		}

	}
}

$WPAdami_Template_Helper = new WPAdami_Template_Helper();
