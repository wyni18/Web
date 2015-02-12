<?php
$url = "http://www.allmusic.com/search/".$_GET["type"]."/".$_GET["title"]; 
$url = str_replace(' ','+',$url);

$html = file_get_contents($url);  
// the whole HTML is loaded in the $html variable  

$options = "i"; 
// check whehter there is no search result
$regexp = "<div\sclass=\"no-results-message\">"; 
if(preg_match_all("/$regexp/$options", $html, $check, PREG_SET_ORDER) )  {
	$result = "<results total=\"0\"></results>";
	
	echo $result;
}

else {
$temp1 = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<results  total=\"1\">\n";
if($_GET["type"] == "artists") {
	// the regular expression to get name information
	$regexp = "class=\"name\">\n.*>([^\<]+)<\/a>"; 
	if(preg_match_all("/$regexp/$options", $html, $artists, PREG_SET_ORDER) )  {
	}
	// the regular expression to get genre and year information
	$regexp = "\<div\sclass=\"info\"\>([^\<]+)\<br\/\>([^\<]+)";
	if(preg_match_all("/$regexp/$options", $html, $artists_gen, PREG_SET_ORDER) )  {
	}
	// the regular expression to get the link information
	$regexp = "href=\"(http:\/\/www\.allmusic\.com\/artist\/[^\"]+)\"";
	if(preg_match_all("/$regexp/$options", $html, $artists_det, PREG_SET_ORDER) )  {
	}	
	// the regular expression to get image information
	$regexp = "<div\sclass=\"image\">\s(.*\s.*\s.*\s.*\s.*\s)";
	if(preg_match_all("/$regexp/$options", $html, $artists_i, PREG_SET_ORDER) )  {
	}
	
	for($i=0; $i<5&&$i<count($artists); $i++) {
		// the regular expression to get image information
		$regexp = "\<img\ssrc=\"(http:\/\/cps-static\.rovicorp\.com[^\"]+)";
		if(preg_match_all("/$regexp/$options", $artists_i[$i][1], $artists_img[$i], PREG_SET_ORDER) )  {
		}
		else {
			$artists_img[$i][0][1] = "http://www-scf.usc.edu/~yanniwan/people.png"; // if image for artist does not exist
		}
		$temp1 .= "<result cover=\"".specialChar($artists_img[$i][0][1])."\" ";  // image
		$temp1 .= "name=\"".specialChar($artists[$i][1])."\" ";     // name
		// the regular expression to get genre information
		$regexp = "\S+";
		if(preg_match_all("/$regexp/$options", $artists_gen[$i][1], $none, PREG_SET_ORDER) )  {
		}
		else {
			$artists_gen[$i][1] = "N/A"; // if genre is not available
		}
		$temp1 .= "genre=\"".specialChar(trim($artists_gen[$i][1]))."\" ";  // genre
		
		if(preg_match_all("/$regexp/$options", $artists_gen[$i][2], $none, PREG_SET_ORDER) )  {
		}
		else {
			$artists_gen[$i][2] = "N/A"; // if year is not available
		}
		$temp1 .= "year=\"".trim($artists_gen[$i][2])."\" ";  // year
		$temp1 .= "detail=\"".specialChar($artists_det[$i][1])."\"></result>\n";  // detail
	}
	$temp1 .= "</results>";
	echo $temp1;		
} 


else if($_GET["type"] == "albums") {
	$temp2 = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<results total=\"2\">\n";
	$options = "i"; 
	// the regular expression to get title and link information
	$regexp = "class=\"title\">\s[^\"]+\"([^\"]+)[^\>]+\>([^\<]+)"; 
	if(preg_match_all("/$regexp/$options", $html, $title, PREG_SET_ORDER) )  {
	}
	// the regular expression to get genre and year information
	$regexp = "\<div\sclass=\"info\"\>([^\<]+)\<br\/\>([^\<]+)";
	if(preg_match_all("/$regexp/$options", $html, $albums_info, PREG_SET_ORDER) )  {
	}
	// the regular expression to get image information
	$regexp = "<div\sclass=\"image\">\s(.*\s.*\s.*\s.*\s.*\s)";
	if(preg_match_all("/$regexp/$options", $html, $albums_i, PREG_SET_ORDER) )  {
	}
	// the regular expression to get artists information
	$regexp = "\<div\sclass=\"title(.*\s.*\s.*\s.*\s.*)";
	if(preg_match_all("/$regexp/$options", $html, $albums_arts, PREG_SET_ORDER) )  {
	}
		
	for($i=0; $i<5&&$i<count($title); $i++) {
		// the regular expression to get image information
		$regexp = "\<img\ssrc=\"(http:\/\/cps-static\.rovicorp\.com[^\"]+)";
		if(preg_match_all("/$regexp/$options", $albums_i[$i][1], $albums_img[$i], PREG_SET_ORDER) )  {
		}
		else {
			$albums_img[$i][0][1] = "http://www-scf.usc.edu/~yanniwan/music.png"; // if the image is not available
		}
		$temp2 .= "<result cover=\"".specialChar($albums_img[$i][0][1])."\" ";  // image
		$temp2 .= "title=\"".specialChar($title[$i][2])."\" ";  // title
		
		$regexp = "class=\"artist\">\s+<a\shref.*"; //artist
		if(preg_match_all("/$regexp/$options", $albums_arts[$i][1], $albums_arts1, PREG_SET_ORDER) )  {
			$regexp = "href[^>]+>([^<]+)";
			if(preg_match_all("/$regexp/$options", $albums_arts1[0][0], $albums_art, PREG_SET_ORDER) )  {
			}
			$temp2 .= "artist=\"";
			for($j=0; $j<count($albums_art); $j++) {
				$temp2 .= specialChar($albums_art[$j][1]);
				if($j<(count($albums_art)-1)) {
					$temp2 .= "/";
				}
			}
			$temp2 .= "\" ";
		}
		else {
			$temp2 .= "artist=\"N/A\" ";  // if there is no artist in the album
		}
		$regexp = "\S+";
		if(preg_match_all("/$regexp/$options", $albums_info[$i][2], $none, PREG_SET_ORDER) )  {
		}
		else {
			$albums_info[$i][2] = "N/A"; // if there is no genre
		}
		$temp2 .= "genre=\"".specialChar(trim($albums_info[$i][2]))."\" ";  // genre
		
		if(preg_match_all("/$regexp/$options", $albums_info[$i][1], $none, PREG_SET_ORDER) )  {
		}
		else {
			$albums_info[$i][1] = "N/A"; // if there is no year
		}
		$temp2 .= "year=\"".trim($albums_info[$i][1])."\" ";  // year
		
		$temp2 .= "detail=\"".specialChar($title[$i][1])."\"/>\n";  // link
	}
	$temp2 .= "</results>";
	echo $temp2;
} 


else {
	$temp3 = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<results total=\"3\">\n";
	$options = "i"; 
	// the regular expression to get the whole sample link information
	$regexp = "class=\"type\">\s.*\s.*"; 
	if(preg_match_all("/$regexp/$options", $html, $sample_whole, PREG_SET_ORDER) )  {
	}
	// the regular expression to get song title information
	$regexp = "class=\"title\">\s[^\"]+\"([^\"]+)[^\>]+\>[^;]+;([^\&]+).*"; 
	if(preg_match_all("/$regexp/$options", $html, $song_title, PREG_SET_ORDER) )  {
	}
	// the regular expression to get song composer information
	$regexp = "class=\"info\".*\n.*\n.*\n[\s]+<\/td";
	if(preg_match_all("/$regexp/$options", $html, $song_coms, PREG_SET_ORDER) )  {
	}
	
	for($i=0; $i<5&&$i<count($song_title); $i++) {
		$regexp = "http:[^\"]+";
		if(preg_match_all("/$regexp/$options", $sample_whole[$i][0], $song_sam, PREG_SET_ORDER) )  {
			$temp3 .= "<result sample=\"".specialChar($song_sam[0][0])."\" ";  // sample exist
		}
		else {
			$temp3 .= "<result sample=\"N/A\" ";  // no sample
		}
		
		$temp3 .= "title=\"".specialChar($song_title[$i][2])."\" ";  // title
		// the regular expression to get song performer information
		$regexp = "class=\"performer\".*";
		if(preg_match_all("/$regexp/$options", $song_title[$i][0], $song_pfms, PREG_SET_ORDER) )  {
		}
		else {
			$song_pfms = "";
		}
		// performer(s)
		$regexp = "href=\"[^>]+>([^<]+)";
		if(preg_match_all("/$regexp/$options", $song_pfms[0][0], $song_pfm, PREG_SET_ORDER) )  {
			$temp3 .= "performer=\" ";
			for($j=0; $j<count($song_pfm); $j++) {
				$temp3 .= specialChar($song_pfm[$j][1]);
				if($j<(count($song_pfm)-1)) {
					$temp3 .= "/";
				}
			}
			$temp3 .= "\" ";
		}
		else {
			$temp3 .= "performer=\"N/A\" ";
		}
		
		
		// composer(s)
		$regexp = "href=\"[^>]+>([^<]+)";
		if(preg_match_all("/$regexp/$options", $song_coms[$i][0], $song_com, PREG_SET_ORDER) )  {
			$temp3 .= "composer=\"";
			for($j=0; $j<count($song_com); $j++) {
				$temp3 .= specialChar($song_com[$j][1]);
				if($j<(count($song_com)-1)) {
					$temp3 .= "/";
				}
			}
			$temp3 .= "\" ";
		}
		else {
			$temp3 .= "composer=\"N/A\" ";
		}
		
		$temp3 .= "detail=\"".specialChar($song_title[$i][1])."\"></result>\n";  // link
	}
	$temp3 .= "</results>";
	
	echo $temp3;
}	
}

function specialChar($string) {
		$stringTrans = str_replace('&', '&amp;', $string);
		$stringTrans = str_replace("'", '&apos;', $stringTrans);
		$stringTrans = str_replace('"', '&quot;', $stringTrans);
		$stringTrans = str_replace('<', '&lt;', $stringTrans);
		$stringTrans = str_replace('>', '&gt;', $stringTrans);
		
		return $stringTrans;
}		

?>