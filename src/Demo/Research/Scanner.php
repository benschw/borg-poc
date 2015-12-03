<?php

namespace Demo\Research;

class Scanner{

	private $http;

	public function __construct($http) {
		$this->http = $http;
	}


	public function getThreatLevel($name) {
		return rand(1, 5);
	}

}

