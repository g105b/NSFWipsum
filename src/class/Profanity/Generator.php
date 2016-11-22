<?php
namespace NSFWipsum\Profanity;

use Gt\Core\Path;
use Gt\Database\Client;
use Gt\Database\Connection\Settings;
use Gt\Database\Result\ResultSet;
use Gt\Dom\Node;
use NSFWipsum\Settings\Config;

class Generator {

const WORDS_IN_PARAGRAPH_MIN = 24;
const WORDS_IN_PARAGRAPH_MAX = 80;
const WORDS_IN_LOREM_MIN = 2;
const WORDS_IN_LOREM_MAX = 4;

/** @var DatabaseClient */
private $db;

public function __construct() {
	$settings = new Settings(
		Path::get(Path::ROOT) . "/src/query",
		Config::get("database.source"),
		Config::get("database.name"),
		Config::get("database.hostname"),
		Config::get("database.username"),
		Config::get("database.password")
	);

	$this->db = new Client($settings);
}

public function generateParagraphWords():array {
	$words = [];

	$wordsInParagraph = rand(
		self::WORDS_IN_PARAGRAPH_MIN,
		self::WORDS_IN_PARAGRAPH_MAX
	);

	while(count($words) < $wordsInParagraph) {
		$profanity = $this->db["profanity"]->getRandomProfanity();
		$profanityContent = $this->addRandomEndingPunctuation(
			$profanity["content"]);
		$profanityWords = explode(" ", $profanityContent);

		$words = array_merge(
			$words,
			$profanityWords,
			$this->generateLoremWords()
		);
	}

	return $words;
}

public function generateLoremWords() {
	$words = [];
	$wordsInLorem = rand(
		self::WORDS_IN_LOREM_MIN,
		self::WORDS_IN_LOREM_MAX
	);

	while(count($words) < $wordsInLorem) {
		$words []= Lorem::getRandom();
	}

	return $words;
}

public function addWordsToParagraphElement(array $words, Node $element) {
	foreach($words as $w) {
		$span = $element->ownerDocument->createElement("span");
		$span->textContent = $w;

		if(Censorship::isProfane($w)) {
			$span->classList->add("profane");
		}

		$element->appendChild($span);

		$space = $element->ownerDocument->createTextNode(" ");
		$element->appendChild($space);
	}

// Remove the last space from the paragraph.
	$element->removeChild($space);
}

private function addRandomEndingPunctuation(string $sentence):string {
	$lastCharacter = substr($sentence, -1);
	$skipCharacters = ["!", "?", ".", ","];
	if(in_array($lastCharacter, $skipCharacters)) {
		return $sentence;
	}

	$ending = "";

	$random = rand(1, 4);
	if($random === 1) {
		$ending = ".";
	}
	if($random === 2) {
		$ending = ",";
	}
	if($random === 3) {
		$ending = "!";
	}

	return $sentence . $ending;
}

}#