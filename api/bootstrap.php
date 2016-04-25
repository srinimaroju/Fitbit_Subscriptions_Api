<?php
date_default_timezone_set( "America/New_York" );
//Set Application Environment var
if( !(defined( 'APPLICATION_ENV' )) ){
        define( 'APPLICATION_ENV', ( isset( $_SERVER[ 'APPLICATION_ENV' ] ))?$_SERVER[ 'APPLICATION_ENV' ]:'local' );
}

//Define path constants
define( 'WWW_ROOT', getcwd() );
define( 'APPLICATION_ROOT', dirname( __FILE__ ) );
define( 'SITE_ROOT', dirname( dirname( __FILE__ ) ) );
define( 'LOG_PATH', SITE_ROOT . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR );
define( 'DATA_DIR', SITE_ROOT . DIRECTORY_SEPARATOR . 'data' );
define( 'API_DIR', SITE_ROOT . DIRECTORY_SEPARATOR . 'api' );

$yaml_contents = file_get_contents(API_DIR.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."config.yml");
//print_r($yaml_contents); exit;
$config = yaml_parse($yaml_contents,-1);
 