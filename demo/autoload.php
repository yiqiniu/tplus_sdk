<?php


/*function _autoload($class) {
    $class = str_replace('yqn\\chanjet\\', 'src/library/', $class);
    $file = __DIR__ . '/../' . $class . '.php';
    require_once $file;
}
spl_autoload_register('_autoload');*/


if(file_exists(__DIR__ . '/../vendor/autoload.php')){
	$loader = require __DIR__ . '/../vendor/autoload.php';
}else{//if the composer is not initialized, note: this is not work in phpunit, switch to composer when unit testing
	function _autoload($class) {
		$class = str_replace('yqn\\chanjet\\', 'src/library/', $class);
		$file = __DIR__ . '/../' . $class . '.php';
		require_once $file;
	}
    spl_autoload_register('_autoload');
}




