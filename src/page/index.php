<?php
namespace NSFWipsum\Page;

use NSFWipsum\Profanity\Generator;

class Index extends \Gt\Page\Logic {

public function go() {
	$this->submitDefaultValues();
	$this->outputParagraphs($_GET["p"], $_GET["uncensored"] ?? false);
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
		$words = $generator->generateParagraphWords($uncensored);

		if($i === 0) {
			array_unshift($words, "Lorem", "ipsum");
		}

		$paragraphElement = $this->template->get("paragraph");
		$generator->addWordsToParagraphElement($words, $paragraphElement);
		$paragraphElement->insertTemplate();
	}
}

}#