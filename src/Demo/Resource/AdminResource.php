<?php

namespace Demo\Resource;


use Fliglio\Http\ResponseWriter;
use Fliglio\Http\Http;


// Manage Assimilation of a race
class AdminResource {


	public function __construct() {
	}

	public function health(ResponseWriter $resp) {
		return ["status" => "ok"];
	}
}



