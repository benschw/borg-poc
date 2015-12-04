<?php

namespace Demo\Db;

use Fliglio\Borg\BorgImplant;
use Demo\Api\Race;

class RaceDbm {
	use BorgImplant;

	private $db;

	public function __construct(\PDO $db) {
		$this->db = $db;
	}


	public function find($race) {
		$stmt = $this->db->prepare("SELECT `race`, `status` FROM Race WHERE race = :race");
		$stmt->execute([":race" => $race]);

		$vo = $stmt->fetch(\PDO::FETCH_ASSOC);
		return $vo;
		return Race::unmarshal($vo);
	}

	public function save(Race $race) {
		$this->cube()->recordSave($race);
	}

	public function recordSave(Race $race) {
		$found = $this->find($race->getRace());

		if ($found) {
			$stmt = $this->db->prepare("UPDATE Race SET `status` = :status WHERE race = :race");
		} else {
			$stmt = $this->db->prepare("INSERT INTO Race (`race`, `status`) VALUES (:race, :status)");
		}
		$stmt->execute([
			":race"   => $race->getRace(),
			":status" => $race->getStatus(),
		]);
	}
}


