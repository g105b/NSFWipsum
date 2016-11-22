<?php
namespace NSFWipsum\Profanity;

use NSFWipsum\Settings\Config;
use Gt\Database\Connection\Settings;
use Gt\Database\DatabaseClient;
use Gt\Dom\Node;
use Gt\Core\Path;

class Generator {

public function __construct() {
	$settings = new Settings(
		Path::get(Path::ROOT) . "/src/query",
		Config::get("database.source"),
		Config::get("database.name"),
		Config::get("database.hostname"),
		Config::get("database.username"),
		Config::get("database.password")
	);

	$this->db = new DatabaseClient($settings);
}

public function generateParagraphWords():array {
	return ["one", "two", "three"];
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

}#