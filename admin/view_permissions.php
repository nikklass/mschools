<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php");
	
	if (!isset($_SESSION)) session_start();
	
	$admin = true;
	$show_bootstrap_dialog = true;
	
	$show_form = true;
	$show_scroller = true;
	$form_validation = true; //form validation classes
	
	//$show_lightbox = true; //lightbox
	//$show_file_upload = true; //show file upload css/ js
	$show_table = true;
	$show_users_list = true;
		
	$db = new DbHandler();
		
	$student_id = $arg_two;
		
?>

<?php 
	//if user has read permissions
	if (!(HAS_READ_USER_PERMISSION || SCHOOL_ADMIN_USER)) 
	{
		//user is not allowed to access page
		$page = LOGIN_URL;
		header("Location: $page"); 
		exit();
	}
?>

<?php
	
	$is_data = true;
	
	if ($_GET["group_id"]) {
		
		$group_id = $_GET["group_id"];
		
		//get the school ids
		$query = "SELECT name, id FROM groups WHERE id = ? ";
		$stmt = $db->conn->prepare($query);
		$stmt->bind_param("i", $group_id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($group_name, $group_id);
		$stmt->fetch();
		
	} else {
		
		//get the school ids
		$query = "SELECT name, id FROM groups ";
		if (SCHOOL_ADMIN_USER) { 
			$query .= " WHERE created_by = " . USER_ID; 
		}
		$query .= " ORDER BY id LIMIT 0,1";
		$stmt = $db->conn->prepare($query);
		//$stmt->bind_param("i", $sch_name, $sch_id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($group_name, $group_id);
		$stmt->fetch();
	
	}
	
	if (!$group_name) { $is_data = false; }
	
	$page_title = "User Group Permissions ";
	
	if ($is_data) { $page_title .= "- " . $group_name; }
	
?>



<!DOCTYPE html>
<html class="st-layout ls-top-navbar-large ls-bottom-footer show-sidebar sidebar-l3" lang="en">

<head>
    
	<?php include_once("includes/head_scripts.php"); ?>
                
    <title><?=$page_title?> :: <?=$page_titles?></title>

</head>

<body>

    <!-- Wrapper required for sidebar transitions -->
    <div class="st-container">

            
        <?php include_once("includes/nav.php"); ?>
        
            
        <?php include_once("includes/left_sidebar1.php"); ?>
            

        <!-- sidebar effects OUTSIDE of st-pusher: -->
        <!-- st-effect-1, st-effect-2, st-effect-4, st-effect-5, st-effect-9, st-effect-10, st-effect-11, st-effect-12, st-effect-13 -->

        <!-- content push wrapper -->
        <div class="st-pusher" id="content">

            <!-- sidebar effects INSIDE of st-pusher: -->
            <!-- st-effect-3, st-effect-6, st-effect-7, st-effect-8, st-effect-14 -->

            <!-- this is the wrapper for the content -->
            <div class="st-content">

                <!-- extra div for emulating position:fixed of the menu -->
                <div class="st-content-inner padding-none">

                    <div class="container-fluid">

						<?php include_once("includes/bread_crumb.php"); ?>
                        
                        <?php 
							//check permissions whether user has permissions to perform tasks on user permissions or if user is a school admin
							if (HAS_CREATE_USER_PERMISSION || SCHOOL_ADMIN_USER){  
						?>
                        
                        <div class="col-sm-9">
                        
                        <?php 
							} else {
						?>
                        
                        <div class="col-sm-12">
                        
                        <?php 
							} 
						?>

                            <div class="panel panel-default paper-shadow" data-z="0.5">
                                
                                <div id="top_group_id" data-group-id="<?=$group_id?>"></div>
                                
                                <div class="form-group padding-20-all form-group-permissions">
                                    <form>
                                        <select id="group-select" name="group_id" class="form-control">
                                                                                        
                                            <option value="">Select Group</option>
                                            
											<?php
                                                    
                                                //get the user types
                                                $query = "SELECT id, name FROM groups ";
												if (SCHOOL_ADMIN_USER) { 
													$query .= " WHERE created_by = " . USER_ID; 
												}
												$query .= " ORDER BY name";
                                                $stmt = $db->conn->prepare($query);
                                                $stmt->execute();
                                                /* bind result variables */
                                                $stmt->bind_result($id, $name);
                                                
                                                while ($stmt->fetch()) 
                                                {
                                          
                                                    echo "<option value='$id' ";
                                                    
                                                    if ($group_id == $id) { echo " selected "; } 
                                                    
                                                    echo ">$name</option>";
                                            
                                                 } 
                                            
                                            ?>
                                            
                                        </select>
                                    </form>
                                </div>
                                
                                <div class="table-responsive" id="table-responsive" data-tbl="sch_students" data-tbl-pk="id">
                                
                                    <table class="table table-condensed table-hover text-subhead v-middle">
                                        <thead>
                                            <tr>
                                               
                                                <th>Object</th>
                                                <th>Create</th>
                                                <th>View</th>
                                                <th>Update</th>
                                                <th>Delete</th>
    
                                            </tr>
                                        </thead>
                                        <tbody>
                                          
                                        <?php  
										  	
										if ($is_data) 
										{
										
										?>
                                        
                                        <?php 
											//check permissions whether user has permissions to perform tasks on user permissions or if user is a school admin
											if (SUPER_ADMIN_USER)
											{ 
										?>
                                          
                                           <tr>
                                                
                                                <td>Users</td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, CREATE_USER_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=CREATE_USER_PERMISSION?>"  name="<?=CREATE_USER_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, READ_USER_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=READ_USER_PERMISSION?>" name="<?=READ_USER_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, UPDATE_USER_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=UPDATE_USER_PERMISSION?>"  name="<?=UPDATE_USER_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, DELETE_USER_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=DELETE_USER_PERMISSION?>" name="<?=DELETE_USER_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
    
                                            </tr>
                                            
                                           <tr>
                                                
                                                <td>Schools</td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, CREATE_SCHOOL_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=CREATE_SCHOOL_PERMISSION?>"  name="<?=CREATE_SCHOOL_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, READ_SCHOOL_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=READ_SCHOOL_PERMISSION?>" name="<?=READ_SCHOOL_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, UPDATE_SCHOOL_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=UPDATE_SCHOOL_PERMISSION?>"  name="<?=UPDATE_SCHOOL_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, DELETE_SCHOOL_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=DELETE_SCHOOL_PERMISSION?>" name="<?=DELETE_SCHOOL_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
    
                                            </tr>
                                            
                                        <?php  
										  	
											}
										
										?>
                                            
                                           <tr>
                                                
                                                <td>Subjects</td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, CREATE_SUBJECT_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=CREATE_SUBJECT_PERMISSION?>"  name="<?=CREATE_SUBJECT_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, READ_SUBJECT_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=READ_SUBJECT_PERMISSION?>" name="<?=READ_SUBJECT_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, UPDATE_SUBJECT_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=UPDATE_SUBJECT_PERMISSION?>"  name="<?=UPDATE_SUBJECT_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, DELETE_SUBJECT_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=DELETE_SUBJECT_PERMISSION?>" name="<?=DELETE_SUBJECT_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
    
                                            </tr>
                                           
                                           <tr>
                                                
                                                <td>Students</td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, CREATE_STUDENT_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=CREATE_STUDENT_PERMISSION?>"  name="<?=CREATE_STUDENT_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, READ_STUDENT_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=READ_STUDENT_PERMISSION?>" name="<?=READ_STUDENT_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, UPDATE_STUDENT_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=UPDATE_STUDENT_PERMISSION?>"  name="<?=UPDATE_STUDENT_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, DELETE_STUDENT_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=DELETE_STUDENT_PERMISSION?>" name="<?=DELETE_STUDENT_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
    
                                            </tr>                                           
                                            
                                           <tr>
                                                
                                                <td>Results</td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, CREATE_RESULT_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=CREATE_RESULT_PERMISSION?>"  name="<?=CREATE_RESULT_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, READ_RESULT_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=READ_RESULT_PERMISSION?>" name="<?=READ_RESULT_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, UPDATE_RESULT_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=UPDATE_RESULT_PERMISSION?>" class="permission-check"  name="<?=UPDATE_RESULT_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, DELETE_RESULT_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=DELETE_RESULT_PERMISSION?>" name="<?=DELETE_RESULT_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
    
                                            </tr>
                                            
                                           <tr>
                                                
                                                <td>Fees</td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, CREATE_FEE_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=CREATE_FEE_PERMISSION?>"  name="<?=CREATE_FEE_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, READ_FEE_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=READ_FEE_PERMISSION?>" name="<?=READ_FEE_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, UPDATE_FEE_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=UPDATE_FEE_PERMISSION?>"  name="<?=UPDATE_FEE_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, DELETE_FEE_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=DELETE_FEE_PERMISSION?>" name="<?=DELETE_FEE_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
    
                                            </tr>
                                            
                                            <tr>
                                                
                                                <td>Bulk SMS</td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, CREATE_BULK_SMS_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=CREATE_BULK_SMS_PERMISSION?>"  name="<?=CREATE_BULK_SMS_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, READ_BULK_SMS_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=READ_BULK_SMS_PERMISSION?>" name="<?=READ_BULK_SMS_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, UPDATE_BULK_SMS_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=UPDATE_BULK_SMS_PERMISSION?>"  name="<?=UPDATE_BULK_SMS_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, DELETE_BULK_SMS_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=DELETE_BULK_SMS_PERMISSION?>" name="<?=DELETE_BULK_SMS_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
    
                                            </tr>
                                            
                                            <tr>
                                                
                                                <td>Mpesa</td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, CREATE_MPESA_TRANS_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=CREATE_MPESA_TRANS_PERMISSION?>"  name="<?=CREATE_MPESA_TRANS_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, READ_MPESA_TRANS_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=READ_MPESA_TRANS_PERMISSION?>" name="<?=READ_MPESA_TRANS_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, UPDATE_MPESA_TRANS_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=UPDATE_MPESA_TRANS_PERMISSION?>"  name="<?=UPDATE_MPESA_TRANS_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" class="permission-check" <?php if ($db->hasRole($group_id, DELETE_MPESA_TRANS_PERMISSION, true)) { echo 'checked'; } else { echo ''; } ?> value="<?=DELETE_MPESA_TRANS_PERMISSION?>" name="<?=DELETE_MPESA_TRANS_PERMISSION?>">
                                                        <label for="checkbox4">&nbsp;</label>
                                                    </div>
                                                </td>
    
                                            </tr>
                                            
                                           <tr>
                                                
                                                <td colspan="5">
                                                	<div class="tbl-data" data-tbl-pk="id" data-tbl="groups" data-reload-page="1">
                                                        <div class="row-data" data-row-name="<?=$group_name?>" data-pk-val="<?=$group_id?>">
                                                            <div class="col-sm-6 col-sm-offset-3">
                                                                <div class="padding-20-all">
                                                                    <a href="" class="btn btn-lg btn-danger btn-block deleteGroup noclick" id="deleteUserGroup">Delete User Group</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                
                                            </tr>
                                            
                                        <?php  
										  	
										}
										
										?>
                                            
                                           
                                        </tbody>
                                    </table>
                                    
                                </div>
    
                            </div>
                        
                        </div>
                        
                        <?php 
							if (HAS_CREATE_USER_PERMISSION || SCHOOL_ADMIN_USER){  
						?>
                        
                        <div class="col-sm-3">
            
            				<!--right-->
          
                                <div class="panel panel-default text-center login-form-bg" data-z="0.5">
                                    <h3 class="text-display-1 text-center margin-bottom-none">Add New User Group</h3>
                                    <hr>
                                    <div class="panel-body">
                                        
                                        <form class="form-add-user-group inputform padding-no-top-20" data-parsley-validate>
                                        
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="resultdiv"></div>
                                                </div>
                                            </div>
                                          
                                            <div class="form-group">
                                                <div class="row">
                                                	<label for="group_name">Group Name</label>
                                                    <input type="text" name="group_name" class="form-control"  data-parsley-trigger="change" required>
                                                </div>
                                               
                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="row">
                                                	<label for="group_description">Group Description</label>
                                                    <textarea type="text" name="group_description" class="form-control" rows="3"></textarea>
                                                </div>
                                               
                                            </div>
                                            
                                            <p>&nbsp;</p>
                                            
                                            <div class="form-group text-center">
                                                <div class="row">
                                                    <button class="btn btn-primary btn-block">Submit</button>
                                                </div>
                                            </div>
                                            
                                        </form>
                                        
                                    </div>
                                </div>
                            
                            
							

                            
                            <!--end right-->
                            
            
                        </div>
                        
                        <?php 
								}
						?>



                    </div>

                </div>
                <!-- /st-content-inner -->

            </div>
            <!-- /st-content -->

        </div>
        <!-- /st-pusher -->

        <!-- Footer -->
        <footer class="footer">
            
            <?php include_once("includes/footer.php"); ?>
            
        </footer>
        <!-- // Footer -->

    </div>
    <!-- /st-container -->

    
    <?php include_once("includes/js.php"); ?>
    

</body>

</html>