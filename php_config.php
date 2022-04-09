<?php

ini_set('display_errors', 'Off');
// Turn off all error reporting
//error_reporting(0);
// Report simple running errors
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
// Reporting E_NOTICE can be good too (to report uninitialized
// variables or catch variable name misspellings ...)
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
// Report all errors except E_NOTICE
//error_reporting(E_ALL & ~E_NOTICE);
// Report all PHP errors
//error_reporting(E_ALL);
// Report all PHP errors
//error_reporting(-1);
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ERROR);


error_reporting(E_ERROR);

$apiConfig = new stdClass();
$apiConfig->apiUrl = "https://api.us-south.natural-language-understanding.watson.cloud.ibm.com/instances/4343cace-f35a-465f-b9c0-5cf106825657";
$apiConfig->apiKey = "eQK34W4VWmLYYnWn_BXpUV2jE8E0BMKpRtA6kGPZpfYU";
$apiConfig->apiVer = "/v1/analyze?version=2020-12-09";

error_log('php_config loaded '.time().microtime());