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
		register_rest_route('rest-demo/v1', '/get-posts', array(
			'methods' => 'GET',
			'callback' => array($this,'get_posts'),
		));
		register_rest_route('rest-demo/v1', '/get-posts/(?P<id>\d+)', array(
			'methods' => 'GET',
			'callback' => array($this,'get_post'),
		));
		register_rest_route('rest-demo/v1', '/qs', array(
			'methods' => 'GET',
			'callback' => array($this,'get_query_string'),
		));
		register_rest_route('rest-demo/v1', '/invoice/(?P<id>\d+)/item/(?P<item_id>\d+)', array(
			'methods' => 'GET',
			'callback' => array($this,'invoice_item'),
		));
		register_rest_route('rest-demo/v1', '/greet/(?P<name>[a-zA-Z0-9]+)', array(
			'methods' => 'GET',
			'callback' => array($this,'greet'),
		));
		register_rest_route('rest-demo/v1', '/person', array(
			'methods' => 'POST',
			'callback' => array($this,'process_person'),
		));
		register_rest_route('rest-demo/v1', '/contact', array(
			'methods' => array('POST', 'GET'),
			'callback' => array($this,'handle_contact'),
		));

	}
	function hello(){
		$response = array(
			"Message" => "Hello World",
		);
		return new WP_REST_Response($response, 200);
	}

	function get_posts(){
		$posts = get_posts(array(
			"numberposts" => -1,
			"post_type" => "post",
			"post_status" => "publish",
		));
		return $posts;
	}

	function get_post($data){
		if(!isset($data['id']) || !is_numeric($data['id'])){
			return new WP_Error('invalid_id', 'Invalid ID', array('status' => 400));
		}
		if(get_post_type( $data['id'] ) !== 'post'){
			return new WP_Error('not_post', 'Not a post', array('status' => 400));
		}
		$post = get_post($data['id']);
		if(!$post){
			return new WP_Error('post_not_found', 'Post not found', array('status' => 404));
		}
		return new WP_REST_Response($post, 200);
	}
	function get_query_string($request){
		$query_string_parameters = $request->get_query_params();
		$page_nubmers = $request->get_param('page');
		if(!empty($page_nubmers)){
			$page_number = 1;
		}
		return new WP_REST_Response($query_string_parameters, 200);
	}

	function invoice_item($data){
		$invoice_id = $data['id'];
		$item_id = $data['item_id'];
		$response = array(
			"invoice_id" => $invoice_id,
			"item_id" => $item_id,
		);
		return new WP_REST_Response($response, 200);
	}
	function greet($request){
		$name = $request['name'];
		$response = array(
			"message" => "Hello $name",
		);
		return new WP_REST_Response($response, 200);
	}
	function process_person($request){
		$name = $request['name'];
		$email = $request['email'];
		$response = array(
			"name" => $name,
			"email" => $email,
		);
		return new WP_REST_Response($response, 200);
	}
}
new Rest_Demo();
