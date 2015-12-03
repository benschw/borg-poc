<?php

namespace Demo\Resource;

use Fliglio\Routing\Routable;
use Fliglio\Web\Body;
use Fliglio\Web\PathParam;
use Fliglio\Web\GetParam;
use Fliglio\Web\Entity;

use Fliglio\Fltk\View;

use Fliglio\Http\ResponseWriter;
use Fliglio\Http\Http;

use Fliglio\Borg\BorgImplant;
use Fliglio\Borg\Type\Primitive;
use Fliglio\Borg\Chan\Chan;
use Fliglio\Borg\Chan\ChanReader;

use Demo\Research\Scanner;
use Demo\Db\ThreatReportDbm;

class GroupScanner {
	use BorgImplant;

	private $scanner;

	public function __construct(Scanner $scanner) {
		$this->scanner = $scanner;
	}

	public function scan(Entity $entity) {
		$names = $entity->bind(Primitive::getClass())->value();
		
		$ch = $this->mkChan(Primitive::getClass());
		
		foreach ($names as $name) {
			$this->coll()->assessThreat($name, $ch);
		}
		$results = [];
		for ($i = 0; $i < count($names); $i++) {
			$results[] = $ch->get();
		}
		return $this->prepareResults($results);
	}

	public function assessThreat($name, Chan $ch) {
		$threatLevel = $this->scanner->getThreatLevel($name);
		$ch->add($threatLevel);
	}

	private function prepareResults(array $results) {
		sort($results);

		$sum = array_sum($results);
		$len = count($results);
		return [
			'mean-threat-level'    => ceil($sum / $len),
			'median-threat-level'  => $results[floor($len/2)],
			'results' => $results,
		];

		return $results;
	}
}



