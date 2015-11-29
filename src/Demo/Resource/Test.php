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

	const NUM = 5;

	public function test(GetParam $msg) {
		$replies = $this->coll()->mkchan(Primitive::getClass());

		for ($i = 0; $i < self::NUM; $i++) {
			$str = sprintf("%s %s", $msg->get(), $i);
			$this->coll()->doTest(new Primitive($str), $replies);
		}

		$resp = [];
		for ($i = 0; $i < self::NUM; $i++) {
			$resp[] = $replies->get()->value();
		}

		$replies->close();

		return $resp;
	}

	public function doTest(Primitive $msg, Chan $replies) {
		$reply = new Primitive($msg->value() . " reply");
		$replies->add($reply);
	}


}
