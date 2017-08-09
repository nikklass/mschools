<?php
	if (!isset($_SESSION)) session_start();
    error_reporting(0);
    //split the url generated by htaccess
	$self_url = $_GET['x'];
	$self_url_split = explode("/",$self_url);
	if ($self_url_split[0]){ $arg_one = strtolower($self_url_split[0]); }
	if ($self_url_split[1]){ $arg_two = strtolower($self_url_split[1]); }
	if ($self_url_split[2]){ $arg_three = strtolower($self_url_split[2]); }
	  /*echo "arg_one - $arg_one<br>";
	  echo "arg_two - $arg_two<br>";
	  echo "arg_three - $arg_three<br>";*/
	//load appropriate file based on url length
	if ($arg_one=='') {
		include_once("home.php");
	} else if ((($arg_one=='headlines') && (!$arg_two)) || (($arg_one=='international') && (!$arg_two)) || (($arg_one=='overheard') && (!$arg_two)) || (($arg_one=='interviews') && (!$arg_two)) ){
        include_once("category.php");
    } else if ((($arg_one=='headlines') && ($arg_two)) || (($arg_one=='international') && ($arg_two)) || (($arg_one=='overheard') && ($arg_two)) || (($arg_one=='interviews') && ($arg_two)) ){
        include_once("post.php");
    } else if ((($arg_one=='artists')||($arg_one=='djs')||($arg_one=='producers')) && (!$arg_two) && (!$arg_three)) {
        include_once("artists.php");
    } else if ((($arg_one=='artists')||($arg_one=='djs')||($arg_one=='producers')) && ($arg_two) && (!$arg_three)) {
        include_once("artist_page.php");
    } else if (($arg_one=='producers') && ($arg_two) && ($arg_three=='songs')) {
        include_once("songs.php");
    } else if (($arg_one=='producers') && ($arg_two) && ($arg_three=='videos')) {
        include_once("videos.php");
    } else if ((($arg_one=='gallery')||($arg_one=='spy-shots')||($arg_one=='models')) && (!$arg_two) && (!$arg_three)) {
        include_once("gallery.php");
    } else if ((($arg_one=='gallery')||($arg_one=='spy-shots')||($arg_one=='models')) && ($arg_two) && (!$arg_three)) {
        include_once("gallery_page.php");
    } else if (($arg_one=='contacts')) {
        include_once("contacts.php");
    } else if (($arg_one=='email-friend')) {
        include_once("email_friend.php");
    } else if (($arg_one=='message')) {
        include_once("message.php");
    } else if (($arg_one=='edit')) {
        include_once("edit.php");
    } else if (($arg_one=='login')) {
        include_once("login.php");
    } else if (($arg_one=='small-login')) {
        include_once("small_login.php");
    } else if (($arg_one=='register')) {
        include_once("register.php");
    } else if (($arg_one=='registration-confirm')) {
        include_once("registration_confirm.php");
    } else if (($arg_one=='subscription-confirm')) {
        include_once("subscription_confirm.php");
    } else if (($arg_one=='terms')) {
        include_once("terms.php");
    } else if (($arg_one=='terms')) {
        include_once("terms.php");
    } else if (($arg_one=='admin')) {
        include_once("admin/index.php");
    } else if (($arg_one=='aar')) {
        include_once("aar/index.php");
    } else if (($arg_one=='phone_backup')) {
        include_once("phone_backup/index.php");
    } else if (($arg_one=='search')) {
        include_once("search.php");
    } else if (($arg_one=='lyrics') && (!$arg_two) && (!$arg_three)) {
        include_once("lyrics.php");
    } else if (($arg_one=='lyrics') && ($arg_two) && (!$arg_three)) {
        include_once("lyrics.php");
    } else if (($arg_one=='lyrics') && ($arg_two) && ($arg_three)) {
        include_once("lyric_page.php");
    } else if (($arg_one=='dj-mixes') && (!$arg_two) && (!$arg_three)) {
        include_once("mixes.php");
    } else if (($arg_one=='dj-mixes') && ($arg_two) && (!$arg_three)) {
        include_once("mixes.php");
    } else if (($arg_one=='dj-mixes') && ($arg_two) && ($arg_three)) {
        include_once("post_mix.php");
    } else if (($arg_one=='songs') && (!$arg_two) && (!$arg_three)) {
        include_once("songs.php");
    } else if (($arg_one=='songs') && ($arg_two) && (!$arg_three)) {
        include_once("songs.php");
    } else if (($arg_one=='songs') && ($arg_two) && ($arg_three)) {
        include_once("post_audio.php");
    } else if (($arg_one=='categories') && (!$arg_two)) {
        include_once("categories.php");
    } else if (($arg_one=='categories') && ($arg_two)) {
        include_once("category.php");
    } else if (($arg_one=='videos') && (!$arg_two) && (!$arg_three)) {
        include_once("videos.php");
    } else if (($arg_one=='videos') && ($arg_two) && (!$arg_three)) {
        include_once("videos.php");
    } else if (($arg_one=='videos') && ($arg_two) && ($arg_three)) {
        include_once("post_video.php");
    } else if (($arg_one=='liveshows') && (!$arg_two)) {
        include_once("liveshows.php");
    } else if (($arg_one=='liveshows') && ($arg_two)) {
        include_once("post_live.php");
    } 
?>