<?php

namespace Demo;

use Fliglio\Http\Http;
use Fliglio\Routing\Type\RouteBuilder;
use Fliglio\Fli\Configuration\DefaultConfiguration;
use Fliglio\Borg\RabbitDriver;
use Fliglio\Borg\Collective;
use Fliglio\Borg\Chan\ChanFactory;
use Fliglio\Borg\Routing\BorgRouteBuilder;

use Demo\Shaky\ShakyResource;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class DemoConfiguration extends DefaultConfiguration {

	public function getRoutes() {
		
		$conn = new AMQPStreamConnection("192.168.0.109", 5672, "guest", "guest", "/");

		$driver = new RabbitDriver($conn);
		$coll = new Collective($driver);
		$ch = new ChanFactory($driver);
		
		$resource = new ShakyResource();
		$resource->setCollective($coll);
		$resource->setChanFactory($ch);

		return [
			RouteBuilder::get()
				->uri('/words')
				->resource($resource, 'getWordCount')
				->method(Http::METHOD_POST)
				->build(),
			RouteBuilder::get()
				->uri('/test')
				->resource($resource, 'test')
				->method(Http::METHOD_GET)
				->build(),
			BorgRouteBuilder::get()
				->uri('/borg')
				->collective($coll)
				->method(Http::METHOD_POST)
				->build(),
		];
	}
}


