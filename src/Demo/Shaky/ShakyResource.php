<?php

namespace Demo\Shaky;

use Fliglio\Routing\Routable;
use Fliglio\Web\Body;
use Fliglio\Web\PathParam;
use Fliglio\Web\GetParam;

use Fliglio\Fltk\View;

use Fliglio\Http\ResponseWriter;
use Fliglio\Http\Http;

use Fliglio\Borg\BorgImplant;
use Fliglio\Borg\Type\Primitive;

class ShakyResource {
	use BorgImplant;

	private $http;

	public function __construct() {
	}

	public function read() {
		return file_get_contents("/tmp/test");
	}

	public function test(GetParam $msg) {
		//$words = $this->ch->makeChan(Primitive::getClass());
		$this->coll()->doTest(new Primitive($msg->get()));
	}

	public function doTest(Primitive $msg) {

		//$this->coll()->doTest(new Primitive("foo"));
		
		file_put_contents("/tmp/test", $msg->value());

		return $msg->value();

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
