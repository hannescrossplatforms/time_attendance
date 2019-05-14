<?php



class Bi extends Eloquent {
    
    // MASS ASSIGNMENT -------------------------------------------------------
    // define which attributes are mass assignable (for security)
    // we only want these 3 attributes able to be filled
    protected $fillable = array('name', 'taste_level');

    

    function set_constants() {

        if (!defined('COOKIE_FILE')) {
            define('COOKIE_FILE', tempnam(sys_get_temp_dir(), 'bi_signup_cookie'));
        }

        if (!defined('CACHE_FOLDER')) {
            //Notice the trailing slash
            define('CACHE_FOLDER', sys_get_temp_dir() . '/bi_cache/');
        }

        if (!defined('BI_USER_AGENT')) {
            define('BI_USER_AGENT', 'BI API Client');
        }

        //Replace username with the username provided to you
        if (!defined('BI_API_USERNAME')) {
            define('BI_API_USERNAME', 'hipzone');
        }
        //Replace password with the password hash provided to you
        if (!defined('BI_API_PASSWORD')) {
            define('BI_API_PASSWORD', 'bdf02a789b1ebd62c9350a26de50293e');
        }
        //Replace password with the password hash provided to you
        if (!defined('BI_API_PATH')) {
            define('BI_API_PATH', 'https://www.datapathway.co.za/api/');
            //define('BI_API_PATH', 'https://stage.datapathway.co.za/api/');
        }
    }

function call_api($path, $data = false, $method = 'post', array $options = array()) {
	if ($method == 'post') {
		//We need to append the mode to the path, otherwise the API won't pick it up on POSTs;
		$path .= '&mode=json';
	} else {
		$data['mode'] = 'json';
	}
	$options['http_username'] = BI_API_USERNAME;
	$options['http_password'] = BI_API_PASSWORD;
	$options['headers'] = array(
		'Accept: application/json',
	);
	$options = array_merge(array(
		'method'        => $method,
		'callback'      => 'bi_handle_api_return',
	), $options);

	if (array_key_exists('debug', $_REQUEST)) {
		$options['return_header'] = true;
	}
	if (array_key_exists('debug', $_REQUEST)) {
		$data['debug'] = $_REQUEST['debug'];
	}
	$full = false;
	if (array_key_exists('full', $options)) {
		$full = (bool)$options['full'];
		unset($options['full']);
	}

    // GCW added the following line
    $xurl = BI_API_PATH . '?q=' . $path;

    error_log("xurl : $xurl");
    error_log("data : $data");
    // error_log("options : " . implode($options));
	$returned = $this->bi_curl_request($xurl, $data, $options);

    //var_dump(json_decode($returned));
    error_log("bi_api_client : call_api : returned : " . $returned);

	$toret = @json_decode($returned, true);
        
	if (!is_array($toret)) {

        error_log("bi_api_client : call_api : toret is empty");
                
		return false;
	}
	if ($full) {
        error_log("bi_api_client : call_api : toret is NOT empty" );
		return $toret;
	}
	if (empty($options['suppress']) || array_key_exists('debug', $_REQUEST)) {
		if (!empty($toret['error'])) {
			if (function_exists('add_error')) {
				add_error($toret['error']);
			}
		}
		if (!empty($toret['success'])) {
			if (function_exists('add_success')) {
				add_success($toret['success']);
			}
		}
		if (!empty($toret['notice'])) {
			if (function_exists('add_notice')) {
				add_notice($toret['notice']);
			}
		}
	}
    error_log("bi_api_client : call_api : 90" );
        //var_dump($toret);
	return array_key_exists('result', $toret) ? $toret['result'] : null;
}

public function bi_handle_api_return($returned, $url, $data, $ch) {
	if (array_key_exists('debug', $_REQUEST)) {
		$headers  = '';
		$body     = '';
		$returned = explode("\r\n\r\n", $returned);
		foreach($returned as $content) {
			if (substr($content, 0, 5) == 'HTTP/') {
				$headers .= "\n\n" . $content;
			} else {
				$body    .= "\n\n" . $content;
			}
		}
		echo '<br>Request Headers:<pre>';
		echo curl_getinfo($ch, CURLINFO_HEADER_OUT);
		echo '</pre>';
		echo '<br>Headers:'. '<pre>' . $headers . '</pre>';
		$returned = $body;
		echo '<br>JSON:'. '<pre>' . $returned . '</pre>';
	}
	if ($curl_error = curl_errno($ch)) {
		add_error('Error accessing API: ' . $curl_error);
		return false;
	} else {
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if (!in_array($http_code, array(200))) {
			add_error('Error accessing API: ' . $http_code . '(' . $url . ')');
			return false;
		}
	}
	return $returned;
}

public function bi_curl_request($url, $data = false, array $options = array()) {
	if (!empty($options['cache']) && $options['cache'] > 0) {
		$cache = $options['cache'];
		if ($data && count($data)) {
			$cache_file = $url . '?' . http_build_query($data);
		} else {
			$cache_file = $url;
		}
		$cache_file = md5($cache_file);
		$cache_file = CACHE_FOLDER . $cache_file;
		if (file_exists($cache_file) && filemtime($cache_file) >= time() - $cache) {
			$contents = file_get_contents($cache_file);
			return $contents;
		}
	} else {
		$cache = false;
	}
	$toret = false;

	if (!is_array($data)) {
		parse_str($data, $data);
	}
	$data_arr = $data;
	$data = http_build_query($data);

	$ch = curl_init();
	$method = strtoupper(array_key_exists('method', $options) ? $options['method'] : 'GET');
	if ($method == 'POST') {
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	} else {
		curl_setopt($ch, CURLOPT_POST, false);
		$tmpurl = parse_url($url);
		if (empty($tmpurl['query'])) {
			$url = $url . '?' . $data;
		} else {
			$url = $url . '&' . $data;
		}
	}
	if (array_key_exists('debug', $_REQUEST)) {
		echo "<pre>$url</pre>";
	}
	if (array_key_exists('header_function', $options) && is_callable($options['header_function'])) {
		curl_setopt($ch, CURLOPT_HEADERFUNCTION, $options['header_function']);
		curl_setopt($ch, CURLOPT_HEADER, false);
	} else if (!empty($options['return_header'])) {
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	} else {
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLINFO_HEADER_OUT, false);
	}

	$headers = array_key_exists('headers', $options) && is_array($options['headers']) ? $options['headers'] : array();
	//Some servers break without the Expect header
	$headers = array_merge(array('Expect:'), $headers);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	//Use the Signup cookie file
	if (!empty($options['use_cookie']) && defined('COOKIE_FILE')) {
		curl_setopt ($ch, CURLOPT_COOKIEFILE, COOKIE_FILE);
		curl_setopt ($ch, CURLOPT_COOKIEJAR, COOKIE_FILE);
	}

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_FAILONERROR, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 200);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, 'PHP / ' . BI_USER_AGENT . ' 0.3.1');
	if (!empty($options['http_username']) && !empty($options['http_password'])) {
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
		curl_setopt($ch, CURLOPT_USERPWD, $options['http_username'] . ':' . $options['http_password']);
	}

	$toret = curl_exec($ch);
        error_log("toret : $toret");

        // GCW added
        return $toret;

	//Don't know if this is a good idea, but if we couldn't fetch the file, and an older one exists, return it
	if (!$toret && $cache && file_exists($cache_file)) {
		$toret = file_get_contents($cache_file);
	}

	$callback = array_key_exists('callback', $options) ? $options['callback'] : false;
	if ((is_array($callback) && count($callback) == 2) || is_string($callback)) {
		if (is_callable($callback, false, $callable_name)) {
			$toret = $callable_name($toret, $url, $data_arr, $ch);
		} else {
			trigger_error('Uncallable function given as callback to curl_request', E_USER_ERROR);
		}
	}
	curl_close($ch);

	if ($toret && $cache && $cache_file) {
		if (!file_exists(CACHE_FOLDER)) {
			mkdir(CACHE_FOLDER);
		}
		file_put_contents($cache_file, $toret);
	}

	return $toret;

}
}

?>
