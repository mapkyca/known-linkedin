<?php

namespace IdnoPlugins\LinkedIn {
    
    class Client {
	
	private $key;
	private $secret;
	
	public $access_token;
	
	function __construct($apikey, $secret) {
	    $this->key = $apikey;
	    $this->secret = $secret;
	}
	
	public function getAuthenticationUrl($baseURL, $redirectURL, $parameters = []) {
	    
	    $parameters['redirect_uri'] = $redirectURL;
	    $parameters['client_id'] = $this->key;
	    
	    $url = [];
	    foreach ($parameters as $key => $value)
		$url[] =  urlencode($key) . '=' . urlencode($value);
	    
	    return $baseURL . '?' . implode('&', $url);
	}
	
	public function getAccessToken($endpointUrl, $grant_type = 'authorization_code', array $parameters) {
	    
	    if ($parameters['state'] != \Idno\Core\site()->plugins()->get('LinkedIn')->getState())
		throw new \Exception('State value not correct, possible CSRF attempt.');
		
	    unset($parameters['state']);
	    
	    $parameters['client_id'] = $this->key;
	    $parameters['client_secret'] = $this->secret;
	    $parameters['grant_type'] = $grant_type;
	    
	    $result = \Idno\Core\Webservice::post(\IdnoPlugins\LinkedIn\Main::$TOKEN_ENDPOINT, http_build_query($parameters, null, '&'));
	    
	    return json_decode($result['content']);
	    
	}
	
	public function setAccessToken($token) {
	    $this->access_token = $token;
	}
	
	
    }
}