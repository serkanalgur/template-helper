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

			add_filter( 'wp_handle_upload_prefilter', array( $this, 'correct_utf_chars_filename' ) );
		}

		/**
		 * correct wrong chars on filenames
		 *
		 * @since 1.0.0
		*/

		public function correct_utf_chars_filename() {

			$find_utf_8   = array( 'İ', 'Ü', 'Ğ', 'Ö', 'Ç', 'Ş', 'ş', 'ç', 'ö', 'ğ', 'ü', 'ı', ' ' ); // Turkish chars for now
			$change_utf_8 = array( 'I', 'U', 'G', 'O', 'C', 'S', 's', 'c', 'o', 'g', 'u', 'i', '_' ); // Turkish chars for now
			$file['name'] = strtolower( str_replace( $find_utf_8, $change_utf_8, $file['name'] ) );
			return $file;
		}

	}
}
