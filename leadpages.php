<?php

/*
Plugin Name: Leadpages Connector
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description:
Author: Leadpages
Version: 2.0.2
Author URI: http://leadpages.net
*/

use LeadpagesWP\Lib\Update;

$plugin_version = '2.0.2';

  /*
    |--------------------------------------------------------------------------
    | Application Entry Point
    |--------------------------------------------------------------------------
    |
    | This will be your plugin entry point. This file should
    | not contain any logic for your plugin what so ever.
    |
    */

require_once('c3.php');
require_once('vendor/autoload.php');
require_once('App/Config/App.php');
require_once($leadpagesConfig['basePath'] . 'Framework/ServiceContainer/ServiceContainer.php');
require_once($leadpagesConfig['basePath'].'App/Config/RegisterProviders.php');

/*
  |--------------------------------------------------------------------------
  | Fix Database items from plugin version 2.0 and 2.0.1
  |--------------------------------------------------------------------------
  */
require_once($leadpagesConfig['basePath'].'App/Lib/RevertChanges.php');
/*
  |--------------------------------------------------------------------------
  | Register Auto Update
  |--------------------------------------------------------------------------
  */
require_once($leadpagesConfig['basePath'].'App/Lib/Update.php');
$update = new Update();
$update->register_auto_update();


/*
  |--------------------------------------------------------------------------
  | Admin Bootstrap
  |--------------------------------------------------------------------------
  |
  |
  |
  */



if (is_admin() || is_network_admin()) {
    $adminBootstrap = $leadpagesApp['adminBootstrap'];
    //include('App/Helpers/ErrorHandlerAjax.php');
}

function getScreen()
{
    global $leadpagesConfig;

    $screen = get_current_screen();
    $leadpagesConfig['currentScreen'] = $screen->post_type;
    $leadpagesConfig['currentScreenAll'] = $screen;
}


add_action('current_screen', 'getScreen');


/*
  |--------------------------------------------------------------------------
  | Front Bootstrap
  |--------------------------------------------------------------------------
  |
  |
  |
  */

if (!is_admin() && !is_network_admin()) {
    $frontBootstrap = $leadpagesApp['frontBootstrap'];
    //include('App/Helpers/ErrorHandlerAjax.php');
}



