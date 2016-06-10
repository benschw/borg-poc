<?php

namespace Demo\Resource;

use Fliglio\Web\GetParam;

use Fliglio\Borg\BorgImplant;
use Fliglio\Borg\Chan;


class DemoResource {
	use BorgImplant;

	// Gregory-Leibniz series
	public function pi(GetParam $terms) {
		$ch = $this->coll()->mkChan();

		$this->coll()->piTerm($ch, 1, $terms->get());

		$pi = 3;

		for ($i = 0; $i < $terms->get(); $i++) {
			$pi += $ch->get();
		}
		return $pi;
	}
	public function piTerm(Chan $ch, $termIdx, $terms) {
		if ($termIdx != $terms) {
			$this->coll()->piTerm($ch, $termIdx+1, $terms);
		}
		$base = $termIdx * 4 - 2;
		$term = 4 / ($base * ($base + 1) * ($base + 2));
		$term += -4 / (($base + 2) * ($base + 3) * ($base + 4));

		$ch->add($term);
	}
}
