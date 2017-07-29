<?php

/////////////////////////////////////////////////////////////////////////////////////////////////////////
// Free PHP IMDb Scraper Web Service API
// Author: Abhinay Rathore
// Website: http://www.AbhinayRathore.com
// Blog: http://web3o.blogspot.com
// Demo: http://lab.abhinayrathore.com/imdb/
// More Info: http://web3o.blogspot.com/2010/10/php-imdb-scraper-for-new-imdb-template.html
// Last Updated: July 3, 2011
/////////////////////////////////////////////////////////////////////////////////////////////////////////

use App\Espinoso\Handlers\ImdbScraper\Imdb;

include_once("Imdb.php");

$movieName = $_REQUEST["m"] ?? '';
$output = strtolower($_REQUEST["o"] ?? '');
if($output != "xml" && $output != "json" && $output != "jsonp"){
	$output = "xml"; //Set default to XML
}

$i = new Imdb;
$mArr = array_change_key_case($i->getMovieInfo($movieName), CASE_UPPER);

///////////////[ XML Output ]/////////////////
if($output == "xml") {
//	header("Content-Type: text/xml");
	$doc = new DomDocument('1.0');
	$doc->formatOutput = true;
	$movie = $doc->createElement('MOVIE');
	$movie = $doc->appendChild($movie);
	foreach ($mArr as $k=>$v){
		if(is_array($v)){
			$node = $doc->createElement($k);
			$node = $movie->appendChild($node);
			$c = 0;
			foreach($v as $a){
				$c++;
				$child = $doc->createElement($k . "_");
				$child = $node->appendChild($child);
				$child->setAttribute('n', $c);
				$value = $doc->createTextNode($a);
				$value = $child->appendChild($value);
			}
		} else {
			$node = $doc->createElement($k);
			$node = $movie->appendChild($node);
			$value = $doc->createTextNode($v);
			$value = $node->appendChild($value);
		}
	}
	$xml_string = $doc->saveXML();
	echo $xml_string;
} //End XML Outout

///////////////[ JSON Output ]/////////////////
if($output == "json") {
//	header('Content-type: application/json');
	echo json_encode($mArr);
} //End JSON Outout

///////////////[ JSONP Output ]/////////////////
if($output == "jsonp") {
//	header('Content-type: application/json');
	echo isset($_GET['callback']) ? $_GET['callback']."(". json_encode($mArr) .")" : json_encode($mArr);
} //End JSONP Outout

