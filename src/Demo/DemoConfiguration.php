<?php

namespace Demo;

use Fliglio\Http\Http;
use Fliglio\Routing\Type\RouteBuilder;
use Fliglio\Fli\Configuration\DefaultConfiguration;
use Fliglio\Borg\Amqp\AmqpCollectiveDriver;
use Fliglio\Borg\Amqp\AmqpChanDriverFactory;
use Fliglio\Borg\Collective;
use Fliglio\Borg\Chan\ChanFactory;

use Fliglio\Consul\AddressProviderFactory;

use PhpAmqpLib\Connection\AMQPStreamConnection;

use Demo\Resource\Test;

class DemoConfiguration extends DefaultConfiguration {

	public function getRoutes() {


		$apFactory = new AddressProviderFactory();
		$rabbitAp = $apFactory->createConsulAddressProvider('rabbitmq');
		$mysqlAp = $apFactory->createConsulAddressProvider('mysql');
		$rAdd = $rabbitAp->getAddress();
		$mAdd = $mysqlAp->getAddress();

		
		$conn = new AMQPStreamConnection($rAdd->getHost(), $rAdd->getPort(), "guest", "guest", "/");

		$dsn = sprintf("mysql:host=%s;dbname=borg", $mAdd->getHost());
		$options = [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'];
		$db = new \PDO($dsn, 'borg', 'changeme', $options);
		$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		$resource = new Test($db);


		$driver = new AmqpCollectiveDriver($conn);

		$coll = new Collective($driver, "borg-demo", $_SERVER['CUBE_DC']);
		$coll->assimilate($resource);
		

		return [
			RouteBuilder::get()
				->uri('/dctest')
				->resource($resource, 'dctest')
				->method(Http::METHOD_GET)
				->build(),
			RouteBuilder::get()
				->uri('/dcread')
				->resource($resource, 'dcread')
				->method(Http::METHOD_GET)
				->build(),
					
			RouteBuilder::get()
				->uri('/prime')
				->resource($resource, 'prime')
				->method(Http::METHOD_GET)
				->build(),
					
			RouteBuilder::get()
				->uri('/test')
				->resource($resource, 'test')
				->method(Http::METHOD_GET)
				->build(),

			// router foir all borg collective calls
			RouteBuilder::get()
				->uri('/borg')
				->resource($coll, "mux")
				->method(Http::METHOD_POST)
				->build(),
		];
	}
}


