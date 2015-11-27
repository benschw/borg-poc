<?php

namespace Demo;

use Fliglio\Http\Http;
use Fliglio\Routing\Type\RouteBuilder;
use Fliglio\Fli\Configuration\DefaultConfiguration;
use Fliglio\Borg\Amqp\AmqpCollectiveDriver;
use Fliglio\Borg\Amqp\AmqpChanDriverFactory;
use Fliglio\Borg\Collective;
use Fliglio\Borg\Chan\ChanFactory;

use Demo\Shaky\ShakyResource;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class DemoConfiguration extends DefaultConfiguration {

	public function getRoutes() {
		
		$resource = new ShakyResource();
		
		$conn = new AMQPStreamConnection("192.168.0.109", 5672, "guest", "guest", "/");

		$driver = new AmqpCollectiveDriver($conn);

		$coll = new Collective($driver, "borg-demo");
		$coll->assimilate($resource);
		

		return [
			RouteBuilder::get()
				->uri('/read')
				->resource($resource, 'read')
				->method(Http::METHOD_GET)
				->build(),
			RouteBuilder::get()
				->uri('/test')
				->resource($resource, 'test')
				->method(Http::METHOD_GET)
				->build(),
			RouteBuilder::get()
				->uri('/borg')
				->resource($coll, "mux")
				->method(Http::METHOD_POST)
				->build(),
		];
	}
}


