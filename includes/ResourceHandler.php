<?php

namespace Propeller;

use Exception;
use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

class ResourceHandler {

	public static $javascript_modified = false;
	public static $styles_modified = false;
	public static $files = [];

	private static $js_path;
	private static $css_path;

	public static function init() {
		self::$js_path = is_dir( PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'js' )
			? PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'js'
			: PROPELLER_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'js';

		self::$css_path = is_dir( PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css' )
			? PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css'
			: PROPELLER_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css';

		self::get_status();
	}

	/**
	 * Returns status of the resource minification
	 *
	 * @return void
	 */
	private static function get_status() {
		$files    = self::get_dev_files();
		$file_mod = self::get_dev_file_mod( $files );
		$last_mod = get_transient( PROPELLER_RESOURCE_LAST_MOD );
		if ( false === $last_mod ) {
			$last_mod = [];
			foreach ( $files as $key => $value ) {
				$last_mod[ $key ] = 0;
			}
		}
		self::$javascript_modified = isset( $last_mod['js'] ) && intval( $last_mod['js'] ) !== intval( $file_mod['js'] );
		self::$styles_modified     = isset( $last_mod['css'] ) && intval( $last_mod['css'] ) !== intval( $file_mod['css'] );
	}

	/**
	 * Returns the file mod
	 *
	 * @param ...$files
	 *
	 * @return array
	 */
	public static function get_dev_file_mod( $files ) {
		$mod = [];
		foreach ( $files as $i => $file ) {
			$mod[ $i ] = filemtime( $file );
		}

		return $mod;
	}

	/**
	 * Returns the dev files
	 *
	 * @return string[]
	 */
	public static function get_dev_files() {

		$files = [
			'js'  => self::$js_path . DIRECTORY_SEPARATOR . 'propeller-frontend.js',
			'css' => self::$css_path . DIRECTORY_SEPARATOR . 'propeller-frontend.css',
		];

		static $checked = null;
		if ( is_null( $checked ) ) {
			$checked = [];
			foreach ( $files as $key => $file ) {
				if ( ! file_exists( $file ) ) {
					continue;
				}
				$checked[ $key ] = $file;
			}
		}

		return $checked;
	}

	/**
	 * Track changes in propeller-frontend js/css files for later minimization of the FE code
	 * @return void
	 */
	public static function set_resource_last_modification() {
		clearstatcache();
		$files    = self::get_dev_files();
		$file_mod = self::get_dev_file_mod( $files );
		set_transient( PROPELLER_RESOURCE_LAST_MOD, $file_mod );
	}

	/**
	 * Minify plugin JS/CSS files
	 * @return void
	 */
	public static function minify() {
		$js_path  = PROPELLER_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'js';
		$css_path = PROPELLER_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css';

		if ( is_dir( PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'js' ) ) {
			$js_path = PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'js';
		}

		if ( is_dir( PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css' ) ) {
			$css_path = PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css';
		}

		$min_js  = $js_path . DIRECTORY_SEPARATOR . 'propel.min.js';
		$min_css = $css_path . DIRECTORY_SEPARATOR . 'propel.min.css';

		$js_order = [
			$js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'autoComplete.min.js',
			$js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'popper.min.js',
			$js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'bootstrap.min.js',
			$js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'jquery-validator' . DIRECTORY_SEPARATOR . 'jquery.validate.js',
			$js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'jquery-validator' . DIRECTORY_SEPARATOR . 'additional-methods.js',
			$js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'plain-overlay.min.js',
			$js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'nouislider.min.js',
			$js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'slick.min.js',
			$js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'calendar.min.js',
			$js_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'photoswipe.min.js',
			$js_path . DIRECTORY_SEPARATOR . 'propeller-frontend.js'
		];

		$css_order = [
			$css_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'bootstrap.min.css',
			$css_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'nouislider.min.css',
			$css_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'slick-theme.css',
			$css_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'slick.css',
			$css_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'calendartheme.css',
			$css_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'photoswipe.css',
			$css_path . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'autoComplete.min.css',
			$css_path . DIRECTORY_SEPARATOR . 'propeller-frontend.css',
			$css_path . DIRECTORY_SEPARATOR . 'propeller-responsive.css',
		];

		ResourceHandler::init();

		$js_minified  = false;
		$css_minified = false;

		try {
			if ( ResourceHandler::$javascript_modified ) {
				//self::get_assets( $js_path, 'js', $js_files );

				$js_minifier = new JS();

				foreach ( $js_order as $js ) {
					$js_minifier->add( $js );
				}

				// minify js
				if ( $js_minifier->minify( $min_js ) ) {
					$js_minified = true;
				}
			}
		} catch ( Exception $ex ) {
			propel_log( $ex->getMessage());
		}

		try {
			if ( ResourceHandler::$styles_modified ) {
				//self::get_assets( $css_path, 'css', $css_files );

				$css_minifier = new CSS();

				foreach ( $css_order as $css ) {
					$css_minifier->add( $css );
				}

				// minify css
				if ( $css_minifier->minify( $min_css ) ) {
					$css_minified = true;
				}
			}
		} catch ( Exception $ex ) {
			propel_log($ex->getMessage());
		}

		if ( $js_minified || $css_minified ) {
			self::set_resource_last_modification();
		}

	}
}