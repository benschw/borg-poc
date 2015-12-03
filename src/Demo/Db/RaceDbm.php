<?php

namespace Demo\Db;

class RaceDbm {
	
	private $db;

	public function __construct(\PDO $db) {
		$this->db = $db;
	}


	public function find($race) {
		$stmt = $this->db->prepare("SELECT `race`, `status` FROM Race WHERE race = :race");
		$stmt->execute([":race" => $race]);

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function set($race, $status = "assimilated") {
		$found = $this->find($race);

		if ($found) {
			$stmt = $this->db->prepare("UPDATE Race SET `status` = :status WHERE race = :race");
		
		} else {
			$stmt = $this->db->prepare("INSERT INTO Race (`race`, `status`) VALUES (:race, :status)");
		
		}
		return $stmt->execute([
			":race"   => $race,
			":status" => $status,
		]);
	}

	public function delete($race) {
		$stmt = $this->db->prepare("DELETE FROM Race WHERE race = :race");
	
		return $stmt->execute([
			":race"   => $race,
		]);
	}

}


