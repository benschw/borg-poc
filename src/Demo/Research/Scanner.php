<?php

namespace Demo\Research;

class Scanner{

	private $http;
	private $name;

	public function __construct($http, $name) {
		$this->http = $http;
		$this->name;
	}


	public function getThreatLevel() {
		return rand(1, 5);
	}

}

