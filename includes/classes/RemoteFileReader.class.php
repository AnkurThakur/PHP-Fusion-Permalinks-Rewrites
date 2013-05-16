<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: RemoteFileReader.class.php
| Author: Hans Kristian Flaatten (Starefossen)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

class RemoteFileReader {

	private $_content = "";
	private $_errorNumber = 0;
	private $_errorMessage = "OK";
	private $_requestMethod = 0;
	private $_requestURL = "";
	
	/**
	* Constructor method for the RemoteFileReader Class
	* @param remoteURL - the URL to the remote file.
	*/
	public function __construct($remoteURL) { 
		$this->_requestURL = $remoteURL;
		$this->_setRequestMethod();
		if ($this->_requestMethod != 0) {
			$this->_getRemoteFileContent();
		}
	}
	
	/**
	* Get the content of the file
	* return - the content of the file
	*/
	public function getContent() {
		return $this->_content;
	}
	
	/** Get any HTTP error while readign the remote file
	* return - array containing the key 'number' containing the HTTP error code,
				and a key 'message' containing the human readable error message.
	*/
	public function getError() {
		return array("number" => $this->_errorNumber, "message" => $this->_errorMessage);
	}
	
	// Set the supported request method 
	private function _setRequestMethod() {
		if (function_exists("curl_init")) {
			$this->_requestMethod = 1;
		} elseif (ini_get("allow_url_fopen") == 1) {
			$this->_requestMethod = 2;
		} else {
			$this->_errorNumber = 700;
			$this->_errorMessage = "Remote file request not supported on the server!";
		}
	}
	
	// Get the content of the remote file
	private function _getRemoteFileContent() {
		if ($this->_requestMethod == 1) {
			$this->_getCurlContent();
		} elseif ($this->_requestMethod == 2) {
			$this->_getFopenContent();
		}
	}
	
	// Use curl to get content
	private function _getCurlContent() {
		// Initialize curl
		$culr = @curl_init();
	
		curl_setopt($culr, CURLOPT_URL, $this->_requestURL);
		curl_setopt($culr, CURLOPT_AUTOREFERER, false);
		curl_setopt($culr, CURLOPT_HEADER, false);
		curl_setopt($culr, CURLOPT_RETURNTRANSFER, true);
	
		// Store content
		$this->_content = curl_exec($culr);

		// Store errors
		$this->_errorNumber = curl_errno($culr);
		$this->_errorMessage = curl_error($culr);
		
		$httpStatus = @curl_getinfo($culr);
		if ($this->_errorNumber == 0 && $httpStatus['http_code'] != 200) {
			$this->_errorNumber = $httpStatus['http_code'];
			$this->_errorMessage = $this->_getHttpStatus($this->_errorNumber);
		}
		
		// Close curl
		curl_close($culr);
	}
	
	// Use fopen to get content
	private function _getFopenContent() {
		$fopen = @fopen($this->_requestURL, "r");
		
		if (isset($http_response_header)) {
			$httpStatus = explode(" ", $http_response_header[0], 3);
			$this->_errorNumber = $httpStatus[1];
			$this->_errorMessage = $httpStatus[2];
		} else {
			$url = parse_url($this->_requestURL);
			$this->_errorNumber = 6;
			$this->_errorMessage = "Couldn't resolve host '".$url['host']."'";
		}
		
		if ($this->_errorNumber == 200) {
			$this->_errorNumber = 0;
			// Read the content of the file 
			while ($line = fread($fopen, 1024)) {
				$this->_content .= $line;
			}
		}
	}
	
	// Get HTTP Status Message
	private function _getHttpStatus($code) {
		$httpStatus = array(
			100 => "Continue",
			101 => "Switching Protocols",
			200 => "OK",
			201 => "Created",
			202 => "Accepted",
			203 => "Non-Authoritative Information",
			204 => "No Content",
			205 => "Reset Content",
			206 => "Partial Content",
			300 => "Multiple Choices",
			301 => "Moved Permanently",
			302 => "Found",
			303 => "See Other",
			304 => "Not Modified",
			305 => "Use Proxy",
			306 => "(Unused)",
			307 => "Temporary Redirect",
			400 => "Bad Request",
			401 => "Unauthorized",
			402 => "Payment Required",
			403 => "Forbidden",
			404 => "Not Found",
			405 => "Method Not Allowed",
			406 => "Not Acceptable",
			407 => "Proxy Authentication Required",
			408 => "Request Timeout",
			409 => "Conflict",
			410 => "Gone",
			411 => "Length Required",
			412 => "Precondition Failed",
			413 => "Request Entity Too Large",
			414 => "Request-URI Too Long",
			415 => "Unsupported Media Type",
			416 => "Requested Range Not Satisfiable",
			417 => "Expectation Failed",
			500 => "Internal Server Error",
			501 => "Not Implemented",
			502 => "Bad Gateway",
			503 => "Service Unavailable",
			504 => "Gateway Timeout",
			505 => "HTTP Version Not Supported"
		);
		
		if (isset($httpStatus[$code])) {
			return $httpStatus[$code];
		} else {
			return "N/A";
		}
	}
}
?>