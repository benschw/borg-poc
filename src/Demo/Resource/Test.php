<?php

namespace Demo\Resource;

use Fliglio\Routing\Routable;
use Fliglio\Web\Body;
use Fliglio\Web\PathParam;
use Fliglio\Web\GetParam;

use Fliglio\Fltk\View;

use Fliglio\Http\ResponseWriter;
use Fliglio\Http\Http;

use Fliglio\Borg\BorgImplant;
use Fliglio\Borg\Type\Primitive;
use Fliglio\Borg\Chan\Chan;

class Test {
	use BorgImplant;

	public function test(GetParam $msg) {
		$words = $this->coll()->mkchan(Primitive::getClass());

		for ($i = 0; $i < 5; $i++) {
			$this->coll()->doTest(new Primitive($msg->get()." ".$i), $words);
		}

		$replies = [];
		for ($i = 0; $i < 5; $i++) {
			$replies[] = $words->get()->value();
		}
		$words->close();

		return $replies;
	}

	public function doTest(Primitive $msg, Chan $words) {
		$words->add(new Primitive($msg->value() . " reply"));
	}


}
