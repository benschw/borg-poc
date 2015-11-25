<?php

namespace Demo\Shaky\Api;

use Fliglio\Web\ApiMapper;


class LinkApiMapper implements ApiMapper {

	public function marshal($link) {
		return $link->getHref();
	}

	public function unmarshal($href) {
		return new Link($href);
	}
}
