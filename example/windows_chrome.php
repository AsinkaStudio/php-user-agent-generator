<?php

use Asinka\UAGenerator;

require __DIR__ . '/../vendor/autoload.php';
try {
	$count     = 10;
	$userAgent = new UAGenerator();
	echo 'UserAgents:' . PHP_EOL;
	for ($x = 0; $x <= $count; $x++) {
		echo $userAgent->random_agent(UAGenerator::BROWSER_CHROME, UAGenerator::OS_WINDOWS) . PHP_EOL;
	}
} catch (Exception $e) {
	echo 'Error: ' . PHP_EOL . $e->getMessage() . PHP_EOL;
}