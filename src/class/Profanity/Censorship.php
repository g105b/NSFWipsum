<?php
namespace NSFWipsum\Profanity;

class Censorship {

public static function isProfane(string $word):bool {
	if($word === "two") {
		return true;
	}
	return false;
}

}#