<?php

use Phinx\Migration\AbstractMigration;

class BorgStart extends AbstractMigration {
	public function change() {
		
		$table = $this->table('Race');
		$table->addColumn('status', 'string')
			->addColumn('race', 'string')
			->create();

	}
}
