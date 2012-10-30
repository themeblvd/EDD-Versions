<?php
/*
Plugin Name: EDD Versions
Description: This plugin adds version numbers to your downloadable software products in Easy Digital Downloads.
Version: 1.0.1
Author: Jason Bobich
Author URI: http://jasonbobich.com
License: GPL2

    Copyright 2012  Jason Bobich

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/

define( 'TB_EDDV_PLUGIN_VERSION', '1.0.1' );
define( 'TB_EDDV_PLUGIN_FILE', __FILE__ );

/**
 * Run plugin.
 *
 * @since 1.0.0
 */

function themeblvd_eddv_init(){
	
	// If EDD Software Licensing plugin isn't installed, 
	// then add a Version option to EDD's metabox.
	if( ! class_exists( 'EDD_Software_Licensing' ) ){
		add_action( 'edd_meta_box_fields', 'themeblvd_eddv_option', 21 );
		add_filter( 'edd_metabox_fields_save', 'themeblvd_eddv_save' );
	}
	
	// Show Version number with [download_history] table
	add_action( 'edd_download_history_header_end', 'themeblvd_eddv_downloads_th' );
	add_action( 'edd_download_history_row_end', 'themeblvd_eddv_downloads_td', 10, 2 ); // @requires EDD v1.3+
	
}
add_action( 'plugins_loaded', 'themeblvd_eddv_init' );
 
/**
 * Localization.
 *
 * @since 1.0.0
 */

function themeblvd_eddv_textdomain(){
	load_plugin_textdomain( 'tb_edd_versions', false, dirname( plugin_basename( TB_EDDV_PLUGIN_FILE ) ) . '/languages/' );
}
add_action( 'init', 'themeblvd_eddv_textdomain' );

/**
 * Add Version option to standard EDD downloadable 
 * product meta box.
 *
 * @since 1.0.0
 */

function themeblvd_eddv_option( $post_id ){
	$value = get_post_meta( $post_id, '_edd_sl_version', true );
	?>
	<p>
		<strong><?php _e( 'Version', 'tb_edd_versions' ); ?>:</strong>
	</p>
	<p>
		<label for="edd_sl_version">
			<input type="text" name="_edd_sl_version" id="edd_sl_version" value="<?php echo $value; ?>" />
			<p class="howto"><?php _e( 'Enter the current version number of the item.', 'tb_edd_versions' ); ?></p>
		</label>
	</p>
	<?php
}

/**
 * Filter in field "_edd_sl_version" to be saved with 
 * EDD meta box. This field also matches the version 
 * saved with EDD Software Licnensing plugin.
 *
 * @since 1.0.0
 */

function themeblvd_eddv_save( $fields ){
	$fields[] = '_edd_sl_version';
	return $fields;
}

/**
 * Show Version row header cell on downloads table of 
 * [download_history] shortcode. 
 *
 * @since 1.0.0
 */

function themeblvd_eddv_downloads_th(){
	echo '<th class="edd_download_download_version">'.__( 'Version', 'tb_edd_versions' ).'</th>';
}

/**
 * Show Version number cell on downloads table of 
 * [download_history] shortcode. 
 *
 * @since 1.0.0
 */
 
function themeblvd_eddv_downloads_td( $puchase_id, $download_id ){
	echo '<td class="edd_download_download_version">'.get_post_meta( $download_id, '_edd_sl_version', true ).'</td>';
}