<?php

/**
 * FM2json.php
 *
 * Returns a json formatted string from a Filemaker database
 * Takes parameters according to Filemaker's XML query requests
 *
 * @author     Ian Shen <designs@2b3pro.com>
 * @copyright  2012 2b3 Productions
 */

	$debug = false;
	/**
	 * 		CONFIGURATION -- set MYHOSTNAME to your server
	 */
	define('FILEMAKER_XMLPATH', 'http://MYHOSTNAME/fmi/xml/fmresultset.xml');

	function curl_get_file_contents($URL)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);

        if ($contents) return $contents;
            else return FALSE;
    }
	
	if ($debug) {
	    if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
	        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
	    } else {
	        error_reporting(E_ALL & ~E_NOTICE);
	    }   
	} else {
		error_reporting(0);
	}

	//$query = str_replace("-sortfield_", "-sortfield.", http_build_query($_GET)); 
	$query = $_SERVER["QUERY_STRING"]; 
	$filename = FILEMAKER_XMLPATH."?".$query;
	$xml = curl_get_file_contents($filename);
	$dom = new DOMDocument; $dom->loadXML($xml);
	$s = simplexml_import_dom($dom);
	
	$fmjson = array();
	foreach($s as $a => $b) {
	    $fmjson[$a] = array();	
	    if (is_object($b)) {
	    	foreach ($b->attributes() as $c => $d) {
	    		$fmjson[$a][$c] = array();
	    		$fmjson[$a][$c] = (string)$b->attributes()->$c;
			}
	    }
	}
	
	/**
	 * 	Process Root Records (ResultSet) Node
	 */
	$fmjson["resultset"]["records"] = array();
	$idx = 0;
	foreach($s->resultset->record as $a => $b) {
	    if (is_object($b)) {
	    	foreach ($b->attributes() as $c => $d) {
	    		$fmjson["resultset"]["records"][$idx][$c] = (string)$d;
			}
	    }
		$fmjson["resultset"]["records"][$idx]["fields"] = array();
		$fs = $s->resultset->record[$idx]->field;
		for ($i=0; $i< count($fs); $i++){
			$key = $s->resultset->record[$idx]->field[$i]->attributes();
			$data = $s->resultset->record[$idx]->field[$i]->data;
			$fmjson["resultset"]["records"][$idx]["fields"][(string)$key] = array ( htmlspecialchars($data) );
		}
		
		/**
		 * 	Within each Record Node, Process Related Portal Sets
		 */
		if (is_object( $s->resultset->record[$idx]->relatedset )) {
			$idx2=0;
			/**
			 * 	Process Records (RelatedSet) Attributes
			 */
			$fmjson["resultset"]["records"][$idx]["relatedset"] = array();
			$rsnode = $s->resultset->record[$idx]->relatedset;
			foreach ($rsnode->attributes() as $c => $d) {
	    		$fmjson["resultset"]["records"][$idx]["relatedset"][$c] = (string)$rsnode->attributes()->$c;
			}
			/**
			 * 	Process Records under RelatedSet
			 */
			foreach($s->resultset->record[$idx]->relatedset->record as $e => $f) {
		    	foreach ($f->attributes() as $g => $h) {
	    			$fmjson["resultset"]["records"][$idx]["relatedset"]["records"][$idx2][$g] = (string)$h;
				}		
				// Process the fields
				$fmjson["resultset"]["records"][$idx]["relatedset"]["records"]["fields"] = array();
				$rs = $s->resultset->record[$idx]->relatedset->record[$idx2]->field;
				for ($ii=0; $ii< count($rs); $ii++){
					$key = $s->resultset->record[$idx]->relatedset->record[$idx2]->field[$ii]->attributes();
					$data = $s->resultset->record[$idx]->relatedset->record[$idx2]->field[$ii]->data;
					$fmjson["resultset"]["records"][$idx]["relatedset"]["records"][$idx2]["fields"][(string)$key] = array ( htmlspecialchars($data) );
				}
				$idx2++;
			}
		}
	    $idx++;
	}

	$json = json_encode( $fmjson );
	$replacedString = preg_replace("/\\\\u([0-9abcdef]{4,})/", "&#x$1;", $json);
	$unicodeString = mb_convert_encoding($replacedString, 'UTF-8', 'HTML-ENTITIES');
	$unicodeString = stripslashes($unicodeString);
	
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
	echo( $unicodeString ); exit;

    
?>