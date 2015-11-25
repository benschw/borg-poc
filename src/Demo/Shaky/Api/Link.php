<?php

namespace Demo\Shaky\Api;

use Fliglio\Web\MappableApi;
use Fliglio\Web\MappableApiTrait;

class Link implements MappableApi {
	use MappableApiTrait;

	private $href;


	public function __construct($href) {
		$this->href = $href;
	}

	public function getHref() {
		return $this->href;
	}

}
