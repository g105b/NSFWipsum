<?php
namespace NSFWipsum\Page;

use NSFWipsum\Profanity\Generator;

class Index extends \Gt\Page\Logic {

public function go() {
	$this->submitDefaultValues();
	$this->outputParagraphs($_GET["p"], $_GET["uncensored"] ?? false);
	$this->markCensorship($_GET["uncensored"] ?? false);
	$this->fillFormFields($_GET);
}

private function submitDefaultValues() {
	if(empty($_GET["p"])) {
		$form = $this->document->querySelector("form");
		$queryString = "?";

		foreach($form->querySelectorAll("[name][value]") as $kvpElement) {
			$queryString .=	$kvpElement->getAttribute("name");
			$queryString .= "=";
			$queryString .=	$kvpElement->value;
			$queryString .= "&";
		}

		\Gt\Response\Headers::redirect("/" . $queryString);
		exit;
	}
}

private function outputParagraphs(int $count, bool $uncensored = false) {
	$generator = new Generator($count);

	for($i = 0; $i < $count; ++$i) {
		$words = $generator->generateParagraphWords();

		if($i === 0) {
			array_unshift($words, "LOREM", "IPSUM");
		}

		$paragraphElement = $this->template->get("paragraph");
		$generator->addWordsToParagraphElement($words, $paragraphElement);
		$paragraphElement->insertTemplate();
	}
}

private function markCensorship(bool $uncensored = false) {
	if(!$uncensored) {
		$outputElement = $this->document->querySelector(".m-output");
		$outputElement->classList->add("censored");
	}
}

private function fillFormFields(array $data) {
	foreach ($data as $key => $value) {
		$element = $this->document->querySelector("[name=$key]");

		if(!$element) {
			continue;
		}

		if($element->type === "checkbox"
		|| $element->type === "radio") {
			$element->checked = true;
		}
		else {
			$element->value = $value;
		}
	}
}

}#