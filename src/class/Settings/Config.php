<?php
namespace NSFWipsum\Settings;

use Gt\Core\Path;

class Config {

public static function get(string $fullyQualifiedName) {
	$config = parse_ini_file(Path::get(Path::ROOT) . "/config.ini", true);
	$currentReference = $config;

	foreach(explode(".", $fullyQualifiedName) as $namespace) {
		$currentReference = $currentReference[$namespace] ?? null;
	}

	return $currentReference;
}

}#