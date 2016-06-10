<?php

namespace Demo;

use Fliglio\Http\Http;
use Fliglio\Routing\Type\RouteBuilder;
use Fliglio\Fli\Configuration\DefaultConfiguration;
use Fliglio\Borg\Amqp\AmqpCollectiveDriver;
use Fliglio\Borg\Amqp\AmqpChanDriverFactory;
use Fliglio\Borg\Collective;
use Fliglio\Borg\Mapper\DefaultMapper;
use Fliglio\Borg\Chan\ChanFactory;
use Fliglio\Borg\RoutingConfiguration;

use Fliglio\Consul\AddressProviderFactory;

use PhpAmqpLib\Connection\AMQPStreamConnection;

use Demo\Db\RaceDbm;
use Demo\Resource\DemoResource;


class DemoConfiguration extends DefaultConfiguration {

	public function getRoutes() {

		// Consul
		$apFactory = new AddressProviderFactory();

		// Rabbitmq
		$rabbitAp = $apFactory->createConsulAddressProvider('rabbitmq');
		$rAdd = $rabbitAp->getAddress();
		$rConn = new AMQPStreamConnection($rAdd->getHost(), $rAdd->getPort(), "guest", "guest", "/");
		
		// MySQL
		$mysqlAp = $apFactory->createConsulAddressProvider('mysql');
		$mAdd = $mysqlAp->getAddress();

		$dsn = sprintf("mysql:host=%s;port=%s;dbname=borg", $mAdd->getHost(), $mAdd->getPort());
		$db = new \PDO($dsn, 'admin', 'changeme', [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
		$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);


		// Resource Dependencies
		$dbm = new RaceDbm($db);

		// Resources
		$dr = new DemoResource();

		// Borg
		$driver  = new AmqpCollectiveDriver($rConn);
		$mapper  = new DefaultMapper($driver);
		$routing = new RoutingConfiguration("borg-demo");
		$coll    = new Collective($driver, $mapper, $routing);

		$coll->assimilate($dr);
		$coll->assimilate($dbm);
		
		return [
			RouteBuilder::get()
				->uri('/pi')
				->resource($dr, 'pi')
				->method(Http::METHOD_GET)
				->build(),
					
			// Router for all Borg Collective calls
			RouteBuilder::get()
				->uri('/borg')
				->resource($coll, "mux")
				->method(Http::METHOD_POST)
				->build(),

		];
	}
}


