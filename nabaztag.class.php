<?php

/**
 * A PHP class to control the essential functions of a Nabaztag rabbit
 * through the Nabaztag API. Handles:
 * - Text to speach
 * - Ear positioning
 * - Wake / sleep
 *
 * Requires PHP5, CURL and a Nabaztag rabbit: http://www.nabaztag.com/
 *
 * Written by Dan Ruscoe: http://danruscoe.com/
 *
 * Nabaztag API documentation: http://doc.nabaztag.com/api/home.html
 */

class nabaztag {

	// This is the API URL parameters are sent to.
	const API_URL_BASE	= 'http://api.nabaztag.com/vl/FR/api.jsp?';

	// Action codes can be found in the API documentation.
	const API_ACTION_SLEEP	= 13;
	const API_ACTION_WAKE	= 14;

	// Additional voices can be found in the API documentation.
	const DEFAULT_VOICE	= 'US-Bethany';

	protected $serial;
	protected $token;
	protected $left_ear_pos;
	protected $right_ear_pos;
	protected $voice;
	protected $api_params;
	protected $last_api_response;

	public function __construct($serial,$token,$voice=null) {

		/**
		 * Serial number and API token are required to use the API.
		 * If no voice identifier is passed, a default will be used.
		 */

		$this->serial	= $serial;
		$this->token	= $token;
		$this->voice	= ($voice)? $voice : self::DEFAULT_VOICE;

		/**
		 * These parameters must always be passed to the API.
		 * Additional paramaters will be added to this array
		 * depending on the functions called.
		 */

		$this->api_params = array(
			'sn'	=> $this->serial,
			'token'	=> $this->token
		);

	}

	/**
	 * Speaks text input through the rabbit's TTS feature.
	 */

	public function speak($phrase) {

		$this->api_params['tts'] = $phrase;

		return $this->call_api();

	}

	/**
	 * Rotates the bunny's ears.
	 * Pass only one value to move a single ear.
	 * Min value: 0, max value: 16
	 */

	public function move_ears($left=null,$right=null) {

		$left	= (int) $left;
		$right	= (int) $right;

		if ($left)
			$this->api_params['posleft'] = $left;

		if ($right)
			$this->api_params['posright'] = $right;

		return $this->call_api();

	}

	/**
	 * Uses an array of parameters to build a complete Nabaztag API URL.
	 */

	private function build_api_url() {

		$url = self::API_URL_BASE;

		foreach ($this->api_params as $key => $value)
			$url .= "&{$key}=".urlencode($value);

		return $url;

	}

	/**
	 * Uses CURL to make the call to the Nabaztag API.
	 * The API response is stored in the variable $last_api_response.
	 */

	private function call_api() {

		$api_url = $this->build_api_url();

		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$api_url);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);

		$result = curl_exec($curl);
		curl_close($curl);

		if (!$result)
			return false;

		$this->last_api_response = $result;

		return $this->last_api_response;

	}

	/**
	 * This function will replay the last API call made by the class.
	 */

	public function replay() {

		return $this->call_api();

	}

	/**
	 * Pass $status = 1 to wake the bunny up, 0 or null to put it to sleep.
	 */

	public function set_wake_status($status) {

		$this->api_params['action'] = ($status == 1)? self::API_ACTION_WAKE : self::API_ACTION_SLEEP;

		return $this->call_api();

	}

	/**
	 * Displays the parameters sent to the Nabaztag API.
	 */

	public function display_api_params() {

		print_r($this->api_params);

	}

	/**
	 * Displays the last response from the Nabaztag API.
	 */

	public function display_api_response() {

		print_r($this->last_api_response);

	}

	public function __get($key) {

		return $this->$key;

	}

}

?>
