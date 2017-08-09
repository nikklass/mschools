<?php
if (!isset($_SESSION)) session_start();
error_reporting(1);
@ini_set(‘display_errors’, 1);

//print_r($_SESSION);
//$record_visitor='n';

$abs_sitepath = '/';

$abs_sitepath = 'http://showbiz.co.ke/';
$sitepath = "http://showbiz.co.ke/huduma/";
$cdn_sitepath = "http://showbiz.co.ke/huduma/";


//$abs_sitepath = '/huduma/';
//$sitepath = "http://localhost/huduma/";
//$cdn_sitepath = "http://localhost/huduma/";


//categories
$profile_pic_cat = 'p';
$user_profile_pic_cat = 'u';
$default_user_img = "images/no_image.jpg";

define("PROFILE_PIC_CAT_ID", 1);


//FACEBOOK FAN PAGE ID
$fb_page_id = '381982811824299';
$recaptcha_privatekey = "6LcjevUSAAAAADsEHS53oFrK2xWIIbjdtpKKm-pb";

$session_id = $_SESSION['ID'];
$logged_user_id = $_SESSION['SESS_USER_ID'];
$logged_user = $_SESSION['SESS_USER_NAME'];
$logged_email = $_SESSION['SESS_EMAIL_ADDR'];
$logged_fname = $_SESSION['SESS_FIRST_NAME'];
$logged_lname = $_SESSION['SESS_LAST_NAME'];
$logged_full_names = $logged_fname . ' ' . $logged_lname;
$logged_user_type = $_SESSION['SESS_USER_TYPE'];
$fb_login = $_SESSION['SESS_FB_LOGIN'];
$fb_user_id = $_SESSION['oauth_id'];
$my_user_name = $_SESSION['SESS_MY_USER_NAME'];
//$logged_admin_user = false;
//if (($logged_user) && ($logged_user_type == 5)){ $logged_admin_user = true; }
$logged_admin_user = true;
//$logged_admin_user = true;
//$logged_user_id = 1;
if (!$logged_user_id) { $logged_user_id = 30; }

$page_url = $_SERVER['REQUEST_URI'];

$no_male_user_image = "images/no_male_user_image.jpg";
$no_image = "images/no_image.jpg";
$no_mix_image = "images/mix_no_image.png";
$no_audio_image = "images/no_audio.png";
$no_ringtone_image = "images/no_ringtone.png";

$song_add_qry = " AND article_id NOT IN (SELECT article_id FROM muz_articles WHERE article_category_id=$song_cat AND article_url='')";

//define variables
define("DELETED", 50);
define("ACTIVE", 1);
define("SUSPENDED", 99);
define("SKIPPED", 98);

//configs
$added_keywords = ",showbiz,music,kenya,videos,lyrics,ringtones";
$added_site_title = "Showbiz - Your ultimate guide to entertainment news";


$timenow = mysql_date(time());

//site configs
$no_reply_mail = get_site_settings('no_reply_mail');
$company_name_title = get_site_settings('company_name_title');
$company_website_address = get_site_settings('company_website_address');
$adminmail = get_site_settings('send_email');
$ccmail = get_site_settings('contact_email');
$email_msg_color = get_site_settings('email_msg_color');
$og_sitename = get_site_settings('company_website_name');
$og_sitedesc = get_site_settings('company_website_desc');
$outer_bg_color = get_site_settings('mail_outer_bg_color');
$msg_title_bg_color = get_site_settings('mail_msg_title_bg_color');
$msg_title_font_color = get_site_settings('mail_msg_title_font_color');
$border_bottom_color = get_site_settings('mail_border_bottom_color');
$border_right_color = get_site_settings('mail_border_right_color');
$border_left_color = get_site_settings('mail_border_left_color');
$msg_bg_color = get_site_settings('mail_msg_bg_color');
$msg_font_color = get_site_settings('mail_msg_font_color');
$msg_footer_color = get_site_settings('mail_msg_footer_color');

$fbPageID = get_site_settings('facebook_page_id'); //facebook page id for muzikki
$fb_page_id = get_site_settings('facebook_page_id');
$fb_app_id = get_site_settings('facebook_app_id');
$fb_app_secret = get_site_settings('facebook_app_secret');

$twitter_access_token = get_site_settings('twitter_access_token');
$twitter_access_token_secret = get_site_settings('twitter_access_token_secret');
$twitter_consumer_key = get_site_settings('twitter_consumer_key');
$twitter_consumer_secret = get_site_settings('twitter_consumer_secret');
//end site configs


//watermark font & text settings
$water_mark_text = "muzikki.com";
$font_path = "../fonts/perpetua.ttf";
$font_size = 22;

function facebook_count($url){
 
    // Query in FQL
    $fql  = "SELECT share_count, like_count, comment_count ";
    $fql .= " FROM link_stat WHERE url = '$url'";
 
    $fqlURL = "https://api.facebook.com/method/fql.query?format=json&query=" . urlencode($fql);
 
    // Facebook Response is in JSON
    $response = file_get_contents($fqlURL);
    return json_decode($response);
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
function getFirstArtistIds($count,$catid) 
	{
	global $sitepath,$profile_pic_cat,$male_no_image,$artist_cat,$producer_cat,$deejay_cat,$artist_type,$deejay_type,$audio_producer_type,$video_producer_type;
	if ($catid==$artist_cat) {$the_type=$artist_type;$addtype="(artist_type='$artist_type')";}
	if ($catid==$deejay_cat) {$the_type=$deejay_type;$addtype="(artist_type='$deejay_type')";}
	if ($catid==$producer_cat) {$the_type=$audio_producer_type;$addtype="((artist_type='$audio_producer_type')||(artist_type='$video_producer_type'))";}
	$the_field = getArtistField();
	//get random artists
	$fullqry="SELECT artist_id FROM muz_artists WHERE suspended='n' AND $addtype AND artist_id = (SELECT image_product_id FROM muz_listing_images WHERE image_product_id=artist_id and approved='y' AND  image_category='$profile_pic_cat' LIMIT 0,1) ORDER BY $the_field DESC LIMIT 0,30";
	//echo "the_read_qry -$fullqry"; exit;
	$fulldNo = mysql_num_rows(mysql_query($fullqry));
	$ids="";
	//get the ids
	$idResult = mysql_query($fullqry);
	$xx=0;
	while(list($field) = mysql_fetch_array($idResult))
	{
		$ids .= mysql_result($idResult,$xx,"artist_id").",";
		$xx++;
	}
	$dataArray = removelastcomma($ids);
	$theids = getrandom($dataArray,$count);		
	return $theids; 
}
function getRandomArticleIds($count,$catid){
	$the_field = getSearchField();
	//get random articles
	$fullqry="SELECT article_id FROM muz_articles WHERE suspended='n' AND article_category_id=$catid ORDER BY $the_field DESC LIMIT 0,30";
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
function get_page_access_token(){
    $qry = "SELECT facebook_page_access_token FROM muz_members WHERE member_id=4";
    $data = mysql_fetch_assoc(mysql_query($qry));
    $fb_token = $data['facebook_page_access_token'];
    return $fb_token;             
}
function save_page_access_token($token){
    $qry = "UPDATE muz_members SET facebook_page_access_token='$token' WHERE member_id=4";
    mysql_query($qry);
}
function extend_fb_access_token($access_token){
    $extended_token = file_get_contents("https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=".APP_ID."&client_secret=".APP_SECRET."&fb_exchange_token=$access_token");
    return $extended_token;
}
function access_token_expired($uid,$provider){
    $err_flag = 0;    
    $q = "SELECT fb_token_date, facebook_oauth_token FROM muz_members WHERE oauth_uid = '$uid' and oauth_provider = '$provider'";
        //echo "q  - $q - $username<br>";
        //$query = mysql_query($q) or die(mysql_error());
        $result = mysql_query($q);
        $row = mysql_fetch_assoc($result);
        //check if last token date is past 2 months (60 days)
        $fb_token = $row['facebook_oauth_token'];
        if (!$fb_token) {$err_flag = 1;}
        
        if (!$err_flag) {
            // Attempt to query the graph: check for expired tokens due to changed password,User de-authorizes your app, or expired token
              $graph_url = "https://graph.facebook.com/me?"
                . "access_token=$token";
              $response = curl_get_file_contents($graph_url);
              $decoded_response = json_decode($response);   
              //Check for errors 
              if ($decoded_response->error) {
              // check to see if this is an oAuth error:
                $err_flag = 1; 
                if ($decoded_response->error->type== "OAuthException") {
                }
                else {
                }
              }
        } 
    if ($err_flag){ return true; } else { return false; }
}
function rev_fb_date($thedate) {    // convert this 06/11/2009
    $ndate = explode("/",$thedate);
    return $ndate[2]."-".$ndate[0]."-".$ndate[1];
}
function get_fb_likes($url)
{
  $query = "select total_count,like_count,comment_count,share_count,click_count from link_stat where url='{$url}'";
   $call = "https://api.facebook.com/method/fql.query?query=" . rawurlencode($query) . "&format=json";

  $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $call);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   $output = curl_exec($ch);
   curl_close($ch);
   return json_decode($output);
}
function fblikes($pageID) {
    $pagelikes = json_decode(file_get_contents('http://graph.facebook.com/' . $pageID));
    return $pagelikes->likes;
}
/*Format facebook count numbers nicely e.g. 20,000,000 show as 20m*/
function nice_number($n) {
    // first strip any formatting;
    $n = (0+str_replace(",","",$n));

    // is this a number?
    if(!is_numeric($n)) return false;

    // now filter it;
    if($n>1000000000000) return round(($n/1000000000000),2).' trillion';
    else if($n>1000000000) return round(($n/1000000000),2).' billion';
    else if($n>1000000) return round(($n/1000000),2).' million';
    else if($n>1000) return round(($n/1000),2).' thousand';

    return number_format($n);
}
function getCatLetter($cat){
    $numQuery="SELECT category_char FROM muz_categories WHERE category_id=$cat";
    $numResult = mysql_query($numQuery);
    $row = mysql_fetch_array($numResult); 
    return $row["category_char"];
}
function getarticlecat($article_id){
    $numQuery="SELECT article_category_id FROM muz_articles WHERE article_id=$article_id";
    $numResult = mysql_query($numQuery);
    $row = mysql_fetch_array($numResult); 
    return $row["article_category_id"];
}
function checkSavedItem($thetype,$article_id,$user_id){
    $numQuery="SELECT id FROM saved_items WHERE item_id=$article_id AND section='$thetype' AND user_id=$user_id";
    $numResult = mysql_query($numQuery);
    $row = mysql_fetch_array($numResult); 
    if($row["id"]){ return true; } else { return false; }
}
function checkLikedItem($type,$id,$user_id){
    $numQuery="SELECT * FROM likes WHERE item_id=$id AND item_section='$type' AND user_id=$user_id";
    $numResult = mysql_query($numQuery);
    if(mysql_num_rows($numResult)){ return true; } else { return false; }
}
function checkLikeCount($type,$id){
	if ($type=='article') { $qry="SELECT likes FROM muz_articles WHERE article_id=$id"; }
	if ($type=='artist') { $qry="SELECT likes FROM muz_artists WHERE artist_id=$id"; }
	if ($type=='user') { $qry="SELECT likes FROM muz_members WHERE member_id=$id"; }
    $data = mysql_fetch_array(mysql_query($qry));
	$num = $data['likes'];
	if (!$num){ $num=0; }
	return $num;
}
function get_site_settings($name){
	$qry = "SELECT text FROM site_settings WHERE name='$name'";
	$result = mysql_query($qry);
	$data = mysql_fetch_assoc($result);
	return trim($data['text']);
}
function get_db_access_token($userid){
    $qry="SELECT facebook_oauth_token FROM muz_members WHERE oauth_uid=$userid ";
	$result = mysql_query($qry);
	$row = mysql_fetch_assoc($result);
	$token =  $row['facebook_oauth_token']; 
    return $token;
}
function get_short_url($url){
    $bitly = file_get_contents("http://api.bit.ly/v3/shorten?login=o_514to1ebdp&apiKey=R_4c669f5e96ff3bcf4f932512aef7949e&longUrl=$url%2F&format=txt");   
    return $bitly;
}
function getCategoryCharFromArticleId($article_id){
    $catsql = "SELECT category_char FROM muz_articles a JOIN muz_categories c ON a.article_category_id=c.category_id WHERE article_id=$article_id"; //get category char
    $data = mysql_fetch_array(mysql_query($catsql));
    return $data['category_char'];
}
function checkIfPermExists($article_id){
    $sql = "SELECT article_permalink FROM muz_articles WHERE article_id=$article_id"; //get category char
    $data = mysql_fetch_array(mysql_query($sql));
    if ($data['article_permalink']) { return true; } else { return false; }
}
function removeImage($image_id){
	$qry="SELECT * FROM muz_listing_images WHERE image_id=$image_id";
	$result = mysql_query($qry);
	$row = mysql_fetch_array ($result);
	$img_path = $row["image_path"];
	$img_thumb = $row["image_thumb_210x210"];
	unlink("../".$img_path);
	if ($img_thumb) { unlink("../".$img_thumb); }
}
function deleteImage($image_id){
	$qry="SELECT * FROM muz_listing_images WHERE image_id=$image_id";
	$result = mysql_query($qry);
	$row = mysql_fetch_array ( $result );
	$img_path = $row["image_path "];
	$img_thumb = $row["image_thumb_210x210 "];
	unlink("../".$img_path);
	unlink("../".$img_thumb);
	//delete record
	$dqry="DELETE FROM muz_listing_images WHERE image_id=$image_id";
	mysql_query($dqry);
}
function generate_mailkey(){
    // Generate a random key 
    $rseed=date("U")%1000000;
    srand($rseed);
    $mailkey=md5(rand(10000,10000000));
    return $mailkey;
}
function subscribe($email,$key){
    //check if email is already subscribed
    $sqry="SELECT subscriber_email FROM muz_subscribers WHERE subscriber_email='$email'";
    $sresult = mysql_query($sqry);
    if (mysql_num_rows($sresult)){ 
        return false; 
    } else {
        mysql_query("INSERT INTO muz_subscribers(subscriber_email,subscriber_published,subscriber_mailkey) VALUES ('$email',NOW(),'$key')");
        $mailmsgtitle = "Muzikki Subscription";
        send_subscribe_email($email,$email,$mailmsgtitle,$key);
        return true; 
    }
}
function get_lyrics_text($song_id){
    //lyrics query
    global $lyric_cat;
    $qry="SELECT article_text FROM muz_articles WHERE  suspended='n' AND article_validated='y' AND article_category_id=$lyric_cat AND article_song_id=$song_id";
    //echo $qry;
    $result=mysql_query($qry);
    $articlez = mysql_fetch_assoc($result);
    return html_entity_decode($articlez['article_text']);
}
function get_attached_file_icon($db_file_type){
    global $pdf_file, $excel_file, $word_file;
    if ($db_file_type=='pdf'){ $this_file = $pdf_file; }
    if ($db_file_type=='excel'){ $this_file = $excel_file; }
    if ($db_file_type=='word'){ $this_file = $word_file; }
    return $this_file;
}
function get_subscriber_name($sub_email){
    $qry = "SELECT names FROM users WHERE email='$sub_email'";
    $result=mysql_query($qry);
    $row = mysql_fetch_assoc($result);
    $names =  $row['names'];
    if (!$names){ $names = $sub_email; }
    return $names;
}
function get_indicator_price($price,$vat){
    //add 21% vat to price
    $full_price = $price + ($price * $vat);
    return $full_price;
}
function gethomepiccatname($id){
    $qry = "SELECT name FROM pic_categories WHERE id=$id";
    $result=mysql_query($qry);
    $row = mysql_fetch_assoc($result);
    $name =  $row['name'];
    return $name;
}

function send_confirm_email($recipient_names,$recipient_email,$key){
    global $sitepath, $no_reply_mail,$cdn_sitepath,$company_name_title,$company_website_address;
	global $outer_bg_color, $msg_title_bg_color, $msg_title_font_color, $border_bottom_color, $border_right_color, $border_left_color;
	global $msg_bg_color, $msg_font_color, $msg_footer_color;
    $sent_date = date('d M Y', time());
    $mailmsgtitle = "$company_name_title Registration Confirmation";
    $mailmsg = '<html><head><title>'.$company_name_title.'</title></head><body><div dir="ltr"><table width="98%" border="0" cellspacing="0" cellpadding="40"><tr><td bgcolor="'.$outer_bg_color.'" width="100%" style="font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;"><table cellpadding="0" cellspacing="0" border="0" width="620"><tr><td style="background:'.$msg_title_bg_color.';color:'.$msg_title_font_color.';font-weight:bold;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;vertical-align:middle;padding:4px 8px;font-size:16px;letter-spacing:-0.03em;text-align:left;">'.$company_name_title.' - Complete Registration</td><td style="background:'.$msg_title_bg_color.';color:'.$msg_title_font_color.';font-weight:bold;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;vertical-align:middle;padding:4px 8px;font-size:11px;text-align:right;"></td></tr><tr><td colspan="2" style="background-color:'.$msg_bg_color.';border-bottom:1px solid '.$border_bottom_color.';border-left:1px solid '.$border_left_color.';border-right:1px solid '.$border_right_color.';padding:15px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;" valign="top"><table width="100%" cellpadding="0" cellspacing="0"><tr><td width="470" style="font-size:12px;" valign="top" align="left"><div style="margin-bottom:15px;font-size:12px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;">Hello '.$recipient_names.',</div><div style="margin-bottom:15px;"></div><div style="margin-bottom:0;"><div><div style="margin-bottom:10px;color:'.$msg_font_color.';">'.$registration_confirm_text.'<br><br> <a href="'.$sitepath.'registration-confirm.html?key='.$key.'">'.$sitepath.'registration-confirm.html?key='.$key.'</a></div></div></div></td></tr><tr><td width="470" style="font-size:12px;" valign="top" align="left"><div style="margin-top:25px;font-size:12px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;">Regards,<br>'.$company_name_title.' Team</div></td></tr></table></td></tr><tr><td colspan="2" style="color:#999999;padding:10px;font-size:12px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;">Didn&#039;t register on '.$company_name_title.'? <a rel="nofollow" target="_blank" href="'.$sitepath.'registration_confirm_report.html?key'.$key.'.html&amp;report=1" style="color:'.$msg_footer_color.';text-decoration:none;">/<span class="yshortcuts">Please let us know.</span></a> </td></tr></table></td></tr></table></div></body></html>';
    $sender_names = $company_name_title;
    $sender_email = $no_reply_mail;
    send_message($recipient_names,$recipient_email,$sender_names,$sender_email,$mailmsgtitle,$mailmsg);
}

function send_welcome_email($recipient_names,$recipient_email){
	global $sitepath, $no_reply_mail,$company_name_title,$company_website_address;
	global $outer_bg_color, $msg_title_bg_color, $msg_title_font_color, $border_bottom_color, $border_right_color, $border_left_color;
	global $msg_bg_color, $msg_font_color, $msg_footer_color;
	$mailmsgtitle = "Welcome to $company_name_title";    	
	$mailmsg = '<html><head><title>'.$company_name_title.'</title></head><body><div dir="ltr"><table width="98%" border="0" cellspacing="0" cellpadding="40"><tr><td bgcolor="'.$outer_bg_color.'" width="100%" style="font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;"><table cellpadding="0" cellspacing="0" border="0" width="620"><tr><td style="background:'.$msg_title_bg_color.';color:'.$msg_title_font_color.';font-weight:bold;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;vertical-align:middle;padding:4px 8px;font-size:16px;letter-spacing:-0.03em;text-align:left;">'.$company_name_title.' - Complete Registration</td><td style="background:'.$msg_title_bg_color.';color:'.$msg_title_font_color.';font-weight:bold;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;vertical-align:middle;padding:4px 8px;font-size:11px;text-align:right;"></td></tr><tr><td colspan="2" style="background-color:'.$msg_bg_color.';border-bottom:1px solid '.$border_bottom_color.';border-left:1px solid '.$border_left_color.';border-right:1px solid '.$border_right_color.';padding:15px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;" valign="top"><table width="100%" cellpadding="0" cellspacing="0"><tr><td width="470" style="font-size:12px;" valign="top" align="left"><div style="margin-bottom:15px;font-size:12px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;">Hello '.$recipient_names.',</div><div style="margin-bottom:15px;"></div><div style="margin-bottom:0;"><div><div style="margin-bottom:10px;color:'.$msg_font_color.';">Hello '.$recipient_names.',</div><div style="margin-bottom:15px;"></div><div style="margin-bottom:0;"><div><div style="margin-bottom:10px;">Thank you for taking time to register on our website '.$company_website_address.' on '.$sent_date.'.<br><br>You can now login and proceed to enjoy content from our website.<br><br> <a href="'.$sitepath.'registration-confirm.html?key='.$key.'">'.$sitepath.'registration-confirm.html?key='.$key.'</a></div></div></div></td></tr><tr><td width="470" style="font-size:12px;" valign="top" align="left"><div style="margin-top:25px;font-size:12px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;">Regards,<br>'.$company_name_title.' Team</div></td></tr></table></td></tr><tr><td colspan="2" style="color:#999999;padding:10px;font-size:12px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;"></td></tr></table></td></tr></table></div></body></html>';
	//send email
	$sender_names = $company_name_title;
	$sender_email = $no_reply_mail;
	if (send_message($recipient_names,$recipient_email,$sender_names,$sender_email,$mailmsgtitle,$mailmsg)){
		//update members table
		//$qry = "UPDATE users SET welcome_email_sent=1 WHERE email='$recipient_email' ";
		//mysql_query($qry);
		return 1;
	}
}
function message_template($recipient_names, $message, $html_title){
	global $sitepath, $no_reply_mail,$company_name_title,$company_website_address;
	global $outer_bg_color, $msg_title_bg_color, $msg_title_font_color, $border_bottom_color, $border_right_color, $border_left_color;
	global $msg_bg_color, $msg_font_color, $msg_footer_color;
	$mailmsg = '<html><head><title>'.$company_name_title.'</title></head><body><div dir="ltr"><table width="98%" border="0" cellspacing="0" cellpadding="40"><tr><td bgcolor="'.$outer_bg_color.'" width="100%" style="font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;"><table cellpadding="0" cellspacing="0" border="0" width="620"><tr><td style="background:'.$msg_title_bg_color.';color:'.$msg_title_font_color.';font-weight:bold;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;vertical-align:middle;padding:4px 8px;font-size:16px;letter-spacing:-0.03em;text-align:left;">'.$company_name_title.' - '.$html_title.'</td><td style="background:'.$msg_title_bg_color.';color:'.$msg_title_font_color.';font-weight:bold;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;vertical-align:middle;padding:4px 8px;font-size:11px;text-align:right;"></td></tr><tr><td colspan="2" style="background-color:'.$msg_bg_color.';border-bottom:1px solid '.$border_bottom_color.';border-left:1px solid '.$border_left_color.';border-right:1px solid '.$border_right_color.';padding:15px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;" valign="top"><table width="100%" cellpadding="0" cellspacing="0"><tr><td width="470" style="font-size:12px;" valign="top" align="left"><div style="margin-bottom:15px;font-size:12px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;">Hello '.$recipient_names.',</div><div style="margin-bottom:0;"><div><div style="margin-bottom:0;"><div><div style="margin-bottom:10px;color:'.$msg_font_color.';">'.$message.'</div></div></div></td></tr><tr><td width="470" style="font-size:12px;" valign="top" align="left"><div style="margin-top:25px;font-size:12px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;">Regards,<br>'.$company_name_title.' Team</div></td></tr></table></td></tr><tr><td colspan="2" style="color:#999999;padding:10px;font-size:12px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;"></td></tr></table></td></tr></table></div></body></html>';
	return $mailmsg;
}
	
function send_subscribe_email($recipient_names,$sub_email,$sent_title,$key){
    global $no_reply_mail,$sitepath, $company_website_address;
	global $outer_bg_color, $msg_title_bg_color, $msg_title_font_color, $border_bottom_color, $border_right_color, $border_left_color;
	global $msg_bg_color, $msg_font_color, $msg_footer_color;
    $subscribe_path = $sitepath.'subscription-confirm.html?key='.$key;
	$mailmsgtitle = "$company_name_title Subscription";    	
	$mailmsg = '<html><head><title>'.$company_name_title.'</title></head><body><div dir="ltr"><table width="98%" border="0" cellspacing="0" cellpadding="40"><tr><td bgcolor="'.$outer_bg_color.'" width="100%" style="font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;"><table cellpadding="0" cellspacing="0" border="0" width="620"><tr><td style="background:'.$msg_title_bg_color.';color:'.$msg_title_font_color.';font-weight:bold;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;vertical-align:middle;padding:4px 8px;font-size:16px;letter-spacing:-0.03em;text-align:left;">'.$company_name_title.' - Subscription Confirmation</td><td style="background:'.$msg_title_bg_color.';color:'.$msg_title_font_color.';font-weight:bold;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;vertical-align:middle;padding:4px 8px;font-size:11px;text-align:right;"></td></tr><tr><td colspan="2" style="background-color:'.$msg_bg_color.';border-bottom:1px solid '.$border_bottom_color.';border-left:1px solid '.$border_left_color.';border-right:1px solid '.$border_right_color.';padding:15px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;" valign="top"><table width="100%" cellpadding="0" cellspacing="0"><tr><td width="470" style="font-size:12px;" valign="top" align="left"><div style="margin-bottom:15px;font-size:12px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;">Hello '.$recipient_names.',</div><div style="margin-bottom:15px;"></div><div style="margin-bottom:0;"><div><div style="margin-bottom:10px;color:'.$msg_font_color.';">Hello '.$recipient_names.',</div><div style="margin-bottom:15px;"></div><div style="margin-bottom:0;"><div><div style="margin-bottom:10px;">This message is sent to you because you subscribed on our website '.$company_website_address.'.<br><br>If you did not subscribe on our website, you can ignore this mail or click below to notify us.<br><br>To complete the subscription process, click the following link or copy the link to your browser url: <br><br> <a href="'.$subscribe_path.'?key='.$key.'">'.$subscribe_path.'?key='.$key.'</a></div></div></div></td></tr><tr><td width="470" style="font-size:12px;" valign="top" align="left"><div style="margin-top:25px;font-size:12px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;">Regards,<br>'.$company_name_title.' Team</div></td></tr></table></td></tr><tr><td colspan="2" style="color:#999999;padding:10px;font-size:12px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;"></td></tr></table></td></tr></table></div></body></html>';
	//send email
	$sender_names = $company_name_title;
	$sender_email = $no_reply_mail;
	if (send_message($recipient_names,$recipient_email,$sender_names,$sender_email,$mailmsgtitle,$mailmsg)){
		return 1;
	}
}
function send_unsubscribe_email($sub_name,$sub_email,$sent_title,$key){
    global $no_reply_mail,$sitepath;
    $unsubscribe_path = $sitepath.'unsubscribe/key/'.$key.'.html';
    $mailmsg = '<html><head><title>ICTPunt</title></head><body><div dir="ltr"><table width="98%" border="0" cellspacing="0" cellpadding="40"><tr><td bgcolor="#f7f7f7" width="100%" style="font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;"><table cellpadding="0" cellspacing="0" border="0" width="620"><tr><td style="background:#21b7c6;color:#FFFFFF;font-weight:bold;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;vertical-align:middle;padding:4px 8px;font-size:16px;letter-spacing:-0.03em;text-align:left;">ICTPunt - Unsubscribe Confirmation</td><td style="background:#21b7c6;color:#FFFFFF;font-weight:bold;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;vertical-align:middle;padding:4px 8px;font-size:11px;text-align:right;"></td></tr><tr><td colspan="2" style="background-color:#FFFFFF;border-bottom:1px solid #7A1D0C;border-left:1px solid #CCCCCC;border-right:1px solid #CCCCCC;padding:15px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;" valign="top"><table width="100%" cellpadding="0" cellspacing="0"><tr><td width="470" style="font-size:12px;" valign="top" align="left"><div style="margin-bottom:15px;font-size:12px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;">Hello '.$sub_name.',</div><div style="margin-bottom:15px;"></div><div style="margin-bottom:0;"><div><div style="margin-bottom:10px;">'.UNSUBSCRIBE_USER_MESSAGE.'<br><br> <a href="'.$unsubscribe_path.'">'.$unsubscribe_path.'</a></div></div></div></td></tr><tr><td width="470" style="font-size:12px;" valign="top" align="left"><div style="margin-top:25px;font-size:12px;font-family:LucidaGrande, tahoma, verdana, arial, sans-serif;">Regards,<br>ICTPunt Team</div></td></tr></table></td></tr></table></td></tr></table></div></body></html>';
    $sender_names = "ICTpunt";
    $sender_email = $no_reply_mail;
    //send_message($recipient_names,$recipient_email,$sender_names,$sender_email,$mailmsgtitle,$mailmsg)
    if (send_message($sub_name,$sub_email,$sender_names,$sender_email,$sent_title,$mailmsg)){return 1;}else{return 0;}
}

function send_message($recipient_names,$recipient_email,$sender_names,$sender_email,$mailmsgtitle,$mailmsg,$attach=NULL){
    //attachment section
    if ($attach){
        $filename = extract_filename_from_url($attach);
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= "To: $recipient_names <$recipient_email> \r\n";
        $headers .= "From: $sender_names <$sender_email> \r\n";
        $random_hash = md5(date('r', time()));
        $headers .= "\r\nContent-Type: multipart/mixed; 
        boundary=\"PHP-mixed-".$random_hash."\"";
        // Set your file path here
        $attachment = chunk_split(base64_encode(file_get_contents($attach))); 
        //define the body of the message.
        $mailmsg = "--PHP-mixed-$random_hash\r\n"."Content-Type: multipart/alternative; 
        boundary=\"PHP-alt-$random_hash\"\r\n\r\n";
        $mailmsg .= "--PHP-alt-$random_hash\r\n"."Content-Type: text/html;charset=\"iso-8859-1\"\r\n"."Content-Transfer-Encoding: 7bit\r\n\r\n";
        //Insert the html message.
        $mailmsg .= $mymailmsg;
        $mailmsg .="\r\n\r\n--PHP-alt-$random_hash--\r\n\r\n";
        //include attachment
        $mailmsg .= "--PHP-mixed-$random_hash\r\n"."Content-Type: application/zip; 
        name=\"$filename\"\r\n"."Content-Transfer-Encoding: 
        base64\r\n"."Content-Disposition: attachment\r\n\r\n";
        $mailmsg .= $attachment;
        $mailmsg .= "/r/n--PHP-mixed-$random_hash--";
    } else {
        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        // Additional headers
        $headers .= "To: $recipient_names <$recipient_email> \r\n";
        $headers .= "From: $sender_names <$sender_email> \r\n";
    }
    if (mail($recipient_email,$mailmsgtitle,$mailmsg,$headers)){ return 1; } else { return 0; }
}
/*function extract_filename_from_url($url){
        $lastslash = strrpos($url,"/");
        //find last pos of slash in string, filename will start from here plus 1
        $startfilename = $lastslash+1;
        $strlength = strlen($url);
        $filename_length = $strlength - $startfilename;
        //extract filename
        return substr($url,$startfilename,$filename_length);
}*/
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


function is_email_sent($email){
    $qry="SELECT welcome_email FROM muz_members WHERE email='$email'";
    $result=mysql_query($qry);
    $data = mysql_fetch_assoc($result);
    $welcome_email = $data['welcome_email'];
    if ($welcome_email=='y') { return true; } else { return false; }
}

function search_engine_query_string($url = false) {



    if(!$url) {

        $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;

    }

    if($url == false) {

        return '';

    }



    $parts = parse_url($url);

    parse_str($parts['query'], $query);



    $search_engines = array(

        'bing' => 'q',

        'google' => 'q',

        'yahoo' => 'p'

    );



    preg_match('/(' . implode('|', array_keys($search_engines)) . ')\./', $parts['host'], $matches);



    return isset($matches[1]) && isset($query[$search_engines[$matches[1]]]) ? $query[$search_engines[$matches[1]]] : '';



}

function getFbLikes($url){

	// build the facebook query

	$fburl = "http://api.facebook.com/method/fql.query?query=select%20like_count%20from%20link_stat%20where%20url='$url'&format=atom";

	// grab the atom dump via facebook api url call above

	/*$ch = curl_init($fburl); // url for page

	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

	$atom_data = curl_exec($ch);*/

	// it returns something like this:

	/* <fql_query_response xmlns="http://api.facebook.com/1.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" list="true">

	  <link_stat>

	  <like_count>9</like_count>

	  </link_stat>

	</fql_query_response> */

	// grab the like count out, i hate dom parsing, so just use regex:

	 

	/*preg_match('#like_count>(\d+)<#',$atom_data,$matches);

	$like_count = $matches[1];

	echo "The URL $url has $like_count likes on facebook";

	return*/

	 

	// OPTION 2 >>> keeping it to a 1 liner:

	$data = json_decode(file_get_contents("http://api.facebook.com/method/fql.query?query=select%20like_count%20from%20link_stat%20where%20url='$url'&format=json"));

	//echo "The URL $url has " . $data[0]->like_count . " likes on facebook";

	$the_count = $data[0]->like_count;

	return $the_count;

 

}

function findIpAdress() {

	$ip = $_SERVER['REMOTE_ADDR'];

	return $ip;

}

function findBrowser() {

	$bs = $_SERVER['HTTP_USER_AGENT'];

	return $bs;

}

function findReferer() {

	$ref = $_SERVER['HTTP_REFERER'];

	return $ref;

}

function getSearchField() {
	$items = range(0, 5);
	$fields_array = array("article_id","article_title","article_hits","article_published","article_validated","article_author");
	shuffle($items);
	return $fields_array[$items[0]]; //return random field from above list of fields for use in search
}

function getArtistField() {
	$items = range(0, 5);
	$fields_array = array("artist_id","artist_name","artist_published","artist_hits","artist_validated","artist_username");
	shuffle($items);
	return $fields_array[$items[0]]; //return random field from above list of fields for use in search
}

function getProfilePictureMaxUrl($facebook,$uid,$userAccessToken) {

    $params = array('access_token' => $userAccessToken);

    $r = $facebook->api('/'.$uid.'/albums',$params);



    foreach ($r['data'] as $album) {

        if ($album['type'] == 'profile') {



            //retrieve information about this album

            $r = $facebook->api('/'.$album['id'],$params);



            $pid = ($r['cover_photo']);



            //retrieve information about this photo

            $r = $facebook->api('/'.$pid,$params);



            return $r['source'];

        }

    }



    //Profile folder not found (could be because of paging)

    error_log('Failed to retrieve profile folder for uid '.$uid);

    return false; 

}



function create_the_session($session_name, $session_value) {

	destroy_the_session($session_name);

	$_SESSION[$session_name] = $session_value;

	ini_set('session.cookie_lifetime',400);

}

function destroy_the_session($session_name) {

	unset($_SESSION[$session_name]);

}

function check_user($user) {

	global $artist_type;

	$qry="SELECT * FROM muz_artists WHERE artist_username='$user' AND artist_type='$artist_type'";

	$res=mysql_query($qry);

	$num_rows = mysql_num_rows($res);

	if ($num_rows) {return true;}else{return false;}

}

function check_deejay($user) {

	global $deejay_type;

	$qry="SELECT * FROM muz_artists WHERE artist_username='$user' AND artist_type='$deejay_type'";

	$res=mysql_query($qry);

	$num_rows = mysql_num_rows($res);

	if ($num_rows) {return true;}else{return false;}

}

function reducelength($str,$maxlength) {

	if (strlen($str) > $maxlength) {

		$newstr = substr($str,0,$maxlength-3) . "..."; $short=true;	

	} else {$newstr = $str;}

	return $newstr;

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



function checkImage($imageField, $num) {

		// image should be of valid size i.e. maxsize

			if ($HTTP_POST_FILES['$imageField']['size'] > $maxsize) {

				$errmsg .= "Image file $num must be less than $maxsizekb MB in size.<br>";

				$errflag = true;

				unlink($HTTP_POST_FILES['$imageField']['tmp_name']);

			}

			// image should be of the right type i.e. gif,pjpeg or jpeg

			if($HTTP_POST_FILES['$imageField']['type'] != "image/gif" AND

			$HTTP_POST_FILES['$imageField']['type'] != "image/pjpeg" AND

			$HTTP_POST_FILES['$imageField']['type'] != "image/png" AND

			$HTTP_POST_FILES['$imageField']['type'] !="image/jpeg" ) {

				$errmsg .= "Image $num may only be .gif. .png or .jpeg files.</font><br>";

				$errflag = true;

				unlink($HTTP_POST_FILES['$imageField']['tmp_name']);

			}

}

function error_admin_login(){

   global $sitepath;

   return "<div id='error'>You must be <a href='".$sitepath."login/' class='small-button red' style='color:#FFF;'>logged in as admin</a> to see this page.</div>";	

}



function get_youtubeid($url) {

	parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );

	return $my_array_of_vars['v'];    

    // Output: C4kxS1ksqtw

  

   //check for these url types later

   //preg_match('#<object[^>]+>.+?http://www.youtube.com/v/([A-Za-z0-9\-_]+).+?</object>#s', $markup, $matches);

   //var_dump($matches[1]);



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

    //http://www.youtube.com/watch?feature=player_embedded&v=m-t4pcO99gI

    //url is http://www.youtube.com/watch?v=xxxx

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

    //return hqthumb

    else if($return == 'hqthumb'){

        return 'http://i1.ytimg.com/vi/'.$id.'/hqdefault.jpg';

    }

    // else return id

    else{

        return $id;

    }

}



function checkSavedStatus($ad_id,$cat) {

		global $logged_user;

		$qry="SELECT saved_id FROM muz_saved_articles WHERE parent_category=$cat AND saved_article_id=$ad_id AND saved_username='$logged_user'";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$saved_id = $data['saved_id'];

		if ($saved_id) {return true;}else{return false;}

}



function checkVideo($song_perm) {

		global $video_cat;

		$qry="SELECT article_id FROM muz_articles WHERE article_permalink='$song_perm' AND article_category_id=$video_cat";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$article_id = $data['article_id'];

		if ($article_id) {return true;}else{return false;}

}



function checkLyric($song_perm) {

		global $lyric_cat;

		$qry="SELECT article_id FROM muz_articles WHERE article_permalink='$song_perm' AND article_category_id=$lyric_cat";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$article_id = $data['article_id'];

		if ($article_id) {return true;}else{return false;}

}





/*function getFbLikes($fb_page_name) {

	$result = file_get_contents("https://graph.facebook.com/".$fb_page_name);

	$result = json_decode($result,true);

	//echo "name: " . $result["name"] . "<br>";

	//echo "website: " . $result["website"] . "<br>";

	//echo "likes: " . $result["likes"] . "<br>";

	return $result["likes"];

}*/



function getGenres($song_genre) {

    //Get all genres and print them out

	$sqlcat = "SELECT genre_id,genre_title FROM muz_genres WHERE genre_id IN ($song_genre) ORDER BY genre_title";

    $resultcat = mysql_query($sqlcat);

    $z = 0;

    while(list($id) = mysql_fetch_array($resultcat))

    {

        $features = ' '.mysql_result($resultcat,$z,"genre_title").',';

		$featuresx .= $features;

		$z++;

	}

	$featuresx = removelastcomma(trim($featuresx));	

	return $featuresx;

}

function getCategoryChar($id) {

		$qry="SELECT category_char FROM muz_categories WHERE category_id='$id'";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$category_char = $data['category_char'];

		return $category_char;

}

function getCategoryId($ad_perm) {

		$qry="SELECT article_category_id FROM muz_articles WHERE article_permalink='$ad_perm'";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$article_category_id = $data['article_category_id'];

		return $article_category_id;

}



function get_full_youtube_url($url) {

		$youtube_id = get_youtubeid($url);

		if ($youtube_id) {

			$youtube_embed_url = "http://www.youtube.com/embed/" . $youtube_id . "?rel=0&autoplay=1";

			return $youtube_embed_url;

		} else { return 0; }

}

function getArtistIdFromPerm($article_perm) {

		$qry="SELECT article_artist_id FROM muz_articles WHERE article_permalink='$article_perm' LIMIT 0,1";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$article_artist_id = $data['article_artist_id'];

		return $article_artist_id;

}

function getUserIdFromPerm($article_perm) {

		$qry="SELECT article_user_id FROM muz_articles WHERE article_permalink='$article_perm' LIMIT 0,1";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$article_user_id = $data['article_user_id'];

		return $article_user_id;

}

function getUserName($userid) {

		$qry="SELECT username FROM muz_members WHERE member_id=$userid LIMIT 0,1";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$username = $data['username'];

		return $username;

}
function getUserFullNames($userid) {
        $qry="SELECT firstname,lastname FROM muz_members WHERE member_id=$userid";
        $data = mysql_fetch_assoc(mysql_query($qry));
        return $data['firstname'] . " ".$data['lastname'];
}

function getJobName($perm) {

		$qry="SELECT job_title FROM muz_jobs WHERE job_permalink='$perm'";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$job_title = $data['job_title'];

		return $job_title;

}

function getJobId($perm) {

		$qry="SELECT id FROM muz_jobs WHERE job_permalink='$perm'";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$id = $data['id'];

		return $id;

}

function getSongIdFromPerm($article_perm) {

		global $song_cat;

		$qry="SELECT article_id FROM muz_articles WHERE article_permalink='$article_perm' AND article_category_id=$song_cat";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$article_id = $data['article_id'];

		return $article_id;

}

function getRingtoneUrl($id) {

		global $ringtone_cat;

		$qry="SELECT article_url FROM muz_articles WHERE article_id=$id AND article_category_id=$ringtone_cat";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$article_url = $data['article_url'];

		return $article_url;

}

function getRingtoneSize($perm) {

		global $ringtone_cat;

		$qry="SELECT article_file_size FROM muz_articles WHERE article_permalink='$perm' AND article_category_id=$ringtone_cat";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$article_file_size = $data['article_file_size'];

		return $article_file_size;

}

function getPathName($perm) {

		global $ringtone_cat;

		$qry="SELECT article_url FROM muz_articles WHERE article_permalink='$perm' AND article_category_id=$ringtone_cat";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$article_url = $data['article_url'];

		return $article_url;

}

function getArtistId($artist_perm) {
        $qry="SELECT artist_id FROM muz_artists WHERE artist_permalink='$artist_perm'";
        //echo "qry - $qry<br>";
        $data = mysql_fetch_assoc(mysql_query($qry));
        $article_id = $data['artist_id'];
        return $article_id;
}
function getArtistNameFromPerm($artist_perm) {
        $qry="SELECT artist_name FROM muz_artists WHERE artist_permalink='$artist_perm'";
        $data = mysql_fetch_assoc(mysql_query($qry));
        $artist_name = $data['artist_name'];
        return $artist_name;
}
function getArtistName($artist_id) {
        $qry="SELECT artist_name FROM muz_artists WHERE artist_id=$artist_id";
        $data = mysql_fetch_assoc(mysql_query($qry));
        $artist_name = $data['artist_name'];
        return $artist_name;
}

function getUserId($artist_perm) {

		$qry="SELECT member_id FROM muz_members WHERE username='$artist_perm'";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$member_id = $data['member_id'];

		return $member_id;

}



function getArtistCreator($artist_perm) {

		$qry="SELECT artist_username FROM muz_artists WHERE artist_permalink='$artist_perm'";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$artist_username = $data['artist_username'];

		return $artist_username;

}

function getSongCategory($artist_id) {

		$qry="SELECT artist_music_category FROM muz_artists WHERE artist_id=$artist_id";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$artist_music_category = $data['artist_music_category'];

		return $artist_music_category;

}

function getSongTags($id) {

		$qry="SELECT article_keywords FROM muz_articles WHERE article_category_id=6 AND article_id=$id";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$article_keywords = $data['article_keywords'];

		return $article_keywords;

}

function getArticleId($id) {

		global $video_cat;

		$qry="SELECT article_id FROM muz_articles WHERE article_category_id=$video_cat AND article_song_id=$id";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$article_id = $data['article_id'];

		return $article_id;

}
function clean_data($value) {
		$value = mysql_escape_string($value);
		return $value;

}
function getArticlePerm($id) {
		$qry="SELECT article_permalink FROM muz_articles WHERE article_id=$id";
		//echo "qry - $qry<br>";
		$data = mysql_fetch_assoc(mysql_query($qry));
		$article_permalink = $data['article_permalink'];
		return $article_permalink;
}
function getCategoryPerm($id) {
		$qry="SELECT category_permalink FROM muz_categories WHERE category_id=$id";
		$data = mysql_fetch_assoc(mysql_query($qry));
		$category_permalink = $data['category_permalink'];
		return $category_permalink;
}

function getArticleShortUrl($id) {
		$qry="SELECT short_url FROM muz_articles WHERE article_id=$id";
		//echo "qry - $qry<br>";
		$data = mysql_fetch_assoc(mysql_query($qry));
		$short_url = $data['short_url'];
		return $short_url;
}

function getLyricId($id) {

		global $lyric_cat;

		$qry="SELECT article_id FROM muz_articles WHERE article_category_id=$lyric_cat AND article_song_id=$id";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$article_id = $data['article_id'];

		return $article_id;

}

function checkRingtone($perm) {

		global $ringtone_cat;

		$qry="SELECT article_id FROM muz_articles WHERE article_category_id=$ringtone_cat AND article_permalink='$perm' AND article_url!='' AND article_tone='y' AND article_validated='y'";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$article_id = $data['article_id'];

		if ($article_id) {return true;} else {return false;}

}

function getVideoUrl($id) {

		$qry="SELECT article_url FROM muz_articles WHERE article_id=$id";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$video_url = $data['article_url'];

		return $video_url;

}

function getVideoId($song_id) {
		global $video_cat;
		$qry="SELECT article_id FROM muz_articles WHERE article_song_id=$song_id AND article_category_id=$video_cat";
		//echo "qry - $qry<br>";
		$data = mysql_fetch_assoc(mysql_query($qry));
		$article_id = $data['article_id'];
		return $article_id;
}
function getArticleHits($article_id){
		$qry="SELECT article_hits FROM muz_articles WHERE article_id=$article_id";
		$data = mysql_fetch_assoc(mysql_query($qry));
		return $data['article_hits'];
}
function checkArticleActive($article_id) {
		global $video_cat;
		$qry="SELECT suspended FROM muz_articles WHERE article_id=$article_id";
		//echo "qry - $qry<br>";
		$data = mysql_fetch_assoc(mysql_query($qry));
		$suspended = $data['suspended'];
		if ($suspended=='n') { return true; } else { return false; } 
}
function getLyricIdFromSongId($song_id) {
        global $lyric_cat;
        $qry="SELECT article_id FROM muz_articles WHERE article_song_id=$song_id AND article_category_id=$lyric_cat";
        //echo "qry - $qry<br>";
        $data = mysql_fetch_assoc(mysql_query($qry));
        $article_id = $data['article_id'];
        return $article_id;
}
function getSongIdFromArticleId($article_id) {
        $qry="SELECT article_song_id FROM muz_articles WHERE article_id=$article_id";
        $data = mysql_fetch_assoc(mysql_query($qry));
        return $data['article_song_id'];
}

function getVideoSongId($id) {

		$qry="SELECT article_url FROM muz_articles WHERE article_song_id=$id";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$video_url = $data['article_url'];

		return $video_url;

}


/*
function getArtistName($id) {

		$qry="SELECT artist_name FROM muz_artists WHERE artist_id=$id";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$artist_name = $data['artist_name'];

		return $artist_name;

}*/

function getFullNames($username) {

		$qry="SELECT firstname, lastname FROM muz_members WHERE username='$username'";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$firstname = $data['firstname'];

		$lastname = $data['lastname'];

		return $firstname." ".$lastname;

}



function getSongName($id) {
		global $song_cat;
		$qry="SELECT article_title FROM muz_articles WHERE article_id=$id";
		$data = mysql_fetch_assoc(mysql_query($qry));
		$article_title = $data['article_title'];
		return $article_title;
}
function getVideoUrlFromSongId($id){
        global $video_cat;
        $qry="SELECT article_url FROM muz_articles WHERE article_song_id=$id AND article_category_id=$video_cat";
        $data = mysql_fetch_assoc(mysql_query($qry));
        $article_url = $data['article_url'];
        return $article_url;
}
function getVideoIdFromSongId($id){
        global $video_cat;
        $qry="SELECT article_id FROM muz_articles WHERE article_song_id=$id AND article_category_id=$video_cat";
        $data = mysql_fetch_assoc(mysql_query($qry));
        $article_id = $data['article_id'];
        return $article_id;
}

function getSongNamePerm($perm) {

		global $song_cat;

		$qry="SELECT article_title FROM muz_articles WHERE article_category_id=$song_cat AND article_permalink='$perm'";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$article_title = $data['article_title'];

		return $article_title;

}

function getSongPerm($id) {

		$qry="SELECT article_permalink FROM muz_articles WHERE article_category_id=6 AND article_id=$id";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$article_permalink = $data['article_permalink'];

		return $article_permalink;

}

function getProducerName($id) {

		$qry="SELECT producer_name FROM muz_producers WHERE producer_id=$id";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$producer_name = $data['producer_name'];

		return $producer_name;

}



function getArtistPerm($id) {

		$qry="SELECT artist_permalink FROM muz_artists WHERE artist_id=$id";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$artist_permalink = $data['artist_permalink'];

		return $artist_permalink;

}

function getArtistType($id) {

		$qry="SELECT artist_type FROM muz_artists WHERE artist_id=$id";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$artist_type = $data['artist_type'];

		return $artist_type;

}

function getGenre($id) {

		$qry="SELECT genre_title FROM muz_genres WHERE genre_id=$id";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$genre_title = $data['genre_title'];

		return $genre_title;

}

function getMemberId($username) {

		$qry="SELECT member_id FROM muz_members WHERE username='$username'";

		//echo "qry - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$member_id = $data['member_id'];

		return $member_id;

}



function getListedNews($cat,$start,$num,$titleLength) {

					global $sitepath;

					$qry="SELECT article_title, article_permalink, article_id, category_permalink FROM muz_articles a, muz_categories c WHERE a.article_category_id = c.category_id AND a.suspended='n' AND article_category_id=$cat ORDER BY article_published DESC LIMIT $start,$num";

					//echo "qry - $qry<br>";

					$resultrows=mysql_num_rows(mysql_query($qry));

					$result=mysql_query($qry);

					$z=0;

					

					while(list() = mysql_fetch_array($result))

					{

						$article_id = mysql_result($result,$z,"article_id");

						$article_title = mysql_result($result,$z,"article_title");

						$article_title = reduceLength($article_title,$titleLength);

						$article_perm = mysql_result($result,$z,"article_permalink");

						$cat_perm = mysql_result($result,$z,"category_permalink");

					?>

                    <li><a href="<?=$sitepath?>articles/<?=$cat_perm?>/<?=$article_perm?>/"><?=$article_title?></a></li>

                    

                    <?php 

						$z++;

					} 

}



function getListedArtists($cat,$start,$num,$titleLength){

				global $sitepath, $artist_type, $deejay_type, $video_producer_type, $audio_producer_type;

							$artist_cat="";

							//get random artists

							$fullqry="SELECT artist_id FROM muz_artists WHERE suspended='n' AND artist_type='$cat' ORDER BY artist_published DESC LIMIT 0,30";

							//echo "fullqry - $fullqry<br>";

							$fulldNo = mysql_num_rows(mysql_query($fullqry));

							

							//get the ids of

							$idResult = mysql_query($fullqry);

							$xx=0;$number=$num;

							while(list($field) = mysql_fetch_array($idResult))

							{

								$ids .= mysql_result($idResult,$xx,"artist_id").",";

								$xx++;

							}

							$dataArray = removelastcomma($ids);

							$theids = getrandom($dataArray,$number);

		

							//Get the new artists

							//AND a.product_id = (select image_product_id from listing_images where image_product_id=product_id and image_category='$cat' LIMIT 0,1)

							$query = "SELECT artist_id, artist_name, artist_permalink FROM muz_artists WHERE artist_id IN ($theids)";

							//echo "query - $query<br>";

							$ArtistResult = mysql_query($query);

							$num_recs = mysql_num_rows($ArtistResult);

							if ($cat==$artist_type) {$artist_cat="artists";}

							if ($cat==$deejay_type) {$artist_cat="deejays";}

							if ($cat==$video_producer_type) {$artist_cat="producers";}

							if ($cat==$audio_producer_type) {$artist_cat="producers";}

							for($i=0;$i<$num_recs; $i++)

							{

									$artist_id=mysql_result($ArtistResult,$i,"artist_id");

									$artist_name=mysql_result($ArtistResult,$i,"artist_name");

									$artist_name_orig=$artist_name;

									$artist_perm=mysql_result($ArtistResult,$i,"artist_permalink");

									$artist_name = reducelength($artist_name,$titleLength);

				?>

                    <li><a href="<?=$sitepath?><?=$artist_cat?>/<?=$artist_perm?>/"><?=$artist_name?></a></li>

                    

                <?php 

							}

}

function getMainListedStories($catid,$start,$end,$max_title,$max_summary,$type) {

		global $sitepath, $interview_cat;

		$query = "SELECT * FROM muz_articles a, muz_categories c WHERE a.article_category_id=c.category_id AND category_id=$catid AND suspended='n' ORDER BY article_published DESC LIMIT $start,$end";

		//echo $query;

		$HeadlinesResult = mysql_query($query);

		$num_recs = mysql_num_rows($HeadlinesResult);

			for($i=0;$i<$num_recs; $i++)

			{

				$short=false;$inner = false; $thecss = "";

				$article_id=mysql_result($HeadlinesResult,$i,"article_id");

				$article_title=titlecase(mysql_result($HeadlinesResult,$i,"article_title"));

				$orig_article_title=$article_title;

				$article_text=mysql_result(html_entity_decode($HeadlinesResult,$i,"article_text"));

				$category_char=mysql_result($HeadlinesResult,$i,"category_char");

				$article_url=mysql_result($HeadlinesResult,$i,"article_url");

				$article_summary=mysql_result($HeadlinesResult,$i,"article_summary");

				$article_published=mysql_result($HeadlinesResult,$i,"article_published");

				$article_perm=mysql_result($HeadlinesResult,$i,"article_permalink");

				$cat_perm=mysql_result($HeadlinesResult,$i,"category_permalink");

				$article_link = $sitepath."articles/$cat_perm/$article_perm/";

				if (strlen($article_title) > $max_title) {

					$article_title = substr($article_title,0,$max_title-3) . "..."; $short=true;	

				}

				if (strlen($article_summary) > $max_summary) {

					$article_summary = substr($article_summary,0,$max_summary-3);	

				}

					$main_img = getmainimg($article_id,$category_char);

					if ((!$main_img) && ($article_url)) 

					{

						$main_img = parse_youtube_url($article_url,'hqthumb');

						$inner = true;

					}

					

				if ($i==($end-1)) {$thecss = " class='links_list_last' ";}

				

				?>

				<?php if (($type==0) && ($catid!=$interview_cat)) { ?>

				<div class="container_300_img round_2px alignleft">

						<a href="<?php echo $article_link ?>">

						<?php if ($inner) { ?>

							<img src="<?php echo $main_img ?>" width=150 height=79>

						<?php } else { ?>

							<img src="<?=$sitepath?>includes/image.php/<?php echo $main_img;?>?width=150&amp;height=79&amp;quality=100&amp;image=<?php echo $sitepath . "$main_img"; ?>" alt="<?=$orig_article_title?>" width="150" height="79"/>

						<?php } ?>

						</a>

				</div>

				<div class="container_300_intext">

						<h4><a href="<?php echo $article_link ?>"><?php echo $article_title ?></a></h4>

						<?php echo $article_summary ?>

						<a href="<?php echo $article_link ?>" title="read more">...</a>

				</div>

				<?php } ?>

				<?php if (($type==1)) { ?>

				<li <?php echo $thecss ?>><a href="<?php echo $article_link ?>" class="links_list"><?php echo $article_title ?></a></li>

				<?php } ?>

				

				<?php if (($catid==$interview_cat) && ($type==0)) { ?>

				<div class="alignleft pic_box">

					<a href="<?php echo $article_link ?>">

						<?php if ($inner) { ?>

							<img src="<?php echo $main_img ?>" width=260 height=137 class="pic_border round_3px">

						<?php } else { ?>

							<img src="<?=$sitepath?>includes/image.php/<?php echo $main_img;?>?width=260&amp;height=137&amp;quality=100&amp;image=<?php echo $sitepath . "$main_img"; ?>" alt="<?=$orig_article_title?>" width="260" height="137" class="pic_border round_3px"/>

						<?php } ?>

					</a>

					<div class="int_title">

						<h3><a href="<?php echo $article_link ?>"><?php echo $article_title ?></a></h3>

					</div>

				</div>

				<?php } ?>

				

				<?php

             }

}



function getMainVideos($catid,$start,$end,$max_title,$max_summary,$type) {

		global $sitepath;

		$thecss = "";

		$query = "SELECT * FROM muz_articles a, muz_categories c WHERE a.article_category_id=c.category_id AND category_id=$catid AND suspended='n' ORDER BY article_published DESC LIMIT $start,$end";

		$HeadlinesResult = mysql_query($query);

		$num_recs = mysql_num_rows($HeadlinesResult);

			for($i=0;$i<$num_recs; $i++)

			{

				$short=false;$inner = false;

				$article_id=mysql_result($HeadlinesResult,$i,"article_id");

				$article_title=sentencecase(mysql_result($HeadlinesResult,$i,"article_title"));

				$article_artist_id=mysql_result($HeadlinesResult,$i,"article_artist_id");

				$artist_name=titlecase(getArtistName($article_artist_id));

				$artist_perm=getArtistPerm($article_artist_id);

				$featured_artists=mysql_result($HeadlinesResult,$i,"featured_artists");

				if ($artist_name && $type==1) { $article_title = $article_title . " - " . $artist_name;}

				if ($featured_artists && $type==2) { $article_title = $article_title . " - " . $featured_artists;}

				$orig_article_title=$article_title;

				$article_text=mysql_result(html_entity_decode($HeadlinesResult,$i,"article_text"));

				$category_char=mysql_result($HeadlinesResult,$i,"category_char");

				$article_url=mysql_result($HeadlinesResult,$i,"article_url");

				$article_published=mysql_result($HeadlinesResult,$i,"article_published");

				$article_perm=mysql_result($HeadlinesResult,$i,"article_permalink");

				$cat_perm=mysql_result($HeadlinesResult,$i,"category_permalink");

				if ($type==1) { $vid_link = $sitepath . "$cat_perm/$artist_perm/$article_perm/"; }

				if ($type==2) { $vid_link = $sitepath . "videos/liveshows/$article_perm/"; }

				if (strlen($article_title) > $max_title) {

					$article_title = substr($article_title,0,$max_title-3) . "..."; $short=true;	

				}

				if ($i ==3) { $thecss = " video_list_one_last "; }

				$main_img=getmainimg($article_id,$category_char);

				if ((!$main_img) && ($article_url)) {

						$main_img = parse_youtube_url($article_url,'hqthumb');

						$video_url_id = get_youtubeid($article_url);

						$new_video_url = get_full_youtube_url($article_url);

						$inner = true;

				}

					

				?>

				

				<div class="video_list_one <?php echo $thecss ?> alignleft">

					<div class="videoz_img round_3px">

					<a href="<?php echo $new_video_url ?>" class="youtube colorboxdiv" title="<?=$orig_article_title?>">

					<img src="<?php echo $main_img ?>" width=138 height=107 alt="<?php echo $orig_article_title ?>">

					</a>

					</div>

					<div class="videoz_desc round_3px" onClick="location.href='#'">

					<a href="<?php echo $vid_link ?>"><?php echo $article_title ?></a></div>

				</div>

				

				<?php

				

             }

}



function getOrigVideos($catid,$start,$end,$max_title,$max_summary) {
		global $sitepath, $video_cat, $liveshow_cat;
		$the_field = getSearchField();
		//get random videos
		$vdfullqry="SELECT * FROM muz_articles WHERE suspended='n' AND article_validated='y' AND article_category_id=$catid ORDER BY $the_field DESC LIMIT 0,30";
		//echo "fullqry - $fullqry<br>";
		$vdfulldNo = mysql_num_rows(mysql_query($vdfullqry));
		//get the ids
		$vdidResult = mysql_query($vdfullqry);
		$vdxx=0;$vdnumber=4;
		while(list($field) = mysql_fetch_array($vdidResult))
		{
			$vdids .= mysql_result($vdidResult,$vdxx,"article_id").",";
			$vdxx++;
		}
		$vddataArray = removelastcomma($vdids);
		$vdtheids = getrandom($vddataArray,$vdnumber);
		//Get the new videos
		$query = "SELECT * FROM muz_articles WHERE article_id IN ($vdtheids)";
		$HeadlinesResult = mysql_query($query);
		$num_recs = mysql_num_rows($HeadlinesResult);
		$num_recs = mysql_num_rows($HeadlinesResult);
			for($i=0;$i<$num_recs; $i++)
			{
				$short=false;$inner = false;
				$article_id=mysql_result($HeadlinesResult,$i,"article_id");
				$article_title=sentencecase(mysql_result($HeadlinesResult,$i,"article_title"));
				$article_artist_id=mysql_result($HeadlinesResult,$i,"article_artist_id");
				$artist_name=titlecase(getArtistName($article_artist_id));
				$artist_perm=getArtistPerm($article_artist_id);
				$featured_artists=mysql_result($HeadlinesResult,$i,"featured_artists");
				if ($featured_artists && $catid==$liveshow_cat) { $artist_name = $featured_artists;}
				$orig_article_title=$article_title;
				$article_text=mysql_result(html_entity_decode($HeadlinesResult,$i,"article_text"));
				$category_char=mysql_result($HeadlinesResult,$i,"category_char");
				$article_url=mysql_result($HeadlinesResult,$i,"article_url");
				$article_published=mysql_result($HeadlinesResult,$i,"article_published");
				$article_perm=mysql_result($HeadlinesResult,$i,"article_permalink");
				$cat_perm=mysql_result($HeadlinesResult,$i,"category_permalink");
				if ($catid==$video_cat) { $vid_link = $sitepath . "$cat_perm/$artist_perm/$article_id-$article_perm.html"; }
				if ($catid==$liveshow_cat) { $vid_link = $sitepath . "liveshows/$article_id-$article_perm.html"; }
				if (strlen($article_title) > $max_title) {
					$article_title = substr($article_title,0,$max_title-3) . "..."; $short=true;	
				}
				$main_img=getmainimg($article_id,$category_char);
				if ((!$main_img) && ($article_url)) {
						$main_img = parse_youtube_url($article_url,'hqthumb');
						$video_url_id = get_youtubeid($article_url);
						$new_video_url = get_full_youtube_url($article_url);
						$inner = true;
				}
				?>
                <div class="item">
                    <div class="item-header">
                        <a href="<?=$new_video_url?>" class="hover-image">
                        <img src="<?=$sitepath?>images/video-icon.png" alt="" class="news-video-icon" />
                        <img src="<?=$main_img?>" width="376" height="212" alt="<?=$orig_article_title?>">
                        </a>
                    </div>
                    <div class="item-content">
                        <h3><a href="<?=$new_video_url?>"><?=$orig_article_title?></a></h3>
                        <p><a href="<?=$new_video_url?>"><?=$artist_name?></a></p>
                    </div> 
                </div>
				<?php
             }

}





function getListedStories($catid,$start,$end,$max_title,$max_summary) {

		global $sitepath;

		$query = "SELECT * FROM muz_articles a, muz_categories c WHERE a.article_category_id=c.category_id AND category_id=$catid AND suspended='n' ORDER BY article_published DESC LIMIT $start,$end";

		//echo "query - $query<br>";

		$HeadlinesResult = mysql_query($query);

		$num_recs = mysql_num_rows($HeadlinesResult);

			for($i=0;$i<$num_recs; $i++)

			{

				$short=false;

				$article_id=mysql_result($HeadlinesResult,$i,"article_id");

				$article_title=sentencecase(mysql_result($HeadlinesResult,$i,"article_title"));

				$orig_article_title=$article_title;

				$category_char=mysql_result($HeadlinesResult,$i,"category_char");

				$article_published=mysql_result($HeadlinesResult,$i,"article_published");

				$article_perm=mysql_result($HeadlinesResult,$i,"article_permalink");

				$cat_perm=mysql_result($HeadlinesResult,$i,"category_permalink");

				if (strlen($article_title) > $max_title) {

					$article_title = substr($article_title,0,$max_title-3) . "..."; $short=true;	

				}

				echo "<li><a href=\"".$sitepath."articles/$cat_perm/$article_perm/\" class='grey'";

				if ($short) { echo " title=\"$orig_article_title\" class='tips'"; }

				echo ">$article_title</a></li>";

             }

}

function getMobileStories($catid,$start,$num,$max_title,$show='n') {

		global $mobile_sitepath;

		//Get the stories

		$query = "SELECT article_id,article_title,category_name,article_permalink,article_published,category_permalink FROM muz_articles a, muz_categories c WHERE a.article_category_id=c.category_id AND category_id=$catid AND suspended='n' ORDER BY article_published DESC LIMIT $start,$num";

		//echo "query - $query<br>";

		$HeadlinesResult = mysql_query($query);

		$num_recs = mysql_num_rows($HeadlinesResult);

			for($i=0;$i<$num_recs; $i++)

			{

				$article_id=mysql_result($HeadlinesResult,$i,"article_id");

				$article_title=titlecase(mysql_result($HeadlinesResult,$i,"article_title"));

				$article_perm=mysql_result($HeadlinesResult,$i,"article_permalink");

				$article_published=mysql_result($HeadlinesResult,$i,"article_published");

				$cat_name=mysql_result($HeadlinesResult,$i,"category_name");

				$cat_perm=mysql_result($HeadlinesResult,$i,"category_permalink");

				$seo_title = $article_perm."/";

				$article_title = reduceLength($article_title,$max_title);

		?>

			

            <p>

                <strong class="column-title"><a href="<?=$mobile_sitepath?>articles/<?=$cat_perm?>/<?=$seo_title?>">- <?=$article_title?></a>

				<?php if ($show=='y') { ?><i class="categories">- <a href="<?=$mobile_sitepath?>articles/<?=$cat_perm?>/"><?=$cat_name?></a></i><?php } ?>

				</strong><br/>

            </p>

		<?php

			 }

}



function getMobileMainStory($catid,$start,$max_title,$max_summary) {

		global $mobile_sitepath, $sitepath;

								//Get the top story

								$query = "SELECT * FROM muz_articles a, muz_categories c WHERE a.article_category_id=c.category_id AND category_id=$catid AND suspended='n' ORDER BY article_published DESC LIMIT $start,1";

								//echo "query - $query<br>";

									$result = mysql_query($query);

									$articlez = mysql_fetch_assoc($result);

									$article_id = $articlez['article_id'];

									$article_title = titlecase($articlez['article_title']);

									$orig_article_title = $article_title;

									$article_text = $articlez['article_text'];

									$article_perm = $articlez['article_permalink'];

									$article_summary = $articlez['article_summary'];

									$article_published = $articlez['article_published'];

									$cat_perm = $articlez['category_permalink'];

									$cat_letter = $articlez['category_char'];

									$seo_title = $article_perm."/";

									$article_title = reduceLength($article_title,$max_title);

									$article_summary = reduceLength($article_summary,$max_summary);

									$main_img=getmainimg($article_id,$cat_letter);

							?>

				

				<p class="two-column">

            	<a href="<?=$mobile_sitepath?>articles/<?=$cat_perm?>/<?=$seo_title?>" title="<?=$orig_article_title?>">

                   <img src="<?=$sitepath?>includes/image.php/<?php echo $main_img;?>?width=130&amp;height=69&amp;quality=100&amp;image=<?php echo $sitepath."$main_img";?>" alt="<?=$orig_article_title?>" width="130" height="69"/>

                </a>

				</p>

				<p class="two-column last-column">

				<strong class="column-title"><a href="<?=$mobile_sitepath?>articles/<?=$cat_perm?>/<?=$seo_title?>"><?=$article_title?></a></strong><br/>

					<?php //echo $article_summary?> <a href="<?=$mobile_sitepath?>articles/<?=$cat_perm?>/<?=$seo_title?>" class="bot_link">more</a> 

				</p>

		<?php

	}

	



function showartists($cat, $cat2=NULL) {

			global $profile_pic_cat, $sitepath, $male_no_image, $artist_type, $deejay_type, $audio_producer_type, $video_producer_type;

			$theids="";

			//get random artists

			$fullqry="SELECT artist_id FROM muz_artists WHERE suspended='n' AND (artist_type='$cat'";

			if ($cat2) {$fullqry.=" OR artist_type='$cat2'";}

			$fullqry.=") AND artist_id = (SELECT image_product_id FROM muz_listing_images WHERE image_product_id=artist_id and approved='y' AND  image_category='$profile_pic_cat' LIMIT 0,1) ORDER BY artist_published DESC LIMIT 0,30";

			//echo "fullqry - $fullqry<br>";

			$fulldNo = mysql_num_rows(mysql_query($fullqry));

							

			//get the ids 

			$idResult = mysql_query($fullqry);

			$xx=0;$number=2;

			while(list($field) = mysql_fetch_array($idResult))

			{

				$ids .= mysql_result($idResult,$xx,"artist_id").",";

				$xx++;

			}

			$dataArray = removelastcomma($ids);

			$theids = getrandom($dataArray,$number);



			$query = "SELECT artist_id, artist_type, artist_name, artist_permalink FROM muz_artists WHERE artist_id IN ($theids)";

			//echo "query - $query<br>";

			$ArtistResult = mysql_query($query);

			$num_recs = mysql_num_rows($ArtistResult);

			for($i=0;$i<$num_recs; $i++)

			{

				$artist_id=mysql_result($ArtistResult,$i,"artist_id");

				$db_artist_type=mysql_result($ArtistResult,$i,"artist_type");

				$artist_name=mysql_result($ArtistResult,$i,"artist_name");

				$artist_name_orig=$artist_name;

				$artist_perm=mysql_result($ArtistResult,$i,"artist_permalink");

				$artist_name = reducelength($artist_name,15);

				$main_img = getmainimg($artist_id,$profile_pic_cat);

				if (!$main_img) {$main_img=$male_no_image;}

				if ($db_artist_type==$artist_type) {$the_link="artists";}

				if ($db_artist_type==$deejay_type) {$the_link="deejays";}

				if (($db_artist_type==$audio_producer_type)||($db_artist_type==$video_producer_type)) {$the_link="producers";}

					?>

			<p class="two-column">

            	<strong class="column-title">

				   <a href="<?=$mobile_sitepath?><?=$the_link?>/<?=$artist_perm?>/"><?=$artist_name?></a>

				</strong><br/>

            	<a href="<?=$mobile_sitepath?><?=$the_link?>/<?=$artist_perm?>/">

                         <img src="<?=$sitepath?>includes/image.php/<?=$main_img?>?width=60&amp;height=60&amp;cropratio=1:1&amp;quality=70&amp;image=<?=$sitepath?><?=$main_img?>" alt="<?=$artist_name_orig?>" class="round_3px" width="60" height="60"/>

                </a>

            </p>

			<?php  

				$i++;

				$artist_id=mysql_result($ArtistResult,$i,"artist_id");

				$db_artist_type=mysql_result($ArtistResult,$i,"artist_type");

				$artist_name=mysql_result($ArtistResult,$i,"artist_name");

				$artist_name_orig=$artist_name;

				$artist_perm=mysql_result($ArtistResult,$i,"artist_permalink");

				$artist_name = reducelength($artist_name,15);

				$main_img = getmainimg($artist_id,$profile_pic_cat);

				if (!$main_img) {$main_img=$male_no_image;}

				if ($db_artist_type==$artist_type) {$the_link="artists";}

				if ($db_artist_type==$deejay_type) {$the_link="deejays";}

				if (($db_artist_type==$audio_producer_type)||($db_artist_type==$video_producer_type)) {$the_link="producers";}

			?>

            <p class="two-column last-column">

				<strong class="column-title">

				   <a href="<?=$mobile_sitepath?><?=$the_link?>/<?=$artist_perm?>/"><?=$artist_name?></a>

				</strong><br/>

            	<a href="<?=$mobile_sitepath?><?=$the_link?>/<?=$artist_perm?>/">

                         <img src="<?=$sitepath?>includes/image.php/<?=$main_img?>?width=60&amp;height=60&amp;cropratio=1:1&amp;quality=70&amp;image=<?=$sitepath?><?=$main_img?>" alt="<?=$artist_name_orig?>" class="round_3px" width="60" height="60"/>

                </a>

            </p>

		<?php

			}

} 

function showotherstories($cat,$num) {

			global $mobile_sitepath, $sitepath;

			//Get the top headline stories

			$query = "SELECT * FROM muz_articles a, muz_categories c WHERE a.article_category_id=c.category_id AND article_category_id=$cat AND suspended='n' ORDER BY article_published DESC LIMIT 0,$num";

			$HeadlinesResult = mysql_query($query);

             while($a1 = mysql_fetch_array($HeadlinesResult))

              {

                                            $inner = false;

											$article_title = ($a1[article_title]);

											$article_title = reduceLength($article_title,60);

                                            $article_id = $a1[article_id];

											$article_url = $a1[article_url];

											$article_summary = $a1[article_summary];

                                            $article_text = html_entity_decode($a1[article_text]);

                                            $cat_perm = getPermalink($a1[article_category_id]);

											$cat = $a1[category_char];

											$cat_name = $a1[category_name];

											$seo_title = $a1[article_permalink];

                                            //$link = $sitepath."articles/$cat_perm/$seo_title/";

											

                                            $link = $mobile_sitepath."articles/$cat_perm/$seo_title/";

											$cat_link = $mobile_sitepath."articles/$cat_perm/";

                                            $main_img = getmainimg($article_id,$cat);

                                            

                                            $article_title = reduceLength($article_title,60);	

											

											if ((!$main_img) && ($article_url)) {

												$main_img = parse_youtube_url($article_url,'hqthumb');

												$inner = true;

											}

												

											if ($inner) {

												$img_tag = "<img src='$main_img' width=60 height=60 alt='$orig_article_title'/>";

											} else {

												$img_tag = "<img src='".$sitepath."includes/image.php/$main_img?width=60&amp;height=60&amp;cropratio=1:1&amp;quality=100&amp;image=".$sitepath."$main_img' alt='$article_title' width='60' height='60'/>"; }

            ?>

			<p class="small-left-column">

				<a href="<?=$link?>">

				  <?=$img_tag?>

				</a>

			</p>

			<p class="small-right-column last-column">

				<strong class="column-title"><a href="<?=$link?>"><?=$article_title?></a></strong><br/>

				<strong><i><a href="<?=$cat_link?>" class="bot_link"><?=$cat_name?></a></i></srong>

				</p>

				<div class="clear"></div>

			<?php } 

}

function showotherartists($cat,$num,$cat2=NULL) {

			global $profile_pic_cat, $sitepath, $mobile_sitepath, $male_no_image, $artist_type, $deejay_type, $audio_producer_type, $video_producer_type;

			$theids="";

			//get random artists

			$fullqry="SELECT artist_id FROM muz_artists WHERE suspended='n' AND (artist_type='$cat'";

			if ($cat2) {$fullqry.=" OR artist_type='$cat2'";}

			$fullqry.=") ORDER BY artist_published DESC LIMIT 0,30";

			//echo "fullqry - $fullqry<br>";

			$fulldNo = mysql_num_rows(mysql_query($fullqry));

							

			//get the ids 

			$idResult = mysql_query($fullqry);

			$xx=0;$number=$num;

			while(list($field) = mysql_fetch_array($idResult))

			{

				$ids .= mysql_result($idResult,$xx,"artist_id").",";

				$xx++;

			}

			$dataArray = removelastcomma($ids);

			$theids = getrandom($dataArray,$number);



			$query = "SELECT artist_id, artist_type, artist_name, artist_permalink FROM muz_artists WHERE artist_id IN ($theids)";

			//echo "query - $query<br>";

			$ArtistResult = mysql_query($query);

			$num_recs = mysql_num_rows($ArtistResult);

			for($i=0;$i<$num_recs; $i++)

			{

				$the_link="";

				$artist_id=mysql_result($ArtistResult,$i,"artist_id");

				$db_artist_type=mysql_result($ArtistResult,$i,"artist_type");

				$artist_name=mysql_result($ArtistResult,$i,"artist_name");

				$artist_name_orig=$artist_name;

				$artist_perm=mysql_result($ArtistResult,$i,"artist_permalink");

				$artist_name = reducelength($artist_name,25);

				$main_img = getmainimg($artist_id,$profile_pic_cat);

				if (!$main_img) {$main_img=$male_no_image;}

				if ($db_artist_type==$artist_type) {$the_link="artists";}

				if ($db_artist_type==$deejay_type) {$the_link="deejays";}

				if (($db_artist_type==$audio_producer_type)||($db_artist_type==$video_producer_type)) {$the_link="producers";}

				$link = $mobile_sitepath."$the_link/$artist_perm/";

				$cat_link = $mobile_sitepath."$the_link/";

				$cat_name = titlecase($the_link);

				$img_tag = "<img src='".$sitepath."includes/image.php/$main_img?width=60&amp;height=60&amp;cropratio=1:1&amp;quality=100&amp;image=".$sitepath."$main_img' alt='$artist_name' width='60' height='60'/>";

			?>

			<p class="small-left-column">

				<a href="<?=$link?>">

				  <?=$img_tag?>

				</a>

			</p>

			<p class="small-right-column last-column">

				<strong class="column-title"><a href="<?=$link?>"><?=$artist_name?></a></strong><br/>

				<strong><i><a href="<?=$cat_link?>" class="bot_link"><?=$cat_name?></a></i></srong>

				</p>

				<div class="clear"></div>

			<?php } 

}





function getMixCount($artist_id) {

	global $deejay_mix_cat;

	//get number of ads in this category

	$countads = "SELECT count(article_id) as thenum FROM muz_articles WHERE article_artist_id=$artist_id AND suspended='n' AND article_category_id=$deejay_mix_cat";

	$b1 = mysql_fetch_array(mysql_query($countads));

	//echo "countads - $countads<br>";

	$totaladcount = $b1[thenum];

    if (!$totaladcount) {$totaladcount = 0;}

	return $totaladcount;

}



function getToneCount($artist_id) {

	global $ringtone_cat;

	//get number of ads in this category

	$countads = "SELECT count(article_id) as thenum FROM muz_articles WHERE article_artist_id=$artist_id AND suspended='n' AND article_tone='y' AND article_validated='y' AND article_category_id=$ringtone_cat";

	$b1 = mysql_fetch_array(mysql_query($countads));

	//echo "countads - $countads<br>";

	$totaladcount = $b1[thenum];

    if (!$totaladcount) {$totaladcount = 0;}

	return $totaladcount;

}

function getVideoCount($artist_id) {
	global $video_cat;
	//get number of ads in this category
	$countads = "SELECT count(article_id) as thenum FROM muz_articles WHERE article_artist_id=$artist_id AND suspended='n' AND article_validated='y' AND article_category_id=$video_cat";
	$b1 = mysql_fetch_array(mysql_query($countads));
	$totaladcount = $b1[thenum];
    if (!$totaladcount) {$totaladcount = 0;}
	return $totaladcount;
}
function getArtistLikeCount($artist_id){
	//get number of ads in this category
	$countads = "SELECT count(article_id) as thenum FROM user_follow WHERE following=$artist_id";
	$b1 = mysql_fetch_array(mysql_query($countads));
	$totaladcount = $b1[thenum];
    if (!$totaladcount) {$totaladcount = 0;}
	return $totaladcount;	
}
function getProducerSongCount($artist_id){
	global $song_cat;
	//get number of ads in this category
	$countads = "SELECT article_id FROM muz_articles WHERE article_producer_id=$artist_id AND article_category_id=$song_cat AND suspended='n' AND article_url!=''";
	return mysql_num_rows(mysql_query($countads));	
}
function getProducerVideoCount($artist_id){
	global $video_cat;
	//get number of ads in this category
	$countads = "SELECT article_id FROM muz_articles WHERE article_producer_id=$artist_id AND article_category_id=$video_cat AND suspended='n' AND article_url!=''";
	return mysql_num_rows(mysql_query($countads));	
}

function getSongCount($artist_id) {
	global $song_cat;
	//get number of ads in this category
	$countads = "SELECT count(article_id) as thenum FROM muz_articles WHERE article_artist_id=$artist_id AND suspended='n' AND article_validated='y' AND article_category_id=$song_cat AND article_url!=''";
	$b1 = mysql_fetch_array(mysql_query($countads));
	$totaladcount = $b1[thenum];
    if (!$totaladcount) {$totaladcount = 0;}
	return $totaladcount;
}





function getLatitude($id) {

		$qry = "SELECT zone_latitude FROM zone WHERE zone_id=$id";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$zone_latitude = $data['zone_latitude'];

		return $zone_latitude;

}

function getLongitude($id) {

		$qry = "SELECT zone_longitude FROM zone WHERE zone_id=$id";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$zone_longitude = $data['zone_longitude'];

		return $zone_longitude;

}

function getEstateLatitude($id) {

		$qry = "SELECT latitude FROM property_estate WHERE estate_id=$id";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$est_latitude = $data['latitude'];

		return $est_latitude;

}

function getEstateLongitude($id) {

		$qry = "SELECT longitude FROM property_estate WHERE estate_id=$id";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$est_longitude = $data['longitude'];

		return $est_longitude;

}

function getManufacturer($id) {

		$qry = "SELECT manufacturer FROM auto_manufacturer WHERE id='$id'";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$manufacturer = $data['manufacturer'];

		return $manufacturer;

}

function getManufacturerThumb($id) {

		$qry = "SELECT manuf_thumbnail FROM auto_manufacturer WHERE id='$id'";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$manuf_thumbnail = $data['manuf_thumbnail'];

		return $manuf_thumbnail;

}

//creates a 3 character sequence

function createSalt()

{

    $string = md5(uniqid(rand(), true));

    return substr($string, 0, 5);

}



//check for duplicate permalinks

function checkPerm($perm)

{

		if (strlen($perm)>60){$perm=substr($perm, 0, 60);}

		$query = "SELECT article_permalink FROM muz_articles WHERE article_permalink= '$perm'";

		if (mysql_num_rows(mysql_query($query))){

				 $newchars = createSalt();

				 $perm = $perm . "-" . $newchars;

		}

	return $perm;

}



function checkArtistPerm($perm)

{

		if (strlen($perm)>60){$perm=substr($perm, 0, 60);}

		$query = "SELECT artist_id FROM muz_artistss WHERE artist_permalink= '$perm'";

		if (mysql_num_rows(mysql_query($query))){

				 $newchars = createSalt();

				 $perm = $perm . "-" . $newchars;

		}

	return $perm;

}



//check for duplicate permalinks

function checkFileName($filename)

{

		global $ringtone_cat;

		$full_name = "ringtones/tones/".$filename;

		$query = "SELECT article_id FROM muz_articles WHERE article_url= '$full_name' AND article_category_id=$ringtone_cat";

		if (mysql_num_rows(mysql_query($query))){

				 $newchars = createSalt();

				 $name_url = explode(".",$filename);

				 $the_name = strtolower($name_url[0]);

				 $the_ext = strtolower($name_url[1]);

				 $filename = $the_name . "-" . $newchars. ".".$the_ext;

		}

	return $filename;

}



// Fixes the encoding to utf8

function fixEncoding($in_str)

{

  $cur_encoding = mb_detect_encoding($in_str) ;

  if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8"))

    return $in_str;

  else

    return utf8_encode($in_str);

} 



/*

Simple PHP Smart Date Function

by Zen (http://zenverse.net)

*/

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
		return date("D, M j, Y",$timestamp);
	}
}

function grammar_date($val, $sentence) {

	if ($val > 1) {

		return $val.str_replace('(s)', 's', $sentence);

	} else {

		return $val.str_replace('(s)', '', $sentence);

	}

}

function getCatList($cat_id) {

		$numq = "select category_name, cat_parent_id from mall_product_categories where category_id=$cat_id";

        $b1 = mysql_fetch_array(mysql_query($numq));

        $category_name = $b1[category_name];

		$category_parent = $b1[cat_parent_id];

		$cat_list = $category_name; 

		if ($category_parent!=0) { 

			do {

				$numq2 = "select category_name, cat_parent_id from mall_product_categories where category_id=$category_parent";

				$c1 = mysql_fetch_array(mysql_query($numq2));

				$category_name = $c1[category_name];

				$category_parent2 = $c1[cat_parent_id];

				$cat_list .= " &gt; $category_name";

			} while ($category_parent2!=0);

		}

		return $cat_list;

}

function getMainCatList($cat_id) {

		$numq = "select category_name, cat_parent_id from class_categories where category_id=$cat_id";

        $b1 = mysql_fetch_array(mysql_query($numq));

        $category_name = $b1[category_name];

		$category_parent = $b1[cat_parent_id];

		$cat_list = $category_name; 

		if ($category_parent!=0) {

			$x = 0;

			do {

				$numq2 = "select category_name, cat_parent_id from class_categories where category_id=$category_parent";

				$c1 = mysql_fetch_array(mysql_query($numq2));

				$category_name = $c1[category_name];

				$category_parent2 = $c1[cat_parent_id];

				$cat_list .= " &gt; $category_name";

			} while ($category_parent2!=0);

		}

		return $cat_list;

}



function isitVideo($char) {

		$isVideo=false;

		global $liveshow_cat_letter,$video_cat_letter;

		if ($char==$liveshow_cat_letter) {$isVideo=true;}

		if ($char==$video_cat_letter) {$isVideo=true;}

		return $isVideo;

}



function getCategoryLink($char,$art_perm,$perm) {

		global $liveshow_cat_letter,$video_cat_letter,$gallery_cat_letter,$international_cat_letter,$picture_perfect_cat_letter,$spy_shot_cat_letter,$lyric_cat_letter,$headline_cat_letter,$interview_cat_letter,$gossip_cat_letter,$deejay_mix_cat_letter,$producer_cat_letter,$ringtone_cat_letter,$song_cat_letter,$usergallery_cat_letter,$artistgallery_cat_letter,$sitepath;

		if ($char==$liveshow_cat_letter) {$the_link="videos/liveshows/";}

		if ($char==$video_cat_letter) {$the_link="videos/$art_perm/";}

		if ($char==$song_cat_letter) {$the_link="songs/$art_perm/";}

		if ($char==$gallery_cat_letter) {$the_link="gallery/";}

		if ($char==$picture_perfect_cat_letter) {$the_link="picture-perfect/";}

		if ($char==$spy_shot_cat_letter) {$the_link="spy-shots/";}

		if ($char==$lyric_cat_letter) {$the_link="lyrics/$art_perm/";}

		if ($char==$headline_cat_letter) {$the_link="articles/headlines/";}

		if ($char==$interview_cat_letter) {$the_link="articles/interviews/";}

		if ($char==$gossip_cat_letter) {$the_link="articles/overheard/";}

		if ($char==$international_cat_letter) {$the_link="articles/international/";}

		if ($char==$deejay_mix_cat_letter) {$the_link="deejays/dj-mixes/";}

		if ($char==$producer_cat_letter) {$the_link="producers/";}

		if ($char==$ringtone_cat_letter) {$the_link="ringtones/";}

		if ($char==$usergallery_cat_letter) {$the_link="profile/$art_perm/photos/";}

		if ($char==$artistgallery_cat_letter) {$the_link="artists/$art_perm/photos/";}

		return $sitepath.$the_link."$perm/";

}



function getCategoryName($cat_perm) {

		$qry = "SELECT category_name FROM class_categories WHERE category_permalink='$cat_perm'";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$category_name = $data['category_name'];

		return $category_name;

}

function getCountryName($loc) {

		$qry = "SELECT name FROM class_country c, zone z WHERE c.country_id=z.country_id and zone_id=$loc";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$country_name = $data['name'];

		return $country_name;

}

function getCountryByID($loc) {

		$qry = "SELECT name FROM muz_country WHERE iso='$loc'";

		//echo "upd - $qry<br>";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$country_name = $data['name'];

		return $country_name;

}

function getCatName($category_id) {

		$qry = "SELECT category_name FROM muz_categories WHERE category_id=$category_id";

		$data = mysql_fetch_assoc(mysql_query($qry));

		$category_name = $data['category_name'];

		return $category_name;

}

function deleteFiles($has_photo){
		$sql = "SELECT full_img, thumb_img FROM images WHERE id = $has_photo";
		$row = mysql_fetch_array(mysql_query($sql));
		$full_img = "../../" . $row["full_img"];
		$thumb_img = "../../" . $row["thumb_img"];	
		unlink($full_img);
		unlink($thumb_img);
}

function updateImage($path,$thumb,$image_id,$user_id) {
		$qry = "UPDATE images SET thumb_img='$thumb',full_img='$path',updated_at=NOW(), updated_by=$user_id WHERE id=$image_id";
		mysql_query($qry);
}

function updateCaption($caption,$image_id) {
		$qry = "UPDATE muz_listing_images SET image_caption='$caption',date_updated=NOW() WHERE image_id=$image_id";
		//echo $qry;
		mysql_query($qry);
}

function addImage($cat_id,$path,$thumb,$id, $creator) {

		$qry = "INSERT INTO images(thumb_img, full_img, image_category_id, category_item_id, created_at, created_by) VALUES ('$thumb', '$path', $cat_id, $id,NOW(), $creator)";
		mysql_query($qry);
		return mysql_insert_id();

}

//get user's large photo
function getMainPhoto($user_id)
{
	global $sitepath, $default_user_img;
	$sql = "SELECT full_img FROM images WHERE category_item_id=$user_id AND image_category_id = ".PROFILE_PIC_CAT_ID."";
	$res = mysql_query($sql) or die(mysql_error() . " " . $sql);
    $row = mysql_fetch_array($res);
    $full_img = $row["full_img"];
	if (!$full_img){ $full_img = $default_user_img; }
	return $sitepath . $full_img; 
}

function getpack($pack) {
    $unpack = array(
        0 => "Don't unpack",
        1 => "GZIP",
        2 => "ZIP",
        3 => "RAR"
    );
    return $unpack[$pack];
}

function getdownload($downloadid) {
    $query = "SELECT DOWNLOAD_ID, TITLE FROM DOWNLOADS WHERE DOWNLOAD_ID={$downloadid}";
    $selectres = mysql_query($query);
    $row = mysql_fetch_array($selectres);
    return $row['TITLE'];
}

function clean_this($strin) {
    $strout = null;

    for ($i = 0; $i < strlen($strin); $i++) {
        $ord = ord($strin[$i]);

        if (($ord > 0 && $ord < 32) || ($ord >= 127)) {
            $strout .= "&#{$ord};";
        } else {
            switch ($strin[$i]) {
                case '<':
                    $strout .= '&lt;';
                    break;
                case '>':
                    $strout .= '&gt;';
                    break;
                case '&':
                    $strout .= '&amp;';
                    break;
                case '"':
                    $strout .= '&quot;';
                    break;
                case '\'':
                    $strout .= '&apos;';
                    break;
                case '½':
                    $strout .= '&frac12';
                    break;

                default:
                    $strout .= $strin[$i];
            }
        }
    }

    return strip_tags($strout);
}

function moveItemUpDownGrid($parentid, $parentfield, $itemid, $itemidfield, $sortid, $sortfield, $table, $direction) {
//var_dump(func_get_args());

    if ($direction == "up") {

        $minmax = getMinMaxSortID($parentid, $parentfield, $sortid, $sortfield, $table, "MIN");
        //echo $minmax; exit;

        if ($sortid > $minmax) {

            $xsql = "UPDATE {$table} SET {$sortfield} = {$sortid} WHERE {$sortfield} = {$sortid}-1";

            if (!empty($parentid)) {
                $xsql.=" AND {$parentfield} = {$parentid}";
            }
            $xres = mysql_query($xsql) or die(mysql_error() . " " . $xsql);

            $sql = "UPDATE {$table} SET {$sortfield} = {$sortid}-1 WHERE {$itemidfield} = {$itemid}";
            if (!empty($parentid)) {
                $sql.=" AND {$parentfield} = {$parentid}";
            }
            $res = mysql_query($sql) or die(mysql_error() . " " . $sql);
        }
    } else if ($direction == "down") {

        $minmax = getMinMaxSortID($parentid, $parentfield, $sortid, $sortfield, $table, "MAX");
//var_dump($minmax);
//var_dump($sortid);
        if ($sortid < $minmax) {

            $xsql = "UPDATE {$table} SET {$sortfield} = {$sortid} WHERE {$sortfield} = {$sortid}+1";

            if (!empty($parentid)) {
                $xsql.=" AND {$parentfield} = {$parentid}";
            }
            $xres = mysql_query($xsql) or die(mysql_error() . " " . $xsql);

            $sql = "UPDATE {$table} SET {$sortfield} = {$sortid}+1 WHERE {$itemidfield} = {$itemid}";

            if (!empty($parentid)) {
                $sql.=" AND {$parentfield} = {$parentid}";
            }

            $res = mysql_query($sql) or die(mysql_error() . " " . $sql);
        }
    }

    $data['data'] = array('success' => true, 'u' => $minmax, 'x' => $xsql);

    return $data;
}

function getCurrentDate()
{
	return date("Y-m-d H:i:s");
}

function getNextIdValue($table_name, $field_name)
{
	$sql = "SELECT MAX($field_name) AS max_field FROM $table_name";
	$res = mysql_query($sql) or die(mysql_error() . " " . $sql);
    $row = mysql_fetch_array($res);
    return $row["max_field"] + 1;
}

// format the date
function adjustDate($format=false, $timestamp=false, $timezone=false)
{
	if (!$format){ $format = 'd-M-Y, h:ia'; } 
	if (!$timezone){ $timezone = 'Africa/Nairobi'; }
	$userTimezone = new DateTimeZone(!empty($timezone) ? $timezone : 'GMT');
	$gmtTimezone = new DateTimeZone('GMT');
	$myDateTime = new DateTime(($timestamp!=false?date("r",(int)$timestamp):date("r")), $gmtTimezone);
	$offset = $userTimezone->getOffset($myDateTime);

	return date($format, ($timestamp!=false?(int)$timestamp:$myDateTime->format('U')) + $offset);
}

//function to check if user already has a photo. if so, return the id, otherwise return 0
function checkUserPhoto($user_id)
{
	$sql = "SELECT id FROM images WHERE category_item_id=$user_id AND image_category_id = ".PROFILE_PIC_CAT_ID."";
	$res = mysql_query($sql) or die(mysql_error() . " " . $sql);
    $row = mysql_fetch_array($res);
    $id = $row["id"];
	if ($id ) { return $id; } else { return 0; }
}

function addDoc($doc_path,$product_id,$doc_type) {
		$qry = "INSERT INTO muz_listing_docs(path,product_id,date_updated,type) VALUES ('$doc_path',$product_id,NOW(),'$doc_type')";
		//echo $qry;
		mysql_query($qry);
}

function editDoc($doc_path,$product_id,$doc_type) {
		$qry = "UPDATE muz_listing_docs SET path='$doc_path',date_updated=NOW(),type='$doc_type' WHERE product_id=$product_id";
		//echo $qry;
		mysql_query($qry);
}

function getImage($id) {

		$qry = "SELECT image_path FROM muz_listing_images WHERE image_id=$id";

		$result=mysql_query($qry);

		$data = mysql_fetch_assoc($result);

		$image_path = $data['image_path'];

		return $image_path;

}

function getPermalink($id) {

		$qry = "SELECT category_permalink FROM muz_categories WHERE category_id=$id";

		$result=mysql_query($qry);

		$data = mysql_fetch_assoc($result);

		$category_permalink = $data['category_permalink'];

		return $category_permalink;

}

function getImageCategory($id,$cat) {

		$qry = "SELECT image_path FROM muz_listing_images WHERE image_category='$cat' AND image_product_id=$id AND approved='y' ORDER BY image_id LIMIT 0,1";

		//echo "ins - $qry<br>";

		$result=mysql_query($qry);

		$data = mysql_fetch_assoc($result);

		$image_path = $data['image_path'];

		return $image_path;

}

function getthumbimg($id,$cat) {

		$qry = "SELECT image_thumb_210x210 FROM muz_listing_images WHERE image_category='$cat' AND image_product_id=$id AND approved='y' ORDER BY image_id LIMIT 0,1";

		//echo "ins - $qry<br>";

		$result=mysql_query($qry);

		$data = mysql_fetch_assoc($result);

		$image_path = $data['image_thumb_210x210'];

		return $image_path;

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

		//echo "ins - $qry<br>";

		$result=mysql_query($qry);

		$data = mysql_fetch_assoc($result);

		$image_id = $data['image_id'];

		return $image_id;

}

function getNextId($tablename,$field) {

		$qry = "SELECT MAX($field) as product_id FROM $tablename";

		$result=mysql_query($qry);

		$product = mysql_fetch_assoc($result);

		$product_id = $product['product_id'];

		$product_id = $product_id + 1;

		return $product_id;

}

function loggedin() {

		if ($_SESSION['SESS_USER_NAME']) { $res=true; } else { $res=false;}

		return $res;

}

function updateAccess($table,$field,$field_id,$field_value) {//update members(table) set member_hits(field)=member_hits+1 where memberid(field_id)=1(field_value)

	$accesssql = "UPDATE $table SET $field=$field+1 WHERE $field_id=$field_value";

	//echo "accesssql - $accesssql<br>";

	mysql_query($accesssql);

}


function tagCloud(){
        global $sitepath;
        //get tags from db
        $tagqry = "SELECT * FROM search_tags_summary ORDER BY num_times DESC LIMIT 0,8 ";
        $tagresult=mysql_query($tagqry);
        //echo "tagqry - " . $tagqry;exit;
        //get total numer of searches
        $sumqry = "SELECT SUM(num_times) AS thetotal FROM search_tags_summary";
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
            $tag_link = $sitepath.'search.html?st='. $search_tag;
            $the_tag = "<a href='$tag_link' class='tag cloud-$tag_ratio' title='$search_tag'>$search_tag</a>";
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
        $j = '<div class="rcrumbs" id=""><ol class="breadcrumbs">';
        foreach($bread as $ahref => $bread_display){
            $bread_final = ucwords(str_replace(array('-','.php', '.html'),array(' ',''),$bread_display));
            if(!($lastone==$bread_display)){
                //<li><a href="#">Home</a> <span class="divider">/</span></li>
                $j .='<li><a href="'.$ahref.'">'.$bread_final.'</a></li>';
            } else {
                    $j .= "<li><a href='#' class='last'>".$bread_final."</a></li>";
            }     
        }
        return $j.'</ol><div class="clear"></div></div>';
 }
 function BreadCrumb222(){
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
        $j = '<div class="rcrumbs" id="breadcrumbs"><ol class="breadcrumbs">';
        foreach($bread as $ahref => $bread_display){
            $bread_final = ucwords(str_replace(array('-','.php', '.html'),array(' ',''),$bread_display));
            if(!($lastone==$bread_display)){
                //<li><a href="#">Home</a> <span class="divider">/</span></li>
                $j .='<li><a href="'.$ahref.'">'.$bread_final.'</a><span class="divider">></span></li>';
            } else {
                    $j .= "<li><a href='#' class='last'>".$bread_final."</a></li>";
            }     
        }
        return $j.'</ol></div>';
 }


 

 function BreadCrumbMobile(){

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

       

		$j = '<div class="breadcrumb"> ';

       

        foreach($bread as $ahref => $bread_display){



            $bread_final = ucwords(str_replace(array('-','.php', '.html'),array(' ',''),$bread_display));



            if(!($lastone==$bread_display)){

                $j .= '<a href="'.$ahref.'" title="'.$bread_final.'">'.$bread_final.'</a> > ';

            } else {

                	if (strlen($bread_final) > 40) { $bread_final = substr($bread_final,0,37) . "...";	}

                    $j .= $bread_final;

            }     

        }



        return $j.'</div>';

 }

 //save search tags
function save_search_tag($search_tag,$this_site=NULL,$user_id=NULL,$location=NULL){
    global $logged_user_id;    
    //search tags summary
        //get current month and year
        $month = date('n');
        $year = date('Y');
        //check if search tag already exists
        $sch_qry = "SELECT * FROM search_tags_summary WHERE search_tag='$search_tag' AND month=$month AND year=$year";
        $sch_res = mysql_query($sch_qry);
        $sch_recs = mysql_num_rows($sch_res);
        $sch_row = mysql_fetch_assoc($sch_res);
        if ($sch_recs) {
            $num_times  = $sch_row['num_times'];
            $new_num = $num_times + 1;
            //update search tag summary
            $upd_qry = "UPDATE search_tags_summary SET num_times=$new_num WHERE search_tag='$search_tag' AND month=$month AND year=$year";
            mysql_query($upd_qry);
        } else {
            $new_num = 1;
            //insert new record in search tag summary
            $new_qry = "INSERT INTO search_tags_summary(search_tag,num_times,month,year) VALUES('$search_tag',$new_num,$month,$year)";
            mysql_query($new_qry);
        }

        //insert search tags
        $qry = "INSERT INTO search_tags(search_tag,location,created_date";
        if ($logged_user_id){ $qry .= ",user_id"; }
        $qry .=") VALUES ('$search_tag','$this_site',NOW()";
        if ($logged_user_id){ $qry .= ",$logged_user_id"; }
        $qry .= ")";
        mysql_query($qry);
}
//end save search tags


function showimage($img_name,$img_width,$img_height,$cropratio=NULL,$imgtitle=NULL,$round) {

	global $sitepath;

	$theimg.="<img src='$sitepath". "includes/image.php/$img_name[$i]?width=$img_width&amp;height=$img_height";

	if ($cropratio) { $theimg.= "&amp;cropratio=$cropratio"; }

	$theimg.="&amp;quality=85&amp;image=$sitepath" . "$img_name' alt='$imgtitle' ";

	$theimg.=" class='avatar";

	if ($round==1) { $theimg.=" round_1px "; }

	if ($round==2) { $theimg.=" round_2px "; }

	if ($round==3) { $theimg.=" round_3px "; }

	if ($round==5) { $theimg.=" round_5px "; }

	if ($round==10) { $theimg.=" round_10px "; }

	$theimg.="' />";

	return $theimg;

}

function getMonth($num) {

	if ($num==1) { $month = "Jan"; }

	if ($num==2) { $month = "Feb"; }

	if ($num==3) { $month = "Mar"; }

	if ($num==4) { $month = "Apr"; }

	if ($num==5) { $month = "May"; }

	if ($num==6) { $month = "Jun"; }

	if ($num==7) { $month = "Jul"; }

	if ($num==8) { $month = "Aug"; }

	if ($num==9) { $month = "Sep"; }

	if ($num==10) { $month = "Oct"; }

	if ($num==11) { $month = "Nov"; }

	if ($num==12) { $month = "Dec"; }

  return $month;

}



//Check if magic qoutes is on then stripslashes if needed

function checkInteger($var) {

	if(preg_match('!^\d+$!', $var)) {  return true; } else { return false; }

}

function printUserSaves($user) {

	global $sitepath; global $show_no_image; 

	$show = false;

	$qry="SELECT saved_id,saved_product_id,parent_id FROM saved_adverts WHERE saved_username='$user'";

	$saves = mysql_fetch_assoc($result);

	$userFavPrint = "";

	

	$qRes = mysql_query($qry); $z=0;

	while(list($saved_id) = mysql_fetch_array($qRes))

		{

		$saved_id = mysql_result($qRes,$z,"saved_id");

		$parent_id = mysql_result($qRes,$z,"parent_id");

		$product_id = mysql_result($qRes,$z,"saved_product_id");

		if ($parent_id=='p') { $table = "property_adverts"; $link="$sitepath". "property/categories"; $category=$sitepath."property/";$catname="Property";}

		if ($parent_id=='t') { $table = "tender_adverts"; $link="$sitepath". "tenders/categories"; $category=$sitepath."tenders/";$catname="Tenders";}

		if ($parent_id=='c') { $table = "class_adverts"; $link="$sitepath". "categories"; $category=$sitepath;$catname="General Classifieds";}

		if ($parent_id=='j') { $table = "job_adverts"; $link="$sitepath". "jobs/categories"; $category=$sitepath."jobs/";$catname="Jobs";}

		if ($parent_id=='a') { $table = "auto_adverts"; $link="$sitepath". "autos/categories"; $category=$sitepath."autos/";$catname="Automobiles";}

		if ($z%2) {$theclass="even_bg";} else {$theclass="odd_bg";}

		

		$pqry="SELECT product_title, product_price, cat_parent_id, category_permalink";

		if ($parent_id!='j') { $pqry .= ", price_currency"; }

		$pqry.=" FROM $table a, class_categories c WHERE a.category_id=c.category_id AND product_id=$product_id";

		//echo "pqry - $pqry<br>";

		$products = mysql_fetch_assoc(mysql_query($pqry));

		$product_title = titlecase($products['product_title']);

		if (strlen($product_title) > 73) { $product_title = substr($product_title,0,70) . "...";} 

		$product_mainimg = getmainimg($product_id,$parent_id);

		$category_picture = $products['category_picture'];

		$cat_perm = $products['category_permalink'];

		if ($parent_id=='c') { $parent_perm = getPermalink($products['cat_parent_id']);$cat_perm = $parent_perm."/".$cat_perm; }

		$product_price = $products['product_price'];

		$price_currency = $products['price_currency'];

		$product_price_fmt = format_num($product_price);

		$seo_title = generate_seo_link($product_title,$replace = '-',$remove_words = true,$words_array = array());

		$seo_title = $product_id . "-" . $seo_title . "/";

		$userFavPrint .= "<div class='message round_3px $theclass txtwhite'>";

		$userFavPrint .= "<div class='imgCheck'><a href='$link/$seo_title'>";

		if (($parent_id=='t') || ($parent_id=='j')) {$product_mainimg = $category_picture;}

		if (!$product_mainimg) { $product_mainimg = $show_no_image;}

		if ($product_mainimg) {

		$userFavPrint .= "<img src='$sitepath"."includes/image.php/". $product_mainimg ."?width=40&amp;height=40&amp;cropratio=1:1&amp;quality=85&amp;image=$sitepath"."$product_mainimg'/>";

		}

		$userFavPrint .= "</div>";

		$userFavPrint .= "<div class='mTitle'><a href='$link/$cat_perm/$seo_title'>$product_title</a><br></div> ";

		$userFavPrint .= "<div class='mDescription'>Category: <a href='$category'>$catname</a><br>";

		if ($product_price) {

			$userFavPrint .= "Cost: $price_currency"." $product_price_fmt";

		}

		$userFavPrint .= "</div><a href='#' title='Delete Item' class='delete mLast deleted' id='$saved_id' onclick=\"return confirm('Are you sure you want to delete?');)\"></a>";

		$userFavPrint .= "</div>";//close outer div

		

		

		$z++;

	}	

	return $userFavPrint;

}

function format_num($num) {

	return number_format($num,0, '.', ',');

}

/*loan calculator*/

function monthly_payment($loan_amount, $interest_rate, $loan_term, $currency_symb, $decimals, $dec_point, $thousands_sep, $down_payment, $format_numbers = false){

	    $new_amount = $loan_amount - (($loan_amount * $down_payment)/100);

		$payment = $new_amount * $interest_rate / 100 / 12 / (1 - pow((1 + $interest_rate / 100 / 12), -$loan_term*12));

	    if($format_numbers) $payment = $currency_symb.number_format($payment, $decimals, $dec_point, $thousands_sep);

		return $payment;

}

function getCurrentDateAds($cat){

	$table = getTableName($cat);

	$qry="SELECT product_title FROM $table WHERE substring(date_posted,1,10)=substring(now(),1,10)";

	$totalrecs=mysql_num_rows(mysql_query($qry));

	return $totalrecs;

}

function getYesterdayAds($cat){

	$table = getTableName($cat);

	$qry="SELECT product_title FROM $table WHERE substring(date_posted,1,10)=substring(DATE_SUB(NOW(), INTERVAL 1 DAY),1,10)";//YESTERDAY

	$totalrecs=mysql_num_rows(mysql_query($qry));

	return $totalrecs;

}

function getLastWeekAds($cat){

	$table = getTableName($cat);

	$qry="SELECT product_title FROM $table WHERE substring(date_posted,1,10) <= substring(NOW(),1,10) AND substring(date_posted,1,10) >= substring(DATE_SUB(NOW(), INTERVAL 7 DAY),1,10)";//ONE WEEK

	//echo "qry - $qry<br>";

	$totalrecs=mysql_num_rows(mysql_query($qry));

	return $totalrecs;

}

function getLastMonthAds($cat){

	$table = getTableName($cat);

	$qry="SELECT product_title FROM $table WHERE substring(date_posted,1,10) <= substring(NOW(),1,10) AND substring(date_posted,1,10) >= substring(DATE_SUB(NOW(), INTERVAL 1 MONTH),1,10)";//ONE WEEK

	//echo "qry - $qry<br>";

	$totalrecs=mysql_num_rows(mysql_query($qry));

	return $totalrecs;

}

function getLast3MonthAds($cat){

	$table = getTableName($cat);

	$qry="SELECT product_title FROM $table WHERE substring(date_posted,1,10) <= substring(NOW(),1,10) AND substring(date_posted,1,10) >= substring(DATE_SUB(NOW(), INTERVAL 3 MONTH),1,10)";//ONE WEEK

	//echo "qry - $qry<br>";

	$totalrecs=mysql_num_rows(mysql_query($qry));

	return $totalrecs;

}



function getActiveAdvertsNum($cat) {

		$table = getTableName($cat);

		$qry="SELECT product_title FROM $table WHERE active='y'";

		$totalrecs=mysql_num_rows(mysql_query($qry));

		return $totalrecs;

}

function getNormalAdvertsNum($cat) {

		$table = getTableName($cat);

		$qry="SELECT product_title FROM $table WHERE active='n'";

		$totalrecs=mysql_num_rows(mysql_query($qry));

		return $totalrecs;

}

function getUserAdsNum($username) {

		//get all ads

		$class_num=mysql_num_rows(mysql_query("SELECT product_id FROM class_adverts WHERE username='$username'"));

		$property_num=mysql_num_rows(mysql_query("SELECT product_id FROM property_adverts WHERE username='$username'"));

		$autos_num=mysql_num_rows(mysql_query("SELECT product_id FROM auto_adverts WHERE username='$username'"));

		$tenders_num=mysql_num_rows(mysql_query("SELECT product_id FROM tender_adverts WHERE username='$username'"));

		$jobs_num=mysql_num_rows(mysql_query("SELECT product_id FROM job_adverts WHERE username='$username'"));

		$totalrecs = $class_num + $property_num + $autos_num + $tenders_num + $jobs_num;

		if (!$totalrecs) { $totalrecs = 0; }

		return $totalrecs;

}

function getUserAdvertsNum($cat,$username) {

		$table = getTableName($cat);

		$qry="SELECT product_title FROM $table WHERE username='$username'";

		$totalrecs=mysql_num_rows(mysql_query($qry));

		if (!$totalrecs) { $totalrecs = 0; }

		return $totalrecs;

}

function getSuspendedAdvertsNum($cat) {

		$table = getTableName($cat);

		$qry="SELECT product_title FROM $table WHERE suspended='y'";

		$totalrecs=mysql_num_rows(mysql_query($qry));

		return $totalrecs;

}

function getAdvertsNum($cat) {

		$table = getTableName($cat);

		$qry="SELECT product_title FROM $table";

		$totalrecs=mysql_num_rows(mysql_query($qry));

		return $totalrecs;

}

function getImagesNum($cat) {

		$table = getTableName($cat);

		$qry="SELECT * FROM muz_listing_images WHERE image_category='$cat' AND approved='y'";

		$totalrecs=mysql_num_rows(mysql_query($qry));

		if (!$totalrecs) {$totalrecs=0;}

		return $totalrecs;

}

function getPhotoNum($id,$cat) {

		$qry="SELECT image_id FROM muz_listing_images WHERE image_category='$cat' AND image_product_id=$id";

		$totalrecs=mysql_num_rows(mysql_query($qry));

		if (!$totalrecs) {$totalrecs=0;}

		return $totalrecs;

}



//user admin functions

function getCurrentDateUsers(){

	$qry="SELECT username FROM class_members WHERE substring(validated_date,1,10)=substring(now(),1,10)";//TODAY

	$totalrecs=mysql_num_rows(mysql_query($qry));

	return $totalrecs;

}

function getYesterdayUsers(){

	$qry="SELECT username FROM class_members WHERE substring(validated_date,1,10)=substring(DATE_SUB(NOW(), INTERVAL 1 DAY),1,10)";//YESTERDAY

	$totalrecs=mysql_num_rows(mysql_query($qry));

	return $totalrecs;

}

function getLastWeekUsers(){

	$qry="SELECT username FROM class_members WHERE substring(validated_date,1,10) <= substring(NOW(),1,10) AND substring(validated_date,1,10) >= substring(DATE_SUB(NOW(), INTERVAL 7 DAY),1,10)";//ONE WEEK

	$totalrecs=mysql_num_rows(mysql_query($qry));

	return $totalrecs;

}

function getLastMonthUsers(){

	$qry="SELECT username FROM class_members WHERE substring(validated_date,1,10) <= substring(NOW(),1,10) AND substring(validated_date,1,10) >= substring(DATE_SUB(NOW(), INTERVAL 1 MONTH),1,10)";//ONE MONTH

	$totalrecs=mysql_num_rows(mysql_query($qry));

	return $totalrecs;

}

function getLast3MonthUsers(){

	$qry="SELECT username FROM class_members WHERE substring(validated_date,1,10) <= substring(NOW(),1,10) AND substring(validated_date,1,10) >= substring(DATE_SUB(NOW(), INTERVAL 3 MONTH),1,10)";//THREE MONTHS

	$totalrecs=mysql_num_rows(mysql_query($qry));

	return $totalrecs;

}

//end user admin functions



function getUserSaves($user) {

	$show = false;

	$qry="SELECT * FROM saved_adverts WHERE saved_username='$user'";

	$totalrecs=mysql_num_rows(mysql_query($qry));

	if ($totalrecs) { $show = $totalrecs; }

	return $show;

}

function getUserSavesNum($user) {

	$totalrecs = 0;

	$qry="SELECT * FROM saved_adverts WHERE saved_username='$user'";

	$totalrecs=mysql_num_rows(mysql_query($qry));

	return $totalrecs;

}



function getUserAdStatus($pid,$user,$cat) {

	$show = true;

	$qry="SELECT * FROM saved_adverts WHERE saved_product_id=$pid AND parent_id='$cat' AND saved_username='$user'";

	//echo "qry - $qry<br>";

	$totalrecs=mysql_num_rows(mysql_query($qry));

	if ($totalrecs) { $show = false; } 

	return $show;

}

function getEstateName($estid) {

	$qry="SELECT estate_name FROM property_estate WHERE estate_id=$estid";

	$result=mysql_query($qry);

	$models = mysql_fetch_assoc($result);

	$estate_name = $models['estate_name'];

	return $estate_name;

}

function getMallTownId($estid) {

	$qry="SELECT z.zone_id as zone_id FROM zone z, mall_adverts a, property_estate e WHERE a.mall_loc=e.estate_id AND e.zone_id=z.zone_id AND estate_id=$estid";

	//echo "qry - $qry<br>";

	$result=mysql_query($qry);

	$models = mysql_fetch_assoc($result);

	$zone_id = $models['zone_id'];

	return $zone_id;

}



function getTownName($townid) {

	$qry="SELECT zone_name FROM zone WHERE zone_id=$townid";

	//echo "qry - $qry<br>";

	$result=mysql_query($qry);

	$models = mysql_fetch_assoc($result);

	$zone_name = $models['zone_name'];

	return $zone_name;

}

function getZoneId($estate_id) {

	$qry="SELECT zone_id FROM property_estate WHERE estate_id=$estate_id";

	//echo "qry - $qry<br>";

	$result=mysql_query($qry);

	$data = mysql_fetch_assoc($result);

	$zone_id = $data['zone_id'];

	return $zone_id;

}



function getModelName($modelid) {

	$qry="SELECT model_name FROM auto_models WHERE model_id=$modelid";

	$result=mysql_query($qry);

	$models = mysql_fetch_assoc($result);

	$model_name = $models['model_name'];

	return $model_name;

}

function getAdminsNum() {

	$qry="SELECT * FROM class_members WHERE user_type IN (4,5) AND validated='y' AND user_suspended='n'";

	$numrows=mysql_num_rows(mysql_query($qry));

	return $numrows;

}

function getFullAdminsNum() {

	$qry="SELECT * FROM class_members WHERE user_type IN (5) AND validated='y' AND user_suspended='n'";

	$numrows=mysql_num_rows(mysql_query($qry));

	return $numrows;

}

function getLtdAdminsNum() {

	$qry="SELECT * FROM class_members WHERE user_type IN (4) AND validated='y' AND user_suspended='n'";

	$numrows=mysql_num_rows(mysql_query($qry));

	return $numrows;

}

function getNonactiveNum() {

	$qry="SELECT * FROM class_members WHERE user_suspended='y'";

	$numrows=mysql_num_rows(mysql_query($qry));

	return $numrows;

}

function getNonvalNum() {

	$qry="SELECT * FROM class_members WHERE validated='n'";

	$numrows=mysql_num_rows(mysql_query($qry));

	return $numrows;

}

function getUsersNum() {

	$qry="SELECT * FROM class_members";

	$numrows=mysql_num_rows(mysql_query($qry));

	return $numrows;

}

function getVideoNum($artist_id){

	global $video_cat;

	$qry="SELECT * FROM muz_articles WHERE article_category_id=$video_cat AND article_artist_id=$artist_id AND article_validated='y' AND suspended='n'";

	$numrows=mysql_num_rows(mysql_query($qry));

	return $numrows;

}



function getToneNum($artist_id) {

	global $ringtone_cat;

	$qry="SELECT * FROM muz_articles WHERE article_category_id=$ringtone_cat AND article_artist_id=$artist_id AND article_validated='y' AND suspended='n'";

	$numrows=mysql_num_rows(mysql_query($qry));

	return $numrows;

}







function codeClean($var)

{

    if (is_array($var)) {

		foreach($var as $key => $val) {

			$output[$key] = codeClean($val);

    	}

    } else {

		$var = strip_tags(trim($var));

		if (function_exists("get_magic_quotes_gpc")) {

			$output = sqlEscapeString((get_magic_quotes_gpc())? stripslashes($var): $var);

		} else {

			$output = sqlEscapeString($var);

		}

	}

	if (!empty($output))

		return $output;

}



/// function to generate random number ///////////////

function random_generator($digits){

	srand ((double) microtime() * 10000000);

	//Array of alphabets

	$input = array ("A", "B", "C", "D", "E","F","G","H","I","J","K","L","M","N","O","P","Q",

	"R","S","T","U","V","W","X","Y","Z","a", "b", "c", "d", "e","f","g","h","i","j","k","l","m","n","o","p","q",

	"r","s","t","u","v","w","x","y","z");

	

	$random_generator="";// Initialize the string to store random numbers

		for($i=1;$i<$digits+1;$i++){ // Loop the number of times of required digits

		

			if(rand(1,2) == 1){// to decide the digit should be numeric or alphabet

			// Add one random alphabet 

			$rand_index = array_rand($input);

			$random_generator .=$input[$rand_index]; // One char is added

			

			}else{

			

			// Add one numeric digit between 1 and 10

			$random_generator .=rand(1,10); // one number is added

			} // end of if else

		

		} // end of for loop 

	

	return $random_generator;

} // end of function





function formatMoney($number, $fractional=false) {

    if ($fractional) {

        $number = sprintf('%.2f', $number);

    }

    while (true) {

        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);

        if ($replaced != $number) {

            $number = $replaced;

        } else {

            break;

        }

    }

    return $number;

} 





function getmaincat($catid) {

	$mainc = "SELECT category_name FROM class_categories WHERE category_id=$cat_id";

	//echo "mainc: $mainc<br>";

	$mainres = mysql_query($mainc);

	$maindata = mysql_fetch_assoc($mainres);

	$maincatname = $maindata['category_name'];

	return $maincatname;

}



function sentencecase($data) {

     return ucfirst(strtolower($data));

}



function lowercase($data) {

     return strtolower($data);

}



function titlecase($data) {

     return ucwords(strtolower($data));

}





function getExtension($str) {



         $i = strrpos($str,".");

         if (!$i) { return ""; } 



         $l = strlen($str) - $i;

         $ext = substr($str,$i+1,$l);

         return $ext;

 }

 

function generate_options($from,$to,$callback=false)

	{

	    $reverse=false;

	 

	    if($from>$to)

	    {

	        $tmp=$from;

	        $from=$to;

	        $to=$tmp;

	 

	        $reverse=true;

	    }

	 

	    $return_string=array();

	    for($i=$from;$i<=$to;$i++)

	    {

	        $return_string[]='

	        <option value="'.$i.'">'.($callback?$callback($i):$i).'</option>

	        ';

	    }

	 

	    if($reverse)

	    {

	        $return_string=array_reverse($return_string);

	    }

	 

	    return join('',$return_string);

	}

	 

	function callback_month($month)

	{

	    return date('M',mktime(0,0,0,$month,1));

	}

	 

	/* and here is how we use it (taken from our XHTML code above):

	generate_options(1,31);             // generate days

	generate_options(date('Y'),1900);           // generate years, in reverse

	generate_options(1,12,'callback_month');        // generate months

	*/





function getlastlogin($username) {

	

	$qry="SELECT logintime FROM biashara_access_logs WHERE username='$username' ORDER BY logintime DESC LIMIT 0,1";

	$result=mysql_query($qry);

	$login = mysql_fetch_assoc($result);

	$lastlogin = $login['logintime'];

	return date("d/m/Y, h:m a",php_date($lastlogin));

	

}





function printFavorites($favorites) {

	global $sitepath;

	

	$favorites = trim($favorites);

	$keyarray = explode(";", $favorites); 

	$thenum = count($keyarray); // get number of items in favarray array

	

	if ($thenum)

	{

			for($p=0;$p<$thenum;$p++)

			{

				$keyarray[$p] = trim($keyarray[$p]);

				$mQ = $mQ." category_id = ".$keyarray[$p]." or";

			}

			$mQ = trim($mFgeQ); 

			$mQ = removelastor($mQ);

	}

	

	

	$qry="SELECT category_name, category_id, category_permalink FROM class_categories WHERE $mQ ORDER BY category_name";

	//echo "qry - $qry<br>";

	$result=mysql_query($qry);

	

			$thecats = "";

			$z = 0;

			while(list($category_name) = mysql_fetch_array($result))

			{

				// store in vars for later printing

				$catname = mysql_result($result,$z,"category_name");

				$catid = mysql_result($result,$z,"category_id");

				$catperm = mysql_result($result,$z,"category_permalink");

				$thecats .= " " . "<a href=\"" . $sitepath . "categories/?cat_perm=$catperm\">$catname</a>" . ",";

				$z++;

									

			} // end while loop

	

	$thecats = removelastcomma($thecats);

	return $thecats;

	

}





function showmixdate($thedate) {

		return date("M-Y",php_date($thedate));

}

function showfulldate($thedate) {

		return date("d-M-Y",php_date($thedate));

}

function showblogdate($thedate) {

		return date("M d, Y",php_date($thedate));

}

function showsepdate($thedate) {

		return date("d/m/Y",php_date($thedate));

}



function conv_date($thedate) {



		$dateparts = explode("/", $thedate);

		$result = $dateparts[2]."-".$dateparts[1]."-".$dateparts[0];

		return strtotime($result);

}



function mysql_date($thedate) {

	return date( 'Y-m-d H:i:s', $thedate );

}



function php_date($thedate) {

	return strtotime( $thedate );

}



function rev_date($thedate) {    // convert this 06/11/2009

	$ndate = explode("/",$thedate);

	return $ndate[2]."-".$ndate[1]."-".$ndate[0];

}



function show_date($thedate) {    // convert this 06/11/2009

	$ndate = explode("-",$thedate);

	return $ndate[2]."/".$ndate[1]."/".$ndate[0];

}



function removelastslash($str) {

  $startpos = strlen($str) - 1;

  $getstring = substr($str,$startpos);

  if ($getstring == "/") {

      return substr($str,0,$startpos);

  } else {

      return $str;

  }

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



function removelastcomma($str) {

  $startpos = strlen($str) - 1;

  $getstring = substr($str,$startpos);

  if ($getstring == ",") {

      return substr($str,0,$startpos);

  } else {

      return $str;

  }

}



function mysql_real_unescape_string($input) {



	$output = $input;

	$output = str_replace("\\\\", "\\", $output);

	$output = str_replace("\'", "'", $output);

	$output = str_replace('\"', '"', $output);



	return $output;



}



function createRandomPassword() {

    $chars = "abcdefghijkmnopqrstuvwxyz023456789";

    srand((double)microtime()*1000000);

    $i = 0;

    $pass = '' ;

    while ($i <= 7) {

        $num = rand() % 33;

        $tmp = substr($chars, $num, 1);

        $pass = $pass . $tmp;

        $i++;

    }

    return $pass;

}



// return true if email matches valid email syntax

function isEmail($email) {

	return preg_match("/^(((([^]<>()[\.,;:@\" ]|(\\\[\\x00-\\x7F]))\\.?)+)|(\"((\\\[\\x00-\\x7F])|[^\\x0D\\x0A\"\\\])+\"))@((([[:alnum:]]([[:alnum:]]|-)*[[:alnum:]]))(\\.([[:alnum:]]([[:alnum:]]|-)*[[:alnum:]]))*|(#[[:digit:]]+)|(\\[([[:digit:]]{1,3}(\\.[[:digit:]]{1,3}){3})]))$/", $email);

} 



//Function to sanitize values received from the form. Prevents SQL injection

function clean($value) {

	/*$str = @trim($str);

	if(get_magic_quotes_gpc()) {

		$str = stripslashes($str);

	}

	return mysql_real_escape_string($str);*/

	

		//function to check for both sql injection and cross site scripting

		 //Trim the value

		 $value = trim($value);

		 

		// Stripslashes

		if (get_magic_quotes_gpc()) {

			$value = stripslashes($value);

		}

		

		 // Convert all &lt;, &gt; etc. to normal html and then strip these

		 $value = strtr($value,array_flip(get_html_translation_table(HTML_ENTITIES)));

		

		 // Strip HTML Tags

		 $value = strip_tags($value);

		

		// Quote the value

		$value = mysql_real_escape_string($value);

		$value = htmlspecialchars ($value);

		return $value;

}

//Function to sanitize values received from the form. Prevents SQL injection

function clean_quotes($value) {

		 //Trim the value

		 $value = trim($value);

		 

		// Stripslashes

		if (get_magic_quotes_gpc()) {

			$value = stripslashes($value);

		}

		// Quote the value

		$value = mysql_real_escape_string($value);

		//$value = htmlspecialchars ($value);

		return $value;

}





$bad_words = array('a','and','the','an','it','is','with','can','of','why','not');

//echo generate_seo_link('Another day and a half of PHP meetings','-',true,$bad_words); - example



/* takes the input, scrubs bad characters */

function generate_seo_link($input,$replace = '-',$remove_words = true,$words_array = array())

{

	  //make it lowercase, remove punctuation, remove multiple/leading/ending spaces

	  $return = trim(ereg_replace(' +',' ',preg_replace('/[^a-zA-Z0-9\s]/','',strtolower($input))));

	

	  //remove words, if not helpful to seo

	  //i like my defaults list in remove_words(), so I wont pass that array

	  if($remove_words) { $return = remove_words($return,$replace,$words_array); }

	

	  //convert the spaces to whatever the user wants

	  //usually a dash or underscore..

	  //...then return the value.

	  return str_replace(' ',$replace,$return);

}



/* takes an input, scrubs unnecessary words */

function remove_words($input,$replace,$words_array = array(),$unique_words = true)

{

	  //separate all words based on spaces

	  $input_array = explode(' ',$input);

	  //create the return array

	  $return = array();

	

	  //loops through words, remove bad words, keep good ones

	  foreach($input_array as $word)

	  {

		//if it's a word we should add...

		if(!in_array($word,$words_array) && ($unique_words ? !in_array($word,$return) : true))

		{

		  $return[] = $word;

		}

	  }

	

	  //return good words separated by dashes

	  return implode($replace,$return);

}

//Text watermark

function watermark_text($oldimage_name, $new_image_name){

    global $font_path, $font_size, $water_mark_text_1, $water_mark_text_2;

    list($owidth,$oheight) = getimagesize($oldimage_name);

    $width = $height = 300;    

    $image = imagecreatetruecolor($width, $height);

    $image_src = imagecreatefromjpeg($oldimage_name);

    imagecopyresampled($image, $image_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);

   // $black = imagecolorallocate($image, 0, 0, 0);

    $blue = imagecolorallocate($image, 79, 166, 185);

   // imagettftext($image, $font_size, 0, 30, 190, $black, $font_path, $water_mark_text_1);

    imagettftext($image, $font_size, 0, 68, 190, $blue, $font_path, $water_mark_text_2);

    imagejpeg($image, $new_image_name, 100);

    imagedestroy($image);

    unlink($oldimage_name);

    return true;

}







function addWatermark($image)

{

    global $font_path, $font_size, $water_mark_text;

	if ($font_path == false) return;

    $borderOffset = 4;

    $dimensions = ImageTtfBBox($font_size, 0, $font_path, $water_mark_text . "@");

    $lineWidth = ($dimensions[2] - $dimensions[0]);

    $textX = (ImageSx($image) - $lineWidth) / 2;

    $textY = $borderOffset - $dimensions[7];

    $maroon = imagecolorallocate($dst, 134, 22, 0);

	$white = imagecolorallocate($dst, 255, 255, 255);

	// Add some shadow to the text

	imagettftext($image, $font_size, 0,  $textX, $textY, $white, $font_path, $water_mark_text);

    imagettftext($image, $font_size, 0, $textX, $textY, $maroon, $font_path, $water_mark_text);

}


//upload and resize image
//resizeUpload($field, $pic_dir, $name_dir, NULL, NULL, $max_width, $max_height)
function resizeUpload($field,$pic_dir,$name_dir,$cropratio=NULL,$watermark=NULL,$max_width,$max_height,$add_to_filename=NULL, $quality=90){
		global $font_path, $font_size, $water_mark_text_1, $water_mark_text;
		$maxwidth = $max_width; // Max new width or height, can not exceed this value.
		$maxheight = $max_height;
		$dir = $pic_dir; // Directory to save resized image. (Include a trailing slash - /)
		// Collect the post variables.
		$postvars = array(
			"image"    => trim($_FILES["$field"]["name"]),
			"image_tmp"    => $_FILES["$field"]["tmp_name"],
			"image_size"    => (int)$_FILES["$field"]["size"],
			);
			// Array of valid extensions.
			$valid_exts = array("jpg","jpeg","gif","png");
			$mod_exts = array("gif","png");
			// Select the extension from the file.
			$ext = end(explode(".",strtolower(trim($_FILES["$field"]["name"]))));
			//echo ("Image size: " . $postvars["image_size"] . "<br> Ext: " . $ext . "<br>");
			// Check is valid extension.
			if(in_array($ext,$valid_exts)){
				if($ext == "jpg" || $ext == "jpeg"){
					$image = imagecreatefromjpeg($postvars["image_tmp"]);
				}
				else if($ext == "gif"){
					$image = imagecreatefromgif($postvars["image_tmp"]);
				}
				else if($ext == "png"){
					$image = imagecreatefrompng($postvars["image_tmp"]);
				}
				// Grab the width and height of the image.
				list($width,$height) = getimagesize($postvars["image_tmp"]);
				// Ratio cropping
				$offsetX	= 0;
				$offsetY	= 0;
				if ($cropratio) {
						$cropRatio = explode(':', (string) $cropratio);
						$ratioComputed		= $width / $height;
						$cropRatioComputed	= (float) $cropRatio[0] / (float) $cropRatio[1];
						if ($ratioComputed < $cropRatioComputed)
						{ // Image is too tall so we will crop the top and bottom
							$origHeight	= $height;
							$height		= $width / $cropRatioComputed;
							$offsetY	= ($origHeight - $height) / 2;
							$smallestSide = $width;
						}
						else if ($ratioComputed > $cropRatioComputed)
						{ // Image is too wide so we will crop off the left and right sides
							$origWidth	= $width;
							$width		= $height * $cropRatioComputed;
							$offsetX	= ($origWidth - $width) / 2;
							$smallestSide = $height;
						}
				}
				// We get the other dimension by multiplying the quotient of the new width or height divided by
				// the old width or height.
			   $w_adjust = ($maxwidth / $width);
			   $h_adjust = ($maxheight / $height);
			   if (($width >= $maxwidth)||($height >= $maxheight)) {
				   if($w_adjust <= $h_adjust)
				   {
					   $newwidth=floor($width*$w_adjust);
					   $newheight=floor($height*$w_adjust);
				   } else {
					   $newwidth=floor($width*$h_adjust);
					   $newheight=floor($height*$h_adjust);
				   }
			   } else {
				   	$newwidth=$width;
					$newheight=$height;
			   }
				// Create temporary image file.
				$tmp = imagecreatetruecolor($newwidth,$newheight);
				
				// Copy the image to one with the new width and height.
					imagecopyresampled($tmp,$image,0,0,$offsetX,$offsetY,$newwidth,$newheight,$width,$height);
					// Create random 5 digit number for filename. Add to current timestamp.
					$rand = rand(10000,99999);
					$rand .= time();
					$origfilename = $name_dir.$rand ;
                    if ($add_to_filename){ $origfilename .= "_".$add_to_filename; }
                    $origfilename .= ".jpg";
					
					$filename = $dir.$rand;
                    if ($add_to_filename){ $filename .= "_".$add_to_filename; }
                    $filename .= ".jpg";

				if ($watermark) {
					//Apply watermark here					
					$maroon = imagecolorallocate($tmp, 134, 22, 0);
					$white = imagecolorallocate($tmp, 255, 255, 255);
					/*$base_height = $newheight-20;
					$base_width = $newwidth/5;*/
					//$borderOffset = 4;
					$dimensions = imagettfbbox($font_size, 0, $font_path, $water_mark_text);
					$lineWidth = ($dimensions[2] - $dimensions[0]);
					$textX = (ImageSx($tmp) - $lineWidth) / 2;
					$textY = ($newheight/10)*9;					
				   // Add some shadow to the text					
					imagettftext($tmp, $font_size, 0,  $textX+1,$textY+1,  $white, $font_path, $water_mark_text);
					imagettftext($tmp, $font_size, 0, $textX, $textY,  $maroon, $font_path, $water_mark_text);
				}							

				// Create image file with specified quality in % (low quality image in less image sharpness)
				imagejpeg($tmp,$filename,$quality);
				return $origfilename;

				imagedestroy($image);
				imagedestroy($tmp);	

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





//Mail functions

function sendEmail($ToEmail,$Subject,$Body,$From,$FromEmail)

{

	$ver = phpversion();

	$Body = preg_replace("!<br \/>!","\n",$Body);



	$headers = '';

	$headers.="From: $From <$FromEmail>\n";

	$headers.="Reply-To: <$FromEmail>\n";

	$headers.="X-Sender: <$FromEmail>\n";

	$headers.="X-Mailer: PHP-$ver \n";

	$headers.="X-Priority: 3\n"; //1 UrgentMessage, 3 Normal

	$headers.="Return-Path: <$FromEmail> \n";



	mail($ToEmail,$Subject,wordwrap($Body),$headers);

}



function verifyLogin($user,$pass)

{

	//Encrypt password for database verification

	$salt = 's+(_a*';

	$pass = md5($pass.$salt);



	$sql = "SELECT pass FROM users WHERE pass = '" . $pass . "' AND user = '" . $user ."'";

	$res = sqlQuery($sql); if(sqlErrorReturn()) sqlDebug(__FILE__,__LINE__,sqlErrorReturn());

	$num = sqlNumRows($res);



	if ($num > 0)

		return true;

	return false;	

}





function logoff()

{

	global $visitor_tracking;



	//when logging off delete from the online users tables if user tracking is enabled

	if (!empty($visitor_tracking) && isset($_SESSION["user"])) {

		$sql = "DELETE FROM onlineusers WHERE user = '" . $_SESSION["user"] . "'";

		$del = sqlQuery($sql); if(sqlErrorReturn()) sqlDebug(__FILE__,__LINE__,sqlErrorReturn());

	}



	// remove all session variables and destroy session

	unset($_SESSION["user"]);

	unset($_SESSION["pass"]);

	unset($_SESSION["logged_in"]);

	unset($_SESSION["admin"]);



	session_destroy();



	if (isset($_COOKIE["user"])) {

		setcookie("user", NULL, time()-3600);

		setcookie("pass", NULL, time()-3600);

	}



	if (isset($_COOKIE[session_name()])) {

    	setcookie(session_name(), NULL, time()-3600);

	}



	// redirect them to anywhere you like.

	header("Location: login.php");

}







function updateUser($user, $email, $first_name, $last_name, $phone, $alt_phone, $fax, $address, $city, $state, $zip)

{

	if (!validateEmail($email))	{

		return 1;

	} elseif (!validatePhone($phone)) {

		return 2;

	} elseif (!validateName($first_name)) {

		return 3;

	} elseif (!validateName($last_name)) {

		return 4;

	} else {

		// Get remote IP

		$ipaddress = ipConvertLong(getenv('REMOTE_ADDR'));

		$sql = "UPDATE users SET ipaddress = " . $ipaddress . ", email = '" . $email . "', first_name = '" . $first_name . "', last_name = '" . $last_name . "', phone = '" . $phone . "', alt_phone = '" . $alt_phone . "', fax = '" . $fax . "', address = '". $address . "', city = '". $city . "', state = '". $state . "', zip = '". $zip . "' WHERE user = '" . $user . "'";		

		$res = sqlQuery($sql); if(sqlErrorReturn()) sqlDebug(__FILE__,__LINE__,sqlErrorReturn());

	return 99;

	}

}



//Reset password functions

function updatePass($user,$pass)

{

	//Encrypt password for database

	$salt = 's+(_a*';

	$new_password = md5($pass.$salt);

	//if user logged in change their session password 

	if (isset($_SESSION["pass"])) {

		$_SESSION["pass"] = "$new_password";

	}



	//if remember me function already set

	//change cookie for remember me

	if (isset($_COOKIE["pass"])) {

		setcookie("pass", "$new_password", time() + (60*60*24*30));

	}



	//perform sqlQuery and update user info in the database

	$sql = "UPDATE users SET pass = '" . $new_password . "' WHERE user = '" . $user . "'";

 	$res = sqlQuery($sql); if(sqlErrorReturn()) sqlDebug(__FILE__,__LINE__,sqlErrorReturn());

}



function generatePassword($len)

{

	$password = "";

	$char = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';



	$c=0;

	while ($c <= $len) {

		$random = rand(1,strlen($char));

		$password .= substr($char,$random -1,1);

	++$c;

	} 



	if (!empty($password))

	    return $password;//echo $password;

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
