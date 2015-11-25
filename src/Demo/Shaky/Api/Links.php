<?php

namespace Demo\Shaky\Api;

use Fliglio\Web\MappableApi;
use Fliglio\Web\MappableApiTrait;

class Links implements MappableApi {
	use MappableApiTrait;

	private $links = [];


	public function __construct($links) {
		$this->links = $links;
	}

	public function getLinks() {
		return $this->links;
	}

}

