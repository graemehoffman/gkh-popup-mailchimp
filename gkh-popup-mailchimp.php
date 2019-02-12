<?php

/**
 * GKH Popup Mailchimp
 *
 * @package   gkh-popup-mailchimp
 * @author    Graeme Hoffman <graeme@graemehoffman.com>
 * @license   GPL-2.0+
 * @copyright 2019 Graeme Hoffman
 *
 * Plugin Name:       GKH Popup Mailchimp
 * Version: 		  1.0
 * Description: 	  Simple Mailchimp Popup
 * Author: 			  graemehoffman
 * Author URI:        http://graemehoffman.com
 * Text Domain:       gkh-popup-mailchimp
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

require_once( plugin_dir_path( __FILE__ ) . 'includes/GKHPopupMailChimp.php' );

use GKH\GKHPopupMailChimp;

$popup = new GKHPopupMailChimp();
$popup->hooks();


