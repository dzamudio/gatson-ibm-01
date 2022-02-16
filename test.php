<?php
//print(get_include_path());
session_start();
include('php_config.php');
require_once("Crawl.php");
require_once("IBMWatson.php");
//require_once('DatabaseHandler.php');



//$page = new \Core\Crawl;
//$page->diagnostics();

$test = new \API\Watson\NLP;
//https://newsroom.ibm.com/Guerbet-and-IBM-Watson-Health-Announce-Strategic-Partnership-for-Artificial-Intelligence-in-Medical-Imaging-Liver
//$test->analyzeURL('https://newsroom.ibm.com/Guerbet-and-IBM-Watson-Health-Announce-Strategic-Partnership-for-Artificial-Intelligence-in-Medical-Imaging-Liver');
//$test->analyzeURL('https://en.wikipedia.org/wiki/France');
//$test->analyzeURL('https://www.ibm.com/watson');
$test->analyzeURL('https://dbpedia.org/page/Watson_(computer)');

//$db = new \Core\DatabaseHandler();
//$db->saveWatsonNLPData();
//var_dump($db);
echo "done";