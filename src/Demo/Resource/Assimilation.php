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

use Demo\Research\Research;
use Demo\Db\RaceDbm;

// Manage Assimilation of a race
class Assimilation {
	use BorgImplant;

	private $dbm;

	public function __construct(RaceDbm $dbm) {
		$this->dbm = $dbm;
	}

	// Get whether or not a race is currently assimilated
	public function getRaceStatus(PathParam $race) {
		return $this->dbm->find($race->get());
	}
	
	// Assimilate a race
	public function assimilateRace(PathParam $race) {
		$this->cube()->recordAssimilationStatus($race->get());
	}
	public function recordAssimilationStatus($race) {
		$this->dbm->set($race);
	}
	
	// Cancel assimilation of a race
	public function cancelAssimilation(PathParam $race) {
		$this->cube()->recordAssimilationCancellation($race->get());
	}
	public function recordAssimilationCancellation($race) {
		$this->dbm->delete($race);
	}
}


