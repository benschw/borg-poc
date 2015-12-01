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
use Fliglio\Borg\Chan\ChanReader;

class Test {
	use BorgImplant;

	const NUM = 5;

	public function dcread() {
		return file_get_contents("/tmp/test");
	}

	public function dctest(GetParam $msg) {
		$this->az("dc2")->write(new Primitive($msg->get()));
	}

	public function write(Primitive $msg) {
		file_put_contents("/tmp/test", $msg->value());
	}

	public function prime(GetParam $limit) {

		$exits = $this->coll()->mkchan(Primitive::getClass());
		$primes = $this->coll()->mkchan(Primitive::getClass());

		$exit = 0;
		for ($i = 3; $i < $limit->get(); $i++) {
			$exit++;
			$this->coll()->isPrime($primes, $exits, new Primitive($i));
		}

		$results = [];

		$reader = new ChanReader([$primes, $exits]);
		while ($exit > 0) {
			list($id, $d) = $reader->get();
			switch ($id) {
			case $primes->getId():
				$results[] = $d->value();
				break;
			case $exits->getId():
				$exit--;
				break;
			}
		}

		$primes->close();
		$exits->close();
		sort($results);
		return $results;
	}

	public function isPrime(Chan $primes, Chan $exits, Primitive $num) {
		for ($i = 2; $i < $num->value(); $i++) {
			if ($num->value() % $i == 0) {
				$exits->add(new Primitive(true));
				$exits->close();
				$primes->close();
				return;
			}
		}
		$primes->add(new Primitive($num->value()));
		$exits->add(new Primitive(true));
		
		$primes->close();
		$exits->close();
	}


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
