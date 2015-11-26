<?php

namespace Demo\Shaky;

use Fliglio\Routing\Routable;
use Fliglio\Web\Body;
use Fliglio\Web\PathParam;
use Fliglio\Web\GetParam;

use Fliglio\Fltk\View;

use MyApp\RestExample\FooApi\FooApi;
use MyApp\RestExample\FooApi\FooApiMapper;

use Fliglio\Http\ResponseWriter;
use Fliglio\Http\Http;

use Fliglio\Borg\BorgImplant;

class ShakyResource {
	use BorgImplant;

	private $http;

	public function __construct() {
	}

	public function test() {
		$this->coll()->doTest();
	}

	public function doTest() {
		file_put_contents("/tmp/testing", "hello world");
	}


	public function getWordCount(Entity $entity) {
		$links = $entity->bind(Links::getClass());

		$words = $this->ch->makeChan(Primitive::getClass());
		$exits = $this->ch->makeChan(Primitive::getClass());

		foreach ($links as $link) {
			$this->go->getWordsForLink($link, $words, $exits);
		}

		$allWords = [];
		$exitCount = 0;

		$reader = new ChanReader([$words, $exits]);
		while ($exitCount < $links->length()) {
			list($chanId, $chEntity) = $reader->next();

			switch ($chanId) {
			case $words->getId():
				$allWords[] = $word;
				break;
			case $exits->getId():
				$exitCount++;
				break;
			default:
				usleep(200); // 200 microseconds
			}
		}

		$exits->close();
		$words->close();

		return array_count_values($allWords);
	}

	public function getWordsForLink(Link $link, Chan $words, Chan $exits) {
		$txt = $this->http->get($link->getHref());

		$wordsArr = split(' +', $txt);
		foreach ($wordsArr as $wordStr) {
			$words->push($wordStr);
		}
		$exits->push(true);
	}

}
