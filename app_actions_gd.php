<?php
//error_reporting(E_ALL);
//error_reporting(1);
include_once("../auth/auth.php");
include_once("../includes/conns.php");
include_once("../includes/funcs.php");

$action = $_GET['action'];

switch ($action) {

    case 1:
        //REGISTER NEW USER
        //get the POST values
        $success = 1; $err_msg = "";$display_message = 0;
		$fullnames = $_POST['FullNames'];
		$dob = $_POST['DOB'];
		$PIN = "6543"; 
		
		$fullnames = clean(trim($fullnames));
		
		//Input Validations
		if (($fullnames == '') && (!$err_msg)) {
			$err_msg =  'Please enter full names';
			$display_message = 1;
			$success = 0;
		}
        
        $dob_array = explode("-", $dob); 
        $year = trim($dob_array[2]);
        $month = trim($dob_array[1]);
        //$month = str_pad($month, 2, '0', STR_PAD_LEFT);  //pad month with left side zeros i.e convert 6 to 06 etc
        $day = trim($dob_array[0]);
        $new_date = $year."-".$month."-".$day;
        $new_date = $new_date;// . " 00:00:00";
		
		if ($success) {
			//insert query
			$qry = "INSERT INTO aar_users (fullnames,dob,created_at) VALUES ('$fullnames','$new_date',NOW())";
			
			if (mysql_query($qry)) 
			{
				$success_msg = "Account created successfully";
			} else {
				$err_msg = 'An error occured. Please try again';
				$display_message = 1;
			   	$success = 0;
			}
		}

        echo json_encode(array('success' => $success, 'err_msg' => $err_msg, 'success_msg' => $success_msg, 'pin' => $PIN,  'dob' => $dob, 'fullnames' => $fullnames, 'display_message' => $display_message));
        break;

    case 2:
        //LOGIN USER
        $success = 1;
		$display_message = 0;
        $err_msg = "";
        $reload_page = 0;
        $PIN = $_POST['PIN'];    
        $phone = $_POST['phone'];    
        if (!$PIN) {
            $success = 0;
			$display_message = 1;
            $err_msg = "Please enter PIN";
        }
        if (((strlen($PIN) > 4 )|| (strlen($PIN) < 4 )) && !$err_msg) {
            $success = 0;
			$display_message = 1;
            $err_msg = "PIN must be 4 digits";
        }
        
        if ($success) {
            $password = MD5($password);
            $qry = "SELECT * FROM aar_users WHERE PIN=$PIN and active=1";
            $member = mysql_fetch_assoc(mysql_query($qry));
            if ($member) {
                $suspended = $member['suspended'];
                $validated = $member['created_at'];
                $fullnames = $member['fullnames'];
				$dob = $member['dob'];
                if ($suspended && !$err_msg) {
                    $success = 0;
					$display_message = 1;
                    $err_msg = "Account suspended";
                }

                if ($success) {
                    $success_msg = "Login success";
					$display_message = 1;
                }
            } else {
                $success = 0;
                if (!$err_msg) {
                    $err_msg = "PIN is not registered! \n\nPlease try another.";
					$display_message = 1;
                }
            }
        }

        echo  json_encode(array('success' => $success, 'err_msg' => $err_msg, 'success_msg' => $success_msg, 'pin' => $PIN,  'dob' => $dob, 'fullnames' => $fullnames, 'display_message' => $display_message));
        //echo json_encode(array('result' => $result_data));
        break;

    case 3:
        //RETRIEVE RECENT ARTICLES
        $success = 1;
        $qry = "SELECT * FROM articles ORDER BY article_id DESC LIMIT 0,5";
        $result = mysql_query($qry);
        //loop thru the results and display
            while ($row = mysql_fetch_assoc($result))
            {
                $article_id = $row['article_id'];
                $article_title = $row['article_title'];
                $article_permalink = $row['article_permalink'];
                $article_date = php_date($row['article_published']);
                $article_date = date('F d, Y',$article_date);
                //$cat_id = getcategoryid($article_id);
                $cat_id = $row['article_category_id'];
                $article_cats = getarticlecats($cat_id);
                $image_title = getimagetitle($article_id,$cat_id);
                $main_img = getmainimg($article_id,$cat_id);
                if (!$main_img) {$main_img = $article_no_image;}
                $main_img = $sitepath . $main_img;
                $cat_links = getcatlinks($cat_id);
                $article_link = $sitepath . $cat_links . "$article_id-$article_permalink.html"; 
                $articles[] = array
                (
                    'article_id' => $article_id,
                    'article_title' => $article_title,
                    'article_link' => $article_link,
                    'article_date' => $article_date,
                    'article_cats' => $article_cats,
                    'image_title' => $image_title,
                    'main_img' => $main_img
                );
            }
            //return $articles; 
        echo json_encode(array('articles' => $articles));
        break;

    case 4:
        //REGISTER NEW USER
        //get the POST values
        $success = 0; $err_msg = "";
        $fullnames = $_POST['FullNames'];
        $dob = $_POST['DOB']; 
        
        $fullnames = clean(trim($fullnames));
        
        //Input Validations
        if (($fullnames == '') && (!$err_msg)) {
            $err_msg =  'Please enter full names';
            $success = 0;
        }
        
        $dob_array = explode("-", $dob); 
        $year = trim($dob_array[2]);
        $month = trim($dob_array[1]);
        //$month = str_pad($month, 2, '0', STR_PAD_LEFT);  //pad month with left side zeros i.e convert 6 to 06 etc
        $day = trim($dob_array[0]);
        $new_date = $year."-".$month."-".$day;
        $new_date = $new_date;// . " 00:00:00";
        
        if ($success) {
            //insert query
            $qry =mysqli_prepare("INSERT INTO aar_users (fullnames,dob,created_at) VALUES (?, ?)");
            mysqli_stmt_bind_param($qry, "sss", $fullnames,$new_date,NOW());
            mysqli_stmt_execute($qry);
            
            mysqli_stmt_close($qry);  
            mysqli_stmt_close($conn);
            
            /*if (mysql_query($qry)) 
            {
                $success_msg = "Account created successfully";
            } else {
                $err_msg = 'An error occured. Please try again';
                   $success = 0;
            }   */
        }

        echo json_encode(array('success' => $success, 'err_msg' => $err_msg, 'success_msg' => $success_msg));
        break;

    case 5:
        //RETRIEVE USER DATA
        //get the POST values
        $success = 0; $err_msg = "";
        $pin = $_POST['pin']; 
        
        $pin = clean(trim($pin));
        
        //Input Validations
        if (($pin == '') && (!$err_msg)) {
            $err_msg =  'Please enter PIN';
            $success = 0;
        }
        
        if ($success) {
            //insert query
            $qry =mysqli_prepare("SELECT * FROM aar_users WHERE PIN = ?");
            mysqli_stmt_bind_param($qry, "s", $pin);
            mysqli_stmt_execute($qry);
            mysqli_stmt_store_result($qry);
            mysqli_bind_result($qry, $id, $fullnames, $pin, $dob, $created_at, $updated_at, $suspended, $active);
            
            $user = array(); //array to store user result
            
            while(mysqli_stmt_fetch($qry))
            {
                $user[fullNames] = $name;
                $user[pin] = $pin;
                $user[dob] = $dob;
            }
            
            $user[err_msg] = $err_msg; //append error msgs to array, if any 
            
            echo json_encode($user);
            
            mysqli_stmt_close($qry);
            
            mysqli_stmt_close($conn);
        }

        break;

        
    case 55:
        //RETRIEVE ARTICLE HEADER 
        $success = 1;
        $article_id = $_REQUEST['article_id'];
        $qry = "SELECT * FROM articles WHERE article_id=$article_id";
        $result = mysql_query($qry);
        //display the result
        $row = mysql_fetch_assoc($result);
        $article_id = $row['article_id'];
        $article_title = $row['article_title'];
        $author_id = $row['article_author'];
        $article_date = php_date($row['article_published']);
        $article_date = date('F d, Y',$article_date);
        $cat_id = $row['article_category_id'];
        $article_cats = getarticlecats($cat_id);
        //author details
        $authqry = "SELECT * FROM members WHERE id=$author_id";
        $authresult=mysql_query($authqry);
        $data = mysql_fetch_assoc($authresult);
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $author_bio = $data['bio'];
        $article_author_text = $firstname ." ". $lastname;
        $author_link = $sitepath . $article_author;
        //End author details
        $article_header = array
        (
            'article_id' => $article_id,
            'article_title' => $article_title,
            'article_link' => $article_link,
            'article_cats' => $article_cats,
            'article_date' => $article_date,
            'article_author_text' => $article_author_text,
            'main_img' => $main_img
        );
            //return $articles; 
        echo json_encode(array('article_header' => $article_header));
        break;

    case 6:
        //RETRIEVE DATA
        $success = 1;
        
        //get images
        $imgqry = "SELECT * FROM aar_users ORDER BY fullnames";
        $imgresult=mysql_query($imgqry);
        
        //loop thru the results and display
            while ($imgrow = mysql_fetch_assoc($imgresult))
            {
                $image_path = $sitepath . $imgrow['image_path'];
                $fullnames = $imgrow['fullnames'];
				$id = $imgrow['id'];
                $user_data[] = array
                (
                    'id' => $id,
					'image' => $image_path,
                    'fullNames' => $fullnames
                );
            }
			//$user_data['success'] = $success;
        echo json_encode($user_data);
        break;
    case 8: //change language id session var
        $success = 1;
        $lang_id = $_GET['lang_id'];
        unset($_SESSION["SESS_LANG_ID"]); //destroy previous session variable value
        $_SESSION["SESS_LANG_ID"] = $lang_id; //set new values
        echo json_encode(array('success' => $success, 'lang_id' => $lang_id));
        break;

    

    case 13:
        //SEND MESSAGE FORM
        $success = 1;
        /*require_once('recaptchalib.php');
        $privatekey = "6LcjevUSAAAAADsEHS53oFrK2xWIIbjdtpKKm-pb";
        $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
        if (!$resp->is_valid) {
            $success = 0;
            $err_msg = "Incorrect security text typed"; //$err_msg=$resp->error;
        } else {
            $success = 1;
        }*/

        if ($success) {
            //get the POST values
            $to_email = get_site_settings('contact_email');

            $full_names = clean($_POST['full_names']);
            $email = $_POST['email'];
            $message = $_POST['message'];
            $title = clean($_POST['subject']);
            if (!$full_names) {
                $err_msg = "Please enter your full names";
                $success = 0;
            }
            if (!$email) {
                $err_msg = "Please enter email address";
                $success = 0;
            }
            // if (!$title) { $err_msg = "Please type in the message title"; $success = 0; }
            if (!$message) {
                $err_msg = "Please type in the message";
                $success = 0;
            }
            //continue if no error is encountered
            if ($success) {
                //send email
                //send_email($email,$fromemail,$fromname,$mailmsgtitle,$mymailmsg,$fullnames,$attach=NULL)
                if (send_email($email, $title, $message, $full_names, $to_email)) {
                    $success = 1;
                    $err_msg = "Your message was successfully sent";
                } else {
                    $success = 0;
                    $err_msg = ERROR_OCCURED_ERROR;
                }
            }
        }
        echo json_encode(array('success' => $success, 'err_msg' => $err_msg, 'success_msg' => $success_msg));
        break;


    case 14:
        //CREATE NEW NEWS ITEM
        //get the POST values
        $cat = $news_cat;
        $success = 1;
        $title = $_POST['title'];
        $article_summary = $_POST['article_summary'];
        $article_text = htmlentities($_POST['article_text']);
        $keywords = $_POST['keywords'];
        $send_subscribers = $_POST['send_subscribers'];

        $perm = generate_seo_link($title, $replace = '-', $remove_words = true, $words_array = array());

        //error checks
        if (!$title) {
            $success = 0;
            $err_msg = "Please enter news title";
        }
        if (!$article_text) {
            $success = 0;
            $err_msg = "Please enter news text";
        }
        if (!$article_summary) {
            $success = 0;
            $err_msg = "Please enter news summary";
        }
        if ($success) {
            $maxsize = 3.5 * 1024 * 1024; //set the max image size in bytes
            $maxsizekb = $maxsize / (1024 * 1024);
            $pic = $_FILES['pic']['name'];
            //IMAGE CHECKS
            if ($pic) {
                // image should be of valid size i.e. maxsize
                if ($_FILES['pic']['size'] > $maxsize) {
                    $err_msg .= "News picture file must be less than $maxsizekb MB in size.<br>";
                    $success = 0;
                    unlink($_FILES['pic']['tmp_name']);
                }
                // image should be of the right type i.e. gif,pjpeg or jpeg
                if ($_FILES['pic']['type'] != "image/gif" AND
                        $_FILES['pic']['type'] != "image/pjpeg" AND
                        $_FILES['pic']['type'] != "image/png" AND
                        $_FILES['pic']['type'] != "image/jpeg") {
                    $err_msg .= "News picture may only be .gif. .png or .jpeg files.</font><br>";
                    $success = 0;
                    unlink($_FILES['pic']['tmp_name']);
                }
            }
        }
        if ($success) {
            //upload pics
            if ($pic) {
                //upload the pictures/photos
                $pic_dir = "../images/news/";
                $name_dir = "images/news/";
                $thumb_pic_dir = "../images/news/thumbs/";
                $thumb_name_dir = "images/news/thumbs/";
                $max_width = $news_image_width;
                $max_height = $news_image_height;
                $thumb_width = $news_image_thumb_width;
                $thumb_height = $news_image_thumb_height;
                $field = "pic";
                $thumb_path = resizeUpload($field, $thumb_pic_dir, $thumb_name_dir, NULL, NULL, $thumb_width, $thumb_height);
                $pic_path = resizeUpload($field, $pic_dir, $name_dir, NULL, NULL, $max_width, $max_height);
            }
            $qry = "INSERT INTO news(title,article_text,article_summary,article_perm,keywords,created_by,created_date) VALUES ('$title','$article_text','$article_summary','$perm','$keywords',$logged_user_id,NOW())";
            if (mysql_query($qry)) {
                $success = 1;
                $sent = 0;
                $new_id = mysql_insert_id();
                $news_item_link = $sitepath . "news/$new_id-$perm.html";
                $success_msg = "News item titled < <b>$title</b> > has been created.";
                //add images/logo to db
                if ($pic) {
                    addImage($cat, $pic_path, $thumb_path, $new_id);
                }
                if ($send_subscribers) {
                    //send news item to all subscribers
                    $sent_title = $title;
                    $sent_text = $article_summary;
                    $sent_picture = $sitepath . $thumb_path;
                    //loop thru subscribers list
                    $cqry = "SELECT email FROM subscribers WHERE validated=1 AND unsubscribed=0";
                    $cresult = mysql_query($cqry);
                    while ($crow = mysql_fetch_array($cresult)) {
                        $sub_email = $crow["email"];
                        $sub_name = get_subscriber_name($sub_email);
                        //send email to this subscriber
                        if (send_subscriber_email($sub_name, $sub_email, $sent_title, $sent_text, $news_item_link, $sent_picture)) {
                            $sent = 1;
                        } else {
                            $sent = 0;
                        }
                    }
                }
            } else {
                $err_msg = ERROR_OCCURED_ERROR;
                $success = 0;
            }
        }

        echo json_encode(array('success' => $success, 'err_msg' => $err_msg, 'success_msg' => $success_msg));
        break;

    case 15:
        //EDIT NEWS
        //get the POST values
        $cat = $news_cat;
        $success = 1;
        $news_id = $_POST['news_id'];
        $title = $_POST['title'];
        $article_summary = $_POST['article_summary'];
        $article_text = htmlentities($_POST['article_text']);
        $keywords = $_POST['keywords'];
        $send_subscribers = $_POST['send_subscribers'];
        $active = $_POST['active'];
        if (!$active) {
            $active = 0;
        }
        $perm = generate_seo_link($title, $replace = '-', $remove_words = true, $words_array = array());

        $thumb_img = getthumbimg($news_id, $cat);
        $main_img = getmainimg($news_id, $cat);
        $main_img_id = getimageid($news_id, $cat, $main_img);

        if (!$title) {
            $success = 0;
            $err_msg = "Please enter news title";
        }
        if (!$article_text) {
            $success = 0;
            $err_msg = "Please enter news text";
        }
        if (!$article_summary) {
            $success = 0;
            $err_msg = "Please enter news summary";
        }

        if ($success) {
            $maxsize = 3.5 * 1024 * 1024; //set the max image size in bytes
            $maxsizekb = $maxsize / (1024 * 1024);
            $pic = $_FILES['pic']['name'];
            //IMAGE CHECKS
            if ($pic) {
                // image should be of valid size i.e. maxsize
                if ($_FILES['pic']['size'] > $maxsize) {
                    $err_msg .= "News picture file must be less than $maxsizekb MB in size.<br>";
                    $success = 0;
                    unlink($_FILES['pic']['tmp_name']);
                }
                // image should be of the right type i.e. gif,pjpeg or jpeg
                if ($_FILES['pic']['type'] != "image/gif" AND
                        $_FILES['pic']['type'] != "image/pjpeg" AND
                        $_FILES['pic']['type'] != "image/png" AND
                        $_FILES['pic']['type'] != "image/jpeg") {
                    $err_msg .= "News picture may only be .gif. .png or .jpeg files.</font><br>";
                    $success = 0;
                    unlink($_FILES['pic']['tmp_name']);
                }
            }
        }

        if ($success) {
            //upload pics
            if ($pic) {
                //upload the pictures/photos
                $pic_dir = "../images/news/";
                $name_dir = "images/news/";
                $thumb_pic_dir = "../images/news/thumbs/";
                $thumb_name_dir = "images/news/thumbs/";
                $max_width = $news_image_width;
                $max_height = $news_image_height;
                $thumb_width = $news_image_thumb_width;
                $thumb_height = $news_image_thumb_height;
                $field = "pic";
                $thumb_path = resizeUpload($field, $thumb_pic_dir, $thumb_name_dir, NULL, NULL, $thumb_width, $thumb_height);
                $pic_path = resizeUpload($field, $pic_dir, $name_dir, NULL, NULL, $max_width, $max_height);
                if ($thumb_img) {
                    unlink("../" . $thumb_img);
                } //delete old thumb
                if ($main_img) {
                    unlink("../" . $main_img);
                } //delete old picture
            }
            $qry = "UPDATE news SET title='$title',article_summary='$article_summary',article_text='$article_text',article_perm='$perm',keywords='$keywords',active=$active WHERE id=$news_id";
            if (mysql_query($qry)) {
                $success_msg = "News item titled < $title > was successfully edited";
                $success = 1;
                $news_item_link = $sitepath . "news/$news_id-$perm.html";
                //update images if any was changed
                if (($pic) && ($_POST['ad_pic_id']) && ($_POST['ad_pic_path'])) {
                    updateImage($pic_path, $thumb_path, $_POST['ad_pic_id']);
                } else if (($pic) && (!$_POST['ad_pic_id'])) {
                    addImage($cat, $pic_path, $thumb_path, $news_id);
                }
                if ($send_subscribers) {
                    $the_thumb = $thumb_img;
                    if ($thumb_path) {
                        $the_thumb = $thumb_path;
                    }
                    //send news item to all subscribers
                    $sent_title = $title;
                    $sent_text = $article_summary;
                    $sent_picture = $sitepath . $the_thumb;
                    //loop thru subscribers list
                    $cqry = "SELECT email FROM subscribers WHERE validated=1 AND unsubscribed=0";
                    $cresult = mysql_query($cqry);
                    while ($crow = mysql_fetch_array($cresult)) {
                        $sub_email = $crow["email"];
                        $sub_name = get_subscriber_name($sub_email);
                        //send email to this subscriber
                        if (send_subscriber_email($sub_name, $sub_email, $sent_title, $sent_text, $news_item_link, $sent_picture)) {
                            $sent = 1;
                        } else {
                            $sent = 0;
                        }
                    }
                }
                $success = 1;
            } else {
                $err_msg = ERROR_OCCURED_ERROR;
                $success = 0;
            }
        }

        echo json_encode(array('success' => $success, 'err_msg' => $err_msg, 'success_msg' => $success_msg));
        break;

    case 16:
        global $news_cat, $home_pics_main_cat;
        //DELETE NEWS
        $success = 0;
        $cat = $_GET['cat']; //get the category
        $id = $_GET['id'];
        if ($admin_user && $cat) {
            if ($cat == $news_cat) {
                $success = 1;
                $delqry = "DELETE FROM news WHERE id=$id";
                if (mysql_query($delqry)) {
                    deleteimages($id, $news_cat);
                    $success = 1;
                } else {
                    $success = 0;
                    $err_msg = ERROR_OCCURED_ERROR;
                }
            }
            if ($cat == $home_pics_main_cat) {
                $success = 1;
                $delqry = "DELETE FROM home_data_pics WHERE id=$id";
                if (mysql_query($delqry)) {
                    deleteimages($id, $home_pics_main_cat);
                    $success = 1;
                } else {
                    $success = 0;
                    $err_msg = ERROR_OCCURED_ERROR;
                }
            }
            if ($cat == $users_cat) {
                $success = 1;
                $delqry = "DELETE FROM users WHERE id=$id";
                if (mysql_query($delqry)) {
                    deleteimages($id, $users_cat);
                    $success = 1;
                } else {
                    $success = 0;
                    $err_msg = ERROR_OCCURED_ERROR;
                }
            }
            if ($cat == $products_cat) {
                $success = 1;
                $delqry = "DELETE FROM products WHERE product_id=$id";
                if (mysql_query($delqry)) {
                    deleteimages($id, $products_cat);
                    $success = 1;
                } else {
                    $success = 0;
                    $err_msg = ERROR_OCCURED_ERROR;
                }
            }
            if ($cat == $brands_cat) {
                $success = 1;
                //check if brand has products assigned to it
                $bqry = "SELECT * FROM products WHERE brand_id=$id";
                $bresult = mysql_query($bqry);
                if (mysql_num_rows($bresult)) {
                    $success = 0;
                    $err_msg = "Cannot delete! Brand has products under it. Please edit or delete those products first.";
                }
                if ($success) {
                    $delqry = "DELETE FROM brands WHERE id=$id";
                    if (mysql_query($delqry)) {
                        deleteimages($id, $brands_cat);
                        $success = 1;
                    } else {
                        $success = 0;
                        $err_msg = ERROR_OCCURED_ERROR;
                    }
                }
            }
        } else {
            $success = 0;
            $err_msg = $admin_login_err_msg;
        }
        echo json_encode(array('success' => $success, 'err_msg' => $err_msg, 'success_msg' => $success_msg));
        break;

    case 17:
        //SEND MESSAGE FORM
        $success = 1; $reset_recaptcha = 0;
        
        if ($success) {
            //get the POST values
            $to_email = get_site_settings('send_email');
            $first_name = clean($_POST['fname']);
			$last_name = clean($_POST['fname']);
			$full_names = $first_name.' '.$last_name;
            $email = $_POST['email'];
            $title = clean($_POST['title']);
			$keywords = clean($_POST['keywords']);
            $message = $_POST['article_text'];
			if (!$logged_user) {  
                require_once('recaptchalib.php');
                $privatekey = $recaptcha_privatekey;
                $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
                if (!$resp->is_valid) {
                    $success = 0;
                    $err_msg = "Incorrect security text typed"; //$err_msg=$resp->error;
                } else {
                    $success = 1;
                }
            }
            if ($logged_user) {
				$full_names = $logged_user;
            	$email = $logged_email;
			}
            if (!$logged_user) {
				if (!$full_names) {
					$err_msg = "Please enter your full names";
					$success = 0;
				}
				if (!$email) {
					$err_msg = "Please enter email address";
					$success = 0;
				}
				if (!isEmail($email)) {
					$err_msg = "Please enter a valid email address";
					$success = 0;
				}
			}
            if ($title=='') {
                $err_msg = "Please enter message title";
                $success = 0;
            }
            /*if ($message=='') {
                $err_msg = "Please type in the message";
                $success = 0;
            }*/
            //continue if no error is encountered
            if ($success) {
                //save data in db and send email
                $subject = $subject . " [[Message from muzikki.com]]";
                $qry = "INSERT INTO site_messages(full_names,email,subject,message,sender_email,created_date";
                if ($logged_user){ $qry .= ",user_id"; }
                $qry .= ") VALUES('$full_names','$email','$title','$message','$email',NOW()";
                if ($logged_user_id){ $qry .= ",$logged_user_id"; }
                $qry .= ")";
                //echo $qry;
                //if message successfully saved in db, send msg
                if (mysql_query($qry)){
                    $recipient_names = 'Admin';
					if (send_message($recipient_names,$to_email,$full_names,$email,$title,$message)){
                        $success = 1;
                        $success_msg = "Your message was successfully sent";
                    } else {
                        $success = 0;
                        $err_msg = "An error occured sending email";
                    }
                } else {
                    $success = 0;
                    $err_msg = "An error occured in db";
                }
            }
            if (!$success){ $reset_recaptcha = 1; }
        }
        echo json_encode(array('success' => $success, 'err_msg' => $err_msg, 'success_msg' => $success_msg, 'reset_recaptcha' => $reset_recaptcha));
        break;

    case 18: //GET PICTURES/ VIDEOS FOR GALLERY
        $article_id = $_GET['id'];
        $image_cat = $_GET['cat'];
        $history = $_GET['history'];
        $select = "SELECT * FROM muz_listing_images WHERE image_category='$image_cat' AND approved='y' AND image_product_id=$article_id ORDER BY image_id";
        $result = mysql_query($select);
        $rowcheck = mysql_num_rows($result);
        if ($rowcheck > 0) {
            while ($row = mysql_fetch_array($result)) {
                $filename = $row['image_path'];
                $name = $row['image_caption'];
                $album_id = $row['image_id'];
                $info = $row['image_desc'];          

                $imageUrl = $sitepath . $filename;

                $data[] = array(
                    "thumb" => $imageUrl,
                    "image" => $imageUrl,
                    "big" => $imageUrl,
                    "title" => $name,
                    "description" => $info,
                    "link" => $imageUrl
                );
            }
        } 
        //get any videos if any and attach to the end of data array
        if ($history){
            $vselect = "SELECT * FROM muz_articles_history WHERE article_id=$article_id";
        } else {
            $vselect = "SELECT * FROM muz_articles WHERE article_id=$article_id";
        }
        $vresult = mysql_query($vselect);
        $vrowcheck = mysql_num_rows($vresult);
        if ($vrowcheck > 0) {
            while ($vrow = mysql_fetch_array($vresult)) {
                $path = $vrow['article_url'];
                $name = $vrow['article_title'];
                $data[] = array(
                    "video" => $path,
                    "title" => $name
                );
                
            }
        } 
        echo json_encode($data);
        break;

    case 19:
        //INSERT OFFER ITEM
        $success = 1;
        $logged_exists = false;
        $not_logged_exists = false;
        $new_offer = false;
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        if (!ctype_digit($quantity)) {
            $success = 0;
            $err_msg = "Quantity field must be a positive number";
        }
        if ($success) {
            $product_title = get_product_title($product_id);
            //check if this logged user's entry already exists
            $eqry = "SELECT * FROM events WHERE user_id=$logged_user_id AND status=$incomplete_offer";
            $eresult = mysql_query($eqry);
            if (mysql_num_rows($eresult)) {
                $logged_exists = true;
                $erow = mysql_fetch_array($eresult);
                $offer_id = $erow["id"];
            }
            //check if this non logged user's entry already exists
            $seqry = "SELECT * FROM events WHERE session_id=$login_session_id AND status=$incomplete_offer";
            $seresult = mysql_query($seqry);
            if (mysql_num_rows($seresult)) {
                $not_logged_exists = true;
                $serow = mysql_fetch_array($seresult);
                $offer_id = $serow["id"];
            }

            if ($logged_exists || $not_logged_exists) {
                
            } else {
                $new_offer = true;
            }

            //an offer does not exist, create one
            if ($new_offer) {
                //is user logged in or not, set variales as appropriate
                if ($logged_user_id) {
                    $thefield = "user_id";
                    $thevalue = $logged_user_id;
                } else {
                    $thefield = "session_id";
                    $thevalue = $login_session_id;
                }
                //insert new offer
                $insqry = "INSERT INTO events($thefield,item_type,created_date,created_by) VALUES('$thevalue',$offer_type,NOW(),$logged_user_id)";
                mysql_query($insqry);
                $offer_id = mysql_insert_id();
            }
            //offer exists, insert offer items
            if ($product_id && $quantity && $offer_id) {
                //check whether item already exists in offer
                $ckqry = "SELECT quantity FROM event_items WHERE offer_id=$offer_id AND product_id=$product_id";
                $ckresult = mysql_query($ckqry);
                //offer item exists, update its quantity
                if (mysql_num_rows($ckresult)) {
                    $ckrow = mysql_fetch_array($ckresult);
                    $db_qty = $ckrow["quantity"]; //current quantity, need to update it
                    $new_qty = $db_qty + $quantity;
                    $updqry = "UPDATE event_items SET quantity=$new_qty WHERE offer_id=$offer_id AND product_id=$product_id";
                    mysql_query($updqry);
                    $success = 1;
                } else {
                    //insert new offer item
                    $newqry = "INSERT INTO event_items(offer_id,product_id,quantity,product_title) VALUES($offer_id,$product_id,$quantity,'$product_title')";
                    mysql_query($newqry);
                    $success = 1;
                }
            } else {
                $success = 0;
                $err_msg = ERROR_OCCURED_ERROR;
            }
        }
        echo json_encode(array('success' => $success, 'err_msg' => $err_msg, 'success_msg' => $success_msg));
        break;
    
    case 22:
        //SAVE TO FAVORITES
        $success = 0; 
		if ($logged_user_id) {
			//get the POST values
			$item_id = $_POST['id'];
			$section = $_POST['section'];
			$qry = "INSERT INTO saved_items(item_id,section,user_id) VALUES ($item_id,'$section',$logged_user_id)";
			if (mysql_query($qry)){ $success = 1; } else { $success = 0; $err_msg = "An error occured saving to favorites"; }
		} else {
			$success = 0; $err_msg = "Please login first";	
		}
		echo json_encode(array('success' => $success, 'err_msg' => $err_msg, 'success_msg' => $success_msg));
        break;
    case 23:
        //SAVE LIKES
        $success = 0; $show_error=false;
		if ($logged_user_id) {
			//get the POST values
			$id = $_POST['id'];
			$item_data = explode('-',$id);
			$item_section = $item_data[0];
			$item_id = $item_data[1];
			//check if like already exists
			$cqry = "SELECT * FROM likes WHERE item_id=$item_id AND user_id=$logged_user_id AND item_section='$item_section'";
			$cresult = mysql_query($cqry);
			if (mysql_num_rows($cresult)) {
				$success = 0;
			} else {
				$qry = "INSERT INTO likes(item_id,user_id,item_section,created_date) VALUES ($item_id,$logged_user_id,'$item_section',NOW())";
				if (mysql_query($qry)){ 
					if ($item_section=='article') { $uqry="UPDATE muz_articles SET likes=likes+1 WHERE article_id=$item_id"; }
					if ($item_section=='artist') { $uqry="UPDATE muz_artists SET likes=likes+1 WHERE artist_id=$item_id"; }
					if ($item_section=='user') { $uqry="UPDATE muz_members SET likes=likes+1 WHERE member_id=$item_id"; }
					if (mysql_query($uqry)){
						$success = 1; 
						$like_count = checkLikeCount($item_section,$item_id);
					}
				} else { $success = 0; }
			}
		} else {
			$success = 0; $err_msg = "Please login first"; $show_login=true; 	
		}
		
		echo json_encode(array('success' => $success, 'err_msg' => $err_msg, 'success_msg' => $success_msg, 'like_count' => $like_count, 'show_login' => $show_login, 'show_error' => $show_error));
        break;
	case 24:
        //DELETE LIKES
        $success = 0; $show_error=false;
		if ($logged_user_id) {
			//get the POST values
			$id = $_POST['id'];
			$item_data = explode('-',$id);
			$item_section = $item_data[0];
			$item_id = $item_data[1];
			//check if like already exists
			$cqry = "SELECT * FROM likes WHERE item_id=$item_id AND user_id=$logged_user_id AND item_section='$item_section'";
			$cresult = mysql_query($cqry);
			if (mysql_num_rows($cresult)) {
				$dqry = "DELETE FROM likes WHERE item_id=$item_id AND user_id=$logged_user_id AND item_section='$item_section'";
				if (mysql_query($dqry)){ 
					if ($item_section=='article') { $uqry="UPDATE muz_articles SET likes=likes-1 WHERE article_id=$item_id"; }
					if ($item_section=='artist') { $uqry="UPDATE muz_artists SET likes=likes-1 WHERE artist_id=$item_id"; }
					if ($item_section=='user') { $uqry="UPDATE muz_members SET likes=likes-1 WHERE member_id=$item_id"; }
					if (mysql_query($uqry)){
						$success = 1; 
						$like_count = checkLikeCount($item_section,$item_id);
					}
				} else { $success = 0; }
			} else {
				$success = 0; 
			}
		} else {
			$success = 0; $err_msg = "Please login first"; $show_login=true; 	
		}
		echo json_encode(array('success' => $success, 'err_msg' => $err_msg, 'success_msg' => $success_msg, 'like_count' => $like_count, 'show_login' => $show_login, 'show_error' => $show_error));
        break;
    

    
    
    case 33: //UPDATE COMMENT
        $success = 1;
        if ($logged_user) {
            $thefield = "user_id";
            $thevalue = $logged_user_id;
        } else {
            $thefield = "session_id";
            $thevalue = $login_session_id;
        }
        $comment = htmlentities($_REQUEST['comment']);
        if ($session_offer_id) {
            $offer_id = $session_offer_id;
        }
        //continue if no error is encountered
        if ($success) {
            //Create query to update events record
            $qry = "UPDATE events SET message='$comment' WHERE id=$offer_id";
            if (mysql_query($qry)) {
                $success = 1;
            } else {
                $success = 0;
                $err_msg = ERROR_OCCURED_ERROR;
            }
        }
        header("Content-Type: text/json");
        echo json_encode(array('success' => $success));
        break;
    case 34: //GET USER DETAILS
        $success = 1;
        $suppliers = "";
        if ($logged_user) {
            $qry = "SELECT * FROM users WHERE id=$logged_user_id";
            $result = mysql_query($qry);
            $row = mysql_fetch_array($result);
            $email = $row["email"];
            $names = $row["names"];
            $company = $row["school"];
            $tel = $row["tel"];
        } else {
            $email = "";
            $names = "";
            $company = "";
            $tel = "";
        }
        $file_data[] = array
            (
            'email_data' => $email,
            'full_names_data' => $names,
            'company_name_data' => $company,
            'tel_data' => $tel
        );

        echo json_encode(array('data' => $file_data, 'success' => $success));
        break;

    

    case 39: //ADD CATEGORY/ NODE
        $success = 1;
        $id = $_POST['parentid'];
        $text = $_POST['nodetext'];
        $perm = generate_seo_link($text, $replace = '-', $remove_words = true, $words_array = array());
        //check if brand already exists in db
        $bqry = "SELECT category_name FROM categories WHERE category_name='$text'";
        $bresult = mysql_query($bqry);
        if (mysql_num_rows($bresult)) {
            $success = 0;
            $err_msg = "Category '<b>$text</b>' already exists. Try another.";
        }
        if ($success) {
            //add node
            $qry = "INSERT INTO categories(cat_parent_id,category_name,category_permalink) VALUES ($id,'$text','$perm')";
            if (mysql_query($qry)) {
                $success = 1;
            } else {
                $success = 0;
                $err_msg = ERROR_OCCURED_ERROR;
            }
        }
        echo json_encode(array('success' => $success));
        break;
    case 40: //DELETE CATEGORY NODE
        $success = 1;
        $id = $_POST['nodeid'];
        //check if it has ads underneath
        //check if category has adverts in db
        $bqry = "SELECT * FROM products WHERE category_id=$id";
        $bresult = mysql_query($bqry);
        if (mysql_num_rows($bresult)) {
            $success = 0;
            $err_msg = "Category has products under it. Edit or delete those products first, then try again.";
        }
        //if no errors encountered i.e. no adverts found under the category  continue and delete           
        if ($success) {
            $qry = "DELETE FROM categories WHERE category_id = $id";
            if (mysql_query($qry)) {
                $success = 1;
            }
        }
        echo json_encode(array('success' => $success, 'err_msg' => $err_msg));
        break;
    case 41://RENAME CATEGORY NODE
        $success = 0;
        $id = $_POST['parentid'];
        $cat_name = $_POST['nodetext'];
        $perm = generate_seo_link($cat_name, $replace = '-', $remove_words = true, $words_array = array());
        $qry = "UPDATE categories SET category_name = '$cat_name', category_permalink = '$perm' WHERE category_id = $id";
        if (mysql_query($qry)) {
            $success = 1;
        }
        echo json_encode(array('success' => $success));
        break;
    case 42://UNSUBSCRIBE USER
        $success = 1;
        $email = $_POST['email'];
        if (!$email) {
            $success = 0;
            $err_msg = "Please enter email";
        }
        if ((!isEmail($email)) && ($success)) {
            $success = 0;
            $err_msg = "Please enter a valid email";
        }
        //check if email already exists in db
        $emqry = "SELECT * FROM subscribers WHERE email='$email'";
        $emresult = mysql_query($emqry);
        $row = mysql_fetch_array($emresult);
        $email = $row["email"];
        $validated = $row["validated"];
        $unsubscribed = $row["unsubscribed"];
        $sub_name = get_subscriber_name($email);
        if (!$sub_name) {
            $sub_name = $email;
        }
        $key = generate_mailkey();
        $sent_title = "ICTPunt - " . UNSUBSCRIBE_CONFIRMATION_LABEL;
        if ((!$email) && $success) {
            $success = 0;
            $err_msg = "Email is not subscribed. Try another.";
        }
        if ((!$validated) && $success) {
            $success = 0;
            $err_msg = "Email is not yet validated.";
        }
        if (($unsubscribed) && $success) {
            $success = 0;
            $err_msg = "Email is already unsubscribed.";
        }
        if ($success) {
            //update user subscription record to indicate new request
            $subqry = "UPDATE subscribers SET date_unsubscription_request=NOW(),mailkey='$key' WHERE email='$email'";
            if (mysql_query($subqry)) {
                $success_msg = UNSUBSCRIBE_LINK_SENT_MESSAGE;
                send_unsubscribe_email($sub_name, $email, $sent_title, $key);
            }
        }
        echo json_encode(array('success' => $success, 'success_msg' => $success_msg, 'err_msg' => $err_msg));
        break;
    case 43: //GET BRANDS
        $qry = "SELECT * FROM brands";
        $result = mysql_query($qry);
        $brands[] = array('id' => $noval, 'name' => "Show All"); //default value
        while ($row = mysql_fetch_array($result)) {
            $id = $row["id"];
            $name = $row["name"];
            $brands[] = array
                (
                'id' => $id,
                'name' => $name
            );
        }
        echo json_encode(array('brands' => $brands));
        break;
    case 44: //GET CATEGORIES
        $qry = "SELECT * FROM categories";
        $result = mysql_query($qry);
        $categories[] = array('id' => $noval, 'name' => "Show All"); //default value
        while ($row = mysql_fetch_array($result)) {
            $id = $row["category_id"];
            $name = $row["category_name"];
            $categories[] = array
                (
                'id' => $id,
                'name' => $name
            );
        }
        echo json_encode(array('categories' => $categories));
        break;
    case 45: //UPDATE INFO PAGE
        $success = 1;
        $language = $_POST['language'];
        $article_text = htmlentities($_POST['article_text']);
        $active = $_POST['active'];
        if (!$active) {
            $active = 0;
        }
        $page_id = $_POST['page_id'];
        $id = $_POST['id'];
        //check if entry already exists in db
        $qry = "SELECT id FROM pages WHERE page_id=$page_id AND lang_id=$language";
        $result = mysql_query($qry);
        if (mysql_num_rows($result)) {
            //record exists, update existing record
            $newqry = "UPDATE pages SET article_text='$article_text', active=$active WHERE id=$id";
        } else {
            //record does not exist, add a new record
            $newqry = "INSERT INTO pages(page_id,article_text,lang_id) VALUES($page_id,'$article_text',$language)";
        }
        if (mysql_query($newqry)) {
            $success = 1;
            $success_msg = OPERATION_SUCCESS_MESSAGE;
        } else {
            $success = 0;
            $err_msg = ERROR_OCCURED_ERROR;
        }
        echo json_encode(array('err_msg' => $err_msg, 'success' => $success, 'success_msg' => $success_msg));
        break;
    
    default:
        break;
}
?>