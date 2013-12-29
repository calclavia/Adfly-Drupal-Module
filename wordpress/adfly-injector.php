<?php
/**
 * Plugin Name: Adfly Injector
 * Plugin URI: http://calclavia.com
 * Description: A plugin that injects Adfly scripts for specific links. Use [adfly id="adflyID"]
 * Version: 1.0.0
 * Author: Calclavia
 * Author URI: http://calclavia.com
 * License: LGPL3
 */
function getAdflyScript($id)
{

	return "
	<script type='text/javascript'> 
	var adfly_id = $id;
	var adfly_advert = 'int';
	var domains = ['.zip', '.jar'];
	</script>
	<script src='http://cdn.adf.ly/js/link-converter.js'></script> ";
}

function getAdfocusScript($id)
{

	return "
	<script type='text/javascript'> 
	var id_user = $id;
	var domains_include = ['.zip', '.jar'];
	</script>
	<script src='http://adfoc.us/js/fullpage/script.js'></script>
	";
}

/**
 * WordPress Shortcode
 */
function adfly_filter($atts)
{
    return getAdflyScript($atts['id']);
}

add_shortcode( 'adfly', 'adfly_filter' );

function adfocus_filter($atts)
{
    return getAdfocusScript($atts['id']);
}

add_shortcode( 'adfocus', 'adfocus_filter' );

?>