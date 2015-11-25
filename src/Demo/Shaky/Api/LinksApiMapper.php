<?php

namespace Demo\Shaky\Api;

use Fliglio\Web\ApiMapper;


class LinksApiMapper implements ApiMapper {

	public function marshal() {
		$arr = [];
		foreach ($this->getLinks() as $link) {
			$arr[] = $link->getHref();
		}
		return $arr;
	}

	public function unmarshal($arr) {
		$links = [];

		foreach ($arr as $href) {
			$links = new Link($href);
		}
		return new Links($links);
	}
}

