<?php
/**
 * Plugin Name: Adfly Injector
 * Plugin URI: http://calclavia.com
 * Description: A plugin that injects Adfly scripts for specific links. Use [adfly id="adflyID" apikey="apikey"]
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

/**
 * Adfly Url Shortener PHP API. Will be used when API key is provided
 * 
 * @param string $url http://www.google.com
 * @param string $key 7abccd03cc3005835cc61dd956b583ca
 * @param int $uid 1234
 * 
 * @param string $advert_type (optional) int || banner
 * @param string $domain (optional) adf.ly || q.gs
 */
function adfly($url, $key, $uid, $domain = 'adf.ly', $advert_type = 'int')
{
    // Base api url
    $api = 'http://api.adf.ly/api.php?';
    
    // Api queries
    $query = array(
        'key' => $key,
        'uid' => $uid,
        'advert_type' => $advert_type,
        'domain' => $domain,
        'url' => $url
    );

    // Full api url with query string
    $api = $api . http_build_query($query, '', '&', PHP_QUERY_RFC3986);

    // get data
    if ($data = file_get_contents($api))
        return $data;
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
function adfly_filter($atts, $content = null)
{
    if(!empty($atts['id']))
    {
        if(!empty($atts['apikey']) && !is_null($content))
        {
            $input = do_shortcode($content);
            $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
            
            if(preg_match_all("/$regexp/siU", $input, $matches, PREG_SET_ORDER))
            {
                foreach($matches as $match)
                {
                    $url = str_replace("'", "", $match[2]);;
                    $input = str_replace($url,  adfly($url, $atts['apikey'], $atts['id']),  $input);
                }
            }
            
            return $input;
        }
        
        return getAdflyScript($atts['id']);
    }
    
    return "<p>Please specify an account ID number.";
}

add_shortcode('adfly', 'adfly_filter');

function adfocus_filter($atts)
{
    if(!empty($atts['id']))
    {
        return getAdfocusScript($atts['id']);
    }
    
    return "<p>Please specify an account ID number.";
}

add_shortcode('adfocus', 'adfocus_filter');

?>