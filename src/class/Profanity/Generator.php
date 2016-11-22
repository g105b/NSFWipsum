<?php
namespace NSFWipsum\Profanity;

use Gt\Dom\Node;

class Generator {

public function __construct() {

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