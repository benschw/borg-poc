<?php

namespace Demo\Research;

class ScannerFactory {

	private $http;

	public function __construct($http) {
		$this->http = $http;
	}

	public function create($name) {
		return new Scanner($this->http, $name);
	}
}
