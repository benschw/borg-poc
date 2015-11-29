<?php

namespace Demo;

use Fliglio\Http\Http;
use Fliglio\Routing\Type\RouteBuilder;
use Fliglio\Fli\Configuration\DefaultConfiguration;
use Fliglio\Borg\Amqp\AmqpCollectiveDriver;
use Fliglio\Borg\Amqp\AmqpChanDriverFactory;
use Fliglio\Borg\Collective;
use Fliglio\Borg\Chan\ChanFactory;

use PhpAmqpLib\Connection\AMQPStreamConnection;

use Demo\Resource\Test;

class DemoConfiguration extends DefaultConfiguration {

	public function getRoutes() {
		
		$resource = new Test();
		
		$conn = new AMQPStreamConnection("localhost", 5672, "guest", "guest", "/");

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


