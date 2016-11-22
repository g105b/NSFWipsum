<?php
namespace NSFWipsum\Profanity;

use Gt\Core\Path;

class Censorship {

private static $profanityList;

public static function isProfane(string $word):bool {
	$profane = false;

	foreach(self::profanityListSingleton() as $profanity) {
		if(strstr($word, $profanity)) {
			$profane = true;
		}
	}

	return $profane;
}

private static function profanityListSingleton() {
	if(empty(self::$profanityList)) {
		self::$profanityList = explode(
			"\n",
			file_get_contents(Path::get(Path::ROOT) . "/profanity-list"
		));
	}

	return self::$profanityList;
}

}#