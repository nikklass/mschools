<?php
if (!isset($_SESSION)) session_start();
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

function newsletterSubscriber($email){
	$numQuery="SELECT * FROM pt_newsletter WHERE newsletter_subscribers='$email' AND newsletter_status='Yes'";
    $numResult = mysql_query($numQuery);
    $row = mysql_num_rows($numResult); 
    if ($row) { return true; } else { return false; }
}

//does category have projects under it?
function hasProjects($id){ 
	$numQuery="SELECT * FROM pt_site_sections WHERE parent_id = $id";
    $numResult = mysql_query($numQuery);
    $row = mysql_num_rows($numResult); 
    if ($row) { return true; } else { return false; }
}

function getCatName($category_id) {

		$qry = "SELECT category_name FROM muz_categories WHERE category_id=$category_id";
		$data = mysql_fetch_assoc(mysql_query($qry));
		$category_name = $data['category_name'];
		return $category_name;

}

function getTheCurrentUrl() {
    $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    //return url minus everything past ?
	$pos = strpos($url, "?");
	if ($pos) {
		$res = substr($url,0, $pos);   //get url length from beginning of url till the first ? . if ? is not found, return full url
	} else {
		$res = $url;
	}
	return "http://".$res;
}

function removelastor($str) {
  
  $startpos = strlen($str) - 2;
  $getstring = substr($str,$startpos);
  if ($getstring == "or") {
      return substr($str,0,$startpos);
  } else {
      return $str;
  }
  
}

/**
 * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
 *
 * @param string $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param string $ending Ending to be appended to the trimmed string.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 *
 * @return string Trimmed string.
 */
function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
    if ($considerHtml) {
        // if the plain text is shorter than the maximum length, return the whole text
        if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        // splits all html-tags to scanable lines
        preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
        $total_length = strlen($ending);
        $open_tags = array();
        $truncate = '';
        foreach ($lines as $line_matchings) {
            // if there is any html-tag in this line, handle it and add it (uncounted) to the output
            if (!empty($line_matchings[1])) {
                // if it's an "empty element" with or without xhtml-conform closing slash
                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                    // do nothing
                // if tag is a closing tag
                } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                    // delete tag from $open_tags list
                    $pos = array_search($tag_matchings[1], $open_tags);
                    if ($pos !== false) {
                    unset($open_tags[$pos]);
                    }
                // if tag is an opening tag
                } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                    // add tag to the beginning of $open_tags list
                    array_unshift($open_tags, strtolower($tag_matchings[1]));
                }
                // add html-tag to $truncate'd text
                $truncate .= $line_matchings[1];
            }
            // calculate the length of the plain text part of the line; handle entities as one character
            $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
            if ($total_length+$content_length> $length) {
                // the number of characters which are left
                $left = $length - $total_length;
                $entities_length = 0;
                // search for html entities
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                    // calculate the real length of all entities in the legal range
                    foreach ($entities[0] as $entity) {
                        if ($entity[1]+1-$entities_length <= $left) {
                            $left--;
                            $entities_length += strlen($entity[0]);
                        } else {
                            // no more characters left
                            break;
                        }
                    }
                }
                $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                // maximum lenght is reached, so get off the loop
                break;
            } else {
                $truncate .= $line_matchings[2];
                $total_length += $content_length;
            }
            // if the maximum length is reached, get off the loop
            if($total_length>= $length) {
                break;
            }
        }
    } else {
        if (strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = substr($text, 0, $length - strlen($ending));
        }
    }
    // if the words shouldn't be cut in the middle...
    if (!$exact) {
        // ...search the last occurance of a space...
        $spacepos = strrpos($truncate, ' ');
        if (isset($spacepos)) {
            // ...and cut the text in this position
            $truncate = substr($truncate, 0, $spacepos);
        }
    }
    // add the defined ending to the text
    $truncate .= $ending;
    if($considerHtml) {
        // close all unclosed html-tags
        foreach ($open_tags as $tag) {
            $truncate .= '</' . $tag . '>';
        }
    }
    return $truncate;
}

function excerpt($text, $words, $length=150, $prefix="...", $suffix = null, $options = array()) {

    // Set default score modifiers [tweak away...]
    /*$options = am(array(
      'exact_case_bonus'  => 2,
      'exact_word_bonus'  => 3,
      'abs_length_weight' => 0.0,
      'rel_length_weight' => 1.0,

      'debug' => true
    ), $options);*/

    // Null suffix defaults to same as prefix
    if (is_null($suffix)) {
      $suffix = $prefix;
    }

    // Not enough to work with?
    if (strlen($text) <= $length) {
      return $text;
    }

    // Just in case
    if (!is_array($words)) {
      $words = array($words);
    }

    // Build the event list
    // [also calculate maximum word length for relative weight bonus]
    $events = array();
    $maxWordLength = 0;

    foreach ($words as $word) {

      if (strlen($word) > $maxWordLength) {
        $maxWordLength = strlen($word);
      }

      $i = -1;
      while ( ($i = stripos($text, $word, $i+1)) !== false ) {

        // Basic score for a match is always 1
        $score = 1;

        // Apply modifiers
        if (substr($text, $i, strlen($word)) == $word) {
          // Case matches exactly
          $score += $options['exact_case_bonus'];
        }
        if ($options['abs_length_weight'] != 0.0) {
          // Absolute length weight (longer words count for more)
          $score += strlen($word) * $options['abs_length_weight'];
        }
        if ($options['rel_length_weight'] != 0.0) {
          // Relative length weight (longer words count for more)
          $score += strlen($word) / $maxWordLength * $options['rel_length_weight'];
        }
        if (preg_match('/\W/', substr($text, $i-1, 1))) {
          // The start of the word matches exactly
          $score += $options['exact_word_bonus'];
        }
        if (preg_match('/\W/', substr($text, $i+strlen($word), 1))) {
          // The end of the word matches exactly
          $score += $options['exact_word_bonus'];
        }

        // Push event occurs when the word comes into range
        $events[] = array(
          'type'  => 'push',
          'word'  => $word,
          'pos'   => max(0, $i + strlen($word) - $length),
          'score' => $score
        );
        // Pop event occurs when the word goes out of range
        $events[] = array(
          'type' => 'pop',
          'word' => $word,
          'pos'  => $i + 1,
          'score' => $score
        );
        // Bump event makes it more attractive for words to be in the
        // middle of the excerpt [@todo: this needs work]
        $events[] = array(
          'type' => 'bump',
          'word' => $word,
          'pos'  => max(0, $i + floor(strlen($word)/2) - floor($length/2)),
          'score' => 0.5
        );

      }
    }

    // If nothing is found then just truncate from the beginning
    if (empty($events)) {
      //return substr($text, 0, $length) . $suffix;
      return truncateHtml($text, $length, $suffix, false, true);
    }

    // We want to handle each event in the order it occurs in
    // [i.e. we want an event queue]
    //$events = sortByKey($events, 'pos');

    $scores = array();
    $score = 0;
    $current_words = array();

    // Process each event in turn
    foreach ($events as $idx => $event) {
      $thisPos = floor($event['pos']);

      $word = strtolower($event['word']);

      switch ($event['type']) {
      case 'push':
        if (empty($current_words[$word])) {
          // First occurence of a word gets full value
          $current_words[$word] = 1;
          $score += $event['score'];
        }
        else {
          // Subsequent occurrences mean less and less
          $current_words[$word]++;
          $score += $event['score'] / sizeof($current_words[$word]);
        }
        break;
      case 'pop':
        if (($current_words[$word])==1) {
          unset($current_words[$word]);
          $score -= ($event['score']);
        }
        else {
          $current_words[$word]--;
          $score -= $event['score'] / sizeof($current_words[$word]);
        }
        break;
      case 'bump':
        if (!empty($event['score'])) {
          $score += $event['score'];
        }
        break;
      default:
      }

      // Close enough for government work...
      $score = round($score, 2);

      // Store the position/score entry
      $scores[$thisPos] = $score;

      // For use with debugging
      $debugWords[$thisPos] = $current_words;

      // Remove score bump
      if ($event['type'] == 'bump') {
          $score -= $event['score'];
      }
    }

    // Calculate the best score
    // Yeah, could have done this in the main event loop
    // but it's better here
    $bestScore = 0;
    foreach ($scores as $pos => $score) {
        if ($score > $bestScore) {
          $bestScore = $score;
        }
    }

    // Find all positions that correspond to the best score
    $positions = array();
    foreach ($scores as $pos => $score) {
      if ($score == $bestScore) {
        $positions[] = $pos;
      }
    }

    if (sizeof($positions) > 1) {
      // Scores are tied => do something clever to choose one
      // @todo: Actually do something clever here
      $pos = $positions[0];
    }
    else {
      $pos = $positions[0];
    }

    // Extract the excerpt from the position, (pre|ap)pend the (pre|suf)fix
    $excerpt = substr($text, $pos, $length);
    //$excerpt = truncateHtml($text, $length, '', false, true);
    if ($pos > 0) {
      $excerpt = $prefix . $excerpt;
    }
    if ($pos + $length < strlen($text)) {
      $excerpt .= $suffix;
    }

    return $excerpt;
  } 

function curPageURL() {

	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 	$pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
   return $pageURL;

}
function strip_html_tags( $text )
{
    $text = preg_replace(
        array(
          // Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
          // Add line breaks before and after blocks
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
        ),
        array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            "\n\$0",
        ),
        $text );
    //strip image tags
    $text = preg_replace("/<img[^>]+\>/i", "", $text);
    $text = preg_replace("/<div[^>]+\>/i", "", $text);
    return strip_tags($text);
}

function showCategoryItems($num_items, $cat_id)
{
	$video = false;
	//fetch 3 random items
	$article_ids = getFirstArticleIds($num_items, $cat_id);
	//echo "article_ids - " .$article_ids;
	$art_qry = "SELECT * FROM muz_articles WHERE article_id IN ($article_ids)";
	$art_res = mysql_query($art_qry);
	while ($row = mysql_fetch_assoc($art_res))
	{
		$thecss = "";$main_img = "";
		$article_id=$row['article_id'];
		$article_artist_id=$row['article_artist_id']; 
		$artist_perm=getArtistPerm($article_artist_id);
		$article_perm=$row['article_permalink'];
		$artist_name=getArtistName($article_artist_id);
		$article_title=getSongName($row['article_song_id']);
		$article_title=$row['article_title'];
		
		$article_url=$row['article_url'];
		
		//AUDIO
		if ($cat_id == AUDIO_CAT_ID) 
		{ 
			$cat_letter = AUDIO_CAT_LETTER; $cat_letter2 = PROFILE_PIC_CAT; $no_main_img = NO_AUDIO_IMAGE; 
			$the_link = SITEPATH . "audio/$artist_perm/$article_id-$article_perm";
			$article_title = $article_title . ' audio';
			/*
				if (!$article_url){ //if song has no audio uploaded, link to its video
				$article_id = getVideoIdFromSongId($article_id);
				$the_link = SITEPATH . "videos/$artist_perm/$article_id-$article_perm"; 
			}
			*/
		}
		
		//LYRICS
		if ($cat_id == LYRIC_CAT_ID) 
		{ 
			$cat_letter = LYRIC_CAT_LETTER; $cat_letter2 = PROFILE_PIC_CAT; $no_main_img = NO_IMAGE; 
			$the_link = SITEPATH . "lyrics/$artist_perm/$article_id-$article_perm";
			$article_title = $artist_name . " - " . $article_title . ' lyrics';
		}
		
		//DEEJAY MIXES
		if ($cat_id == DEEJAY_MIX_CAT_ID) 
		{ 
			$cat_letter = DEEJAY_MIX_CAT_LETTER; $cat_letter2 = PROFILE_PIC_CAT; $no_main_img = NO_MIX_IMAGE; 
			$the_link = SITEPATH . "dj-mixes/$artist_perm/$article_id-$article_perm";
			$article_title = $artist_name . " - " . $article_title . ' mix';
		}
		
		//VIDEOS
		if ($cat_id == VIDEO_CAT_ID) 
		{
			$video = true;
			$cat_letter = VIDEO_CAT_LETTER; $cat_letter2 = PROFILE_PIC_CAT; $no_main_img = NO_IMAGE; 
			$the_link = SITEPATH . "dj-mixes/$artist_perm/$article_id-$article_perm";
			$article_title = $artist_name . " - " . $article_title;

			$main_img = parse_youtube_url($article_url,'mqthumb');
			//$video_url_id = get_youtubeid($article_url);
			//$new_video_url = get_full_youtube_url($article_url);
			$the_link  = SITEPATH . "videos/$artist_perm/$article_id-$article_perm";
		}	
		
		//LIVESHOWS
		if ($cat_id == LIVESHOW_CAT_ID) 
		{
			$video = true;
			$cat_letter = LIVESHOW_CAT_LETTER; $cat_letter2 = PROFILE_PIC_CAT; $no_main_img = NO_IMAGE; 
			$the_link = SITEPATH . "liveshows/$article_id-$article_perm";

			$main_img = parse_youtube_url($article_url,'full');

		}
		
		//LIVESHOWS
		if ($cat_id == GALLERY_CAT_ID) 
		{
			$cat_letter = GALLERY_CAT_LETTER; $cat_letter2 = PROFILE_PIC_CAT; $no_main_img = NO_IMAGE; 
			$the_link = SITEPATH . "gallery/$article_id-$article_perm";
			$main_img = getmainimg($article_id, $cat_letter);

		}
		
		//LIVESHOWS
		if ($cat_id == SPY_SHOT_CAT_ID) 
		{
			$cat_letter = SPY_SHOT_CAT_LETTER; $cat_letter2 = PROFILE_PIC_CAT; $no_main_img = NO_IMAGE; 
			$the_link = SITEPATH . "spotted/$article_id-$article_perm";
			$main_img = getmainimg($article_id, $cat_letter);

		}
		
		//LIVESHOWS
		if ($cat_id == MODEL_CAT_ID) 
		{
			$cat_letter = MODEL_CAT_LETTER; $cat_letter2 = PROFILE_PIC_CAT; $no_main_img = NO_IMAGE; 
			$the_link = SITEPATH . "models/$article_id-$article_perm";
			$main_img = getmainimg($article_id, $cat_letter);

		}
		
		//IMAGES
		if (!$main_img) { $main_img = getmainimg($article_artist_id, $cat_letter); }
		if (!$main_img) { $main_img = getmainimg($article_artist_id, $cat_letter2); }
		if (!$main_img) { $main_img=$no_main_img;}
		if (!$video){ $main_img = SITEPATH . $main_img; }
		
		//$artist_link = SITEPATH . "artists/$artist_perm";
		
		?>
		
				<li class="theimage">
					<a href="<?=$the_link?>">
						<?php if (!$video) { ?>
                            <img height="204" width="204" alt="<?=$article_title?>" 
                        src="<?=SITEPATH?>includes/image.php/<?=$main_img?>?width=204&height=204&cropratio=1:1&quality=90&image=<?=$main_img?>">
                        <?php } else { ?>
                            <img height="204" width="204" alt="<?=$article_title?>"  src="<?=$main_img?>"/> 
                        <?php } ?>
					</a>
					<h3 class="absolute menu-item-title-text"><a href="<?=$the_link?>"><?=$article_title?></a></h3>
				</li>
		
		<? 
		
	}	
}

function get_youtubeid($url) {

	parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
	return $my_array_of_vars['v'];    
	
}

function get_full_youtube_url($url) {

		$youtube_id = get_youtubeid($url);
		if ($youtube_id) {
			$youtube_embed_url = "http://www.youtube.com/embed/" . $youtube_id . "?rel=0&autoplay=1";
			return $youtube_embed_url;
		} else { return 0; }

}

function parse_youtube_url($url,$return='embed',$width='',$height='',$rel=0){

    $urls = parse_url($url);
    //url is http://youtu.be/xxxx
    if($urls['host'] == 'youtu.be'){
        $id = ltrim($urls['path'],'/');
    }
    //url is http://www.youtube.com/embed/xxxx
    else if(strpos($urls['path'],'embed') == 1){
        $id = end(explode('/',$urls['path']));
    }
     //url is xxxx only
    else if(strpos($url,'/')===false){
        $id = $url;
    }
    else{
        parse_str($urls['query']);
        $id = $v;
        if(!empty($feature)){
            $id = end(explode('v=',$urls['query']));
        }
    }
    //return embed iframe
    if($return == 'embed'){
        return '<iframe width="'.($width?$width:560).'" height="'.($height?$height:349).'" src="http://www.youtube.com/embed/'.$id.'?rel='.$rel.'" frameborder="0" allowfullscreen></iframe>';
    }
    //return normal thumb
    else if($return == 'thumb'){
        return 'http://i1.ytimg.com/vi/'.$id.'/default.jpg';
    }
	//return full image
    else if($return == 'full'){
        return 'http://i1.ytimg.com/vi/'.$id.'/0.jpg';
    }
    //return mqthumb
    else if($return == 'mqthumb'){
        return 'http://i1.ytimg.com/vi/'.$id.'/mqdefault.jpg';
    }
	//return hqthumb
    else if($return == 'hqthumb'){
        return 'http://i1.ytimg.com/vi/'.$id.'/hqdefault.jpg';
    }
	//return maxres
    else if($return == 'maxres'){
        return 'http://i1.ytimg.com/vi/'.$id.'/maxresdefault.jpg';
    }
    // else return id
    else{
        return 'http://i1.ytimg.com/vi/'.$id.'/default.jpg';
    }
}

//get random ids
function getRandomArticleIds($count,$catid,$notid=NULL){
	$the_field = getSearchField();
	//get random articles
	$fullqry="SELECT article_id FROM muz_articles WHERE suspended='n' AND article_category_id=$catid ";
	if ($notid) { $fullqry .= " AND article_id NOT IN ($notid) "; } 
	$fullqry .= " ORDER BY $the_field DESC LIMIT 0,30";
	$fulldNo = mysql_num_rows(mysql_query($fullqry));
	$ids="";
	//get the ids
	$idResult = mysql_query($fullqry);
	$xx=0;
	while(list($field) = mysql_fetch_array($idResult))
	{
		$ids .= mysql_result($idResult,$xx,"article_id").",";
		$xx++;
	}
	$dataArray = removelastcomma($ids);
	//$dataArray = implode(mysql_fetch_array($idResult),',');
	$theids = getrandom($dataArray,$count);		
	return $theids; 
}

function getSearchField() {
	$items = range(0, 5);
	$fields_array = array("article_id","article_title","article_hits","article_published","article_validated","article_author");
	shuffle($items);
	return $fields_array[$items[0]]; //return random field from above list of fields for use in search
}

function sentencecase($data) {
     return ucfirst(strtolower($data));
}

function lowercase($data) {
     return strtolower($data);
}

//set default duration to 6 hrs - 6h
function checkValidDate($date, $duration_type="h", $duration=6)
{
	$timestamp = get_timestamp($date); 
	$diff = time() - $timestamp;
	
	if ($duration_type=="m") { $duration_unit = 60; } //minutes
	if ($duration_type=="h") { $duration_unit = 60*60; } //hours
	if ($duration_type=="d") { $duration_unit = 60*60*24; } //days
			
	//get number of units
	$total_units = $diff / $duration_unit;
	
	//compare units
	if ($total_units <= $duration)
	{
		return true; //duration / date is still valid
	} else {
		return false; //duration/ date is invalid
	}

}

function formatDate($date)
{
	return date('M j, Y',get_timestamp($date));
}

function smartdate($timestamp) {
	$diff = time() - $timestamp;
	if ($diff <= 0) {
		return 'Now';
	}
	else if ($diff < 60) {
		return grammar_date(floor($diff), ' sec(s) ago');
	}
	else if ($diff < 60*60) {
		return grammar_date(floor($diff/60), ' min(s) ago');
	}
	else if ($diff < 60*60*24) {
		return grammar_date(floor($diff/(60*60)), ' hr(s) ago');
	}
	else if ($diff < 60*60*24*3) { //3 days
		return grammar_date(floor($diff/(60*60*24)), ' day(s) ago');
	}
	else {
		return date("M j, Y",$timestamp);
	}
}

function grammar_date($val, $sentence) {

	if ($val > 1) {
		return $val.str_replace('(s)', 's', $sentence);
	} else {
		return $val.str_replace('(s)', '', $sentence);
	}

}

function getSubCategories($id){
	
	$query="SELECT id FROM pt_site_sections WHERE parent_id=$id";
    $result = mysql_query($query);
	$id_array = array();
	while ($row = mysql_fetch_assoc($result)) 
	{
		$id_array[] = $row['id'];
	}
	$the_id_array =  implode(', ', $id_array);  
	return $the_id_array;
	
}

function formatArticleDate($timestamp, $format=""){
	if ($format) {
		$date = date($format, $timestamp);
	} else {
		$date = date( 'jS M Y', $timestamp );	
	}
	return $date;
}

function get_mysql_date($thedate) {
	return date( 'Y-m-d H:i:s', $thedate );
}

function get_timestamp($thedate) {
	return strtotime( $thedate );
}

function get_site_settings($name){
	$qry = "SELECT text FROM site_settings WHERE name='$name'";
	$result = mysql_query($qry);
	$data = mysql_fetch_assoc($result);
	return trim($data['text']);
}

function editNewsletterSubscriber($email, $sub){
	//check if sub already exists. if not, insert
	$numQuery="SELECT * FROM pt_newsletter WHERE newsletter_subscribers='$email'";
	$numResult = mysql_query($numQuery);	
	//sub was checked
	if ($sub) {
		if (mysql_num_rows($numResult)) { 
			$query = "UPDATE pt_newsletter SET newsletter_status='Yes' WHERE newsletter_subscribers='$email'";
			mysql_query($query);
		} else { 
			//insert new record
			$query = "INSERT INTO pt_newsletter(newsletter_subscribers) VALUES ('$email')";
			mysql_query($query);
		}
	} else {
		//disable existing sub if any
		if (mysql_num_rows($numResult)) { 
			$query = "UPDATE pt_newsletter SET newsletter_status='No' WHERE newsletter_subscribers='$email'";
			mysql_query($query);
		} 
	}
	
}

function getFbPageLikesCount($page='Muzikki') 
{ 
    //$pageData = @file_get_contents('https://graph.facebook.com/'.$page."?access_token=$access_token");
    $access_token = get_page_access_token();
    # URL to call
    $url = "https://graph.facebook.com/".$page."?access_token=$access_token";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    # Get the response
    $pageData = curl_exec($curl);
    # Close connection
    curl_close($curl);
    if($pageData) { // if valid json object
        $pageData = json_decode($pageData); // decode json object
        if(isset($pageData->likes)) { // get likes from the json object
           return $pageData->likes;
        }
    } else {
        echo 'page is not a valid FB Page';
    }
}
function getTwitterFollowers($screenName = 'muzikkikenya')
{
    global $twitter_access_token, $twitter_access_token_secret, $twitter_consumer_key, $twitter_consumer_secret;    
    require_once('TwitterAPIExchange.php');
        // this variables can be obtained in http://dev.twitter.com/apps
        // to create the app, follow former tutorial on http://www.codeforest.net/get-twitter-follower-count
        $settings = array(
            'oauth_access_token' => $twitter_access_token,
            'oauth_access_token_secret' => $twitter_access_token_secret,
            'consumer_key' => $twitter_consumer_key,
            'consumer_secret' => $twitter_consumer_secret
        );
        // forming data for request
        $apiUrl = "https://api.twitter.com/1.1/users/show.json";
        $requestMethod = 'GET';
        $getField = '?screen_name=' . $screenName;

        $twitter = new TwitterAPIExchange($settings);
        $response = $twitter->setGetfield($getField)
             ->buildOauth($apiUrl, $requestMethod)
             ->performRequest();

        $followers = json_decode($response);
        $numberOfFollowers = $followers->followers_count;

    return $numberOfFollowers;
}

function generatelink($id, $parent_id) {

		//get parent slug
		$parent_links = getParentLinks($id);
		$page_slug = getPageSlug($id);
		return SITEPATH . $parent_links . $page_slug;

}

function generatelinkid($id, $parent_id) {

		//get parent slug
		$parent_links = getParentLinks($id);
		$page_slug = getPageSlug($id);
		return SITEPATH . $parent_links . $page_slug . "-" . $id;

}

function getPageSlug($id) {

		//get item slug
		$qry = "SELECT page_slug FROM pt_site_sections WHERE id=$id";
		$result=mysql_query($qry);
		$data = mysql_fetch_assoc($result);
		$page_slug = $data['page_slug'];
		return $page_slug;

}

function getParentLinks($id) {

		//get level 1 parent
		$qry = "SELECT parent_id FROM pt_site_sections WHERE id=$id AND parent_id!=0";
		$result=mysql_query($qry);
		$data = mysql_fetch_assoc($result);
		$parent_id = $data['parent_id'];
		$parent_links = getPageSlug($parent_id);
		if ($parent_links) {
			$parent_links .= "/";
		} else {
			$parent_links = "";
		}
		
		if ($parent_links)
		{
			//get level 2 parent, if any
			$qry2 = "SELECT parent_id FROM pt_site_sections WHERE id=$parent_id AND parent_id!=0";
			$result2=mysql_query($qry2);
			$data2 = mysql_fetch_assoc($result2);
			$parent_id2 = $data2['parent_id'];
			$parent_links2 = getPageSlug($parent_id2);
			if ($parent_links2) {
				$parent_links2 .= "/";
			} else {
				$parent_links2 = "";
			}
		}
		
		return $parent_links2 . $parent_links;

}

function getthumbimg($id,$cat) {

		$qry = "SELECT image_thumb_210x210 FROM muz_listing_images WHERE image_category='$cat' AND image_product_id=$id AND approved='y' ORDER BY image_id LIMIT 0,1";

		$result=mysql_query($qry);
		$data = mysql_fetch_assoc($result);
		$image_path = $data['image_thumb_210x210'];
		return $image_path;

}
/*
function getmainimg($id,$cat='site_section',$size='thumb') {
        
		if ($size=='large'){ $field = "image_large"; }
		if ($size=='medium'){ $field = "image_medium"; }
		if ($size=='thumb'){ $field = "image_thumbnail"; }
		
		$qry = "SELECT $field as image_path FROM pt_images WHERE image_section='$cat' AND image_section_id=$id AND status='yes' ORDER BY id LIMIT 0,1";
        $result=mysql_query($qry);
        $data = mysql_fetch_assoc($result);
        $image_path = $data['image_path'];
        return $image_path;
}*/

function reducelength($str,$maxlength) {
	if (strlen($str) > $maxlength) {
		$newstr = substr($str,0,$maxlength-3) . "..."; $short=true;	
	} else {$newstr = $str;}
	return $newstr;
}

function getmainimg($id,$cat) {
        $qry = "SELECT image_path FROM muz_listing_images WHERE image_category='$cat' AND image_product_id=$id AND approved='y' ORDER BY image_id LIMIT 0,1";
        $result=mysql_query($qry);
        $data = mysql_fetch_assoc($result);
        $image_path = $data['image_path'];
        return $image_path;
}
function getmainimgid($id,$cat) {
        $qry = "SELECT image_id FROM muz_listing_images WHERE image_category='$cat' AND image_product_id=$id AND approved='y' ORDER BY image_id LIMIT 0,1";
        $result=mysql_query($qry);
        $data = mysql_fetch_assoc($result);
        return $data['image_id'];
}
function getmaindoc($product_id) {
		$qry = "SELECT path FROM muz_listing_docs WHERE product_id=$product_id AND approved='y' ORDER BY id LIMIT 0,1";
		//echo "ins - $qry<br>";
		$result=mysql_query($qry);
		$data = mysql_fetch_assoc($result);
		$path = $data['path'];
		return $path;
}

function getimageid($id,$cat,$path) {

		$qry = "SELECT image_id FROM muz_listing_images WHERE image_category='$cat' AND image_product_id=$id AND image_path='$path'";
		$result=mysql_query($qry);
		$data = mysql_fetch_assoc($result);
		$image_id = $data['image_id'];
		return $image_id;

}

function removelastcomma($str) {
  
  $startpos = strlen($str) - 1;
  $getstring = substr($str,$startpos);
  if ($getstring == ",") {
      return substr($str,0,$startpos);
  } else {
      return $str;
  }
  
}

function getrandom($dataArray,$number) {

		

		$maxcomment = $number;

		$numbers = explode(",", $dataArray);

		$tipNumber = count($numbers);

		$trio = "";

		

		if ($tipNumber < $maxcomment) {

			$maxcomment = $tipNumber;

		}

									

		for( $i=0; $i<$tipNumber; $i++ ) // get 1 unique comment

		{

			$r = mt_rand( 0, sizeof($numbers)-1 ); // generate random key

			if( isset($trio) ) # var $trio will hold the lucky commentid

			{

				if( in_array($numbers["$r"], $trio) )

				{

					--$i;

				} else {

					$trio[] = $numbers["$r"];

				}

			} else {

				$trio[] = $numbers["$r"];

			}

									

		}

									 

		if ($trio[0]) {

			$totevents .= $trio[0];

		}

									 

		$ids = "";

		

		for($p=0;$p<$maxcomment; $p++) {

			$ids .= $trio[$p] . ",";

		}

		$ids = removelastcomma($ids);

		return $ids;

		 

}

function getNextId($tablename,$field) {
		
		$qry = "SELECT MAX($field) as product_id FROM $tablename";
		$result=mysql_query($qry);
		$product = mysql_fetch_assoc($result);
		$product_id = $product['product_id'];
		$product_id = $product_id + 1;
		return $product_id;

}

function tagCloud(){
        //get tags from db
        $tagqry = "SELECT * FROM search_tags_summary ORDER BY num_times DESC LIMIT 0,8 ";
        $tagresult=mysql_query($tagqry);
        //echo "tagqry - " . $tagqry;exit;
        //get total numer of searches
        $sumqry = "SELECT SUM(num_times) AS thetotal FROM search_tags_summary";
		//echo "sumqry - " . $sumqry;exit;
        $sumresult=mysql_query($sumqry);
        $sumrow = mysql_fetch_assoc($sumresult);
        $search_total = $sumrow['thetotal'];
        //loop thru the results and display
        while ($tagrow = mysql_fetch_assoc($tagresult))
        {
            $search_tag_id = $tagrow['id'];
            $search_tag = $tagrow['search_tag'];
            $num_times = $tagrow['num_times'];
            $tag_ratio = (100/$search_total) * $num_times;
            $tag_ratio = round($tag_ratio,-1);
            $tag_link = SITEPATH.'tags?tag='. $search_tag;
            $the_tag = "<a href='$tag_link' class='cloud-$tag_ratio' title='$search_tag'>$search_tag</a>";
            $search_tags .= $the_tag;
        }
        return $search_tags;
}


function BreadCrumb(){
        $req_url = $_SERVER['REQUEST_URI'];$extd='';
        if(strstr($req_url,'?')){
            $req_url = substr($req_url, 0, stripos($req_url, "?"));
        }
        $bc = explode('/',$req_url);
        // we dont want to show the breadcrumb on the index page - lets filter
        if($bc[1]=='index.php' || $bc[1]==NULL)return false;
        // remove bad requests
        foreach($bc as $key => $value) {
            if($value == "" || $value == " " || is_null($value) || $value == "index.php") {
                unset($bc[$key]);
            }
        }
        $lastone = end($bc);
        $bread = array();
        // line below should be changed to the specific site
        $bread['http://'.$_SERVER["SERVER_NAME"].'/'] = 'Home';
        foreach($bc as $d){
            if($d!=NULL){
                $extd.=$d.'/';
                $bread['http://'.$_SERVER["SERVER_NAME"].'/'.$extd] = $d;
            }
        }	
		
		$j = '<ol class="breadcrumb">';

        foreach($bread as $ahref => $bread_display){
            $bread_final = ucwords(str_replace(array('-','.php', '.html'),array(' ',''),$bread_display));
			if(!($lastone==$bread_display)){
                $j .='<li><a href="'.$ahref.'">'.$bread_final.'</a></li>';
            } else {
                $j .= "<li class='active'>".$bread_final."</li>";
            }     
        }
        return $j.'</ol>';

 }

function validateName($name)

{

	$first = substr($name, 0, 1);
	if ($first == '.' || $first == '-' || $first == '_') {
		return false;
	}

	//if slashes are added remove them since this doesn't go in the db and we don't want names with \
	$name = str_replace('\\', '', $name);
	if (preg_match('!^[a-zA-Z\']+$!', $name))
    	return true;

	return false;

}



function validateUsername($user)
{

	$first = substr($user, 0, 1);
	if ($first == '.' || $first == '-' || $first == '_') {
		return false;
	}

	if (preg_match('!^[a-zA-Z0-9._-]{5,60}$!', $user))
    	return true;
	return false;

}



function validateEmail($email)

{

	if (preg_match("!^[a-zA-Z0-9]+([_\\.-][a-zA-Z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,4}$!", $email))

   		return true;

	return false;

}



//Registration functions

function checkIfUser($user)
{

	$sql = "SELECT user FROM users WHERE user = '" . $user ."' ";
	$res = sqlQuery($sql); if(sqlErrorReturn()) sqlDebug(__FILE__,__LINE__,sqlErrorReturn());
	$num = sqlNumRows($res);

	if ($num > 0)
		return true;
	return false;	

}



function checkIfEmail($email)

{

	if (isset($_SESSION["user"])) {
		$user = $_SESSION["user"];
		$sql = "SELECT * FROM users WHERE email = '" . $email ."' AND user = '" . $user ."'";
	} else {
		$sql = "SELECT * FROM users WHERE email = '" . $email ."' ";
	}

	$res = sqlQuery($sql); if(sqlErrorReturn()) sqlDebug(__FILE__,__LINE__,sqlErrorReturn());

	$num = sqlNumRows($res);

	

	if ($num > 0)

		return true;

	return false;	

}






//last avtive

function lastActive($user)

{

	global $visitor_tracking;

	$current_time = date("Y-m-d H:i:s");
	$ipaddress = ipConvertLong(getenv('REMOTE_ADDR'));

	//check if user is a guest or a logged in user

	//if logged in update the last active time in the users table and if activated the onlineusers table

	//if not logged in update the onlineusers table with correct guest info

	//checks for guest user first then checks if a user is logged in



	if (!empty($visitor_tracking) && $user == 'guest') {

		//guest is viewing check if already listed using their ip address in onlineusers table

		$sql = "SELECT ipaddress FROM onlineusers WHERE user = '" . $user . "' AND ipaddress = " . $ipaddress . "";

		$res = sqlQuery($sql); if(sqlErrorReturn()) sqlDebug(__FILE__,__LINE__,sqlErrorReturn());

		$num = sqlNumRows($res);

		if ($num > 0) {

			//if check showed result then perform an update to the onlineusers table

			$sql = "UPDATE onlineusers SET last_active = '" . $current_time . "', ipaddress = " . $ipaddress . " WHERE user = '" . $user . "' AND ipaddress = " . $ipaddress . "";

			$res = sqlQuery($sql); if(sqlErrorReturn()) sqlDebug(__FILE__,__LINE__,sqlErrorReturn());

		} else {

			//if check failed insert result in to the onlineusers table

			$sql = "INSERT INTO onlineusers (user,last_active,ipaddress) VALUES ('" . $user . "', '" . $current_time . "', " . $ipaddress . ")";

			$res = sqlQuery($sql); if(sqlErrorReturn()) sqlDebug(__FILE__,__LINE__,sqlErrorReturn());

		} 

	} elseif (!empty($visitor_tracking) && $user == $_SESSION["user"]) {

		//user is logged in check if they are listed in onlineusers table

		$sql = "SELECT user FROM onlineusers WHERE user = '" . $user . "'";

		$res = sqlQuery($sql); if(sqlErrorReturn()) sqlDebug(__FILE__,__LINE__,sqlErrorReturn());

		$num = sqlNumRows($res);

		if ($num > 0) {

			//if check showed result then perform the update to the tables users and onlineusers

			$sql = "UPDATE users,onlineusers SET users.last_active = '" . $current_time . "', onlineusers.last_active = '" . $current_time . "' WHERE onlineusers.user = users.user";

			$res = sqlQuery($sql); if(sqlErrorReturn()) sqlDebug(__FILE__,__LINE__,sqlErrorReturn());

		} else {

			//if check failed insert result in the onlineusers table

			$sql = "INSERT INTO onlineusers (user,last_active,ipaddress) VALUES ('" . $user . "', '" . $current_time . "', " . $ipaddress . ")";

			$res = sqlQuery($sql); if(sqlErrorReturn()) sqlDebug(__FILE__,__LINE__,sqlErrorReturn());

		}

	} else {

		//not using the visitor tracking feature so just update the last_active field for the user

		$sql = "UPDATE users SET last_active = '" . $current_time . "' WHERE user = '" . $user . "' ";

	}



	//perform some cleanup actions for the onlineusers table if visitor_tracking is enabled

	if (!empty($visitor_tracking)) {

		//now that we have checked the guest user or logged in user perform some cleanups of old dead userdata

		$sql = "SELECT * FROM onlineusers";

		$res = sqlQuery($sql); if(sqlErrorReturn()) sqlDebug(__FILE__,__LINE__,sqlErrorReturn());

		$num = sqlNumRows($res);



		//print "".__LINE__." $sql, $num, I am $user this is my ip $ipaddress<br />";

		if ($num > 0) {

			while ($a_row = sqlFetchArray($res)) {

				$id = $a_row["id"];

		 		$last_active_time = $a_row["last_active"];

				//print $last_active_time;

				

				//if last active time is less than last active time plus 5 minutes

				$last_active_timestamp = strtotime($last_active_time);

				$current_timestamp = strtotime(date("Y-m-d H:i:s"));

				//print "<br />$last_active_timestamp";

				//print "<br />$current_timestamp";



				$time_diff = ($current_timestamp-$last_active_timestamp);

				//print "<br />$time_diff";



				$time_diff_minutes = date("i",$time_diff);

				//print "<br /> $time_diff_minutes<br />";

				

				//delete the row from onlineusers if the current time is greater than last_active_time by x minutes

				if ($time_diff_minutes >= 5) {

					$sql = "DELETE FROM onlineusers WHERE id = '" . $id . "'";

					$del = sqlQuery($sql); if(sqlErrorReturn()) sqlDebug(__FILE__,__LINE__,sqlErrorReturn());

					//print "it worked there is a difference of $time_diff_minutes minutes<br />";

				} else {

					//print "it did not work there is only a difference of $time_diff_minutes minutes<br />";

				}

			}

		}

	}

}

?>
