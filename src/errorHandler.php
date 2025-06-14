<?php
error_reporting(E_ALL);
// ini_set('display_errors', 'stderr');

function errorHandler($errno, $errstr, $errfile, $errline): bool {
	if ($errno === E_DEPRECATED) {
		fwrite(STDERR, "Deprecated: {$errstr} in {$errfile} on line {$errline}\n");
		return true;
	}
	if ($errno === E_NOTICE) {
		fwrite(STDERR, "Notice: {$errstr} in {$errfile} on line {$errline}\n");
		return true;
	}
	return false;
}
set_error_handler('errorHandler');