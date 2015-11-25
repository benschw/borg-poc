<?php

namespace Demo;

use Fliglio\Fli\DefaultResolverApp;
use Fliglio\Fli\ResolverAppMux;

class MyApplication extends ResolverAppMux {
	public function __construct() {
		parent::__construct();

		$fli = new DefaultResolverApp();
		$fli->configure(new DemoConfiguration());

		$this->addApp($fli);
	}

}
