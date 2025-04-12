<?php 
/*
Plugin Name: REST Demo
Description: REST Demo
Version: 1.0
Author: Devrupash
Author URI: https://devrupash.com
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
text-domain: rest-demo
Domain Path: /languages
*/
class Rest_Demo{
	function __construct(){
		add_action('rest_api_init', array($this,'register_routes'));
	}
	function register_routes(){
		register_rest_route('rest-demo/v1', '/hello', array(
			'methods' => 'GET',
			'callback' => array($this,'hello'),
		));
	}
	function hello(){
		$response = array(
			"Message" => "Hello World",
		);
		return new WP_REST_Response($response, 200);
	}
}
new Rest_Demo();
