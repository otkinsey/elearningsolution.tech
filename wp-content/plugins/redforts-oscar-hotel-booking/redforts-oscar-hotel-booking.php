<?php

/*
    Plugin Name: Redforts Oscar Hotel Booking
    Plugin URI: https://redforts.com
    Description: The Oscar Hotel Booking plug-in is fully integrated with the Oscar PMS of Redforts Software and enables online commission-free booking from your hotel, hostels or B&B website.
    Author: Redforts Software S.L.
    Version: 1.3
    Author URI: https://redforts.com
    License: GPL2
    License URI: https://www.gnu.org/licenses/gpl-2.0.html
    Domain Path: /languages
    Text Domain: rohb
*/

require_once('includes/ROHB-main.php');
require_once('includes/ROHB-admin.php');
require_once('includes/ROHB-widget.php');

define('ROHB_BASE', __FILE__);
define('ROHB_PATH', plugin_dir_path(ROHB_BASE));
define('ROHB_URL', plugins_url('/', ROHB_BASE));


ROHB_Main::get_instance();
