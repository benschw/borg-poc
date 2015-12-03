<?php

namespace Demo\Resource;

use Fliglio\Routing\Routable;
use Fliglio\Web\Body;
use Fliglio\Web\PathParam;
use Fliglio\Web\GetParam;
use Fliglio\Web\Entity;

use Fliglio\Http\ResponseWriter;
use Fliglio\Http\Http;

use Fliglio\Borg\BorgImplant;
use Fliglio\Borg\Type\Primitive;
use Fliglio\Borg\Chan\Chan;
use Fliglio\Borg\Chan\ChanReader;

use Demo\Research\Scanner;
use Demo\Db\ThreatReportDbm;

class LifeFormScanner {
	use BorgImplant;

	private $scanner;

	public function __construct(Scanner $scanner) {
		$this->scanner = $scanner;
	}

	public function scan(Entity $entity) {
		$names = $entity->bind(Primitive::getClass())->value();
		
		$assessment = [];
		foreach ($names as $name) {
			$ch = $this->mkChan(Primitive::getClass());
			$this->coll()->assessThreat($name, $ch);
			$assessment[$name] = $ch;
		}
		$results = [];
		foreach ($assessment as $name => $ch) {
			$results[$name] = $ch->get();
		}

		return $results;
	}

	public function assessThreat($name, Chan $ch) {
		$threatLevel = $this->scanner->getThreatLevel($name);
		$ch->add($threatLevel);
	}
}

