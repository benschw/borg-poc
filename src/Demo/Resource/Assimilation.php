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

use Fliglio\Borg\Type\Primitive;

use Demo\Db\RaceDbm;
use Demo\Api\Race;

// Manage Assimilation of a race
class Assimilation {

	private $dbm;

	public function __construct(RaceDbm $dbm) {
		$this->dbm = $dbm;
	}

	// Get whether or not a race is currently assimilated
	public function getRaceStatus(PathParam $race) {
		return $this->dbm->find($race->get());
	}
	
	// Update Race Assimilation Status
	public function assimilateRace(PathParam $race, Entity $e) {
		$r = $e->bind(Race::getClass());
		$r->setRace($race->get());
		$this->dbm->save($r);
	}
}


