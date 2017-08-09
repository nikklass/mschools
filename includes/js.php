<script type="text/javascript" language="javascript" src="<?=SITEPATH?>admin/js/vendor/core/head.min.js"></script>
<script type="text/javascript" language="javascript">
        
	<!-- Inline Script for colors and config objects; used by various external scripts; -->
	var colors = {
        "danger-color": "#e74c3c",
        "success-color": "#81b53e",
        "warning-color": "#f0ad4e",
        "inverse-color": "#2c3e50",
        "info-color": "#2d7cb5",
        "default-color": "#6e7882",
        "default-light-color": "#cfd9db",
        "purple-color": "#9D8AC7",
        "mustard-color": "#d4d171",
        "lightred-color": "#e15258",
        "body-bg": "#f6f6f6"
    };
    var config = {
        theme: "html",
        skins: {"default":{"primary-color":"#42a5f5"}}
    };
	
            
            
	
	head.js("<?=SITEPATH?>admin/js/vendor/core/jquery.js",
	
		"<?=SITEPATH?>admin/js/vendor/core/bootstrap.js",
		"<?=SITEPATH?>admin/js/vendor/core/breakpoints.js",
		"<?=SITEPATH?>admin/js/vendor/core/jquery.nicescroll.js",
		"<?=SITEPATH?>admin/js/vendor/core/isotope.pkgd.js",
		"<?=SITEPATH?>admin/js/vendor/core/packery-mode.pkgd.js",
		"<?=SITEPATH?>admin/js/vendor/noty/packaged/jquery.noty.packaged.js",
		"<?=SITEPATH?>admin/js/vendor/noty/layouts/top.js",
		"<?=SITEPATH?>admin/js/vendor/noty/themes/default.js",
		
		"<?=SITEPATH?>admin/js/vendor/core/jquery.cookie.js",
		"<?=SITEPATH?>admin/js/vendor/core/jquery-ui.custom.js",
		"<?=SITEPATH?>admin/js/vendor/core/jquery.hotkeys.js",
		"<?=SITEPATH?>admin/js/vendor/core/handlebars.js",
		"<?=SITEPATH?>admin/js/vendor/core/load_image.js",
		"<?=SITEPATH?>admin/js/vendor/core/jquery.debouncedresize.js",
		"<?=SITEPATH?>admin/js/vendor/core/modernizr.js",
		"<?=SITEPATH?>admin/js/vendor/core/velocity.js",
		"<?=SITEPATH?>admin/js/vendor/tables/all.js",
		"<?=SITEPATH?>admin/css/vendor/sweetalert/dist/sweetalert.min.js",
		"<?=SITEPATH?>admin/js/vendor/loading/loadingoverlay.min.js",
		"<?=SITEPATH?>admin/js/vendor/jquery.matchHeight.js",
        "<?=SITEPATH?>admin/js/vendor/core/jquery.grid-a-licious.js",
		
		
		"<?=SITEPATH?>admin/js/app/material.js",
		"<?=SITEPATH?>admin/js/app/layout.js",
		"<?=SITEPATH?>admin/js/app/sidebar.js",
		"<?=SITEPATH?>admin/js/app/media.js",
		"<?=SITEPATH?>admin/js/app/messages.js",
		
		<?php if ($show_chart) { ?>
		
			"<?=SITEPATH?>admin/js/vendor/charts/flot/all.js",
		
		<?php } ?>
		
		<?php if ($show_popup) { ?>
		
			"<?=SITEPATH?>admin/js/vendor/jquery.colorbox-min.js",
		
		<?php } ?>
		
		
		<?php if ($show_scroll) { ?>
			
			"<?=SITEPATH?>admin/js/vendor/jquery.nicescroll.js",
			
		<?php } ?>
		
		<?php if ($show_form) { ?>
		
			"<?=SITEPATH?>admin/js/vendor/jquery.ba-throttle-debounce.min.js",
			"<?=SITEPATH?>admin/css/app/bootstrap-select.min.js",
			"<?=SITEPATH?>admin/js/vendor/forms/all.js",
			
		<?php } ?>
		
		<?php if ($form_validation) { ?>
			
			"<?=SITEPATH?>admin/js/vendor/forms/validation/jquery.parsley.min.js",
			
		<?php } ?>
			
		<?php if ($show_map) { ?>
		
			"<?=SITEPATH?>admin/js/app/maps.js",
		
		<?php } ?>
		
		<?php if ($show_file_upload) { ?>
		
			"<?=SITEPATH?>admin/admin/js/vendor/fileinput.min.js",
			"<?=SITEPATH?>admin/admin/js/vendor/sortable.min.js",
			"<?=SITEPATH?>admin/admin/js/vendor/purify.min.js",
		
		<?php } ?>
		
		<?php if ($ladda_button) { ?>
		
			
			"<?=SITEPATH?>admin/js/vendor/ladda/spin.min.js",
			"<?=SITEPATH?>admin/js/vendor/ladda/ladda.min.js",
		
		<?php } ?>
		
		<?php if ($show_file_upload) { ?>
		
			"<?=SITEPATH?>admin/js/vendor/fileinput.min.js",
		
		<?php } ?>
		
		<?php if ($show_bootstrap_dialog) { ?>
		
			"<?=SITEPATH?>admin/js/vendor/bootstrap-dialog.min.js",
		
		<?php } ?>
		
		<?php if ($show_waypoints) { ?>
		
			"<?=SITEPATH?>admin/js/vendor/waypoint.js",
			"<?=SITEPATH?>admin/js/vendor/jquery.waypoints.min.js",
		
		<?php } ?>
		
		<?php if ($show_table) { ?>
			"<?=SITEPATH?>admin/js/vendor/jquery.bootgrid.js",
			"<?=SITEPATH?>admin/js/vendor/jquery.bootgrid.fa.js",
		<?php } ?>
		
		<?php if ($show_scroller) { ?>
		
			"<?=SITEPATH?>admin/js/vendor/customscroll/jquery.mCustomScrollbar.concat.min.js",
			
		<?php } ?>
			
		"<?=SITEPATH?>admin/js/app/main.js"
	
	);
		
	head.ready(function() {
       
		var fetching = false;
		var fetchingFees = false;
		
		var success_check_mark = '<svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg>';
		
		var success_check_mark_big = '<svg class="checkmark-big" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg>';
			
		var loader_object = "<object type='image/svg+xml' data='<?=SITEPATH?>admin/images/form_loader.svg' height='30'>";
			loader_object += 	"<img src='<?=SITEPATH?>admin/images/form_loader.gif' height='30'>";
			loader_object += 	"</object>";
			
		var loadingContainer = "<div class='containerLoading'></div>";
				
		var resizefunc = []; 
		
		var timestamp = new Date().getTime();
	
		jQuery(document).ready(function($){
				
				$(".hidden-field").show(1000);
				
				//noty messges
				 function generateNotyMessage(type, text, layout, closeit, closeTime) {

					var n = noty({
						text        : text,
						type        : type,
						dismissQueue: true,
						layout      : layout,
						theme       : 'relax',
						maxVisible  : 10,
						animation   : {
							open  : 'animated fadeInUp',
							close : 'animated fadeOutUp'
						}
					});
					//console.log('html: ' + n.options.id);
					
					//close the notification if var is set
					if (closeit=='y') {
						//if (closeTime==''){ closeTime = 5000; }
						setTimeout(function() { $.noty.closeAll(); }, closeTime);
					}
				}
				
				<?php if ($show_form) { ?>
					//set the dates format
					$('.datepicker').datepicker({
						format: "dd/mm/yyyy",
						todayHighlight: true,
						autoclose: true,
						toggleActive: true
					});
				<?php } ?>
				
				function numberWithCommas(x) {
					return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				}
				
				<?php if ($ladda_button) { ?>
					Ladda.bind( '.ladda-button', { timeout: 2000 } );
				<?php } ?>
				
				<?php if ($show_scroller) { ?>
					$(".boxscroll").mCustomScrollbar({
						setHeight:420,
						theme:"minimal-dark"
					});
					 
					$("#content-3").mCustomScrollbar({
						scrollButtons:{enable:true},
						theme:"light-thick",
						scrollbarPosition:"outside"
					});
				
				<?php } ?>
				
				<?php if ($show_scroll) { ?>
					$(".nicescroll").niceScroll({ cursorcolor: '#cccccc', cursorborderradius: '5px', cursorwidth: "8px", cursorborder: "2px solid #fcfcfc", bouncescroll: true});
					$('#ascrail2000 *').show();
				<?php } ?>
				
				<?php if ($show_waypoints) { ?>
					
					function generateWaypoint(theFunction, checkElement){
					
						$footer = $('#' + checkElement);
						opts = {
							offset: '75%'
							/*offset: function() {
								return -this.element.clientHeight
							}*/
						};
						
						var waypoint = new Waypoint({
						  element: document.getElementById(checkElement),
						  handler: function(direction) {
							//notify('Waypoint triggered in ' + direction + ' direction');
							theFunction;
						  },
						  offset: '75%'
						})
						
					}
				
				<?php } ?>
				
				<?php if ($show_popup) { ?>
				//COLORBOX
				$(".group1").colorbox({rel:'group1'});
				$(".group2").colorbox({rel:'group2', transition:"fade"});
				$(".group3").colorbox({rel:'group3', transition:"none", width:"75%", height:"75%"});
				$(".group4").colorbox({rel:'group4', slideshow:true});
				$(".ajax").colorbox();
				$(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
				$(".vimeo").colorbox({iframe:true, innerWidth:500, innerHeight:409});
				$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
				$(".inline").colorbox({inline:true, width:"50%"});
				$(".callbacks").colorbox({
					onOpen:function(){ alert('onOpen: colorbox is about to open'); },
					onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
					onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
					onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
					onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
				});

				$('.non-retina').colorbox({rel:'group5', transition:'none'})
				$('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});
				
				//Example of preserving a JavaScript event for inline calls.
				$("#click").click(function(){ 
					$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
					return false;
				});
				
				//END COLOR BOX
				
				<?php } ?>
				
				<?php if ($show_scroller) { ?>
					$("#cboxLoadedContent").mCustomScrollbar({
						live:true,
						theme:"inset-dark",
						width:"80%",
						maxHeight:"80%"
					});
				<?php } ?>
				 
				//overlay functions
				function load_overlay(overlay_container)
				{
					if (overlay_container)
					{
						$(overlay_container).LoadingOverlay("show", {
							image       : "<?=SITEPATH?>admin/images/form_loader.gif"
						}); 
					} else {
						$.LoadingOverlay("show", {
							image       : "<?=SITEPATH?>admin/images/form_loader.gif"
						});		
					}
				}
				 
				function hide_overlay(overlay_container)
				{
					if (overlay_container)
					{
						$(overlay_container).LoadingOverlay("hide"); 
					} else {
						$.LoadingOverlay("hide");		
					}
				}				
		
				//end overlay functions
				 
				//HANDLE FORMS
				function submitForm(formName, action, ref, error_quickmessage, success_quickmessage, error_autoclose, success_autoclose, error_dialog_timeout, success_dialog_timeout, overlay_div){
					
					var dataString = new FormData($(formName)[0]);
					
					if (overlay_div) { load_overlay(overlay_div); }
				
					$.ajax({
						type: "POST",
						url: "<?=SITEPATH?>admin/api/v1/"+action,
						data: dataString,
						dataType: "json",
						async: false,
						cache: false,
						contentType: false,
						processData: false,
						success: function(data) {
							error = data.error;
							message = data.message;
							success_url = data.success_url;
							redirect_duration = data.redirect_duration;
							reload_page = data.reload_page;
							reset_recaptcha = data.reset_recaptcha;
							close_form = data.close_form;
							clear_form = data.clear_form;
							close_error = data.close_error;
							close_error_duration = data.close_error_duration;
							slide_form = data.slide_form;
							slide_duration = data.slide_duration;
							popup = data.popup;
							noty_msg = data.noty_msg;
							reload_grid = data.reload_grid;
							ref = data.ref;
							close_box = data.close_box; //close colorbox
							
							if (overlay_div) { hide_overlay(overlay_div); }
							
							if (slide_form) {
								if (!slide_duration) { slide_duration = 8000; }
								result_div = $(this).closest('form').find(".resultdiv");
								slide_up(result_div, 1000, slide_duration, "fadeOut");
							}
							
							if (close_box && reload_page) {
								//parent.$.fn.colorbox.resize();
								parent.location.reload(); 
								parent.$.colorbox.close();	
								
							}
							
							if (close_box && !reload_page) {
								parent.$.colorbox.close();
							}						
											
							if (ref != "none") {
							
								//if popup, disply colorbox and inject message in it
								if (popup) {
									
									$.colorbox({html:message});
									
								} else {
	
									//normal messaging
									if(!error){
										
										//$(".inputform").parsley().reset();
										
										var home_url = "<?=SITEPATH?>admin/";
										
										if ((!reload_page) && (!close_form)){
												
												result_div = $(this).closest('form').find(".resultdiv");
												$(result_div).html("<div class='alert alert-success login wow fadeIn animated text-center padding-20'>" + success_check_mark + "<br>" + message + "</div>")
													.hide()
													.fadeIn(500, function() { $('.resultdiv').append(""); });
		
												slide_up(".resultdiv", 1000, 8000, "fadeOut");
											
										} 
										
										if (close_form){ 
												
											$('#wrapper_form').html("<div id='message' class='alert alert-success login wow fadeIn animated text-center padding-20 text-center'></div>");
											$('#message').html(message + "<br>" + success_check_mark)
												.hide()
												.fadeIn(500, function() { $('#message').append(""); });
											//$(".resultdiv").fadeOut(1000);
											result_div = $(this).closest('form').find(".resultdiv");
											$(result_div).fadeOut(1000);
											scrollPageTo("title");
											//showCheckMark();
						
										}
										
										if (noty_msg){ 
											
											generateNotyMessage("success", message, 'topCenter', 'y', 7000);
						
										}
										
										if (success_url) { 
											if (redirect_duration){
												setTimeout(function() {
													window.location.assign(success_url);
												}, redirect_duration); 
											} else {
												window.location.assign(success_url);	
											}
										}
										if (reload_page){ location.reload(); }
										
										
									} else {
									
										//show message using noty
										//generateNotyMessage(type, text, layout, closeit)
										generateNotyMessage("error", message, 'topCenter', 'y', 7000);
										
										if (reset_recaptcha){ grecaptcha.reset(); }
								
									}
								
								} //end if popup
							
							} else {
								
								//handle data using bootstrap message dialog
								if (!error) {
									
									<?php if ($show_bootstrap_dialog) { ?>
									
										if (success_quickmessage == "yes") {
											//showQuickMessage(title, message, timeout, type, autoclose)
											showQuickMessage("Success", message, success_dialog_timeout, "success", success_autoclose); 
										} else {
											//(title, message, close_dialog_text, close_dialog_css, timeout, type, autoclose)
											showMessage("Success", message, "Close", "btn-success", success_dialog_timeout, "success", success_autoclose); 	
										}
									
									<?php } else { ?>
									
										generateNotyMessage("success", message, 'topCenter', 'y', 7000);
										
									<?php } ?>
									
								} else {
									
									<?php if ($show_bootstrap_dialog) { ?>
									
										if (error_quickmessage == "yes") {
											//showQuickMessage(title, message, timeout, type, autoclose)
											showQuickMessage("Error", message, error_dialog_timeout, "error", error_autoclose); 
										} else {
											//(title, message, close_dialog_text, close_dialog_css, timeout, type, autoclose)
											//showMessage("Error", message, "Close", "btn-danger", error_dialog_timeout, "error", error_autoclose); 	
											generateNotyMessage("error", message, 'topCenter', 'y', 7000);
										}
									
									<?php } else { ?>
									
										//show message using noty
										//generateNotyMessage(type, text, layout, closeit)
										generateNotyMessage("error", message, 'topCenter', 'y', 7000);
									
									<?php } ?>
									
								}
							
							}
							
							if (clear_form){ 
											
								$(".inputform").closest('form').find("input[type=text], textarea, file, select").val("");
			
							}
							
							if (reload_grid){ 
											
								$("#mybootgrid").bootgrid("reload");
			
							}
						  
						}
						
					});  
				     
			}
			
			<?php if ($show_file_upload) { ?>
				
					//file input
					$("#noupload").fileinput({
						showUpload: false,
						maxFileCount: 1
						//mainClass: "input-group-lg"
					});
					
					//avatar
					var btnCust = '<button type="button" class="btn btn-default" title="Add picture tags" ' + 
						'onclick="alert(\'Call your custom code here.\')">' +
						'<i class="glyphicon glyphicon-tag"></i>' +
						'</button>'; 
					$("#avatar-1").fileinput({
						overwriteInitial: true,
						maxFileSize: 4000,
						showClose: false,
						showCaption: false,
						browseLabel: '',
						removeLabel: '',
						browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
						removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
						removeTitle: 'Cancel or reset changes',
						elErrorContainer: '#kv-avatar-errors-1',
						msgErrorClass: 'alert alert-block alert-danger',
						defaultPreviewContent: '<img src="<?=SITEPATH?>admin/admin/images/default_avatar_male.jpg" alt="Your Avatar" style="width:160px">',
						layoutTemplates: {main2: '{preview} ' +  btnCust + ' {remove} {browse}'},
						allowedFileExtensions: ["jpg", "png", "gif"]
					});
											
					$("#multiple-images").fileinput({
						maxFileCount: 10,
						theme: "gly",
						showUpload: false,
						allowedFileExtensions: ["jpg", "gif", "png", "jpeg"]
					}).off('fileuploaded').on('fileuploaded', function(){ // refresh plugin after uploading 
						//generateNotyMessage(type, text, layout, closeit)
						generateNotyMessage("success", "Images successfully updated", 'topCenter', 'y', 7000);
					});
					
					$("#multiple-images2").fileinput({
						maxFileCount: 10,
						theme: "gly",
						showUpload: false,
						allowedFileExtensions: ["jpg", "gif", "png", "jpeg"]
					}).off('fileuploaded').on('fileuploaded', function(){ // refresh plugin after uploading 
						//generateNotyMessage(type, text, layout, closeit)
						generateNotyMessage("success", "Images successfully updated", 'topCenter', 'y', 7000);
					});
					
					$("#multiple-images3").fileinput({
						maxFileCount: 10,
						theme: "gly",
						showUpload: false,
						allowedFileExtensions: ["jpg", "gif", "png", "jpeg"]
					}).off('fileuploaded').on('fileuploaded', function(){ // refresh plugin after uploading 
						//generateNotyMessage(type, text, layout, closeit)
						generateNotyMessage("success", "Images successfully updated", 'topCenter', 'y', 7000);
					});
	  
			<?php } ?>
			
			
			<?php if ($show_bootstrap_dialog) { ?>
			
				function showQuickMessage(title, message, timeout, type, autoclose)
				{
		
					if (type == "primary" ) { msgtype = BootstrapDialog.TYPE_PRIMARY; }
					else if (type == "info" ) { msgtype = BootstrapDialog.TYPE_INFO; }
					else if (type == "success" ) { msgtype = BootstrapDialog.TYPE_SUCCESS; }
					else if (type == "warning" ) { msgtype = BootstrapDialog.TYPE_WARNING; }
					else if (type == "error" ) { msgtype = BootstrapDialog.TYPE_DANGER; }
					else { msgtype = BootstrapDialog.TYPE_DEFAULT; }
					
					if (timeout=="") { timeout = 5000; }
					var dialog = new BootstrapDialog({
						title: title,
						type: msgtype,
						message: function(){
						  var msg = message;
						  return msg;
						}
					});
					dialog.open();
					if (autoclose == "yes") {
						setTimeout(function() {
							dialog.close();
						}, timeout);
					}
				}
				
				function showMessage(title, message, close_dialog_text, close_dialog_css, timeout, type, autoclose)
				{
		
					if (type == "primary" ) { msgtype = BootstrapDialog.TYPE_PRIMARY; }
					else if (type == "info" ) { msgtype = BootstrapDialog.TYPE_INFO; }
					else if (type == "success" ) { msgtype = BootstrapDialog.TYPE_SUCCESS; }
					else if (type == "warning" ) { msgtype = BootstrapDialog.TYPE_WARNING; }
					else if (type == "error" ) { msgtype = BootstrapDialog.TYPE_DANGER; }
					else { msgtype = BootstrapDialog.TYPE_DEFAULT; }
					
					if (timeout=="") { timeout = 5000; }
					var dialog = new BootstrapDialog({
						title: title,
						type: msgtype,
						message: function(){
						  var msg = message;
						  return msg;
						},
						buttons: [{
							label: close_dialog_text,
							cssClass: close_dialog_css,
							action: function(dialogRef){
								dialogRef.close();
							}
						}]
						
					});
					dialog.open();
					if (autoclose == "yes") {
						setTimeout(function() {
							dialog.close();
						}, timeout);
					}
				}
				
			<?php } ?>
		
			
			function slide_up(divname, close_duration, close_wait_time, effect) {
				setTimeout(function(){
					$(divname).slideUp(close_duration);
				}, close_wait_time);
			}
			
			//HANDLE FORMS
			
			//equal height
			if ($('.equalheight').length) {
				//match height
				// remove the old group
				$('.equalheight').matchHeight({ remove: true });
			
				// apply matchHeight on the new selection, which includes the new element
				$('.equalheight').matchHeight();
				//end match height
			}
			
			if ($('.equalheight2').length) {
				//match height
				// remove the old group
				$('.equalheight2').matchHeight({ remove: true });
			
				// apply matchHeight on the new selection, which includes the new element
				$('.equalheight2').matchHeight();
				//end match height
			}
			//end equal height
							
			
			//Login form
			$(".form-signin").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-signin";
			   submitForm(loader_div, "user/login", "", "", "", "", "", "", "", loader_div);
			});
			//End login form
			
			//change password form
			$(".form-change-password").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-change-password";
			   submitForm(loader_div, "user/changepass", "", "", "", "", "", "", "", loader_div);
			});
			
			//set password form
			$(".form-set-password").submit(function(e){
			   e.preventDefault();
			   loader_div = $(".inputform").closest('form').find(".resultdiv");
			   var loader_div = ".form-set-password";
			   load_overlay(".form-set-password");
			   submitForm(loader_div,"user/setPassword", "<?=$ref_page?>", "", "", "", "", "", "", loader_div);
			});
			
			//FORM EDIT USER
			$(".form-upload-user-pic").submit(function(e){
				e.preventDefault();
				
				//loader_div = ".form-upload-user-pic";
				loader_div = $(this).closest("form").find(".resultdiv");
				load_overlay(loader_div);
				
				var dataString = new FormData($('.form-upload-user-pic')[0]);
										
				$.ajax({
					type: "POST",
					url: "<?=SITEPATH?>admin/api/v1/uploadUserPic",
					data: dataString,
					dataType: "json",
					async: false,
					cache: false,
					contentType: false,
					processData: false,
					success: function(data) {
						
						hide_overlay(loader_div);
						
						//prepend only if its not existing
						if (data.error) {
							//show error message
							$(".resultdiv").html("<div class='alert alert-danger login wow fadeIn animated text-center padding-20'>" + data.message + "</div>")
							.hide()
							.fadeIn(1000, function() { $('.resultdiv').append(""); });											
							slide_up(".resultdiv", 1000, 8000, "fadeOut"); //hide after 8 seconds
						} else {
							
							//$(loader_div).slideUp(1000);
							$(".resultdiv").html("<div class='alert alert-success loading wow fadeIn animated text-center'>" + data.message + "</div>")
							.hide()
							.fadeIn(1000, function() { $('.resultdiv').append(""); });
							//slide_up(".resultdiv", 1000, 8000, "fadeOut"); //hide after 8 seconds
							
							//load pic in the user profile box on the right
							$(".thumbnail").html("<img src='<?=SITEPATH?>admin/" + data.image_src + "' width='400'>")
							.hide()
							.fadeIn(1000, function() { $('.thumbnail').append(""); });
		
						}

					}
				});

			});
			//END FORM EDIT USER
			
			
			//FORM EDIT USER
			$(".form-edit-user").submit(function(e){
				e.preventDefault();
				var dataString = new FormData($('.form-edit-user')[0]);
				loader_div = ".wrapper_form";
				load_overlay(loader_div);
						
				$.ajax({
					type: "POST",
					url: "<?=SITEPATH?>admin/api/v1/user/edituser",
					data: dataString,
					dataType: "json",
					async: false,
					cache: false,
					contentType: false,
					processData: false,
					success: function(data) {
						hide_overlay(loader_div);
						//prepend only if its not existing
						if (data.error) {
							//show error message
							$(".resultdiv").html("<div class='alert alert-danger login wow fadeIn animated text-center padding-20'>" + data.message + "</div>")
							.hide()
							.fadeIn(1000, function() { $('.resultdiv').append(""); });											
							slide_up(".resultdiv", 1000, 8000, "fadeOut"); //hide after 8 seconds
						} else {
							
							//$(loader_div).slideUp(1000);
							$(".resultdiv").html("<div class='alert alert-success loading wow fadeIn animated text-center'>" + data.message + "</div>")
							.hide()
							.fadeIn(1000, function() { $('.resultdiv').append(""); });
							slide_up(".resultdiv", 1000, 8000, "fadeOut"); //hide after 8 seconds
		
						}

					}
				});

			});
			//END FORM EDIT USER
			
			//register form
			$(".form-register").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-register";
			   $(".resultdiv").html("<div class='alert alert-info loading wow fadeOut animated'>Hold On...</div>")
					.hide()
					.fadeIn(1000, function() { $('.resultdiv').append(""); });
			   submitForm(".form-register","user/register", "", "", "", "", "", "", "", loader_div);
			});
			
			//resend registration email - in case of expired links i.e. links older than 6 hrs
			$(".form-register-resend").submit(function(e){
			   e.preventDefault();
			   /*$(".resultdiv").html("<div class='alert alert-info loading wow fadeOut animated'>Hold On...</div>")
					.hide()
					.fadeIn(500, function() { $('.resultdiv').append(""); });
			   submitForm(".form-register-resend","user/registerresend", "<?//=$ref_page?>", "", "", "", "", "", "");*/
			   //submitForm(formName, action, ref, error_quickmessage, success_quickmessage, error_autoclose, success_autoclose, error_dialog_timeout, success_dialog_timeout)
			   submitForm(".form-register-resend","user/registerresend", "none", "yes", "no", "yes", "no", "7000", "", "");
			});
			//End register form
			
			//subscribe form
			$(".form-subscribe").submit(function(e){
			   e.preventDefault();
			   //submitForm(formName, action, ref, error_quickmessage, success_quickmessage, error_autoclose, success_autoclose, error_dialog_timeout, success_dialog_timeout)
			   submitForm(".form-subscribe","user/subscribe", "none", "yes", "no", "yes", "no", "7000", "", "");
			});
			
			$(".form-subscribe-resend").submit(function(e){
			   e.preventDefault();
			   //submitForm(formName, action, ref, error_quickmessage, success_quickmessage, error_autoclose, success_autoclose, error_dialog_timeout, success_dialog_timeout)
			   submitForm(".form-subscribe-resend","user/subscriberesend", "none", "yes", "no", "yes", "no", "7000", "", "");
			});
			//End subscribe form
			
			//Contacts form
			$(".form-contacts").submit(function(e){
			   e.preventDefault();
			   tinyMCE.triggerSave();
			   $(".resultdiv").html("<div class='alert alert-info loading wow fadeOut animated'>Hold On...</div>")
					.hide()
					.fadeIn(500, function() { $('.resultdiv').append(""); });
			   submitForm(".form-contacts","user/usermessage", "<?=$ref_page?>", "", "", "", "", "", "", "");
			});
			//End contacts form
			
			//Forgot password form
			$(".form-forgot-pass").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-forgot-pass";
			   submitForm(".form-forgot-pass","user/forgotpass", "<?=$ref_page?>", "", "", "", "", "", "", loader_div);
			});
			//End forgot password form
			
			//Change password form
			$(".form-change-pass").submit(function(e){
			   e.preventDefault();
			   $(".resultdiv").html("<div class='alert alert-info loading wow fadeOut animated'>Hold On...</div>")
					.hide()
					.fadeIn(500, function() { $('.resultdiv').append(""); });
			   submitForm(".form-change-pass","user/changepass", "<?=$ref_page?>", "", "", "", "", "", "", "");
			});
			//End Change password form
			
			//LOGOUT
			var logout = $('a.logout');
			logout.on('click',function(e) {
				e.preventDefault();
				
				$.LoadingOverlay("show"); //overlay
				
				$.ajax({
					url: "<?=SITEPATH?>admin/api/v1/user/logout",
					type: "POST",
					dataType: "json",
					success: function(data) {
						error = data.error;
						message = data.message;
						
						if (!error){
							
							var login_url = "<?=LOGIN_URL?>";
							$(".topresultdiv").html("<div class='alert alert-success login wow fadeIn animated'>Redirecting ...</div>")
								.hide()
								.fadeIn(1000, function() { $('.resultdiv').append(""); });
							window.location.replace(login_url); 
							
						} else {
							
							$(".topresultdiv").html("<div class='alert alert-danger loading wow fadeIn animated'>"+message+"</div>")
								.hide()
								.fadeIn(1000, function() { $('.resultdiv').append(""); });
							
						}
						
					}
				});                                                    
			});
			//END LOGOUT

			function scrollPageTo(hash) {

				var trgt = "#" + hash;
	
				//get the top offset of the target anchor
				var target_offset = $(trgt).offset();
				var target_top = target_offset.top;
		
				//goto that anchor by setting the body scroll top to anchor top
				$('html, body').animate({scrollTop:target_top}, 1500, '');
				
			}
			
			
			<?php if ($show_file_upload) { ?>
				
					//file input
					$("#noupload").fileinput({
						showUpload: false,
						maxFileCount: 1
						//mainClass: "input-group-lg"
					});
					
					//avatar
					var btnCust = '<button type="button" class="btn btn-default" title="Add picture tags" ' + 
						'onclick="alert(\'Call your custom code here.\')">' +
						'<i class="glyphicon glyphicon-tag"></i>' +
						'</button>'; 
					$("#avatar-1").fileinput({
						overwriteInitial: true,
						maxFileSize: 4000,
						showClose: false,
						showCaption: false,
						browseLabel: '',
						removeLabel: '',
						browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
						removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
						removeTitle: 'Cancel or reset changes',
						elErrorContainer: '#kv-avatar-errors-1',
						msgErrorClass: 'alert alert-block alert-danger',
						defaultPreviewContent: '<img src="<?=SITEPATH?>admin/images/default_avatar_male.jpg" alt="Your Avatar" style="width:160px">',
						layoutTemplates: {main2: '{preview} ' +  btnCust + ' {remove} {browse}'},
						allowedFileExtensions: ["jpg", "png", "gif"]
					});
	  
			<?php } ?>
		
			
			<?php if ($show_popup) { ?>
				$(".ajax").colorbox();
			<?php } ?>
			
			//EDIT RECORD
			/*function editRecord(field_name, field_value, table_name, full_title)
			{
				if (table_name == "sch_activities") 
				{
					//open url fior editing activities	
					//window.location.href = "<?//=SITEPATH?>";
					$.colorbox({inline:true, href:"#lightboxform", width:"80%", height:"25%"});
					
				} 
				
				if (table_name == "sch_ussd") 
				{
					//open url fior editing activities	
					window.location.href = "<?//=SITEPATH?>edit-school/"+field_value;
				}	
			}*/
			//END EDIT RECORD
			
			<?php if ($show_delete_images) { ?>
				
				$(".deleteItem .close").hide();     
				$(document).on('hover','.deleteItem',function(){
					//submit the form
					$(this).find(".close").show();
				});
				
				$(document).on('click','.deleteImage',function(){
					//console.log("click it");
					
					var field_name = $(this).closest('#table-data').data("tbl-pk");
					var field_value = $(this).data("pk-val");
					var full_title = $(this).data("row-name");
					var table_name = $(this).closest('#table-data').data("tbl");
					var target_div = "#image-" + field_value;
					//console.log("table_name - " + table_name + " == field_name - " +field_name);
					//delete record
					deleteImage(full_title, field_value, target_div);
							
				});
				
			<?php } ?>
			
			function create_image_entry(item) {
				var html = 		   '<div class="tkt-evt shadow1 tkt-border margin-btm-10 deleteImage relative" id="image-' + item.image_id + '" data-pk-val="' + item.image_id + '" data-row-name="' + item.caption + '">';
				html = html 	+  '  	<span class="close close-image" title="Delete Image"><button class="btn btn-danger btn-circle"><i class="fa fa-fw fa-close"></i></button></span>';
				html = html 	+  '    <img src="' + item.image + '" alt="' + item.caption + '" width="100%">';
				
				html = html 	+  '    <div style="position:absolute; top:1px, right:1px;">' +  item.image_dimensions + '</div>'; 
				
				html = html 	+  '</div>';
				return html;
			}
			
			//SWEET ALERT FUNCTIONS
			<?php if ($show_table || $show_delete) { ?>
			//ONCLICK OF DELETE BUTTON
			$(document).on("click", '.delete', function(e) { 
				e.preventDefault();
	
				var field_name = $(this).closest('.tbl-data').data("tbl-pk");
				var field_value = $(this).closest('.row-data').data("pk-val");
				var full_title = $(this).closest('.row-data').data("row-name");
				var table_name = $(this).closest('.tbl-data').data("tbl");
				var target_div = $(this).closest('.row-data').attr("id");
				var reload_page = $(this).closest('.tbl-data').data("reload-page");
				var main_content_div = $(this).closest('.tbl-data').attr("id");
				var items_count = $(this).closest('.tbl-data').prev(".counter-data").data("total-items");
				var is_last_page = $(this).closest('.tbl-data').prev(".counter-data").data("last-page");
				var current_page = $(this).closest('.tbl-data').prev(".counter-data").data("page");
				var items_per_page = $(this).closest('.tbl-data').prev(".counter-data").data("items-per-page");
				console.log('field_name - ' + field_name + ' == field_value - ' +  field_value + ' == table_name - ' + table_name + ' == full_title - ' + full_title);
				
				//delete
				deleteRecord(field_name, field_value, table_name, full_title, target_div, main_content_div, items_count, current_page, items_per_page, is_last_page, reload_page);
				
			});
			//END ONCLICK OF DELETE BUTTON	
			
			function showAlertMessage(title, message, message_type, confirmButtonColor, confirmButtonText) {
				if (!confirmButtonColor) { confirmButtonColor = "#DD6B55"; }
				if (!confirmButtonText) { confirmButtonText = "Yes"; }
				if (!message_type) { message_type = "success"; }
				swal({   
					title: title,   
					text: message,   
					type: message_type,   
					showCancelButton: true,   
					confirmButtonColor: confirmButtonColor,   
					confirmButtonText: confirmButtonText,
					closeOnConfirm: true 
				});	
			}		
			
			//delete record
			function deleteRecord(field_name, field_value, table_name, full_title, target_div, main_content_div, items_count, current_page, items_per_page, is_last_page, reload_page)
			{
				swal({   
					title: "Are you sure you want to delete \n\""+full_title+"\"?",   
					text: "All data for this item will be deleted!",   
					type: "warning",   
					showCancelButton: true,   
					confirmButtonColor: "#DD6B55",   
					confirmButtonText: "Yes",
					showLoaderOnConfirm: true,   
					closeOnConfirm: false 
				}, function(){   
					//DELETE record before showing message
					//send data to delete via json	
					$.post("<?=SITEPATH?>admin/api/v1/deleteItem", {'field_name' : field_name, 'field_value' : field_value, 'table_name' : table_name}, 
						 function(data){
							var success = data.success;
							
							if (success){
								//show success msg
								swal({   
									title: "Deleted!",   
									text: "\"" + full_title + "\" has been deleted.",   
									timer: 1000,
									type: "success"
								}, function(){
										
										if (target_div) {
											
											//set new items count div
											new_items_count = items_count - 1;
											$("#"+main_content_div).prev(".counter-data").data("total-items", new_items_count);
											
											//container div exists, slide it up to close
											$("#"+target_div).slideUp(1000, function() {
												$(this).remove();
											});
											
											//if items count is less than 1 and isLatPage is true, display no more records
											if ((new_items_count < 1) && is_last_page) {
												$("#"+main_content_div).html("<div class='text-center text-danger no-records'><h3 class='text-danger'>No Subscriptions Found</h3><br/><h3 class='text-success'>Please subscribe to proceed</h3></div>");
											}
											
											//more records exist, load the data
											if ((new_items_count < 1) && !is_last_page) {
												//prepare to load more data
												var next_page = current_page + 1;
												var url = "<?=SITEPATH?>admin/api/v1/fetchSubscriptions";
												var params = { "phone_number": "<?=USER_PHONE?>", "page": next_page, "items_per_page": items_per_page };
												load_subs_list("#"+main_content_div, params, url);
											}
											
										} else if (reload_page==1) {
											
											location.reload();
											
										} else {
											
											//reload grid data
											$("#mybootgrid").bootgrid("reload");
											
										}
									});
				
							} else {
								//show error msg
								swal({   
									title: "Error!",   
									text: "\""+full_title+"\" could not be deleted. Try again",   
									type: "danger"
								});	
							}
							
					}, 'json');
									
				});	
			}
			<?php } ?>
			
			//END SWEET ALERT FUNCTIONS
			
			

			<?php if ($show_form) { ?>
			
			//EDIT FORM INPUT FUNCTIONS
			
			
			var debounce = function (func, threshold, execAsap) {
 
				var timeout;
			 
				return function debounced () {
					var obj = this, args = arguments;
					function delayed () {
						if (!execAsap)
							func.apply(obj, args);
						timeout = null; 
					};
			 
					if (timeout)
						clearTimeout(timeout);
					else if (execAsap)
						func.apply(obj, args);
			 
					timeout = setTimeout(delayed, threshold || 100); 
				};
			 
			}
	
			function updateSingleFieldData(field_name, field_value, primary_field_name, primary_field_value, data_type, table_name, loader_div) {
				$.ajax({
					url: "<?=SITEPATH?>admin/api/v1/updateSingleFieldData",
					type: "POST",
					data: { "field_name": field_name, "field_value": field_value, "data_type": data_type, "primary_field_name": primary_field_name, "primary_field_value": primary_field_value, "table_name": table_name},
					dataType: "json",
					success: function(data) {
						error = data.error;
						message = data.message;
						
						if (!error){
							
							var login_url = "<?=SITEPATH?>admin/login";
							$(loader_div).html(success_check_mark)
								.hide()
								.fadeIn(1000);
							
						} else {
							
							$(loader_div).html("<div class='alert alert-danger loading wow fadeIn animated'>"+message+"</div>")
								.hide()
								.fadeIn(1000);
							
						}
						
					}
				});  
			}
			
			function deleteSingleItem(primary_field_name, primary_field_value, primary_field_data_type, table_name) {
				$.ajax({
					url: "<?=SITEPATH?>admin/api/v1/deleteSingleItem",
					type: "POST",
					data: { "data_type": primary_field_data_type, "primary_field_name": primary_field_name, "primary_field_value": primary_field_value, "table_name": table_name},
					dataType: "json",
					success: function(data) {
						error = data.error;
						message = data.message;
						
						if (!error){

							//call success bootstrap message
							
						} else {
							
							//call error bootstrap message
							
						}
						
					}
				});  
			}
				
			//fetch new data after user types in and pauses for one second
			$('input.input').keyup(debounce(function(){				
				var field_name = $(this).attr("name");
				var field_value = $(this).val();
				var datatype = $(this).data('tp');
				var table_name = $("#tbl-settings").data('tbl');
				var primary_field_name = $("#tbl-settings").data('pk');
				var primary_field_value = $("#tbl-settings").data('pkval');
				var parent_next_sib = $(this).closest(".col-data").find(".result");
				parent_next_sib.html(loader_object);
				//update field data
				updateSingleFieldData(field_name, field_value, primary_field_name, primary_field_value, datatype, table_name, parent_next_sib);
			}, 1000, false));
			//end fetch new data
			
			//fetch new data after change of field
			$('.input').change(debounce(function(){				
				var field_name = $(this).attr("name");
				var field_value = $(this).val();
				var datatype = $(this).data('tp');
				var table_name = $("#tbl-settings").data('tbl');
				var primary_field_name = $("#tbl-settings").data('pk');
				var primary_field_value = $("#tbl-settings").data('pkval');
				var parent_next_sib = $(this).closest(".col-data").find(".result");
				parent_next_sib.html(loader_object);
				//update field data
				updateSingleFieldData(field_name, field_value, primary_field_name, primary_field_value, datatype, table_name, parent_next_sib);
			}, 1000, false));
			//end fetch new data
			
			//END EDIT FORM INPUT FUNCTIONS
			
			<?php } ?>
			
			/*overlay divs*/
			$(".box").hover(function(){
			  $(this).find(".overlay").fadeIn();
			  },function(){
				$(this).find(".overlay").fadeOut();
			  }
		    );        
			/*overlay divs*/
									
			<?php if ($show_subs_list) { ?>
				
				//create new chat button click
				$(document).on('click','#add-new-sub',function(e){
								
					e.preventDefault();
					load_sub_form();
					//createNewChatPopup();
					
				});
				
				//show popup form
				function load_sub_form()
				{
													
					//show colorbox
					$.colorbox({
	
						inline:true, 
						href: "#create_new_sub_form",
						scrolling: false,
						width: '600px',
						height: '400px',
						onComplete: function() {
							$(this).colorbox.resize();
						}
					
					}); 							
						
				}
				
				function create_sub_entry (item) {
											
					var html =     '<div class="list-group-item media v-middle row-data" data-pk-val="'+item.sub_id +'" data-row-name="'+item.sub_student_name +' | ' + item.sub_sch_name + ' subscription" id="sub-'+item.sub_id+'">';
					html = html +  '  <div class="media-left">';
					html = html +  '    <div class="icon-block half img-circle">';
					html = html +  '    	<img src="'+item.sub_user_image +'" class="img-circle" width="50">';
					html = html +  '    </div>';
					html = html +  '  </div>';
					
					html = html +  '  <div class="media-body">';
					html = html +  '    <h4 class="text-title media-heading">';
					html = html +  '    	<span class="link-text-color">' + item.sub_student_name + '</span> &nbsp; | &nbsp;<span class="text-default">' + item.sub_sch_name + '</span>';
					html = html +  '    </h4>';
					html = html +  '    <div class="text-caption">Subscribed: ' + item.sub_created_at + '</div>';
					html = html +  '  </div>';
					html = html +  '  <div class="media-right">';
					html = html +  '	<a href="delete" class="btn btn-white btn-flat noclick sub delete"><i class="fa fa-times fa-fw text-danger"></i> Delete</a>';
					html = html +  '  </div>';
					html = html +  '</div>';
					
					return html;
					
				}
				
				//get items to show in modal
				function load_subs_list(loader_div, params, url)
				{

					if(fetching==false) //we are not loading
					{
						
						$(loader_div).html(loader_object);
						//load_overlay(loader_div);
				
						fetching = true;
						
						$.ajax({
							url: url,
							type: 'POST',
							data: params,
							success : function(data) {
								
								$(loader_div).html("");
								
								//set current page
								$(loader_div).prev(".counter-data").data("page", data.page);
								
								//set number of items in the div above this, named counter-data
								$(loader_div).prev(".counter-data").data("total-items", data.rowCount);
								$(loader_div).prev(".counter-data").data("last-page", data.isLastPage);
								//console.log("data.rowCount - " + data.rowCount);
								
								//print out the data
								if (data.rows.length == 0) {
									$(loader_div).html("<div class='text-center text-danger no-records'><h3 class='text-danger'>No Subscriptions Found</h3><br/><h3 class='text-success'>Please subscribe to proceed</h3></div>");
								} else {
									$.each(data.rows, function(index, item){
										var row = create_sub_entry(item);
										$(loader_div).append(row);
									});
								}
								
								//hide_overlay(loader_div);
								
								fetching = false;
								//refresh waypoints
								//Waypoint.refreshAll();
						
							},
							error : function(xhr, statusText, error) { 
								console.log("Error! Could not retrieve the data.");
								fetching = false;
								//refresh waypoints
								//Waypoint.refreshAll();
							}
						});
							
					}
						
				}
				
				//fetch subs
				var loader_div = "#subs_list";
				var items_per_page = $(loader_div).prev(".counter-data").data("items-per-page");
				var url = "<?=SITEPATH?>admin/api/v1/fetchSubscriptions";
				var params = { "phone_number": "<?=USER_PHONE?>", "page": "1", "items_per_page": items_per_page };
				load_subs_list(loader_div, params, url);
				
				//ONCHANGE OF SCHOOLS SELECT SUBSCRIPTIONS
				$(document).on('change','#sch_prov',function(){
				  	prov_id = this.value; // or $(this).val(
					loader_div = ".form-add-subscription";
					data_div = "#sch_name";
					//$("#school-list").addClass("hidden");
					//$("#student-list").addClass("hidden");
					$("#sch_name").html("");
					$("#student_name").html("");
					$(data_div).html("<option value=''>Select School</option>");
					//hide
					
					load_overlay(loader_div);
					//get students in this school
					$.ajax({
							url: "<?=SITEPATH?>admin/api/v1/fetchSchoolListing",
							type: 'POST',
							data: { "full_list": "1", "province": prov_id },
							success : function(data) {
								//load data to select field
								$.each(data.schools, function(index, item){
									var row = create_new_school_select_entry(item);
									$(data_div).append(row);
								});
								hide_overlay(loader_div);
								//$("#school-list").removeClass("hidden");
								
							},
							error : function(xhr, statusText, error) { 
								console.log("Error! Could not retrieve the data.");
								fetching = false;
							}
						});
				});
				
				$(document).on('change','#sch_name',function(){
				  	sch_id = this.value; // or $(this).val(
					loader_div = ".form-add-subscription";
					data_div = "#student_name";
					//hide
					//$("#student-list").addClass("hidden");
					$("#student_name").html("");
					$(data_div).html("<option value=''>Select Student</option>");
					
					load_overlay(loader_div);
					//get students in this school
					$.ajax({
							url: "<?=SITEPATH?>admin/api/v1/fetchStudentsInSchool",
							type: 'POST',
							data: { "sch_id": sch_id },
							success : function(data) {
								//load data to select field
								$.each(data.students, function(index, item){
									var row = create_new_student_select_entry(item);
									$(data_div).append(row);
								});
								hide_overlay(loader_div);
								//$("#student-list").removeClass("hidden");
							},
							error : function(xhr, statusText, error) { 
								console.log("Error! Could not retrieve the data.");
								fetching = false;
							}
						});
				});
				//END ONCHANGE OF SCHOOLS SELECT SUBSCRIPTIONS
				
				//create new student select entry
				function create_new_student_select_entry (item) {
			
					var html =     '<option value="'+item.reg_no+'">'+item.full_names+'</option>';
					
					return html;
					
				}
				
				//create new school select entry
				function create_new_school_select_entry (item) {
			
					var html =     '<option value="'+item.sch_id+'">'+item.sch_name+'</option>';
					
					return html;
					
				}
				
				//FORM ADD SUBSCRIPTION
				$(".form-add-subscription").submit(function(e){
					e.preventDefault();
					var dataString = new FormData($('.form-add-subscription')[0]);
					//var dob = dataString["dob"];
					var dob = $('#dob').val();
					var school_id = $('#sch_name').val();
					var reg_no = $('#student_name').val();
					//console.log("dob - " + dob + " == school_id - " + school_id + " == reg_no - " + reg_no);
					
					//check valid dob
					$.ajax({
						type: "POST",
						url: "<?=SITEPATH?>admin/api/v1/fetchStudentData",
						data: {"phone_number": <?=USER_PHONE?>, "reg_no": reg_no, "school_id": school_id, "dob": dob},
						dataType: "json",
						async: false,
						cache: false,
						//contentType: false,
						//processData: false,
						success: function(data) {

							//prepend only if its not existing
							if (data.student.error) {
								
								//show error message
								generateNotyMessage("error", data.student.message, 'topCenter', 'y', 7000);
								
							} else {
								
								//student data exists, subscribe user
								addSub(dataString);
								
							}

						}
					});	
										
	
				});
				
				function addSub(dataString){
					loader_div = "#subs_list";
					$.ajax({
						type: "POST",
						url: "<?=SITEPATH?>admin/api/v1/user/subscribe",
						data: dataString,
						dataType: "json",
						async: false,
						cache: false,
						contentType: false,
						processData: false,
						success: function(data) {

							//prepend only if its not existing
							if (data.error) {
								
								//show error message
								generateNotyMessage("error", data.message, 'topCenter', 'y', 7000);
								
							} else {
								
								//console.log(" data.message - " +  data.message);
								generateNotyMessage("success", data.message, 'topCenter', 'y', 7000);
								
								//get current sub data
								items_count = $(loader_div).prev(".counter-data").data("total-items");
								
								//if there were no records before, clear div
								if (items_count < 1) {
									$(loader_div).html('');
								}
											
								//sub has been created, prepend new chat at the top of subs list
								new_sub = create_sub_entry(data.subs);
								
								//prepend new data
								$(new_sub).hide().prependTo(loader_div).slideDown("slow");
								//end prepend new data
								
								//update sub data
								new_items_count = items_count + 1;
								
								//update sub data
								$(loader_div).prev(".counter-data").data("total-items", new_items_count);
								
								setTimeout(function(){
									parent.$.colorbox.close();	
								},1000);
								
							}

						}
					});	
				}
				//END FORM ADD SUBSCRIPTION
				
			<?php } ?>
			
			<?php if ($show_admin_home) { ?>
				
				function create_chat_entry (item) {
					
					var html =     '<li class="list-group-item chat-item noclick" data-chat-id="' + item.chat_id + '">';
					html = html +  '  <a href="#">';
					html = html +  '    <div class="media v-middle margin-v-0-10">';
					html = html +  '    	<div class="media-body">';
					html = html +  '    		<p class="text-subhead">';
					html = html +  '    			<img src="' + item.user_image + '" alt="' + item.student_full_names + '" class="width-30 img-circle" /> &nbsp;';
					html = html +  '    			' + item.student_full_names + ' - ' + item.full_names + ' - ';
					html = html +  '    			<span class="text-caption text-light"> ' + item.updated_at + '</span>';
					html = html +  '    		</p>';
					html = html +  '    	</div>';
					html = html +  '    	<div class="media-right">';
					html = html +  '    		<div class="width-50 text-right">';
					html = html +  '    			<a href="#" class="btn btn-white btn-xs"><i class="fa fa-reply"></i></a>';
					html = html +  '    		</div>';
					html = html +  '    	</div>';
					html = html +  '    </div>';
					html = html +  '    <p class="recent_msg_id" data-msgid="' + item.recent_message_id + '">' + item.recent_message + '...</p>';
					html = html +  '</li>';
					
					return html;
					
				}
								
				function create_student_fee_entry (item) {
					
					var html =     '<tr>';
					html = html +  '	<td class="text-caption">';
					html = html +  '		<div class="label label-grey-200 label-xs">' + item.payment_paid_at + '</div>';
					html = html +  '	</td>';
					html = html +  '	<td>' + item.payment_student_name + ' (' + item.payment_paid_by + ')</td>';
					html = html +  '	<td>' + item.payment_amount + '</td>';
					html = html +  '	<td>' + item.payment_mode + '</td>';
					html = html +  '</tr>';
						
					return html;
					
				}
								
				function create_school_sub_entry (item) {
					
					var html =     '<li class="list-group-item chat-item noclick" data-chat-id="5">';
					html = html +  '	<a href="#"> </a>';
					html = html +  '	<div class="media v-middle margin-v-0-10">';
					html = html +  '		<a href="#"> </a>';
					html = html +  '		<div class="media-body">';
					html = html +  '			<p class="text-subhead">';
					html = html +  '				<a href="#"> </a>';
					html = html +  '				<a href="#"><img src="' + item.sub_user_image + '" alt="' + item.sub_student_name + ' - ' + item.sub_sch_name + '" class="width-30 img-circle"></a> &nbsp; <a href="#">' + item.sub_student_name + ' - (' + item.sub_client_name + ')</a> - <span class="text-caption text-light">' + item.sub_created_at + '</span> </p>';
					html = html +  '		</div>';
					html = html +  '		<div class="media-right">';
					html = html +  '			<div class="width-50 text-right"> <a href="#" class="btn btn-white btn-xs"><i class="fa fa-reply"></i></a> </div>';
					html = html +  '		</div>';
					html = html +  '	</div>';
					html = html +  '	<p>' + item.sub_sch_name + ' - ' + item.sub_current_class + ' ' + item.sub_stream + '</p>';
					html = html +  '</li>';
						
					return html;
					
				}
				
				//get items to show
				function load_home_fees_list(loader_div, params, url)
				{

					if(fetching==false) //we are not loading
					{
						
						$(loader_div).html(loader_object);
						//load_overlay(loader_div);
				
						fetching = true;
						
						$.ajax({
							url: url,
							type: 'POST',
							data: params,
							success : function(data) {
								
								$(loader_div).html("");
																																			
								//print out the data
								if (data.fee_payment.length == 0) {
									$(loader_div).html("<div class='text-center text-danger no-records'><h3 class='text-danger'>No Fee Payments Found</h3></div>");
									//hide create pdf button
									$("#show_pdf").addClass("hidden");
								} else {
									
									$.each(data.fee_payment, function(index, item){
										var row = create_student_fee_entry(item);
										$(loader_div).append(row);
									});
									//show create pdf button
									$("#show_pdf").removeClass("hidden");
									
								}
																										
								fetching = false;
						
							},
							error : function(xhr, statusText, error) { 
								console.log("Error! Could not retrieve the data.");
								fetching = false;
							}
						});
							
					}
						
				}
				
				//get items to show
				function load_home_subs_list(loader_div, params, url)
				{

					if(fetching==false) //we are not loading
					{
						
						$(loader_div).html(loader_object);
				
						fetching = true;
						
						$.ajax({
							url: url,
							type: 'POST',
							data: params,
							success : function(data) {
								
								$(loader_div).html("");
																																			
								//print out the data
								if (data.rows.length == 0) {
									$(loader_div).html("<div class='text-center text-danger no-records'><h3 class='text-danger'>No Subscriptions Found</h3></div>");
								} else {
									
									$.each(data.rows, function(index, item){
										var row = create_school_sub_entry(item);
										$(loader_div).append(row);
									});
									//show create pdf button
									$("#show_pdf").removeClass("hidden");
									
								}
																										
								fetching = false;
						
							},
							error : function(xhr, statusText, error) { 
								console.log("Error! Could not retrieve the data.");
								fetching = false;
							}
						});
							
					}
						
				}
				
				//GET CHATS
				var url = "<?=SITEPATH?>admin/api/v1/chats";
				var loader_div = "#messages-list";
				var params = { "user_id": "<?=USER_ID?>", "page": "1", "limit": "5", "school_ids": "<?=USER_SCHOOL_IDS?>" };
				counter_div = "#messagesPageNum";
				retrieveChatJSON(url, loader_div, counter_div, params);
				
				//GET FEE PAYMENTS
				fetching = false;
				var url = "<?=SITEPATH?>admin/api/v1/fetchStudentFeePayments";
				var loader_div = "#latest-fee-payments";
				var params = { "user_id": "<?=USER_ID?>", "page": "1", "limit": "6", "sch_id": "<?=USER_SCHOOL_IDS?>" };
				load_home_fees_list(loader_div, params, url);
				
				//GET SCHOOL SUBS
				fetching = false;
				var url = "<?=SITEPATH?>admin/api/v1/fetchSubscriptions";
				var loader_div = "#school-sub-list";
				var params = { "user_id": "<?=USER_ID?>", "page": "1", "limit": "6", "sch_id": "<?=USER_SCHOOL_IDS?>" };
				load_home_subs_list(loader_div, params, url);
			
			<?php } ?>
			
			<?php if ($show_chat_list || $show_admin_home) { ?>
			
			//new chat funcs
			$(document).on('click','.chat_head',function(e){
				
				e.preventDefault();			
				$(".chat_body").hide();
				
			});
			//end new chat funcs
			
			//create new chat button click
			$(document).on('click','#start-new-chat',function(e){
							
				e.preventDefault();
				load_user_list(<?=USER_ID?>, "#chat-users-list");
				//createNewChatPopup();
				
			});
			
			
			//format items for the modal display
			function create_user_list_entry (item) {
				
				var user_name = item.sub_sch_name + " ("+ item.sub_student_name + ")";
				
				var html =     '<div class="panel panel-default paper-shadow chat-msg-item-full margin-top-10" data-z="0.5" data-hover-z="1" data-animated data-user-id="' + item.sub_sch_id + '" data-student-id="' + item.sub_student_id + '">';
				html = html +  '  <div class="panel-body">';
				html = html +  '    <div class="media v-middle">';
				html = html +  '    	<div class="media-left">';
				html = html +  '    		<img src="' + item.sub_user_image + '" alt="' + user_name + '" class="media-object img-circle width-50" />';
				html = html +  '    	</div>';
				
				html = html +  '      <div class="media-body message">';
				html = html +  '          <h4 class="text-subhead margin-none">' + user_name + '</h4>';
				html = html +  '      </div>';
				html = html +  '    </div>';
				html = html +  '  </div>';
				html = html +  '</div>';
				
				return html;
				
			}
			
			//get items to show in modal
			function load_user_list(user_id, source_div)
			{
				
				var url = "<?=SITEPATH?>admin/api/v1/fetchSubscriptions";
				var params = { "phone_number": "<?=USER_PHONE?>", "page": "1" };
				
				if(fetching==false) //we are not loading
				{
					//load initial ul markup
					$(source_div).html("<ul class='list-group' id='subs_list'></ul>");
					//set ul as target for new data
					loader_div = "#subs_list";
					//$(loader_div).html(loader_object);
					load_overlay(loader_div);
			
					fetching = true;
					
					$.ajax({
						url: url,
						type: 'POST',
						data: params,
						success : function(data) {
							
							//$(loader_div).html("");
							hide_overlay(loader_div);
							
							//set current page
							$(counter_div).data('page',data.page);
							
							//print out the data
							if (data.rows.length == 0) {
								$(loader_div).html("<div class='text-center text-danger'>No subscriptions found. <br><br>Please subscribe to enable chat.</div>");
							} else {
								$.each(data.rows, function(index, item){
									var row = create_user_list_entry(item);
									$(loader_div).append(row);
								});
							}
							
							//hide_overlay(loader_div);
							
							fetching = false;
							//refresh waypoints
							//Waypoint.refreshAll();
							
							//show colorbox
							$.colorbox({
			
								inline:true, 
								href: "#create_new_chat_form",
								scrolling: false,
								width: '600px',
								height: '400px',
								onComplete: function() {
									$(this).colorbox.resize();
								}
							
							}); 
					
						},
						error : function(xhr, statusText, error) { 
							console.log("Error! Could not retrieve the data.");
							fetching = false;
							//refresh waypoints
							//Waypoint.refreshAll();
						}
					});
						
				}
					
			}
					
			function retrieveChatJSON(myUrl, loader_div, counter_div, params)
				{
					if(fetching==false) //we are not loading
					{
					
						$(loader_div).html(loader_object);
						//load_overlay(loader_div);
				
						fetching = true;
				
						$.ajax({
							url: myUrl,
							type: 'POST',
							data: params,
							success : function(data) {
								
								$(loader_div).html("");
																
								//set current page
								$(counter_div).data('page', data.page);
								
								//print out the data
								if (data.chats.length == 0) {
									
									var show_text = '<li class="list-group-item chat-item noclick" data-chat-id="12">';
									show_text = show_text + '		<div class="media v-middle">';
									show_text = show_text + '			<div class="media-left">';
									show_text = show_text + '				<img src="http://localhost/pendoschools/images/no_image.jpg" width="50" alt="ggg" class="media-object img-circle"/>';
									show_text = show_text + '			</div>';
									show_text = show_text + '			<div class="media-body">';
									show_text = show_text + '			  <h3 class="text-danger">No Chats Found</h3>';
									show_text = show_text + '			</div>';
									show_text = show_text + '		</div>';
									show_text = show_text + '	</li>';
							
									$(loader_div).html(show_text);
									
									console.log(data);
									
								} else {
									
									$.each(data.chats, function(index, item){
										var row = create_chat_entry(item);
										$(loader_div).append(row);
									});
								
								}
								
								//hide_overlay(loader_div);
								
								//chat_id = getChatId(location.href);
								//if ((chat_id==null) || (chat_id=='')){
									//onload, click on first item after 2 seconds
									setTimeout(function() {
										$('.chat-item').first().click();
									}, 2000);
								//}
								
								
								fetching = false;
								//refresh waypoints
								//Waypoint.refreshAll();
						
							},
							error : function(xhr, statusText, error) { 
								console.log("Error! Could not retrieve the data.");
								fetching = false;
								//refresh waypoints
								//Waypoint.refreshAll();
							}
						});
					
					}
					
				}
				
			<?php } ?>
				
				
			<?php if ($show_chat_list) { ?>
																	
				//hover delete link
				$(".c-delete").hide();     

				
				
				$(document).on("hover", ".chat-item", function() {
					 $(this).find('.c-delete').fadeIn(1500);
				}, function() {
					$(this).find('.c-delete').fadeOut(1500); 
				});
				//end hover delete link

				function create_chat_message_entry (item) {
			
					user_name = item.full_names + ' - ' + item.phone_number;
					message_css = "other";
					
					if (item.user_id == <?=USER_ID?>) {
						user_name = "You";
						message_css = "self";	
					}
					
					html = '<div class="panel panel-default paper-shadow chat-msg-item margin-top-10 ' + message_css + '" data-z="0.5" data-hover-z="1" data-animated>';
					html = html +  '  <div class="panel-body">';
					html = html +  '    <div class="media v-middle">';
					html = html +  '    	<div class="media-left">';
					html = html +  '    		<img src="' + item.user_image + '" alt="' + item.full_names + '" class="media-object img-circle width-50" />';
					html = html +  '    	</div>';
					
					html = html +  '      <div class="media-body message">';
					html = html +  '        <h4 class="text-subhead margin-none">' + user_name + '</h4>';
					html = html +  '     	<div class="text-light">' + item.message + '</div>';
					html = html +  '        <p class="text-caption text-light"><i class="fa fa-clock-o"></i> ' + item.created_at + '</p>';
					html = html +  '      </div>';
					html = html +  '    </div>';
					html = html +  '  </div>';
					html = html +  '</div>';
					
					return html;
										
				}
				
				function create_chat_entry (item) {
			
					var full_names = item.full_names + " (" + item.student_full_names + ")";
					
					var html =     '<li class="list-group-item chat-item noclick" data-chat-id="' + item.chat_id + '">';
					html = html +  '    <div class="media v-middle">';
					html = html +  '    	<div class="media-left">';
					html = html +  '    		<img src="' + item.user_image + '" width="50" alt="" class="media-object img-circle" />';
					html = html +  '    	</div>';
					
					html = html +  '      <div class="media-body">';
					html = html +  '         <div class="date">' + item.recent_message_created_at + '</div>';
					html = html +  '         <div class="user">' + item.full_names + '</div>';
					html = html +  '         <div class="user">(' + item.student_full_names + ')</div>';
					if (item.recent_message != null){
						html = html +  '     <div class="text-light recent_msg_id" data-msgid="' + item.recent_message_id + '">' + item.recent_message + '...</div>';
												
					}
					html = html +  '         <div class="c-delete text-danger"></div>';
					html = html +  '      </div>';
					html = html +  '    </div>';
					html = html +  '</li>';
					
					return html;
					
				}
				
				
				function retrieveChatMessagesJSONSilent(url, loader_div, counter_div, params, theItem)
				{
					
					if(fetching==false) //we are not loading
					{
				
						fetching = true;
						
						$.ajax({
							url: url,
							type: 'POST',
							data: params,
							success : function(data) {						
								
								//set current page
								$(counter_div).data('page',data.page);
								
								//current msg id
								current_msg_id = $("#currentMessageId").data("msgid");
								//console.log("current_msg_id - sss " + current_msg_id);
																
								if (data.chat_messages.length > 0) {
									
									//reset data
									$(loader_div).html("");
									
									//print out the data
									$.each(data.chat_messages, function(index, item){
										var row = create_chat_message_entry(item);
										$(loader_div).append(row);
									});
									
									//update recent messages id and msg
									$(theItem).find(".recent_msg_id").data("msgid", data.recent_message_id);
									$(theItem).find(".recent_msg_id").html(data.recent_message);
									$(theItem).find(".date").html(data.recent_message_created_at);
									//thedate = $(theItem).find(".date").text();
									//console.log("thedate - " + thedate);
									
									//update recent message id
									$("#currentMessageId").data("msgid", data.recent_message_id);
									//console.log(data.recent_message_id);
									
									//scroll to bottom									
									$("#messages-list").scrollTop($("#messages-list").prop("scrollHeight"));
									
								}
								
								//hide_overlay(loader_div);
								
								fetching = false;
								//refresh waypoints
								//Waypoint.refreshAll();
						
							},
							error : function(xhr, statusText, error) { 
								console.log("Error! Could not retrieve the data.");
								fetching = false;
								//refresh waypoints
								//Waypoint.refreshAll();
							}
						});
							
					}
					
				}
				
				function retrieveChatMessagesJSONSilent2(url, loader_div, counter_div, params)
				{
					
					if(fetching==false) //we are not loading
					{
				
						fetching = true;
						
						$.ajax({
							url: url,
							type: 'POST',
							data: params,
							success : function(data) {						
								
								//set current page
								$(counter_div).data('page',data.page);
								
								//current msg id
								current_msg_id = $("#currentMessageId").data("msgid");
								//console.log("current_msg_id - sss " + current_msg_id);
																
								if (data.recent_message_id > current_msg_id) {
									
									//print out the data
									$.each(data.chat_messages, function(index, item){
										var row = create_chat_message_entry(item);
										$(loader_div).append(row);
									});
									
									//update recent message id
									$("#currentMessageId").data("msgid", data.recent_message_id);
									//console.log(data.recent_message_id);
									
									//scroll to bottom									
									$("#messages-list").scrollTop($("#messages-list").prop("scrollHeight"));
									
								}
								
								//hide_overlay(loader_div);
								
								fetching = false;
								//refresh waypoints
								//Waypoint.refreshAll();
						
							},
							error : function(xhr, statusText, error) { 
								console.log("Error! Could not retrieve the data.");
								fetching = false;
								//refresh waypoints
								//Waypoint.refreshAll();
							}
						});
							
					}
					
				}
				
				function retrieveChatMessagesJSON(myUrl, loader_div, counter_div, params)
				{
					
					if(fetching==false) //we are not loading
					{
					
						$(loader_div).html(loader_object);
						//load_overlay(loader_div);
				
						fetching = true;
						
						$.ajax({
							url: myUrl,
							type: 'POST',
							data: params,
							success : function(data) {
								
								$(loader_div).html("");
								
								//set current page
								$(counter_div).data('page',data.page);
								
								if (data.chat_messages.length == 0) {
									
									$(loader_div).html("<div class='text-center text-danger'><h2>No Chat Messages</h2></div>");
									
								} else {
									
									//set  recipient name
									$("#recipient_name").html(data.student_name);
									
									//print out the data
									$.each(data.chat_messages, function(index, item){
										var row = create_chat_message_entry(item);
										$(loader_div).append(row);
									});
									
									//scroll to bottom									
									$("#messages-list").scrollTop($("#messages-list").prop("scrollHeight"));
									
								}
								
								//hide_overlay(loader_div);
								
								fetching = false;
								//refresh waypoints
								//Waypoint.refreshAll();
						
							},
							error : function(xhr, statusText, error) { 
								console.log("Error! Could not retrieve the data.");
								fetching = false;
								//refresh waypoints
								//Waypoint.refreshAll();
							}
						});
							
					}
					
				}
				
				//CREATE A NEW CHAT FOR THIS USER
				function create_new_chat(creator_id, recipient_id, student_id, loader_div)
				{
					
					url = "<?=SITEPATH?>admin/api/v1/createNewChat";
					var params = { "creator_id": creator_id, "recipient_id": recipient_id, "student_id": student_id };
					
					if(fetching==false) //we are not loading
					{
						//set ul as target for new data
						//loader_div = "ul#chats-list";
						
						load_overlay(loader_div);
				
						fetching = true;
						
						$.ajax({
							url: url,
							type: 'POST',
							data: params,
							success : function(data) {
								
								hide_overlay(loader_div);
								
								//chat has been created, close modal and prepend new chat at the top of chats list
								new_chat = create_chat_entry (data.chat);
								
								//close colorbox after 1 secs
								setTimeout(function(){
									parent.$.colorbox.close();	
								},1000);	
								
								//prepend only if its not existing
								if (!data.chat.error) {
									//prepend new data
									$(new_chat).hide().prependTo(loader_div).slideDown("slow");
									//end prepend new data
									
									//onload, click on first item after 2 seconds
									setTimeout(function() {
										$('.chat-item').first().click();
									}, 2000);
								} else {
									generateNotyMessage("error", data.chat.message, 'topCenter', 'y', 7000);	
								}
								
								fetching = false;
								//refresh waypoints
								//Waypoint.refreshAll();
						
							},
							error : function(xhr, statusText, error) { 
								console.log("Error! Could not retrieve the data.");
								fetching = false;
								//refresh waypoints
								//Waypoint.refreshAll();
							}
						});
							
					}
						
				}
				
				
				
				var url = "<?=SITEPATH?>admin/api/v1/chats";
				var loader_div = "#chats-list";
				var params = { "user_id": "<?=USER_ID?>", "page": "1" };
				counter_div = "#chatPageNum";
				retrieveChatJSON(url, loader_div, counter_div, params);
				
				//catch clicks on chat item div
				$(document).on("click", '.chat-item', function(e) { 
				   
				   e.preventDefault();
				   
				   //get chat id
				   var chat_id = $(this).data("chat-id");

				   //var msg_id = $(this).find(".recent_msg_id").data("msgid");
				   //console.log("hii chat_id - " + chat_id + " == msg_id - " + msg_id);
				   
				   //redirect url
				   var redirect_url = "<?=SITEPATH?>chats/" + chat_id;
				   
				   load_ajax_url(redirect_url);
				   
				   //console.log(location.href);
				   chat_id = getChatId(location.href);
				   //console.log("chat_id - " + chat_id);
				   				   
				   //set clicked div
				   var activeOpacity   = 1.0,
				   inactiveOpacity = 0.9,
				   fadeTime = 100,
				   clickedClass = "active";				   
				   var li = $(this);
				   var alreadySelected = li.hasClass('active');
				   // Remove selected class from any elements other than this
				   $('#chats-list li').removeClass(clickedClass).fadeTo(fadeTime, inactiveOpacity);
				   li.addClass(clickedClass).fadeTo(((alreadySelected) ? 0 : fadeTime), activeOpacity);
				   //end set clicked div
				   
				   //retrieve chat messages
				   var url = "<?=SITEPATH?>admin/api/v1/chats/" + chat_id;
				   var loader_div = "#messages-list";
				   counter_div = "#messagesPageNum";
				   var params = {  "user_id": "<?=USER_ID?>", "chat_id": chat_id, "page": "1" };
				   retrieveChatMessagesJSON(url, loader_div, counter_div, params);
	
	
				});
				
				setTimeout(function() {
														
					setInterval(function() {
						//repeat messages checks
						chat_id = getChatId(location.href);
						
						if ((chat_id==null) || (chat_id=='')) {
							//click the first chat item
							$('.chat-item').first().click();
						} else {
						
							var url = "<?=SITEPATH?>admin/api/v1/chats/" + chat_id;
							var loader_div = "#messages-list";
							counter_div = "#messagesPageNum";
							var params = {  "user_id": "<?=USER_ID?>", "chat_id": chat_id, "page": "1" };
							var theItem = $('body').find("li.chat-item.active");
							
							retrieveChatMessagesJSONSilent(url, loader_div, counter_div, params, theItem);
						
						}
						
					}, 10000);
					
					//end repeat messages checks
				
				}, 20000);
				
				//clicking on a subscription item in popup
				$(document).on("click", '.chat-msg-item-full', function(e) { 
				   
				   //get user id
				   var user_id = $(this).data("user-id");
				   var student_id = $(this).data("student-id");
				   
				   var loader_div = "#create_new_chat_form";
				   //console.log('user_id - ' + user_id + ' == student_id - ' + student_id);
				   
				   //set clicked div by setting class .active
				   var activeOpacity   = 1.0,
				   inactiveOpacity = 0.9,
				   fadeTime = 100,
				   clickedClass = "active";				   
				   var li = $(this);
				   var alreadySelected = li.hasClass('active');
				   //end set clicked div by setting class .active
					
				   // Remove selected class from any elements other than this
				   $('#subs_list li').removeClass(clickedClass).fadeTo(fadeTime, inactiveOpacity);
				   li.addClass(clickedClass).fadeTo(((alreadySelected) ? 0 : fadeTime), activeOpacity);
				   //end set clicked div
				   
				   create_new_chat(<?=USER_ID?>, user_id, student_id, loader_div);
				   
				});
				
				function load_ajax_url(url){
				   window.history.pushState(null,null, url);
   				   return false;	
				}
				
				function getChatId(url){
					
					var pieces = url.split("/");
					
					id = pieces[pieces.length-1];
					//console.log(id);
					
					return id;
					
				}
				
				//SEND MESSAGE BUTTON CLICK
				$(document).on("click", '#send-msg', function(e) { 
					
					e.preventDefault();
				   	
					//get chat id
					var chat_id = $(this).closest(".page-section").find("li.chat-item.active").data("chat-id");
					
					var loading_div = "#message-send-form";
					
					//msg_id = $(this).closest(".page-section").find("li.chat-item.active .recent_msg_id").data("msgid");
					//console.log("new chat_id - " + chat_id);
					
				   	//check if field has data
				   	message = $("input#message").val();
				   
				   	//send the chat message
				   	if (message != '') {
						fetching = false;
						var theItem = $(this).closest(".page-section").find("li.chat-item.active");
						createNewChatMessage(<?=USER_ID?>, chat_id, message, loading_div, theItem);   
				   	} 
				   
				});
				//END SEND MESSAGE BUTTON CLICK				
				
				//CREATE A NEW CHAT MSG FOR THIS USER
				function createNewChatMessage(user_id, chat_id, message, loading_div, theItem)
				{
					
					url = "<?=SITEPATH?>admin/api/v1/chats/" + chat_id + "/message";
					var params = { "user_id": user_id, "chat_id": chat_id , "message": message};
					
					if(fetching==false) //we are not loading
					{
						
						load_overlay(loading_div);
						
						//set ul as target for new data
						loader_div = "#messages-list";
				
						fetching = true;
						
						$.ajax({
							url: url,
							type: 'POST',
							data: params,
							success : function(data) {
								
								hide_overlay(loading_div);
								
								if (data.error) {
									
									generateNotyMessage("error", data.message, 'topCenter', 'y', 7000);
									
								} else {
									
									//chat has been created, close modal and prepend new chat at the top of chats list
									new_chat = create_chat_message_entry(data.message);
									
									//append new data
									$(loader_div).append(new_chat);
									//end append new data
									
									//update recent messages id and msg
									$(theItem).find(".recent_msg_id").data("msgid", data.message.recent_message_id);
									$(theItem).find(".recent_msg_id").html(data.message.recent_message);
									$(theItem).find(".date").html(data.message.recent_message_created_at);
									
									//scroll to bottom									
									$("#messages-list").scrollTop($("#messages-list").prop("scrollHeight"));
									
									$("input#message").val('');
								
									fetching = false;
									
									//refresh waypoints
									Waypoint.refreshAll();
								}
						
							},
							error : function(xhr, statusText, error) { 
								console.log("Error! Could not retrieve the data.");
								fetching = false;
								//refresh waypoints
								//Waypoint.refreshAll();
							}
						});
							
					}
						
				}
				
				
			
			<?php } ?>
			
			//add file inputs
			function add_file_input() {

			   $('div#add_pic').append("<div class='addedDiv' style='display:none'><label>Artist Pic:</label><input type='file' name='pics[]'><br/></div>");
			   $('div.addedDiv').slideDown("slow");
			
			}
			
			$(document).ready(function(){
			
			   $('a#add_field').click(add_file_input);
			
			})
			//end add file inputs
			
			//get json data
			function retrieveJSON(myUrl, loader_div, params)
			{
								
					$.ajax({
						url: myUrl,
						type: "POST",
						data: params,
						dataType: "json",
						success: function(data) {
							error = data.error;
							message = data.message;
							
							if (!error){
								
								$(loader_div).html(success_check_mark)
									.hide()
									.fadeIn(1000);
								
							} else {
								
								$(loader_div).html("<div class='alert alert-danger loading wow fadeIn animated'>"+message+"</div>")
									.hide()
									.fadeIn(1000);
								
							}
							
						}
					});  
				
			
			}
			
			//NOCLICK LINKS
			$(document).on("click", '.noclick', function(e) { 
			   e.preventDefault();
			});
			//END NOCLICK LINKS
			
			//ALLOW NUMBERS ONLY FIELDS
            $('.numbersOnly').keydown(function(event) {
                // Allow special chars + arrows 
                if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 
                    || event.keyCode == 27 || event.keyCode == 13 
                    || (event.keyCode == 65 && event.ctrlKey === true) 
                    || (event.keyCode >= 35 && event.keyCode <= 39)){
                        return;
                }else {
                    // If it's not a number stop the keypress
                    if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                        event.preventDefault(); 
                    }   
                }
            });
			//END ALLOW NUMBERS ONLY FIELDS
			
			//START CHRACTERS REMAINING
			
			var left = <?=MAX_CHAR_LENGTH?>;
			$('#text_counter').text(left);
		
			$('#sms_message').keyup(function () {
		
				left = <?=MAX_CHAR_LENGTH?> - $(this).val().length;
		
				if(left < 0){
					$('#text_counter').addClass("overlimit");
				}
				if(left >= 0){
					$('#text_counter').removeClass("overlimit");
				}
		
				$('#text_counter').text(left);
				
			});

			//END CHRACTERS REMAINING
			
			<?php if ($show_table) { ?>
		    
			//START JQUERY GRID
			
				<?php if ($show_activities) { ?>
					
					//load activities grid
					listUrl = "<?=SITEPATH?>admin/api/v1/fetchSchoolActivities";
					editUrl = "<?=SITEPATH?>admin/edit-activity";
					param_name = "sch_id";
					param_value = "<?=$sch_id?>";
					param_name2 = "";
					param_value2 = "";
					loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, "", "", "", "");
					
				<?php } ?>
				
				<?php if ($regular_show_results_list) { ?>
					
						//on change of student combo
						$(document).on('change','#student_id',function(){
							selected = $(this).val();
							//get and set school_id for selected student
							setSchoolId(selected);
							
						});
						
						//on click show pdf button
						$(document).on('click','#show_pdf',function(){
							
							sch_id = $("#sch_id").val();
							reg_no = $("#reg_no").val();
							year = $("#year").val();
							term = $("#term").val();
							//load pdf creation console
							var pdfCreationURL = "<?=SITEPATH?>admin/savepdf.php?sch_id=" + sch_id + "&reg_no=" + reg_no + "&year=" + year + "&term=" + term + "&item_type=results";
							window.open(pdfCreationURL);
							
						});
												
						function setSchoolId(selected){
							var url = "<?=SITEPATH?>admin/api/v1/fetchSchoolIdFromStudentId";
							$.ajax({
								url: url,
								type: 'POST',
								data: {student_id: selected},
								success : function(data) {
									if (!data.error) {
										//update the sch_id textfield value
										$("#sch_id").val(data.sch_id);
										$("#reg_no").val(data.reg_no);
										//reload results
										fetchResults();
									} 
								},
								error : function(xhr, statusText, error) { 
									console.log("Error! Could not retrieve the data.");
									fetching = false;
								}
							});
						}
						
						//load data
						function create_student_result_header () {
												
							var html =     '<div class="div-table-row table-head">';
							html = html +  '  <div class="div-table-col">Subject</div>';
							html = html +  '  <div class="div-table-col">Score</div>';
							html = html +  '  <div class="div-table-col">Grade</div>';
							html = html +  '</div>';
								
							return html;
							
						}
						
						function create_student_result_entry (item) {
												
							var html =     '<div class="div-table-row">';
							html = html +  '  <div class="div-table-col">' + item.name + '</div>';
							html = html +  '  <div class="div-table-col">' + item.score + '</div>';
							html = html +  '  <div class="div-table-col">' + item.grade + '</div>';
							html = html +  '</div>';
								
							return html;
							
						}
						
						//DIFFERENT FUNCS
						//fetch results
						function fetchResults(){
							
							reg_no = $("#reg_no").val();
							sch_id = $("#sch_id").val();
							term = $("#term").val();	
							year = $("#year").val();
						
							var loader_div = "#results-list";
							var url = "<?=SITEPATH?>admin/api/v1/fetchStudentResults";
							var params = { "sch_id": sch_id, "reg_no": reg_no, "year": year, "term": term };
							
							//show result summary data
							showSummaryResults(sch_id, reg_no, year, term);
						
							load_results_list(loader_div, params, url);
							
						}
						//END DIFFERENT FUNCS
							
				<?php } ?>
				
				<?php if ($regular_show_results_list || $show_results_list) { ?>
				
					//SHARED FUNCS
					
					//get items to show
					function load_results_list(loader_div, params, url)
					{
	
						if(fetching==false) //we are not loading
						{
							
							$(loader_div).html(loader_object);
							//load_overlay(loader_div);
					
							fetching = true;
							
							$.ajax({
								url: url,
								type: 'POST',
								data: params,
								success : function(data) {
									
									$(loader_div).html("");
									
									header_html = create_student_result_header ();
									
									$(loader_div).html(header_html);
									
									//print out the data
									if (data.rows.length == 0) {
										//no results
										$(loader_div).html("<div class='text-center text-danger no-records'><h3 class='text-danger'>No Results Found</h3></div>");
										//hide create pdf button
										$("#show_pdf").addClass("hidden");
									} else {
										$.each(data.rows, function(index, item){
											var row = create_student_result_entry(item);
											$(loader_div).append(row);
										});
										//show create pdf button
										$("#show_pdf").removeClass("hidden");
									}
																		
									fetching = false;
							
								},
								error : function(xhr, statusText, error) { 
									console.log("Error! Could not retrieve the data.");
									fetching = false;
								}
							});
								
						}
							
					}
					
					function showSummaryResults(sch_id, reg_no, year, term) {
						$.ajax({
							type: "POST",
							url: "<?=SITEPATH?>admin/api/v1/fetchStudentResults",
							data: { "reg_no": reg_no, "year": year, "term": term, "sch_id": sch_id },							
							success: function(data) {
	
								//prepend only if its not existing
								if (!data.error) {
									//set static values
									$("#mean_score").html(data.mean_score);
									$("#total_score").html(data.total_score);
									$("#mean_grade").html(data.mean_grade);
									$("#mean_points").html(data.mean_points);
									//console.log(" data.mean_score - " + data.mean_score);
								} 
	
							}
						});
					}
						
					//onchange of year and term 
					$(document).on('change','#year',function(){
						
						//refresh results
						fetchResults();					
						
				    });
					
					$(document).on('change','#term',function(){
						
						//refresh results
						fetchResults();		
						
				    });
					//end onchange of year and term 
					
					//load student results
					fetchResults();
					
					//END SHARED FUNCS
				
				<?php } ?>
				
				<?php if ($show_results_list) { ?>
					
					//FORM ADD SUBJECT
					$(".form-new-result").submit(function(e){
						e.preventDefault();
						var dataString = new FormData($('.form-new-result')[0]);
						$.ajax({
							type: "POST",
							url: "<?=SITEPATH?>admin/api/v1/createStudentResult",
							data: dataString,
							dataType: "json",
							async: false,
							cache: false,
							contentType: false,
							processData: false,
							success: function(data) {
								
								var result_div = $(this).closest('form').find(".resultdiv");
								
								//prepend only if its not existing
								if (data.error) {
	
									message_type = "error";
									message = data.message;
									error_dialog_timeout = 5000;
									error_autoclose = "yes";
																	
									//show error message
									//(title, message, close_dialog_text, close_dialog_css, timeout, type, autoclose)
									showMessage("Error", message, "Close", "btn-danger", error_dialog_timeout, message_type, error_autoclose);
									
								} else {
									//show success message
									result_div.html("<div class='alert alert-success login wow fadeIn animated text-center padding-20'>" + data.message + "</div>")
												.hide().prependTo(".resultdiv").slideDown("slow");
									slide_up(".resultdiv", 1000, 4000, "fadeOut"); //hide after 4 seconds
									
									//reload results
									fetchResults();
									
								}
	
							}
						});
		
					});
					
					//NEW STUDENT RESULT FORM
					
					//FORM EDIT RESULT
					$(".form-edit-result").submit(function(e){
						e.preventDefault();
						
						//show overlay
						loader_div = "#edit_record";
						load_overlay(loader_div);
						
						var dataString = new FormData($('.form-edit-result')[0]);
						$.ajax({
							type: "POST",
							url: "<?=SITEPATH?>admin/api/v1/editSingleResult",
							data: dataString,
							dataType: "json",
							//async: false,
							cache: false,
							contentType: false,
							processData: false,
							success: function(data) {
																
								hide_overlay(loader_div);
								
								if (data.error) {
									
									error_dialog_timeout = <?=MESSAGE_DIALOG_TIMEOUT?>;

									$('.resultdiv').html("<div class='alert alert-danger login wow fadeIn animated text-center padding-20'>" + data.message + "</div>")
										.hide()
										.fadeIn(500, function() { $('.resultdiv').append(""); });
									$(this).colorbox.resize();

									slide_up('.resultdiv', 1000, error_dialog_timeout, "fadeOut");
									
								} else {
									//close colorbox here									
									$(this).colorbox.close();
									//reload results
									fetchResults();
									
								}
	
							}
						});
		
					});
					
					//EN EDIT RESULT
					
					//load data
					function create_student_result_header () {
											
						var html =     '<div class="div-table-row table-head">';
						html = html +  '  <div class="div-table-col">Subject</div>';
						html = html +  '  <div class="div-table-col">Score</div>';
						html = html +  '  <div class="div-table-col">Grade</div>';
						html = html +  '  <div class="div-table-col">Edit</div>';
						html = html +  '  <div class="div-table-col">Delete</div>';
						html = html +  '</div>';
							
						return html;
						
					}
					
					function create_student_result_entry (item) {
											
						var html =     '<div class="div-table-row">';
						html = html +  '  <div class="div-table-col">' + item.name + '</div>';
						html = html +  '  <div class="div-table-col">' + item.score + '</div>';
						html = html +  '  <div class="div-table-col">' + item.grade + '</div>';
						html = html +  '  <div class="div-table-col"><button type="button" class="btn btn-xs btn-default command-delete edit-data-row" data-pk-val="' + item.id + '"  id="edit-' + item.id + '" data-row-name="' + item.name + '" data-pk="id"><span class="fa fa-pencil"></span></button></div>';
						html = html +  '  <div class="div-table-col"><button type="button" class="btn btn-xs btn-default command-delete remove-data-row" data-pk-val="' + item.id + '" data-row-name="' + item.name + '" data-pk="id"><span class="fa fa-trash-o"></span></button></div>';
						html = html +  '</div>';
							
						return html;
						
					}
					
					//fetch results
					function fetchResults(){
						
						term = $("#term").val();	
						year = $("#year").val();
					
						var loader_div = "#results-list";
						var url = "<?=SITEPATH?>admin/api/v1/fetchStudentResults";
						var params = { "sch_id": "<?=$sch_id?>", "reg_no": "<?=$reg_no?>", "year": year, "term": term };
						
						//show result summary data
						showSummaryResults(<?=$sch_id?>, <?=$reg_no?>, year, term);
					
						load_results_list(loader_div, params, url);
						
					}
					
					//delete record
					function deleteResultRecord(result_id, full_title)
					{
						swal({   
							title: "Are you sure you want to delete \n\""+full_title+"\"?",   
							text: "All data for this item will be deleted!",   
							type: "warning",   
							showCancelButton: true,   
							confirmButtonColor: "#DD6B55",   
							confirmButtonText: "Yes",
							showLoaderOnConfirm: true,   
							closeOnConfirm: false 
						}, function(){   
							
							//DELETE record
							$.post("<?=SITEPATH?>admin/api/v1/deleteResultRecord", {'id' : result_id, 'sch_id' : <?=$sch_id?>}, 
								 function(data){
									var success = data.success;
									
									if (success){
										//show success msg
										swal({   
											title: "Deleted!",   
											text: "\"" + full_title + "\" has been deleted.",   
											timer: 1000,
											type: "success"
										}, function(){
												
												//refresh results
												fetchResults();
												
											});
						
									} else {
										//show error msg
										swal({   
											title: "Error!",   
											text: "\""+full_title+"\" could not be deleted. Try again",   
											type: "danger"
										});	
									}
									
							}, 'json');
											
						});	
					}					
					
					//delete record
					$(document).on('click','.remove-data-row',function(){

						var field_value = $(this).data("pk-val");
						var full_title = $(this).data("row-name");
						//delete record
						deleteResultRecord(field_value, full_title);
						
				    });
					
					//delete record
					$(document).on('click','.edit-data-row',function(){

						var field_value = $(this).data("pk-val");
						var full_title = $(this).data("row-name");
						//$(this).colorbox({scrolling: false, width:'95%',});
						//$(this).colorbox();
						editRecord(field_value, full_title);
						
				    });
					
					function editRecord(field_value, field_title){
						
						url = "<?=GET_SINGLE_RESULT_URL?>";
						loader_div = "#results-list";						
						params = {"id" : field_value};
						
						setFormData(url, params, loader_div);
						
					}
					
					function setFormData(url, params, loader_div)
					{

						load_overlay(loader_div);
						
						$.ajax({
							url: url,
							type: 'POST',
							data: params,
							success : function(data) {
								
								hide_overlay(loader_div);
								//print out the data
								if (data.error == true) {
									//(title, message, close_dialog_text, close_dialog_css, timeout, type, autoclose)
									message = "No result found";
									error_dialog_timeout = <?=MESSAGE_DIALOG_TIMEOUT?>;
									error_autoclose = "yes";
									showMessage("Error", message, "Close", "btn-danger", error_dialog_timeout, "error", error_autoclose); 
								} else {
									
									$("#score").val(data.score);
									$("#subject_name").val(data.subject_name);
									$("#result_item_id").val(data.id);
									$("#sch_id").val(<?=$sch_id?>);
									
									//show colorbox here
									$.colorbox({
			
										inline:true, 
										href: "#edit_record",
										scrolling: false,
										width: '400px',
										height: '400px',
										onComplete: function() {
											$(this).colorbox.resize();
										}
									
									});
									
									$('#edit_record').bind('cbox_complete', function(){
										$('form input:first').focus();
									});
									
								}
																							
							},
							error : function(xhr, statusText, error) { 
								console.log("Error! Could not retrieve the data.");
								fetching = false;
							}
						});	
					}				
					
				<?php } ?>
				
				<?php if ($show_fees_list) { ?>
						//read student_id from db fetched data
						student_id = <?=$student_id?>;
						sch_id = <?=$sch_id?>;
				<?php } ?>
				
				<?php if ($regular_show_fees_list) { ?>
					
						//on change of student combo
						$(document).on('change','#student_id',function(){
							selected = $(this).val();
							//get and set school_id for selected student
							setSchoolId(selected);
						});
						
						//on click show pdf button
						$(document).on('click','#show_pdf',function(){
							
							sch_id = $("#sch_id").val();
							reg_no = $("#reg_no").val();
							year = $("#fee_year").val();
							term = $("#term").val();
							student_id = $("#student_id").val();
							//console.log("sch_id " + sch_id + " == student_id " + student_id + " == year " + year + " == term " + term);
							//load pdf creation console
							var pdfCreationURL = "<?=SITEPATH?>admin/savepdf.php?student_id=" + student_id + "&year=" + year + "&term=" + term + "&item_type=fees";
							window.open(pdfCreationURL);
							
						});
												
						function setSchoolId(selected){
							var url = "<?=SITEPATH?>admin/api/v1/fetchSchoolIdFromStudentId";
							$.ajax({
								url: url,
								type: 'POST',
								data: {student_id: selected},
								success : function(data) {
									if (!data.error) {
										//update the sch_id textfield value
										$("#sch_id").val(data.sch_id);
										fetchFees();
									} 
								},
								error : function(xhr, statusText, error) { 
									console.log("Error! Could not retrieve the data.");
									fetchingFees = false;
								}
							});
						}
						
						//load data
						function create_student_fee_entry (item) {
												
							var html =     ' <tr>';
							html = html +  '  <td>' + item.payment_amount + '</td>';
							html = html +  '  <td>' + item.payment_mode + '</td>';
							html = html +  '  <td>' + item.payment_paid_at + '</td>';
							html = html +  '  <td>' + item.payment_paid_by + '</td>';
							html = html +  ' </tr>';
								
							return html;
							
						}
						
						//DIFFERENT FUNCS
						//fetch fees
						function fetchFees(){
							
							//fetch student_id from combo
							student_id = $("#student_id").val();
							sch_id = $("#sch_id").val();
							
							year = $("#fee_year").val();
						
							var loader_div = "#fees-data";
							var url = "<?=SITEPATH?>admin/api/v1/fetchStudentFeePayments";
							var params = { "school_id": sch_id, "student_id": student_id, "year": year };
							
							//show result summary data
							showSummaryFees(sch_id, student_id, year);
						
							load_fees_list(loader_div, params, url);
							
						}
						//END DIFFERENT FUNCS
							
				<?php } ?>
				
				<?php if ($regular_show_fees_list || $show_fees_list) { ?>
				
					//SHARED FUNCS
					fetchingFees=false;
					//get items to show
					function load_fees_list(loader_div, params, url)
					{
	
						if(fetchingFees==false) //we are not loading
						{
							
							$(loader_div).html(loader_object);
							//load_overlay(loader_div);
					
							fetchingFees = true;
							
							$.ajax({
								url: url,
								type: 'POST',
								data: params,
								success : function(data) {
									
									$(loader_div).html("");
																																				
									//print out the data
									if (data.fee_payment.length == 0) {
										$(loader_div).html("<div class='text-center text-danger no-records'><h3 class='text-danger'>No Fee Payments Found</h3></div>");
										//hide create pdf button
										$("#show_pdf").addClass("hidden");
									} else {
										
										$.each(data.fee_payment, function(index, item){
											var row = create_student_fee_entry(item);
											$(loader_div).append(row);
										});
										//show create pdf button
										$("#show_pdf").removeClass("hidden");
										
									}
																											
									fetchingFees = false;
							
								},
								error : function(xhr, statusText, error) { 
									console.log("Error! Could not retrieve the data.");
									fetchingFees = false;
								}
							});
								
						}
							
					}
					
					function showSummaryFees(sch_id, student_id, year) {
						$.ajax({
							type: "POST",
							url: "<?=SITEPATH?>admin/api/v1/fetchStudentFees",
							data: { "student_id": student_id, "year": year, "school_id": sch_id },							
							success: function(data) {
	
								//prepend only if its not existing
								if (!data.fees_summary.error) {
									//set static values
									$("#fees_total").html(data.fees_summary.total_fees);
									$("#fees_paid").html(data.fees_summary.fees_paid);
									$("#fees_balance").html(data.fees_summary.fees_bal);
									//console.log(" data.mean_score - " + data.mean_score);
								} 
	
							}
						});
					}
						
					//on change of fees year
					$(document).on('change','#fee_year',function(){

						fetchFees();
						
				    });
					
					//load student fees
					fetchFees();
					
					//END SHARED FUNCS
				
				<?php } ?>
				
				<?php if ($show_fees_list) { ?>
					
					//DIFFERENT FUNCS
					//load data
					function create_student_fee_entry (item) {
											
						var html =     ' <tr>';
						html = html +  '  <td>' + item.payment_amount + '</td>';
						html = html +  '  <td>' + item.payment_mode + '</td>';
						html = html +  '  <td>' + item.payment_paid_at + '</td>';
						html = html +  '  <td>' + item.payment_paid_by + '</td>';
						html = html +  '  <td><button type="button" class="btn btn-xs btn-default command-delete edit-fee-data-row" data-pk-val="' + item.payment_id + '"  id="edit-' + item.payment_id + '" data-row-name="Amount: ' + item.payment_amount + ", Paid via: " + item.payment_mode + '" data-pk="id"><span class="fa fa-pencil"></span></button></td>';
						html = html +  '  <td><button type="button" class="btn btn-xs btn-default command-delete remove-fee-data-row" data-pk-val="' + item.payment_id + '" data-row-name="' + item.payment_amount + '", Paid via: "' + item.payment_mode + '" data-pk="id"><span class="fa fa-trash-o"></span></button></td>';
						html = html +  ' </tr>';
							
						return html;
						
					}
					//fetch fees
					function fetchFees(){
						
						year = $("#fee_year").val();
					
						var loader_div = "#fees-data";
						var url = "<?=SITEPATH?>admin/api/v1/fetchStudentFeePayments";
						var params = { "school_id": <?=$sch_id?>, "student_id": <?=$student_id?>, "year": year };
						
						//show result summary data
						showSummaryFees(sch_id, student_id, year);
					
						load_fees_list(loader_div, params, url);
						
					}
					
					//END DIFFERENT FUNCS
					
					//FORM ADD FEES
					$(".form-new-fee").submit(function(e){
						e.preventDefault();
						var dataString = new FormData($('.form-new-fee')[0]);
						
						//show loader
						loader_div = ".form-new-fee";
						load_overlay(loader_div);
						
						$.ajax({
							type: "POST",
							url: "<?=SITEPATH?>admin/api/v1/createStudentFee",
							data: dataString,
							dataType: "json",
							async: false,
							cache: false,
							contentType: false,
							processData: false,
							success: function(data) {
								
								var result_div = $(this).closest('form').find(".resultdiv");
								
								hide_overlay(loader_div);
								
								//prepend only if its not existing
								if (data.error) {
	
									message_type = "error";
									message = data.message;
									error_dialog_timeout = 5000;
									error_autoclose = "yes";
																	
									//show error message
									//(title, message, close_dialog_text, close_dialog_css, timeout, type, autoclose)
									showMessage("Error", message, "Close", "btn-danger", error_dialog_timeout, message_type, error_autoclose);
									
								} else {
									//show success message
									result_div.html("<div class='alert alert-success login wow fadeIn animated text-center padding-20'>" + data.message + "</div>")
												.hide().prependTo(".resultdiv").slideDown("slow");
									slide_up(".resultdiv", 1000, 4000, "fadeOut"); //hide after 4 seconds
									
									//reload results
									fetchFees();
									
								}
	
							}
						});
		
					});
					
					//NEW STUDENT FEES FORM
					
					//FORM EDIT FEES
					$(".form-edit-fee").submit(function(e){
						e.preventDefault();
						
						//show overlay
						loader_div = "#edit_fee_record";
						load_overlay(loader_div);
						
						var dataString = new FormData($('.form-edit-fee')[0]);
						$.ajax({
							type: "POST",
							url: "<?=SITEPATH?>admin/api/v1/editSingleFee",
							data: dataString,
							dataType: "json",
							//async: false,
							cache: false,
							contentType: false,
							processData: false,
							success: function(data) {
																
								hide_overlay(loader_div);
								
								if (data.error) {
									
									error_dialog_timeout = <?=MESSAGE_DIALOG_TIMEOUT?>;

									$('.resultdiv').html("<div class='alert alert-danger login wow fadeIn animated text-center padding-20'>" + data.message + "</div>")
										.hide()
										.fadeIn(500, function() { $('.resultdiv').append(""); });
									$(this).colorbox.resize();

									slide_up('.resultdiv', 1000, error_dialog_timeout, "fadeOut");
									
								} else {
									//close colorbox here									
									$(this).colorbox.close();
									//reload results
									fetchFees();
									
								}
	
							}
						});
		
					});
					
					//END EDIT RESULT
					
					
					
					//delete record
					function deleteFeeRecord(id, full_title)
					{
						swal({   
							title: "Are you sure you want to delete \n\""+full_title+"\"?",   
							text: "All data for this item will be deleted!",   
							type: "warning",   
							showCancelButton: true,   
							confirmButtonColor: "#DD6B55",   
							confirmButtonText: "Yes",
							showLoaderOnConfirm: true,   
							closeOnConfirm: false 
						}, function(){   
							
							//DELETE record
							$.post("<?=SITEPATH?>admin/api/v1/deleteFeeRecord", {'id' : id, 'sch_id' : <?=$sch_id?>}, 
								 function(data){
									var success = data.success;
									
									if (success){
										//show success msg
										swal({   
											title: "Deleted!",   
											text: "\"" + full_title + "\" has been deleted.",   
											timer: 1000,
											type: "success"
										}, function(){
												
												//refresh results
												fetchFees();
												
											});
						
									} else {
										//show error msg
										swal({   
											title: "Error!",   
											text: "\""+full_title+"\" could not be deleted. Try again",   
											type: "danger"
										});	
									}
									
							}, 'json');
											
						});	
					}					
					
					//onchange of payment mode
					/*
					$(document).on('change','#payment_mode',function(){
						//if value is cheque, show cheque no row, else hide the row
						var selected = $(this).val();
						if (selected=='cheque')
						{
							$("#cheque-row").fadeIn("fast");
						} else {
							$("#cheque-row").fadeOut("fast");
						}
						
				    });
					
					$(document).on('change','#fee_payment_mode',function(){
						//if value is cheque, show cheque no row, else hide the row
						var selected = $(this).val();
						if (selected=='cheque')
						{
							$("#fee-cheque-row").fadeIn("fast");
						} else {
							$("#fee-cheque-row").fadeOut("fast");
						}
						
				    });
					*/
					//end onchange of payment mode 
					
					//delete record
					$(document).on('click','.remove-fee-data-row',function(){

						var field_value = $(this).data("pk-val");
						var full_title = $(this).data("row-name");
						//delete record
						deleteFeeRecord(field_value, full_title);
						
				    });
					
					//delete record
					$(document).on('click','.edit-fee-data-row',function(){

						var field_value = $(this).data("pk-val");
						var full_title = $(this).data("row-name");
						editFeeRecord(field_value, full_title);
						
				    });
					
					function editFeeRecord(field_value, field_title){
						
						url = "<?=GET_SINGLE_FEE_URL?>";
						loader_div = "#fees-data";						
						params = {"id" : field_value};
						
						setFeesFormData(url, params, loader_div);
						
					}
					
					function setFeesFormData(url, params, loader_div)
					{

						load_overlay(loader_div);
						
						$.ajax({
							url: url,
							type: 'POST',
							data: params,
							success : function(data) {
								
								hide_overlay(loader_div);
								//print out the data
								if (data.error == true) {
									//(title, message, close_dialog_text, close_dialog_css, timeout, type, autoclose)
									message = "No result found";
									error_dialog_timeout = <?=MESSAGE_DIALOG_TIMEOUT?>;
									error_autoclose = "yes";
									showMessage("Error", message, "Close", "btn-danger", error_dialog_timeout, "error", error_autoclose); 
								} else {
									
									$("#fee_payment_id").val(data.id);
									$("#fee_amount").val(data.amount);
									$("#fee_payment_mode").val(data.payment_mode);
									$("#fee_cheque_no").val(data.cheque_no);
									$("#fee_paid_by").val(data.paid_by);
									$("#fee_paid_at").val(data.paid_at);
									$("#fee_year").val(data.year);
									
									//show colorbox here
									$.colorbox({
			
										inline:true, 
										href: "#edit_fee_record",
										scrolling: false,
										width: '600px',
										height: '400px',
										onComplete: function() {
											if (data.cheque_no != null) {
												//show cheque field if field is not blank
												//$("#fee_cheque_no").fadeIn("fast");
											}
											$(this).colorbox.resize();
										}
									
									});
									
									
									
									
									$('#edit_fee_record').bind('cbox_complete', function(){
										$('form input:first').focus();
									});
									
								}
																							
							},
							error : function(xhr, statusText, error) { 
								console.log("Error! Could not retrieve the data.");
							}
						});	
					}
										
				<?php } ?>
				
				function saveDataLocalStorage(name, value){
					
					if (localStorage) {
						  // Save the data in localStorage.
						  localStorage.setItem(name, value);
					}
						
				}
				
				function getDataLocalStorage(name){
					
					if (localStorage) {
						  return localStorage.getItem(name);
					}
						
				}
								
				<?php if ($show_schools_list) { ?>
				
					//load schools grid
					listUrl = "<?=SITEPATH?>admin/api/v1/fetchSchoolGridListing";
					editUrl = "<?=SITEPATH?>admin/view-schools";
					param_name = "user_id";
					param_value = "<?=USER_ID?>";
					param_name2 = "";
					param_value2 = "";
					loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, "", "", "", "");
				
				<?php } ?>
				
				
				
				<?php if ($show_students_list) { ?>
				
					//load schools grid
					top_school_id = $("#top_school_id").data("sch-id");
					listUrl = "<?=SITEPATH?>admin/api/v1/fetchStudentGridListing";
					editUrl = "<?=SITEPATH?>admin/view-students";
					param_name = "sch_id";
					param_value = top_school_id;
					param_name2 = "";
					param_value2 = "";
					<?php if ($show_contacts_list) { ?>
						loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, "", "", "", "", true, "#users_selected", "#selected");
						var contactsHeight = $(window).height() - ($('div.footer').height() + $('div#main-nav').height() + $('div.page-section').height() + $('div#select-school').height());
						$("#contactsHeight").css("height", contactsHeight);
						
						//check selection of messagetype and show fields as appropriate
						var msgTypeRadios = document.querySelectorAll('input[type=radio][name="messageType"]');

						function changeHandler(event) {
							
						   if ( this.value === 'memo' ) {
							   
							 //show memo fields, hide others
							 $("#memo-fields").removeClass("hidden");
							 $("#results-fields").addClass("hidden");
							 $("#fees-fields").addClass("hidden");
							 
						   } else if ( this.value === 'results' ) {
							   
							  $("#memo-fields").addClass("hidden");
							  $("#results-fields").removeClass("hidden");
							  $("#fees-fields").addClass("hidden");
							  
						   } else if ( this.value === 'fees' ) {
							  
							  $("#memo-fields").addClass("hidden");
							  $("#results-fields").addClass("hidden");
							  $("#fees-fields").removeClass("hidden");

						   }  
						   
						}
						
						Array.prototype.forEach.call(msgTypeRadios, function(radio) {
						   radio.addEventListener('change', changeHandler);
						});



						/*$(document).on("change", '#messageType', function(e) { 
							e.preventDefault();
							selected = $(this).val();
							//console.log("selected - " + selected);
			
							if (selected=="recurring-weekly") {
								$("#offer-day-div").removeClass("hidden");
								$("#offer-period-div").addClass("hidden");
								//set date fields to blank
								$("#start_date").val("");
								$("#end_date").val("");
							} else {
								$("#offer-day-div").addClass("hidden");
								$("#offer-period-div").removeClass("hidden");
								//set day field to blank
								$("#day-select").val("");	
							}*/
						
					<?php } else { ?>
						loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, "", "", "", "");
					<?php } ?>				
					
				    $(document).on('change','#school-select',function(){
						//submit the form
						$(this).closest('form').trigger('submit');
						
				    });
				
				<?php } ?>
				
				<?php if ($show_contacts_list) { ?>
				
					//load subjects grid
					top_sch_id = $("#top_school_id").data("sch-id");
					listUrl = "<?=SITEPATH?>admin/api/v1/fetchContactGridListing";
					editUrl = "";
					param_name = "sch_id";
					param_value = top_sch_id;
					param_name2 = "";
					param_value2 = "";
					loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, "", "", "", "");
					
				    $(document).on('change','#subject-select',function(){
						//submit the form
						$(this).closest('form').trigger('submit');
						
				    });
					
					//delete record
					$(document).on('click','.remove-row',function(e){

						e.preventDefault();
						var field_value = $(this).data("pk-val");
						var full_title = $(this).data("row-name");
						//delete record
						deleteResultRecord(field_value, full_title);
						
				    });
					
					//delete record
					$(document).on('click','.edit-row',function(e){

						e.preventDefault();
						var field_value = $(this).data("pk-val");
						var full_title = $(this).data("row-name");
						//$(this).colorbox({scrolling: false, width:'95%',});
						//$(this).colorbox();
						editRecord(field_value, full_title);
						
				    });
					
					function editRecord(field_value, field_title){
						
						url = "<?=GET_SINGLE_SUBJECT_URL?>";
						loader_div = "#subjects-list";						
						params = {"id" : field_value};
						
						setFormData(url, params, loader_div);
						
					}
					
					function setFormData(url, params, loader_div)
					{

						load_overlay(loader_div);
						
						$.ajax({
							url: url,
							type: 'POST',
							data: params,
							success : function(data) {
								
								hide_overlay(loader_div);
								//print out the data
								if (data.error == true) {
									//(title, message, close_dialog_text, close_dialog_css, timeout, type, autoclose)
									message = "No result found";
									error_dialog_timeout = <?=MESSAGE_DIALOG_TIMEOUT?>;
									error_autoclose = "yes";
									showMessage("Error", message, "Close", "btn-danger", error_dialog_timeout, "error", error_autoclose); 
								} else {
									
									$("#score").val(data.score);
									$("#subject_name").val(data.subject_name);
									$("#result_item_id").val(data.id);
									$("#sch_id").val(<?=$sch_id?>);
									
									//show colorbox here
									$.colorbox({
			
										inline:true, 
										href: "#edit_record",
										scrolling: false,
										width: '400px',
										height: '400px',
										onComplete: function() {
											$(this).colorbox.resize();
										}
									
									});
									
									$('#edit_record').bind('cbox_complete', function(){
										$('form input:first').focus();
									});
									
								}
																							
							},
							error : function(xhr, statusText, error) { 
								console.log("Error! Could not retrieve the data.");
								fetching = false;
							}
						});	
					}
				
				<?php } ?>
				
				<?php if ($show_subjects_list) { ?>
				
					//load subjects grid
					top_level_id = $("#top_level_id").data("level-id");
					listUrl = "<?=SITEPATH?>admin/api/v1/fetchSubjectGridListing";
					editUrl = "";
					param_name = "level_id";
					param_value = top_level_id;
					param_name2 = "";
					param_value2 = "";
					loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, "", "", "", "");
					
				    $(document).on('change','#subject-select',function(){
						//submit the form
						$(this).closest('form').trigger('submit');
						
				    });
					
					//delete record
					$(document).on('click','.remove-row',function(e){

						e.preventDefault();
						var field_value = $(this).data("pk-val");
						var full_title = $(this).data("row-name");
						//delete record
						deleteResultRecord(field_value, full_title);
						
				    });
					
					//delete record
					$(document).on('click','.edit-row',function(e){

						e.preventDefault();
						var field_value = $(this).data("pk-val");
						var full_title = $(this).data("row-name");
						//$(this).colorbox({scrolling: false, width:'95%',});
						//$(this).colorbox();
						editRecord(field_value, full_title);
						
				    });
					
					function editRecord(field_value, field_title){
						
						url = "<?=GET_SINGLE_SUBJECT_URL?>";
						loader_div = "#subjects-list";						
						params = {"id" : field_value};
						
						setFormData(url, params, loader_div);
						
					}
					
					function setFormData(url, params, loader_div)
					{

						load_overlay(loader_div);
						
						$.ajax({
							url: url,
							type: 'POST',
							data: params,
							success : function(data) {
								
								hide_overlay(loader_div);
								//print out the data
								if (data.error == true) {
									//(title, message, close_dialog_text, close_dialog_css, timeout, type, autoclose)
									message = "No result found";
									error_dialog_timeout = <?=MESSAGE_DIALOG_TIMEOUT?>;
									error_autoclose = "yes";
									showMessage("Error", message, "Close", "btn-danger", error_dialog_timeout, "error", error_autoclose); 
								} else {
									
									$("#score").val(data.score);
									$("#subject_name").val(data.subject_name);
									$("#result_item_id").val(data.id);
									$("#sch_id").val(<?=$sch_id?>);
									
									//show colorbox here
									$.colorbox({
			
										inline:true, 
										href: "#edit_record",
										scrolling: false,
										width: '400px',
										height: '400px',
										onComplete: function() {
											$(this).colorbox.resize();
										}
									
									});
									
									$('#edit_record').bind('cbox_complete', function(){
										$('form input:first').focus();
									});
									
								}
																							
							},
							error : function(xhr, statusText, error) { 
								console.log("Error! Could not retrieve the data.");
								fetching = false;
							}
						});	
					}
				
				<?php } ?>
				
				<?php if ($show_users_list) { ?>
				
					//load users grid
					listUrl = "<?=SITEPATH?>admin/api/v1/fetchUserGridListing";
					editUrl = "<?=SITEPATH?>admin/view-users";
					param_name = "";
					param_value = "";
					param_name2 = "";
					param_value2 = "";
					loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, "", "", "", "");
					
					$(document).on('change','#group-select',function(){
						//submit the form
						$(this).closest('form').trigger('submit');
						
				    });
					
					//UPDATE USER GROUP PERMISSIONS
					function updateUserPermissions(params)
					{
						loader_div = ".form-group-permissions";
						load_overlay(loader_div);
						
						$.ajax({
							url: "<?=SITEPATH?>admin/api/v1/updateGroupPermissions",
							type: "POST",
							data: params,
							dataType: "json",
							success: function(data) {
	
								hide_overlay(loader_div);
								
								//prepend only if its not existing
								if (data.error) {
									//show error message
									//showQuickMessage(title, message, timeout, type, autoclose)
									showQuickMessage("Error", data.message, 5000, "error", "yes"); 
								} 
	
							}
						});
		
					}
					//END UPDATE USER GROUP PERMISSIONS
					
					//delete record
					function deleteGroupRecord(field_name, field_value, table_name, full_title, reload_page)
					{
						swal({   
							title: "Are you sure you want to delete group \n\""+full_title+"\"?",   
							text: "All data for this group will be deleted!",   
							type: "warning",   
							showCancelButton: true,   
							confirmButtonColor: "#DD6B55",   
							confirmButtonText: "Yes",
							showLoaderOnConfirm: true,   
							closeOnConfirm: false 
						}, function(){   
							//DELETE record before showing message
							//send data to delete via json	
							$.post("<?=SITEPATH?>admin/api/v1/deleteGroup", {'field_name' : field_name, 'field_value' : field_value, 'table_name' : table_name}, 
								 function(data){
									var success = data.success;
									
									if (success){
										//show success msg
										swal({   
											title: "Deleted!",   
											text: "\"" + full_title + "\" has been deleted.",   
											timer: 1000,
											type: "success"
										}, function(){
												
												location.reload();
												
										});
						
									} else {
										//show error msg
										swal({   
											title: "Error!",   
											text: data.message,   
											type: "error"
										});	
									}
									
							}, 'json');
											
						});	
					}					
					//END SWEET ALERT FUNCTIONS
					
					//DETECT CHECKBOX CLICKS
					$(".permission-check").change(function() {
						check_val = $(this).is(':checked');
						check_name = this.name;
						user_id = $('#top_group_id').data('group-id');
						params = { "user_id": user_id, "check_name": check_name, "check_val": check_val, "sch_id": 1 };
						updateUserPermissions(params);
					});
					//END DETECT CHECKBOX CLICKS
					
					$(document).on("click", '.deleteGroup', function(e) { 
						e.preventDefault();
			
						var field_name = $(this).closest('.tbl-data').data("tbl-pk");
						var field_value = $(this).closest('.row-data').data("pk-val");
						var full_title = $(this).closest('.row-data').data("row-name");
						var table_name = $(this).closest('.tbl-data').data("tbl");
						var reload_page = $(this).closest('.tbl-data').data("reload-page");						
						//delete
						deleteGroupRecord(field_name, field_value, table_name, full_title, reload_page);
						
					});
					//END ONCLICK OF DELETE BUTTON			
									
				<?php } ?>
				
				/*var instance_boot_grid = $("#mybootgrid");

				function init_boot_grid()
				{
					instance_boot_grid.bootgrid({
						ajax: true,
						navigation: 0,
						url: function ()
						{
							var carteira = $("#txtIdCart").val();
							var data = $("#txtData").val();
							return "url" + data;
						}
					});
				}
		
				function reload()
				{
					instance_boot_grid.bootgrid("reload");
				}
		
				init_boot_grid();
				$("#reload-grid-basic").on("click", reload);*/
			
				function loadGrid(listUrl, editUrl, param_name, param_value, param_name2, param_value2, param_name3, param_value3, param_name4, param_value4, limited_count, count_div, selected_div)
				{
					
					var selectedRows;
					var the_row_count;
					if (!limited_count) {
						<?php $the_row_count = "50, 25, 10, 100, -1"; ?>
					} else {
						<?php $the_row_count = "50, 10, 25, 100, -1"; ?>	
					}
						
					$("#mybootgrid").on("initialize.rs.jquery.bootgrid", function (e) {
						if ($("#gridStateCurrent").val() !== "") {
							$("#grid-data").bootgrid("setCurrentPage", $("#gridStateCurrent").val());
						}
						if ($("#gridStateRowCount").val() !== "") {
							$("#grid-data").bootgrid("setRowCount", $("#gridStateRowCount").val());
						}
						if ($("#gridStateSearchPhrase").val() !== "") {
							$("#grid-data").bootgrid("setSearchPhrase", $("#gridStateSearchPhrase").val());
						}
						if ($("#gridStateSortDirection").val() !== "" && $("#gridStateSortField").val() !== "") {
							$("#grid-data").bootgrid("setSortDictionary", $("#gridStateSortField").val(), $("#gridStateSortDirection").val());
						}
						else {
							$("#grid-data").bootgrid("setSortDictionary", "SortOrder", "Ascending");
						}
					}).on("initialized.rs.jquery.bootgrid", function (e) {
						if ($("#gridStateSearchPhrase").val() !== "") {
							$("#grid-data").bootgrid("setSearchBoxText", $("#gridStateSearchPhrase").val());
						}
					});
			
					var grid = $("#mybootgrid").bootgrid({
						ajax: true,
						ajax: true,
						selection:true,
						multiSelect: false,
						rowSelect: true,
						rowCount: [<?=$the_row_count?>],
						searchSettings: {
							delay: 300
							//characters: 3
						},
						css: {
							actions: 'actions btn-group',
							dropDownMenu: 'dropdown btn-group'
						},
						labels: {
							noResults: "No Results Found"
						},
						url: listUrl,
						requestHandler: function (request) {
							if ((param_name != "") && (param_value != "")) {
								request[param_name] = param_value;
							}
							if ((param_name2 != "") && (param_value2 != "")) {
								request[param_name2] = param_value2;
							}
							if ((param_name3 != "") && (param_value3 != "")) {
								request[param_name3] = param_value3;
							}
							if ((param_name4 != "") && (param_value4 != "")) {
								request[param_name4] = param_value4;
							}
							return request;
						},
						formatters: {
							"commands": function(column, row)
							{
								return "<button type=\"button\" class=\"btn btn-xs btn-default command-delete remove-row\" data-pk-val=\"" + row.id + "\" data-row-name=\"" + row.name + "\" data-pk=\"id\"><span class=\"fa fa-trash-o\"></span></button>";
							},
							"links": function(column, row)
							{
								return "<a href=\"" + editUrl + "/" + row.id + "\" class=\"btn btn-xs btn-default edit-row noclick\" data-type=\"iframe\"  data-row-name=\"" + row.name + "\" data-pk-val=\"" + row.id + "\"><span class=\"fa fa-pencil\"></span></a>";
							},
							"status-links": function(column, row)
							{
								
								if (row.status == <?=ACTIVE_STATUS?>) {
										return "<span class='label label-success' title='<?=ACTIVE_TEXT?>'><?=ACTIVE_TEXT?></span>";
									
								} else if (row.status == <?=INACTIVE_STATUS?>) {
										return "<span class='label label-warning' title='<?=INACTIVE_TEXT?>'><?=INACTIVE_TEXT?></span>";
									
								} else if (row.status == <?=CONFIRMED_STATUS?>) {
										return "<span class='label label-success' title='<?=CONFIRMED_TEXT?>'><?=CONFIRMED_TEXT?></span>";
									
								} else if (row.status == <?=NOT_CONFIRMED_STATUS?>) {
										return "<span class='label label-danger' title='<?=NOT_CONFIRMED_TEXT?>'><?=NOT_CONFIRMED_TEXT?></span>";
									
								} else if (row.status == <?=SUSPENDED_STATUS?>) {
										return "<span class='label label-danger' title='<?=SUSPENDED_TEXT?>'><?=SUSPENDED_TEXT?></span>";
									
								} else if (row.status == <?=EXPIRED_STATUS?>) {
										return "<span class='label label-danger' title='<?=EXPIRED_TEXT?>'><?=EXPIRED_TEXT?></span>";
									
								} else if (row.status == <?=COMPLETED_STATUS?>) {
										return "<span class='label label-success' title='<?=COMPLETED_TEXT?>'><?=COMPLETED_TEXT?></span>";
										
								} else if (row.status == <?=PENDING_STATUS?>) {
										return "<span class='label label-warning' title='<?=PENDING_TEXT?>'><?=PENDING_TEXT?></span>";
										
								} else if (row.status == <?=AWAITING_DELIVERY_STATUS?>) {
										return "<span class='label label-info' title='<?=AWAITING_DELIVERY_TEXT?>'><?=AWAITING_DELIVERY_TEXT?></span>";
										
								} else if (row.status == <?=NOT_ACTIVATED_STATUS?>) {
										return "<span class='label label-danger' title='<?=SUSPENDED_TEXT?>'><?=NOT_ACTIVATED_TEXT?></span>";
									
								} else if (row.status == <?=PAID_STATUS?>) {
										return "<span class='label label-success' title='<?=PAID_STATUS_TEXT?>'><?=PAID_STATUS_TEXT?></span>";
										
								} else if (row.status == <?=NOT_PAID_STATUS?>) {
										return "<span class='label label-danger' title='<?=NOT_PAID_STATUS_TEXT?>'><?=NOT_PAID_STATUS_TEXT?></span>";
									
								} 								
								
							},
							"status-links-img": function(column, row)
							{
								
								if (row.status_img == <?=ACTIVE_STATUS?>) {
										return "<i class='fa fa-fw fa-2x fa-check text-success'></i>";
									
								} else if (row.status_img == <?=INACTIVE_STATUS?>) {
										return "<i class='fa fa-fw fa-2x fa-times text-danger'></i>";
									
								} else if (row.status_img == <?=CONFIRMED_STATUS?>) {
										return "<i class='fa fa-fw fa-2x fa-check text-success'></i>";
									
								} else if (row.status_img == <?=NOT_CONFIRMED_STATUS?>) {
										return "<i class='fa fa-fw fa-2x fa-times text-danger'></i>";
									
								} else if (row.status_img == <?=SUSPENDED_STATUS?>) {
										return "<span class='label label-danger' title='<?=SUSPENDED_TEXT?>'><?=SUSPENDED_TEXT?></span>";
									
								} else if (row.status_img == <?=EXPIRED_STATUS?>) {
										return "<span class='label label-danger' title='<?=EXPIRED_TEXT?>'><?=EXPIRED_TEXT?></span>";
									
								} else if (row.status_img == <?=COMPLETED_STATUS?>) {
										return "<span class='label label-success' title='<?=COMPLETED_TEXT?>'><?=COMPLETED_TEXT?></span>";
										
								} else if (row.status_img == <?=PENDING_STATUS?>) {
										return "<span class='label label-warning' title='<?=PENDING_TEXT?>'><?=PENDING_TEXT?></span>";
										
								} else if (row.status_img == <?=AWAITING_DELIVERY_STATUS?>) {
										return "<span class='label label-info' title='<?=AWAITING_DELIVERY_TEXT?>'><?=AWAITING_DELIVERY_TEXT?></span>";
										
								} else if (row.status_img == <?=NOT_ACTIVATED_STATUS?>) {
										return "<span class='label label-danger' title='<?=SUSPENDED_TEXT?>'><?=NOT_ACTIVATED_TEXT?></span>";
									
								} 								
								
							}
							//rowCount: [10, 25, 50, 75, 100]
			
						}
						/*,
						
						 converters: { 
								datetime: {
									from: function (value) { return moment(value); },
									to: function (value) { return moment(value); }
								}
						 }*/
						
					}).on("loaded.rs.jquery.bootgrid", function()
					{						
						grid.find(".command-edit").on("click", function(e)
						{
							var field_name = $(this).parent().closest('div').data("tbl-pk");
							var field_value = $(this).data("pk-val");
							var full_title = $(this).data("row-name");
							var table_name = $(this).parent().closest('div').data("tbl");
							//edit record
							editRecord(field_name, field_value, table_name, full_title);
						}).end().find(".command-delete").on("click", function(e)
						{						
							var field_name = $(this).parent().closest('div').data("tbl-pk");
							var cat_char = $(this).parent().closest('div').data("cat-char");
							var user_id = <?=USER_ID?>;
							var field_value = $(this).data("pk-val");
							var full_title = $(this).data("row-name");
							var table_name = $(this).parent().closest('div').data("tbl");
							//delete record
							deleteRecord(field_name, field_value, table_name, full_title, "");
							
						});
						
					}).on("selected.rs.jquery.bootgrid", function (e, rows) {
						
						var row_id;
						for (var i = 0; i < rows.length; i++) {
							row_id = rows[i].id;
						}
						//console.log("row id : " + row_id);
						loadFormData(row_id);
				
		  
					/*}).on("deselected.rs.jquery.bootgrid", function (e, rows) {
						
						var rows = grid.bootgrid('getCurrentRows');
						$.each(rows, function(index, row){
							if(row.checked){
								grid.bootgrid('select', [row.id]);
							}
							
						})
						
						var mySelectedRows = $('#mybootgrid').bootgrid('getSelectedRows');
						var numSelectedRows = mySelectedRows.length;
						
						$(count_div).html(numSelectedRows);
						$(selected_div).val(mySelectedRows);
						
						//console.log("mySelectedRows : " + mySelectedRows + " == numSelectedRows : " + numSelectedRows);
						//if a contact is selected, remove disabled class from display div
						if (numSelectedRows > 0) {
							$("#numContacts").removeClass("disabled_div");
							$("#enter_contacts").addClass("hidden");
							$("#enter_contacts_field").val("");	
						} else {
							$("#numContacts").addClass("disabled_div");
							$("#enter_contacts").removeClass("hidden");
						}
						*/
			
					});
					
				}
				
			//JQUERY GRID
			
			function loadFormData(row_id){
				loader_div = "#item-details";
				sch_id = $("#establishment-select").val();
				//console.log(est_id);
				//$(loader_div).html(loading_image);
				load_overlay(loader_div);
				
				<?php 
					
					if ($show_schools_list) { $url = SITEPATH . "admin/api/v1/fetchSchoolGridListing"; } 
					if ($show_offers_grid_list) { $url = SITEPATH . "admin/api/v1/getOffers"; } 
					if ($show_users_list) { $url = SITEPATH . "admin/api/v1/fetchUserGridListing"; } 
					if ($show_events_grid_list) { $url = SITEPATH . "admin/api/v1/getEvents"; } 
					if ($show_orders_grid_list) { $url = SITEPATH . "admin/api/v1/getOrders"; } 
					//if ($show_redeem_list) { $url = SITEPATH . "admin/api/v1/getEstPayments"; } 
					if ($show_tills_grid_list) { $url = SITEPATH . "admin/api/v1/getTills"; } 
					if ($show_home_slider_grid_list) { $url = SITEPATH . "admin/api/v1/getHomeSliders"; } 
					if ($show_product_categories_grid_list) { $url = SITEPATH . "admin/api/v1/getProductCategories"; } 
					if ($show_est_categories_grid_list) { $url = SITEPATH . "admin/api/v1/getEstCategories"; } 
					if ($show_products_grid_list) { $url = SITEPATH . "admin/api/v1/getProductsListing"; } 	
					if ($show_est_products_grid_list) { $url = SITEPATH . "admin/api/v1/getEstProductsListing"; } 	
					
				?>
				
				$.ajax({
					
					url: "<?=$url?>",
					type: "POST",
					data: {'id': row_id, 'sch_id': sch_id, 'user_id': <?=USER_ID?>},
					dataType: "json",
					success: function(data) {

						//$(loader_div).html("");
						hide_overlay(loader_div);
						
						//prepend only if its not existing
						if (data.error) {
							
							//show error message
							//showQuickMessage(title, message, timeout, type, autoclose)
							showQuickMessage("Error", data.message, 5000, "error", "yes"); 
							
						}  else {
							
							<?php if ($show_schools_list) { ?>load_school_details_form_data(data);<?php } ?>
							<?php if ($show_events_grid_list) { ?>load_event_details_form_data(data);<?php } ?>
							<?php if ($show_orders_grid_list) { ?>load_order_details_form_data(data);<?php } ?>
							<?php if ($show_tills_grid_list) { ?>load_till_details_form_data(data);<?php } ?>
							<?php if ($show_users_list) { ?>load_user_details_form_data(data);<?php } ?>
							<?php if ($show_establishments_grid_list) { ?>load_est_details_form_data(data);<?php } ?>
							<?php if ($show_home_slider_grid_list) { ?>load_slider_item_form_data(data);<?php } ?>
							<?php if ($show_product_categories_grid_list) { ?>load_product_categories_form_data(data);<?php } ?>
							<?php if ($show_est_categories_grid_list) { ?>load_est_categories_form_data(data);<?php } ?>
							<?php if ($show_products_grid_list) { ?>load_products_admin_form_data(data);<?php } ?>
							<?php if ($show_est_products_grid_list) { ?>load_est_products_admin_form_data(data);<?php } ?>
														
						}

					}
					
				});	
				
			}
			
			//load item images
			function loadItemImages(loader_div, item_cat, item_id){

				load_overlay(loader_div);
				$(loader_div).html("");

				$.ajax({
					
					url: "<?=SITEPATH?>admin/admin/api/v1/getItemImagesNew",
					type: "POST",
					data: {'item_cat': item_cat, 'item_id': item_id},
					dataType: "json",
					success: function(data) {

						//$(loader_div).html("");
						hide_overlay(loader_div);
						
						//prepend only if its not existing
						if (data.error) {
							
							//show error message
							//showQuickMessage(title, message, timeout, type, autoclose)
							showQuickMessage("Error", data.message, 5000, "error", "yes"); 
							
						}  else {
							
							//create images 
							$.each(data.rows, function(index, item){
								var row = create_image_entry(item);
								if (row!=null){ $(loader_div).append(row); }
							});		
							
						}

					}
					
				});	
				
			}
			
			<?php if ($show_schools_list) { ?>
			
				function load_school_details_form_data(data){
	
					var id = data.rows[0].id;
					var sch_name = data.rows[0].sch_name;
					var category = data.rows[0].category;
					var level = data.rows[0].level;
					var address = data.rows[0].address;
					var province = data.rows[0].province;
					var county = data.rows[0].county;
					var status = data.rows[0].status;
					var motto = data.rows[0].motto;
					var phone1 = data.rows[0].phone1;
					var phone2 = data.rows[0].phone2;
					var profile = data.rows[0].profile;
					var paybill_no = data.rows[0].paybill_no;
					var sms_welcome1 = data.rows[0].sms_welcome1;
					var sms_welcome2 = data.rows[0].sms_welcome2;
					
					$("#id_edit").val(id);
					$("#sch_name_edit").val(sch_name);
					$("#sch_category_edit").val(category);
					$("#sch_level_edit").val(level);
					$("#address_edit").val(address);
					$("#sch_county_edit").val(county);
					$("#status_edit").val(status);
					$("#province_edit").val(province);
					$("#motto_edit").val(motto);
					$("#phone1_edit").val(phone1);
					$("#phone2_edit").val(phone2);
					$("#sms_welcome1_edit").val(sms_welcome1);
					$("#sms_welcome2_edit").val(sms_welcome2);
					$("#sch_profile_edit").val(profile);
					$("#sch_paybill_no_edit").val(paybill_no);
					
					//hide no results
					$(".no-results").addClass("hidden");
					
					//unhide hidden data
					$(".item-details").removeClass("hidden");
					
					//load images data
					$("#item_title_logo").val(data.rows[0].name);
					$("#category_logo").val("<?=SCHOOL_PROFILE_PHOTO?>");
					$("#category_id_logo").val(data.rows[0].id);				
					
					//load user images
					loadItemImages("#item-images-logo", "<?=SCHOOL_PROFILE_PHOTO?>", id)
					
				}
			
			<?php } ?>
			
			
			<?php } ?>
	
	
		});
		
});
	
	

    </script>
