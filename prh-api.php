<?php

/**
 *
 * Penguin Random House API Class
 *
 * Based on ijanerik's iTunes API Class
 * https://github.com/ijanerik/PHP-iTunes-API
 *
 * @license MIT License
 * @see http://developer.penguinrandomhouse.com
 */

class PRHAPI
{
    const API_KEY 		= 'API_KEY';

    const GET_AUTHORS 	= 'https://api.penguinrandomhouse.com/resources/authors?';
    const GET_AUTHOR 	= 'https://api.penguinrandomhouse.com/resources/authors/';
    const GET_TITLE 	= 'https://api.penguinrandomhouse.com/resources/titles/';
    const GET_WORKS 	= 'https://api.penguinrandomhouse.com/resources/works';
    
    /**
     * The query config
     * 
     * We can add stuff to this throughout the class 
     *
     * (default value: array())
     * 
     * @var array
     * @access protected
     * @static
     */
    protected static $_query_config = array(
    	'expandLevel' => '1',
    	'key' =>  self::API_KEY
    	);

    /**
     * Set a new config
     *
     * <code>
     * PRHAPI::config('index', 'value');
     * PRHAPI::config(array('index' => 'value'));
     * </code>
     * 
     * @access public
     * @static
     * @param array|string $index (default: array())
     * @param string $value (default: null)
     * @param string $type (default: 'search')
     * @return void
     */
    public static function config($index = array(), $value = null)
    {	
    	// If it's just a ('param','value') input, make it an array
        if(!is_array($index))
        {
            $index = array($index => $value);
        }
        
        // Add $index array to main $_query_config
        self::$_query_config = array_merge(self::$_query_config, $index);
        
    }

    /**
     * Check local cache for content
     * 
     * @access protected
     * @static
     * @param string $url (default: 'search')
     * @return array
     */
    protected static function _get_json($url) {

        if (!file_exists('cache')) {
            mkdir('cache', 0777, true);
        }

        // cache files are created like cache/abcdef123456...
        $cacheFile = 'cache' . DIRECTORY_SEPARATOR . md5($url).'.json';

        if (file_exists($cacheFile)) {
            $fh = file_get_contents($cacheFile);
            return $fh;
        } else {
            $json = self::_curl_data($url);

            $fh = fopen($cacheFile, 'w');
            fwrite($fh, $json);
            fclose($fh);
            return $json;
        }
    }

	 /**
	 * Get the content from the PRH API servers
	 * 
	 * @access protected
	 * @static
	 * @param string $type (default: 'search')
	 * @param array $config
	 * @return array
	 */
    protected static function _get_content($term, $config, $type)
    {	
    	if(is_array($term))
        {
            $term = http_build_query($term);
        }

    	// Because each URI is structured a little differently... 
    	switch ($type) {
    		case 'GET_AUTHORS':
    			$url 	= self::GET_AUTHORS;
    			$url 	.= $term.'&';
    			break;
    		case 'GET_WORKS':
    			$url 	= self::GET_WORKS;
    			$url 	.= '?'.$term.'&';
    			break;
    		case 'GET_AUTHOR':
    			$url 	= self::GET_AUTHOR;
    			$url 	.= $term.'?';
    			break;
    		case 'GET_TITLE':
    			$url = self::GET_TITLE;
    			$url .= $term.'?';
    			break;
    		default:
    			$url = self::GET_TITLE;
    			break;
    	}
    	
    	// Add configuration, key and the rest of the query
        $url 		.= http_build_query($config);
        $content 	= self::_get_json($url);

        $array 		= json_decode($content);

        return $array;
    }

 	/**
     * Make data transfer
     * 
     * @access protected
     * @static
     * @param string $uri
     * @return array
     */
    protected static function _curl_data($uri)
    {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $uri);
		curl_setopt($ch,CURLOPT_HTTPHEADER, array(
	    		"Accept: application/json"
	    	));
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$content = curl_exec($ch);

        // Check if any error occurred
        if(!curl_errno($ch)){
            $info = curl_getinfo($ch);
            $array = json_decode($content, true);
            $array['responseCode'] = $info['http_code'];
            $content = json_encode($array, JSON_PRETTY_PRINT);
        }

		curl_close($array);

		// If there's something there...
		if($content){
		  return $content;
		} else {
		  return false;
		}
    }

    /**
     * Get specific title
     * 
     * @access public
     * @static
     * @param mixed $term
     * @param array $config (default: array())
     * @return array
     */
    public static function get_title($term, array $config = array())
    {
    	// Combine local $config with $_query_config settings
    	$config  = array_merge(self::$_query_config, $config);
        $content = self::_get_content($term, $config, 'GET_TITLE');

        return $content;
    }

    /**
     * Search for authors by firstname, etc.
     * 
     * @access public
     * @static
     * @param mixed $term
     * @param array $config (default: array())
     * @return array
     */
    public static function get_authors($term, array $config = array())
    {
    	// Combine local $config with $_query_config settings
    	$config  = array_merge(self::$_query_config, $config);
        $content = self::_get_content($term, $config, 'GET_AUTHORS');

        return $content;
    }

    /**
     * Search for authors by firstname, etc.
     * 
     * @access public
     * @static
     * @param mixed $term
     * @param array $config (default: array())
     * @return array
     */
    public static function get_author_id($term, array $config = array())
    {
    	// Combine local $config with $_query_config settings
    	$config  = array_merge(self::$_query_config, $config);
        $content = self::_get_content($term, $config, 'GET_AUTHOR');

        return $content;
    }

    /**
     * Search for authors by firstname, etc.
     * 
     * @access public
     * @static
     * @param mixed $term
     * @param array $config (default: array())
     * @return array
     */
    public static function get_works($term, array $config = array())
    {
    	// Must be array for search
    	if(!is_array($term))
        {
            $term = array('search'=>$term);
        }

    	// Combine local $config with $_query_config settings
    	$config  = array_merge(self::$_query_config, $config);
        $content = self::_get_content($term, $config, 'GET_WORKS');

        return $content;
    }

}

?>