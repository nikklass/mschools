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
	
	head.js(
	
		"https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js",
		
		//"<?//=SITEPATH?>admin/js/vendor/core/jquery.js",
	
		"<?=SITEPATH?>admin/js/vendor/core/bootstrap.js",
		"<?=SITEPATH?>admin/js/vendor/core/bootstrap-datetimepicker.min.js",
		"<?=SITEPATH?>admin/js/vendor/core/breakpoints.js",
		"<?=SITEPATH?>admin/js/vendor/core/jquery.nicescroll.js",
		"<?=SITEPATH?>admin/js/vendor/core/isotope.pkgd.js",
		"<?=SITEPATH?>admin/js/vendor/core/packery-mode.pkgd.js",
		"<?=SITEPATH?>admin/js/vendor/noty/packaged/jquery.noty.packaged.js",
		"<?=SITEPATH?>admin/js/vendor/noty/layouts/center.js",
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
			
			//"<?//=SITEPATH?>admin/js/vendor/magnific-popup/jquery.magnific-popup.min.js",
		
		<?php } ?>
		
		
		<?php if ($show_scroll) { ?>
			
			"<?=SITEPATH?>admin/js/vendor/jquery.nicescroll.js",
			
		<?php } ?>
		
		<?php if ($show_form) { ?>
		
			"<?=SITEPATH?>admin/js/vendor/jquery.ba-throttle-debounce.min.js",
			"<?=SITEPATH?>admin/css/app/bootstrap-select.min.js",
			"<?=SITEPATH?>admin/js/vendor/forms/all.js",
			"<?=SITEPATH?>admin/js/bootstrap-multiselect.js",
			
			
		<?php } ?>
		
		<?php if ($form_validation) { ?>
			
			"<?=SITEPATH?>admin/js/vendor/forms/validation/jquery.parsley.min.js",
			
		<?php } ?>
			
		<?php if ($show_map) { ?>
		
			"<?=SITEPATH?>admin/js/app/maps.js",
		
		<?php } ?>
		
		<?php if ($show_file_upload) { ?>
		
			"<?=SITEPATH?>admin/js/vendor/fileinput.min.js",
			"<?=SITEPATH?>admin/js/vendor/sortable.min.js",
			"<?=SITEPATH?>admin/js/vendor/purify.min.js",
		
		<?php } ?>
		
		<?php if ($ladda_button) { ?>
		
			
			"<?=SITEPATH?>admin/js/vendor/ladda/spin.min.js",
			"<?=SITEPATH?>admin/js/vendor/ladda/ladda.min.js",
		
		<?php } ?>
		
		<?php if ($show_bootstrap_dialog) { ?>
		
			"<?=SITEPATH?>admin/js/vendor/bootstrap-dialog.min.js",
		
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
		
	//head.ready(function() {
	head.ready(function () {
       
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
	
		//jQuery(document).ready(function($){
				
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
				
				
				
				<?php if ($show_pie) { ?>
				
					//on load
					getMonthFeeSummary();
					
					//on change
					
					$(document).on('change','#payment-select',function(){
						payment_date = this.value; // or $(this).val(
						getMonthFeeSummary(payment_date);
					});
					
					//get month stats
					function getMonthFeeSummary(payment_date) {
						
						sch_id = $("#school-select").val();
						if (!payment_date) { payment_date = $("#payment-select").val(); }
						
						$.ajax({
							url: "<?=CURRENT_API_PATH?>getMonthFeesSummary",
							type: "POST",
							data: { "sch_id": sch_id, "payment_date": payment_date},
							dataType: "json",
							success: function(data) {
								error = data.error;
								message = data.message;
								
								if (!error){
		
									//create chart
									createChart(data);
									
								} else {
									
									//call error bootstrap message
									generateNotyMessage("success", message, '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
									
								}
								
							}
						}); 
						 
					}
					
					function createChart(item) {
						
						var placeholder = $("#flot-chart-pie");
						
						placeholder.addClass("height-300");
						
						//unbind chart
						placeholder.unbind();
						
						if (item.cash.fees_paid > 0 || item.mpesa.fees_paid > 0 || item.cheque.fees_paid > 0) {
							
							
							var data = [];
							
							data[0] = {
									label: "Cash",
									data: item.cash.fees_paid
								}
							
							data[1] = {
									label: "MPESA",
									data: item.mpesa.fees_paid
								}
								
							data[2] = {
									label: "Cheque",
									data: item.cheque.fees_paid
								}
				
							
				
							//placeholder.unbind();
			
							//$("#title").text("Default pie chart");
							//$("#description").text("The default pie chart with no options set.");
			
							$.plot(placeholder, data, {
								series: {
									pie: {
										show: true
									}
								},
								grid: {
									hoverable: true,
									clickable: true
								}
							});
							
							setCode([
								"$.plot('#placeholder', data, {",
								"    series: {",
								"        pie: {",
								"            show: true",
								"        }",
								"    },",
								"    grid: {",
								"        hoverable: true,",
								"        clickable: true",
								"    }",
								"});"
							]);
			
							placeholder.bind("plothover", function(event, pos, obj) {
			
								if (!obj) {
									return;
								}
			
								var percent = parseFloat(obj.series.percent).toFixed(2);
								$("#hover").html("<span style='font-weight:bold; color:" + obj.series.color + "'>" + obj.series.label + " (" + percent + "%)</span>");
							});
			
							placeholder.bind("plotclick", function(event, pos, obj) {
			
								if (!obj) {
									return;
								}
			
								percent = parseFloat(obj.series.percent).toFixed(2);
								message = "" + obj.series.label + ": " + percent + "%";
								generateNotyMessage("success", message, '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
								
							});
							
						} else {
							
							// no data found
							placeholder.html("<div class='text-center'><h3 class='text-danger'>No Fee Payments Made For Period</h3></div>");
							
							placeholder.removeClass("height-300");
						
						}
							
					}
					
					
					function labelFormatter(label, series) {
						return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
					}
			
					//
			
					function setCode(lines) {
						$("#code").text(lines.join("\n"));
					}
				
				<?php } ?>
				
				
				
				
				<?php if ($show_form) { ?>
				
					if ($('.datepicker').length){
						//set the dates format
						$('.datepicker').datepicker({
							format: "dd/mm/yyyy",
							todayHighlight: true,
							autoclose: true,
							toggleActive: true
						});
					}
					
					if ($('.datetimepicker').length){
						//set the dates format
						$('.datetimepicker').datetimepicker({
							format: "dd/mm/yyyy hh:ii",
							todayHighlight: true,
							toggleActive: true,
							autoclose: true,
							todayBtn: true,
							pickerPosition: "top-left",
        					startDate: "<?=date("Y-m-d")?>"
						});
					}
										
					if ($('.multiselect_box').length){
						$('.multiselect_box').multiselect({
							checkboxName: function(option) {
								return 'multiselect[]';
							},
							onChange: function(option, checked, select) {
								console.log('Changed option ' + $(option).val() + '.');
							},
							buttonWidth: '100%',
							//maxHeight: 200,
							includeSelectAllOption: true,
							enableFiltering: true,
							enableClickableOptGroups: true,
            				enableCollapsibleOptGroups: true
						});
					}
					
				<?php } ?>
				
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
					$(".nicescroll").niceScroll({ cursorcolor: '#cccccc', cursorborderradius: '5px', cursorwidth: "10px", cursorborder: "2px solid #cccccc", bouncescroll: true, autohidemode: false, background: "#fcfcfc", enablemousewheel: true, enablekeyboard: true,smoothscroll: true });
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
						url: "<?=CURRENT_API_PATH?>"+action,
						data: dataString,
						dataType: "json",
						async: true,
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
							//select_id = data.select_id;
							reload_grid = data.reload_grid;
							reload_grid2 = data.reload_grid2;
							reload_grid3 = data.reload_grid3;
							reload_grid_history = data.reload_grid_history;
							ref = data.ref;
							close_box = data.close_box; //close colorbox
							
							if (overlay_div) { hide_overlay(overlay_div); }
							
							if (slide_form) {
								if (!slide_duration) { slide_duration = 8000; }
								result_div = $(this).closest('form').find(".resultdiv");
								slide_up(result_div, 1000, slide_duration, "fadeOut");
							}
							
							if (reload_page) {
								location.reload(); 
								
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
											
											generateNotyMessage("success", message, '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
						
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
										generateNotyMessage("error", message, '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
										
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
									
										generateNotyMessage("success", message, '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
										
									<?php } ?>
									
								} else {
									
									<?php if ($show_bootstrap_dialog) { ?>
									
										if (error_quickmessage == "yes") {
											//showQuickMessage(title, message, timeout, type, autoclose)
											showQuickMessage("Error", message, error_dialog_timeout, "error", error_autoclose); 
										} else {
											//(title, message, close_dialog_text, close_dialog_css, timeout, type, autoclose)
											//showMessage("Error", message, "Close", "btn-danger", error_dialog_timeout, "error", error_autoclose); 	
											generateNotyMessage("error", message, '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
										}
									
									<?php } else { ?>
									
										//show message using noty
										//generateNotyMessage(type, text, layout, closeit)
										generateNotyMessage("error", message, '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
									
									<?php } ?>
									
								}
							
							}
							
							if (clear_form){ 
											
								$(".inputform").closest('form').find("input[type=text], input[type=password], input[type=email], textarea, file, select").val("");
			
							}
							
							if (reload_grid){ 
							
								$("#mybootgrid").bootgrid("reload");
								//if((select_id) && ($("#mybootgrid"))) { $("#mybootgrid").bootgrid("select", select_id); }
								
							}
							
							if (reload_grid2){ 
							
								$("#mybootgrid2").bootgrid("reload");
								
							}
							
							if (reload_grid3){ 
							
								$("#mybootgrid3").bootgrid("reload");
								
							}
							
							if (reload_grid_history){ 
							
								$("#mybootgrid-history").bootgrid("reload");
								
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
						defaultPreviewContent: '<img src="<?=SITEPATH?>admin/images/default_avatar_male.jpg" alt="Your Avatar" style="width:160px">',
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
						generateNotyMessage("success", "Images successfully updated", '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
					});
					
					$("#multiple-images2").fileinput({
						maxFileCount: 10,
						theme: "gly",
						showUpload: false,
						allowedFileExtensions: ["jpg", "gif", "png", "jpeg"]
					}).off('fileuploaded').on('fileuploaded', function(){ // refresh plugin after uploading 
						//generateNotyMessage(type, text, layout, closeit)
						generateNotyMessage("success", "Images successfully updated", '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
					});
					
					$("#multiple-images3").fileinput({
						maxFileCount: 10,
						theme: "gly",
						showUpload: false,
						allowedFileExtensions: ["jpg", "gif", "png", "jpeg"]
					}).off('fileuploaded').on('fileuploaded', function(){ // refresh plugin after uploading 
						//generateNotyMessage(type, text, layout, closeit)
						generateNotyMessage("success", "Images successfully updated", '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
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
						buttons: [{
							label: 'Close',
							action: function(dialogItself){
								dialogItself.close();
							}
						}],
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
					
					/*dialog.show({
						onshown: function() {
							reposition();
						}
					});*/
					
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
				$('.equalheight').matchHeight(false);
				//end match height
			}
			
			if ($('.equalheight2').length) {
				//match height
				// remove the old group
				$('.equalheight2').matchHeight({ remove: true });
			
				// apply matchHeight on the new selection, which includes the new element
				$('.equalheight2').matchHeight(false);
				//end match height
			}
			//end equal height
							
			
			//Login form
			$(".form-signin").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-signin";
			   submitForm(".form-signin","user/login", "<?=$ref_page?>", "", "", "", "", "", "", loader_div);
			});
			//End login form
			
			
						
			//change password form
			$(".form-change-password").submit(function(e){
			   e.preventDefault();
			   $(".resultdiv").html("<div class='alert alert-info loading wow fadeOut animated'>Hold On...</div>")
					.hide()
					.fadeIn(500, function() { $('.resultdiv').append(""); });
			   submitForm(".form-change-password","user/changepass", "<?=$ref_page?>", "", "", "", "", "", "", "");
			});
			
			//create new password form
			$(".form-create-password").submit(function(e){
			   
			   e.preventDefault();
			   var loader_div = ".form-create-password";
			   submitForm(loader_div,"user/createpass", "", "", "", "", "", "", "", loader_div);
			   
			});
						
			//new fee form
			$(".form-new-fees").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-new-fees";
			   submitForm(loader_div,"createStudentFee", "", "", "", "", "", "", "", loader_div);
			});
			
			//edit fee form
			$(".form-edit-fees").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-edit-fees";
			   submitForm(loader_div,"editSingleFee", "", "", "", "", "", "", "", loader_div);
			});
			
			//edit subject form
			$(".form-edit-subject").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-edit-subject";
			   submitForm(loader_div,"editSubject", "", "", "", "", "", "", "", loader_div);
			});
			
			//edit score grade form
			$(".form-edit-score-grade").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-edit-score-grade";
			   submitForm(loader_div,"editScoreGrade", "", "", "", "", "", "", "", loader_div);
			});
			
			//edit total score grade form
			$(".form-edit-total-score-grade").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-edit-total-score-grade";
			   submitForm(loader_div,"editTotalScoreGrade", "", "", "", "", "", "", "", loader_div);
			});
			
			//edit subject form
			$(".form-edit-parent").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-edit-parent";
			   submitForm(loader_div,"editParent", "", "", "", "", "", "", "", loader_div);
			});
						
			//set password form
			$(".form-set-password").submit(function(e){
			   e.preventDefault();
			   loader_div = $(".inputform").closest('form').find(".resultdiv");
			   /*$(loader_div).html("<div class='alert alert-info loading wow fadeOut animated'>Hold On...</div>")
					.hide()
					.fadeIn(500, function() { $(loader_div).append(""); });*/
			   load_overlay(".form-set-password");
			   submitForm(".form-set-password","user/setPassword", "<?=$ref_page?>", "", "", "", "", "", "", ".form-set-password");
			});
			
			//edit user form
			//NEW USER PIC
			/*$(".form-upload-user-pic").submit(function(e){
			   e.preventDefault();
			   var resultdiv = $(this).closest('.form-group').find('.resultdiv');
			   $(resultdiv).html("<div class='alert alert-info loading wow fadeOut animated'>Hold On...</div>")
					.hide()
					.fadeIn(500, function() { $(resultdiv).append("");  });
			   submitForm(".form-upload-user-pic","uploadUserPic", "", "", "", "", "", "", "", ".form-upload-user-pic");			   
			});*/
			//END NEW USER PIC
			
			//FORM EDIT USER
			$(".form-upload-user-pic").submit(function(e){
				e.preventDefault();
				
				//loader_div = ".form-upload-user-pic";
				loader_div = $(this).closest("form").find(".resultdiv");
				load_overlay(loader_div);
				
				var dataString = new FormData($('.form-upload-user-pic')[0]);
				
				
						
				$.ajax({
					type: "POST",
					url: "<?=CURRENT_API_PATH?>uploadUserPic",
					data: dataString,
					dataType: "json",
					async: true,
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
					url: "<?=CURRENT_API_PATH?>user/edituser",
					data: dataString,
					dataType: "json",
					async: true,
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
			
			//NEW SCHOOL FORM
			$(".form-new-school").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-new-school";
			   submitForm(loader_div,"createSchool", "<?=$ref_page?>", "", "", "", "", "", "", loader_div);
			   
			});
			//END NEW SCHOOL FORM
			
			//NEW STUDENT FORM
			$(".form-new-student").submit(function(e){
			   e.preventDefault();
			   /*$(".resultdiv").html("<div class='alert alert-info loading wow fadeOut animated'>Hold On...</div>")
					.hide()
					.fadeIn(500, function() { $('.resultdiv').append(""); });
			   submitForm(".form-new-school","createSchool", "<?//=$ref_page?>", "", "", "", "", "", "");*/
			   
			   //submitForm(formName, action, ref, error_quickmessage, success_quickmessage, error_autoclose, success_autoclose, error_dialog_timeout, success_dialog_timeout)
			   submitForm(".form-new-student","createStudent", "none", "yes", "no", "yes", "no", "7000", "", "");
			   
			});
			//END NEW STUDENT FORM
			
			//NEW USER GROUP FORM
			$(".form-add-user-group").submit(function(e){
			   
			   e.preventDefault();
			   //load_overlay('.form-add-user-group');
			   //submitForm(formName, action, ref, error_quickmessage, success_quickmessage, error_autoclose, success_autoclose, error_dialog_timeout, success_dialog_timeout)
			   //submitForm(".form-add-user-group","createUserGroup", "none", "yes", "no", "yes", "no", "7000", "", ".form-add-user-group");
			   submitForm(".form-add-user-group","createUserGroup", "", "", "", "", "", "", "", ".form-add-user-group");
			   
			});
			//END NEW USER GROUP FORM
						
			//EDIT STUDENT FORM
			$(".form-edit-student").submit(function(e){
				
			   e.preventDefault();
			   var loader_div = ".form-edit-student";
			   submitForm(loader_div,"editStudent", "", "", "", "", "", "", "", loader_div);	
			   
			});
			//END EDIT STUDENT FORM
			
			//UPLOAD STUDENTS
			$(".form-upload-students").submit(function(e){
			   
			    e.preventDefault();
				
				var loader_div = ".form-upload-students";
				
				var dataString = new FormData($(loader_div)[0]);
			   			   				
				load_overlay(loader_div); //overlay
				
				$.ajax({
					url: "<?=CURRENT_API_PATH?>uploadStudents",
					type: "POST",
					dataType: "json",
					data: dataString,
					processData: false,
    				contentType: false,
					success: function(data) {
						
						hide_overlay(loader_div);
						
						error = data.error;
						message = data.message;
						
						if (error) {
							
							showQuickMessage("Error", message, <?=MESSAGE_DIALOG_TIMEOUT_LONG?>, "error", "");
							
						} else {
						
							//show results in colorbox
							//set the data
							$("#student_upload_results").html(message);
							
							$.colorbox({
			
								inline:true, 
								href: "#edit_show_student_upload_results",
								scrolling: true,
								width: '600px',
								height: '400px',
								onComplete: function() {
									$(this).colorbox.resize();
								}
							
							});
							
							//reload grid
							$("#mybootgrid").bootgrid("reload");
							//$("#mybootgrid2").bootgrid("reload");
						
						}
						
					}
					
				}); 
						   
			});
			//END UPLOAD STUDENTS
			
			
			//SEND SINGLE SMS 
			$(".form-send-single-sms").submit(function(e){
			   
			    e.preventDefault();
				
				var loader_div = ".form-send-single-sms";
				
				dataString = new FormData($(loader_div)[0]);
				
				// Construct data string
    			//var dataString = $(".form-send-bulk-sms, .form-bulk-sms-query").serialize();
			
				load_overlay(loader_div); //overlay
				
				$.ajax({
					url: "<?=CURRENT_API_PATH?>sendSingleSchoolSMS",
					type: "POST",
					dataType: "json",
					data: dataString,
					processData: false,
    				contentType: false,
					success: function(data) {
						
						hide_overlay(loader_div);
						
						error = data.error;
						message = data.message;
						
						if (error) {
							
							showQuickMessage("Error", data.message, "", "error", ""); 
							
						} else {
						
							//generateNotyMessage("success", message, '<?//=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
							showQuickMessage("Success", data.message, "", "success", "yes"); 
							
							//clear textarea
							$("#sms_message2").val("");
							
							loadSchoolSMSBalance();
						
						}
						
					}
					
				}); 
						   
			});
			//END SEND SINGLE SMS
			
			
			//SEND BULK SMS
			$(".form-send-bulk-sms").submit(function(e){
			   
			    e.preventDefault();
				
				var loader_div = ".form-send-bulk-sms";
				
				// dataString = new FormData($(loader_div)[0]);
				
				// Construct data string
    			var dataString = $(".form-send-bulk-sms, .form-bulk-sms-query").serialize();
			
				load_overlay(loader_div); //overlay
				
				$.ajax({
					url: "<?=CURRENT_API_PATH?>sendBulkSMS",
					type: "POST",
					dataType: "json",
					data: dataString,
					//processData: false,
    				//contentType: false,
					success: function(data) {
						
						hide_overlay(loader_div);
						
						
						error = data.error;
						message = data.message;
						
						if (error) {
							
							showQuickMessage("Error", message, <?=MESSAGE_DIALOG_TIMEOUT_LONG?>, "error", "");
							
						} else {
						
							//show results in colorbox
							//set the data
							$("#sent_sms_results").html(message);
							
							$.colorbox({
			
								inline:true, 
								href: "#sent_sms_results",
								scrolling: true,
								width: '600px',
								height: '400px',
								onComplete: function() {
									$(this).colorbox.resize();
								}
							
							});
							
							//reload bulk sms balance					
							loadSchoolSMSBalance();
						
						}
						
					}
					
				}); 
						   
			});
			//END SEND BULK SMS
			
			
			<?php if ($show_parents_list) { ?>
			
				//RADIO SELECT
				//check selection of messagetype and show fields as appropriate
				var msgTypeRadios = document.querySelectorAll('input[type=radio][name="messageType"]'); 

				function changeHandler(event) {
					
				   if ( this.value === 'send_msg' ) {
					   
					 $("#send_msg_div").removeClass("hidden");
					 $("#send_msg_btn").html("Send Message");
					 
				   } else if ( this.value === 'add_account' ) {
					   
					  $("#send_msg_div").addClass("hidden");
					  $("#send_msg_btn").html("Add Parent Accounts");
					  
					  
				   }
				}
				
				Array.prototype.forEach.call(msgTypeRadios, function(radio) {
				   radio.addEventListener('change', changeHandler);
				});
				//END RADIO SELECT
				
				//ADD PARENT/ SEND PARENTS SMS
				$(".form-add-parent").submit(function(e){
				   
					e.preventDefault();
					
					var loader_div = ".form-add-parent";
					
					dataString = new FormData($(loader_div)[0]);
					
					// Construct data string
					//var dataString = $(".form-send-bulk-sms, .form-bulk-sms-query").serialize();
				
					load_overlay(loader_div); //overlay
					
					$.ajax({
						url: "<?=CURRENT_API_PATH?>addParent",
						type: "POST",
						dataType: "json",
						data: dataString,
						processData: false,
						contentType: false,
						success: function(data) {
							
							hide_overlay(loader_div);
							
							error = data.error;
							message = data.message;
							
							if (error) {
								
								showQuickMessage("Error", message, <?=MESSAGE_DIALOG_TIMEOUT_LONG?>, "error", "");
								
							} else {
							
								//show results in colorbox
								//set the data
								$("#sent_sms_results").html(message);
								
								$.colorbox({
				
									inline:true, 
									href: "#sent_sms_results",
									scrolling: true,
									width: '600px',
									height: '400px',
									onComplete: function() {
										$(this).colorbox.resize();
									}
								
								});
								
								//refresh displayed data
								$("#mybootgrid").bootgrid("reload");
								$("#mybootgrid2").bootgrid("reload");
								
								//reload bulk sms balance					
								loadSchoolSMSBalance();
							
							}
							
						}
						
					}); 
							   
				});
				//END ADD PARENT/ SEND PARENTS SMS
			
			<?php } ?>
			
			
			//SEND BULK SMS FORM
			/*
			$(".form-send-bulk-sms").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-send-bulk-sms";
			   submitForm(loader_div,"sendBulkSMS", "", "", "", "", "", "", "", loader_div);			   
			});
			*/
			//END SEND BULK SMS FORM
			
			//EDIT SCHOOL FORM
			$(".form-edit-school").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-edit-school";
			   submitForm(loader_div,"editSchool", "", "", "", "", "", "", "", loader_div);
			});
			//END EDIT SCHOOL FORM
									
			//NEW FEE FORM
			$(".form-upload-fees").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-upload-fees";
			   submitForm(loader_div,"uploadFees", "", "", "", "", "", "", "", loader_div);			   
			});
			//END NEW FEE FORM
			
			//NEW FEE FORM
			$(".form-upload-results").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-upload-results";
			   submitForm(loader_div,"uploadResults", "", "", "", "", "", "", "", loader_div);			   
			});
			//END NEW FEE FORM
			
			//NEW SUBJECT FORM
			$(".form-add-subject").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-add-subject";
			   submitForm(loader_div,"createSubject", "", "", "", "", "", "", "", loader_div);			   
			});
			//END NEW SUBJECT FORM	
			
			//NEW SCORE GRADE FORM
			$(".form-add-score-grade").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-add-score-grade";
			   submitForm(loader_div,"createScoreGrade", "", "", "", "", "", "", "", loader_div);			   
			});
			//END NEW SCORE GRADE FORM
			
			//NEW TOTAL SCORE GRADE FORM
			$(".form-add-total-score-grade").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-add-total-score-grade";
			   submitForm(loader_div,"createTotalScoreGrade", "", "", "", "", "", "", "", loader_div);			   
			});
			//END NEW TOTAL SCORE GRADE FORM						
			
			//NEW ACTIVITY FORM
			$(".form-new-activity").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-new-activity";
			   submitForm(loader_div,"createActivity", "", "", "", "", "", "", "", loader_div);			   
			});
			//END NEW ACTIVITY FORM
						
			//EDIT ACTIVITY FORM
			$(".form-edit-activity").submit(function(e){
			   e.preventDefault();
			   var loader_div = ".form-edit-activity";
			   submitForm(loader_div,"updateActivity", "", "", "", "", "", "", "", loader_div);			   
			});
			//END EDIT ACTIVITY FORM			
			
			//LOGOUT
			var logout = $('a.logout');
			logout.on('click',function(e) {
				e.preventDefault();
				
				$.LoadingOverlay("show"); //overlay
				
				$.ajax({
					url: "<?=CURRENT_API_PATH?>user/logout",
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
			
			//UPLOAD PICS FORM					
			$(".form-upload-pics").submit(function(e){
			    e.preventDefault();
			   
			    var loader_div = ".form-upload-pics";
				
				load_overlay(loader_div);
			   
			    var target_div = "#item-images";
								
				var dataString = new FormData($(loader_div)[0]);
				
				$.ajax({
					url: "<?=CURRENT_API_PATH?>uploadPics",
					type: "POST",
					data: dataString,
					dataType: "json",
					async: true,
					cache: false,
					contentType: false,
					processData: false,
					success: function(data) {
						
						hide_overlay(loader_div);
						
						error = data.error;
						message = data.message;
						
						if (!error){
							
							$.each(data.images, function(index, item){
								var row = create_image_entry(item);
								$(row).hide().prependTo(target_div).slideDown("slow");
							});
							generateNotyMessage("success", message, '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
							
						} else {
							
							//generateNotyMessage(type, text, layout, closeit)
							generateNotyMessage("error", message, '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
							
						}
						
					}
				});                                                    
			});
			//END UPLOAD PICS 
			
			
			//UPLOAD PICS FORM					
			$(".form-upload-pics2").submit(function(e){
			    e.preventDefault();
			   
			    var loader_div = ".form-upload-pics2";
				
				load_overlay(loader_div);
			   
			    var target_div = "#item-images2";
								
				var dataString = new FormData($(loader_div)[0]);
				
				$.ajax({
					url: "<?=CURRENT_API_PATH?>uploadPics",
					type: "POST",
					data: dataString,
					dataType: "json",
					async: true,
					cache: false,
					contentType: false,
					processData: false,
					success: function(data) {
						
						hide_overlay(loader_div);
						
						error = data.error;
						message = data.message;
						
						if (!error){
							
							$.each(data.images, function(index, item){
								var row = create_image_entry(item);
								$(row).hide().prependTo(target_div).slideDown("slow");
							});
							
							generateNotyMessage("success", message, '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
							
						} else {
							
							//generateNotyMessage(type, text, layout, closeit)
							generateNotyMessage("error", message, '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
							
						}
						
					}
				});                                                    
			});
			//END UPLOAD PICS 
			

			function scrollPageTo(hash) {

				var trgt = "#" + hash;
	
				//get the top offset of the target anchor
				var target_offset = $(trgt).offset();
				var target_top = target_offset.top;
		
				//goto that anchor by setting the body scroll top to anchor top
				$('html, body').animate({scrollTop:target_top}, 1500, '');
				
			}
			
			<?php if ($show_popup) { ?>
			
				$(".ajax").colorbox();
				
				/*if ((".magnific-popup2").length) {
				
					$('.magnific-popup2').magnificPopup({
						  type: 'inline',
				
						  fixedContentPos: false,
						  fixedBgPos: true,
				
						  overflowY: 'auto',
				
						  closeBtnInside: true,
						  preloader: false,
						  
						  midClick: true,
						  removalDelay: 300,
						  mainClass: 'my-mfp-zoom-in'
					});
				
				}*/
				
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
				var html = 		   '<div class="img-thumb shadow1 tkt-border margin-btm-10 deleteImage relative" id="image-' + item.image_id + '" data-pk-val="' + item.image_id + '" data-row-name="' + item.caption + '">';
				html = html 	+  '  	<span class="close close-image" title="Delete Image"><button class="btn btn-danger btn-circle"><i class="fa fa-fw fa-close"></i></button></span>';
				html = html 	+  '    <img src="' + item.image + '" alt="' + item.caption + '" width="100%">';
				
				html = html 	+  '    <div class="image-size">Image Size: ' +  item.image_dimensions + '</div>'; 
				
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
			
			//delete image
			function deleteImage(full_title, image_id, target_div)
			{
				swal({   
					title: "Are you sure you want to delete \n\""+full_title+"\" photo?",   
					text: "You will not be able to recover the photo after deleting",   
					type: "warning",   
					showCancelButton: true,   
					confirmButtonColor: "#DD6B55",   
					confirmButtonText: "Yes",
					showLoaderOnConfirm: true,   
					closeOnConfirm: false 
				}, function(){   
					//DELETE record before showing message
					//send data to delete via json	
					$.post("<?=CURRENT_API_PATH?>deleteImage", {'image_id' : image_id}, 
						 function(data){
							var error = data.error;
							//var message = data.message;
							
							if (!error){
								//show success msg
								swal({   
									title: "Deleted!",   
									text: "\"" + full_title + "\" has been deleted.",   
									timer: 1000,
									type: "success"
								}, function(){
										
									if (target_div) {
										
										//container div exists, slide it up to close
										$(target_div).slideUp(1000, function() {
											$(this).remove();
										});
										
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
					$.post("<?=CURRENT_API_PATH?>deleteItem", {'field_name' : field_name, 'field_value' : field_value, 'table_name' : table_name}, 
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
												var url = "<?=CURRENT_API_PATH?>fetchSubscriptions";
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
					url: "<?=CURRENT_API_PATH?>updateSingleFieldData",
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
					url: "<?=CURRENT_API_PATH?>deleteSingleItem",
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
				var url = "<?=CURRENT_API_PATH?>fetchSubscriptions";
				var params = { "phone_number": "<?=USER_PHONE?>", "page": "1", "items_per_page": items_per_page };
				load_subs_list(loader_div, params, url);
				
				//ONCHANGE OF SCHOOLS SELECT SUBSCRIPTIONS
				$(document).on('change','#sch_prov',function(){
				  	prov_id = this.value; // or $(this).val(
					loader_div = ".form-add-subscription";
					data_div = "#sch_name";
					$("#school-list").addClass("hidden");
					$("#student-list").addClass("hidden");
					$("#sch_name").html("");
					$("#student_name").html("");
					$(data_div).html("<option value=''>Select School</option>");
					//hide
					
					load_overlay(loader_div);
					//get students in this school
					$.ajax({
							url: "<?=CURRENT_API_PATH?>fetchSchoolListing",
							type: 'POST',
							data: { "full_list": "1", "province": prov_id },
							success : function(data) {
								//load data to select field
								$.each(data.schools, function(index, item){
									var row = create_new_school_select_entry(item);
									$(data_div).append(row);
								});
								hide_overlay(loader_div);
								$("#school-list").removeClass("hidden");
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
					$("#student-list").addClass("hidden");
					$("#student_name").html("");
					$(data_div).html("<option value=''>Select Student</option>");
					
					load_overlay(loader_div);
					//get students in this school
					$.ajax({
							url: "<?=CURRENT_API_PATH?>fetchStudentsInSchool",
							type: 'POST',
							data: { "sch_id": sch_id },
							success : function(data) {
								//load data to select field
								$.each(data.students, function(index, item){
									var row = create_new_student_select_entry(item);
									$(data_div).append(row);
								});
								hide_overlay(loader_div);
								$("#student-list").removeClass("hidden");
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
					loader_div = "#subs_list";
					$.ajax({
						type: "POST",
						url: "<?=CURRENT_API_PATH?>user/subscribe",
						data: dataString,
						dataType: "json",
						async: true,
						cache: false,
						contentType: false,
						processData: false,
						success: function(data) {

							//prepend only if its not existing
							if (data.error) {
								//show error message
								$(".resultdiv").html("<div class='alert alert-danger login wow fadeIn animated text-center padding-20'>" + data.message + "</div>")
											.hide().prependTo(".resultdiv").slideDown("slow");
											//.fadeIn(500, function() { $('.resultdiv').append(""); });
								slide_up(".resultdiv", 1000, 4000, "fadeOut"); //hide after 4 seconds
							} else {
								
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
							}

						}
					});
	
				});
				//END FORM ADD SUBSCRIPTION
				
			<?php } ?>
						
			<?php if ($show_admin_home) { ?>
				
				$(document).on('change','#school-select',function(){
					//submit the form
					$(this).closest('form').trigger('submit');
					
				});


				function create_chat_entry (item) {
					
					var html =     '<li class="list-group-item chat-item noclick" data-chat-id="' + item.chat_id + '">';
					html = html +  '  <a href="#">';
					html = html +  '    <div class="media v-middle margin-v-0-10">';
					html = html +  '    	<div class="media-body">';
					html = html +  '    		<p class="text-subhead">';
					html = html +  '    			<a href="#"><img src="' + item.user_image + '" alt="' + item.student_full_names + '" class="width-30 img-circle" /></a> &nbsp;';
					html = html +  '    			<a href="#">' + item.student_full_names + ' - ' + item.full_names + '</a> - ';
					html = html +  '    			<span class="text-caption text-light"> ' + item.updated_at + '</span>';
					html = html +  '    		</p>';
					html = html +  '    	</div>';
					html = html +  '    	<div class="media-right">';
					html = html +  '    		<div class="width-50 text-right">';
					html = html +  '    			<a href="#" class="btn btn-white btn-xs"><i class="fa fa-reply"></i></a>';
					html = html +  '    		</div>';
					html = html +  '    	</div>';
					html = html +  '    </div>';
					html = html +  '    <p>' + item.recent_message + '...</p>';
					html = html +  '</li>';
					
					return html;
					
				}
								
				function create_student_fee_entry (item) {
					
					var html =     '<tr>';
					html = html +  '	<td class="text-caption"><div class="label label-grey-200 label-xs">' + item.payment_paid_at_fmt + '</div></td>';
					html = html +  '	<td>' + item.payment_student_name + '</td>';
					html = html +  '	<td>' + item.payment_paid_by + '</td>';
					html = html +  '	<td>' + item.payment_mode + '</td>';
					html = html +  '	<td align="right">' + item.payment_amount_fmt + '</td>';
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
								if (data.rows.length == 0) {
									$(loader_div).html("<tr><td colspan='5' class='text-center text-danger no-records'><h3 class='text-danger'>No Fee Payments Found</h3></td></tr>");
									//hide create pdf button
									$("#show_pdf").addClass("hidden");
									$("#latest-fee-payments-head").addClass("hidden");
									
								} else {
									
									$.each(data.rows, function(index, item){
										var row = create_student_fee_entry(item);
										$(loader_div).append(row);
									});
									//show create pdf button
									$("#show_pdf").removeClass("hidden");
									$("#latest-fee-payments-head").removeClass("hidden");
									
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
				
				var sch_id = $("#school-select").val();
				
				//GET CHATS
				/*var url = "<?=CURRENT_API_PATH?>chats";
				var loader_div = "#messages-list";
				var params = { "user_id": "<?=USER_ID?>", "admin": "1", "page": "1", "limit": "5", "school_ids": sch_id };
				counter_div = "#messagesPageNum";
				retrieveChatJSON(url, loader_div, counter_div, params);*/
				
				//GET FEE PAYMENTS
				fetching = false;
				var url = "<?=CURRENT_API_PATH?>fetchStudentFeePayments";
				var loader_div = "#latest-fee-payments";
				var params = { "user_id": "<?=USER_ID?>", "admin": "1", "page": "1", "limit": "6", "sch_id": sch_id };
				load_home_fees_list(loader_div, params, url);
				
				//GET SCHOOL SUBS
				/*fetching = false;
				var url = "<?=CURRENT_API_PATH?>fetchSubscriptions";
				var loader_div = "#school-sub-list";
				var params = { "user_id": "<?=USER_ID?>", "admin": "1", "page": "1", "limit": "6", "sch_id": sch_id };
				load_home_subs_list(loader_div, params, url);*/
			
			<?php } ?>
			
			//detect clicked tab
			$(document).on( 'shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
			   var target = $(e.target).attr("href") // activated tab
			   //console.log(target) // activated tab
			   if (target=="#form-new-school" || target=="#new_item" || target=="#new_bulk" || target=="#new_record_bulk"){
				  	//show no results 
					$(".no-results").removeClass("hidden");
					//hide
					if ($(".item-details").length) { $(".item-details").addClass("hidden"); }
					if ($("#item-details").length) { $("#item-details").addClass("hidden"); }
					if ($("#item-details2").length) { $("#item-details2").addClass("hidden"); }
					if ($("#item-details3").length) { $("#item-details3").addClass("hidden"); }
					
					$("#mybootgrid").bootgrid("deselect");
			   }
			   
			})
			//end detect clicked tab
			
			<?php if ($show_chat_list || $show_admin_home) { ?>
			
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
								$(counter_div).data('page',data.page);
								
								//print out the data
								if (data.chats.length == 0) {
									$(loader_div).html("<div class='text-center text-danger no-records'><h3 class='text-danger'>No Chats Found</h3></div>");
								} else {
									$.each(data.chats, function(index, item){
										var row = create_chat_entry(item);
										$(loader_div).append(row);
									});
								
								}
								
								//hide_overlay(loader_div);
								
								//onload, click on first item after 2 seconds
								setTimeout(function() {
									$('.chat-item').first().click();
								}, 2000);
								
								
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
																	
				$(document).on('change','#school-select',function(){
					//submit the form
					$(this).closest('form').trigger('submit');
					
				});
				
				//hover delete link
				$(".c-delete").hide();     

				
				
				$(document).on("hover", ".chat-item", function() {
					 $(this).find('.c-delete').fadeIn(1500);
				}, function() {
					$(this).find('.c-delete').fadeOut(1500); 
				});
				//end hover delete link

				function create_chat_message_entry (item) {
			
					var html =     '<div class="panel panel-default paper-shadow" data-z="0.5" data-hover-z="1" data-animated>';
					html = html +  '  <div class="panel-body">';
					html = html +  '    <div class="media v-middle">';
					html = html +  '    	<div class="media-left">';
					html = html +  '    		<img src="' + item.user_image + '" alt="' + item.full_names + '" class="media-object img-circle width-50" />';
					html = html +  '    	</div>';
					
					html = html +  '      <div class="media-body message">';
					html = html +  '          <h4 class="text-subhead margin-none"><a href="#">' + item.full_names + ' - ' + item.phone_number + '</a></h4>';
					html = html +  '         <p class="text-caption text-light"><i class="fa fa-clock-o"></i> ' + item.created_at + '</p>';
					html = html +  '      </div>';
					html = html +  '    </div>';
					if (item.message != null){
						html = html +  '    <p>' + item.message + '</p>';
					}
					html = html +  '  </div>';
					html = html +  '</div>';
					
					return html;
					
				}
				
				function create_chat_entry (item) {
			
					var html =     '<li class="list-group-item chat-item noclick" data-chat-id="' + item.chat_id + '">';
					html = html +  '  <a href="#">';
					html = html +  '    <div class="media v-middle">';
					html = html +  '    	<div class="media-left">';
					html = html +  '    		<img src="' + item.user_image + '" width="50" alt="" class="media-object" />';
					html = html +  '    	</div>';
					
					html = html +  '      <div class="media-body">';
					html = html +  '         <span class="date">' + item.recent_message_created_at + '</span>';
					html = html +  '         <span class="user">' + item.full_names;
					if (item.student_name != null){
						html = html +  '          - ' + item.student_name;
					}
					html = html +  '         </span>';
					if (item.recent_message != null){
						html = html +  '         <div class="text-light">' + item.recent_message + '...</div>';
					}
					html = html +  '         <div class="c-delete text-danger">Delete</div>';
					html = html +  '      </div>';
					html = html +  '    </div>';
					html = html +  '  </a>';
					html = html +  '</li>';
					
					return html;
					
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
									//print out the data
									$.each(data.chat_messages, function(index, item){
										var row = create_chat_message_entry(item);
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
				
				//CREATE A NEW CHAT FOR THIS USER
				function create_new_chat(creator_id, recipient_id, student_id)
				{
					
					userDataUrl = "<?=CURRENT_API_PATH?>createNewChat";
					var params = { "creator_id": creator_id, "recipient_id": recipient_id, "student_id": student_id };
					
					if(fetching==false) //we are not loading
					{
						//set ul as target for new data
						loader_div = "ul#chats-list";
				
						fetching = true;
						
						$.ajax({
							url: userDataUrl,
							type: 'POST',
							data: params,
							success : function(data) {
								
								//chat has been created, close modal and prepend new chat at the top of chats list
								new_chat = create_chat_entry (data.chat);
								
								//close modal
								$("#modal-general").modal('hide');
								
								//prepend only if its not existing
								if (!data.chat.error) {
									//prepend new data
									$(new_chat).hide().prependTo(loader_div).slideDown("slow");
									//end prepend new data
									
									//onload, click on first item after 2 seconds
									setTimeout(function() {
										$('.chat-item').first().click();
									}, 2000);
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
				
				//format items for the modal display
				function create_user_list_entry (item) {
			
					var html =     '<li class="list-group-item sub-item noclick" data-user-id="' + item.sch_id + '" data-student-id="' + item.student_id + '">';
					html = html +  '  <a href="#">';
					html = html +  '    <div class="media v-middle">';
					html = html +  '    	<div class="media-left">';
					html = html +  '    		<img src="' + item.user_image + '" width="50" alt="" class="media-object" />';
					html = html +  '    	</div>';
					
					html = html +  '      <div class="media-body">';
					html = html +  '         <span class="user">' + item.school_name + ' - ' + item.student_name + '</span>';
					html = html +  '      </div>';
					html = html +  '    </div>';
					html = html +  '  </a>';
					html = html +  '</li>';
					
					return html;
					
				}
				
				//get items to show in modal
				function load_user_list(user_id, source_div)
				{
					
					userDataUrl = "<?=CURRENT_API_PATH?>fetchSubscriptions";
					var params = { "phone_number": "<?=USER_PHONE?>", "page": "1" };
					
					if(fetching==false) //we are not loading
					{
						//load initial ul markup
						$(source_div).html("<ul class='list-group' id='subs_list'></ul>");
						//set ul as target for new data
						loader_div = "#subs_list";
						$(loader_div).html(loader_object);
						//load_overlay(loader_div);
				
						fetching = true;
						
						$.ajax({
							url: userDataUrl,
							type: 'POST',
							data: params,
							success : function(data) {
								
								$(loader_div).html("");
								
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
				
				var url = "<?=CURRENT_API_PATH?>chats";
				var loader_div = "#chats-list";
				var params = { "user_id": "<?=USER_ID?>", "page": "1", "school_ids": <?=$sch_id?> };
				counter_div = "#chatPageNum";
				retrieveChatJSON(url, loader_div, counter_div, params);
				
				//catch clicks on chat item div
				$(document).on("click", '.chat-item', function(e) { 
				   //get chat id
				   var chat_id = $(this).data("chat-id");
				   //console.log(chat_id);
				   //set chat id variable
				   $("#currentChatId").data('chat-id',chat_id);
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
				   var url = "<?=CURRENT_API_PATH?>chats/" + chat_id;
				   var loader_div = "#messages-list";
				   counter_div = "#messagesPageNum";
				   var params = {  "user_id": "<?=USER_ID?>", "chat_id": chat_id, "page": "1" };
				   retrieveChatMessagesJSON(url, loader_div, counter_div, params);
	
	
				});
				
				//setting up bs modal
				$('.modal').on('shown',function () {
					var offset = $(this).offset().top;
					$(window).scrollTop(offset);
				});
				//end setting up bs modal
				
				//starting  new chat
				$(document).on("click", '#start-new-chat', function(e) { 
					var mytitle = "Select User";
					$("#general-modal-title").text(mytitle);
					load_user_list("<?=USER_ID?>", "#general-modal-body");
				});
				
				//clicking on a subscription item in modal
				$(document).on("click", '.sub-item', function(e) { 
				   
				   //get user id
				   var user_id = $(this).data("user-id");
				   var student_id = $(this).data("student-id");
				   //console.log('user_id - ' + user_id);
				   
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
				   create_new_chat("<?=USER_ID?>", user_id, student_id);
				   
				});
				
				//SEND MESSAGE BUTTON CLICK
				$(document).on("click", '#send-msg', function(e) { 
				   //get user id
				   var chat_id = $("#currentChatId").data("chat-id");
				   //check if field has data
				   message = $("input#message").val();
				   console.log('message - ' + message + ' === chat_id - ' + chat_id);
				   
				   ///chats/:id/message
				   //send the chat message
				   if (message != '') {
						createNewChatMessage("<?=USER_ID?>", chat_id, message);   
				   }
				   
				});
				//END SEND MESSAGE BUTTON CLICK
				
				//CREATE A NEW CHAT MSG FOR THIS USER
				function createNewChatMessage(user_id, chat_id, message)
				{
					
					userDataUrl = "<?=CURRENT_API_PATH?>chats/" + chat_id + "/message";
					var params = { "user_id": user_id, "chat_id": chat_id , "message": message};
					
					if(fetching==false) //we are not loading
					{
						//set ul as target for new data
						loader_div = "#messages-list";
				
						fetching = true;
						
						$.ajax({
							url: userDataUrl,
							type: 'POST',
							data: params,
							success : function(data) {
								
								//chat has been created, close modal and prepend new chat at the top of chats list
								new_chat = create_chat_message_entry (data.message);
								
								//prepend new data
								$(new_chat).hide().prependTo(loader_div).slideDown("slow");
								//end prepend new data
								
								$("input#message").val('');
							
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

			//START JQUERY GRID
			
				<?php if ($show_activities) { ?>
					
					//load activities grid
					listUrl = "<?=CURRENT_API_PATH?>fetchSchoolActivities";
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
							var url = "<?=CURRENT_API_PATH?>fetchSchoolIdFromStudentId";
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
							
							if ($("#reg_no").length) { reg_no = $("#reg_no").val(); }
							if ($("#sch_id").length) { sch_id = $("#sch_id").val(); }
							if ($("#term").length) { term = $("#term").val();	 }
							if ($("#year").length) { year = $("#year").val(); }
							if ($("#student_id").length) { student_id = $("#student_id").val(); }
							single_student_result = $("#single_student_result").val();
						
							var loader_div = "#results-list";
							var params = { "sch_id": sch_id, "reg_no": reg_no, "year": year, "term": term, "student_id": student_id, "single_student_result": single_student_result };
						
							load_results_list(params);
														
						}
						//END DIFFERENT FUNCS
													
				<?php } ?>
				
				<?php if ($show_results_list) { ?>
				
					function load_student_results_form_data(data){
		
						var id = data.rows[0].id;
						var student_name = data.rows[0].name;
						var term = data.rows[0].term;
						var year = data.rows[0].year;
						var current_class = data.rows[0].current_class;
						var mean_score = data.rows[0].mean_score;
						var total_score = data.rows[0].total_score;
						var mean_grade = data.rows[0].grade;
						var mean_points = data.rows[0].points;
						
						$("#result_id").val(id);
						
						var loader_div = "#results-data";
						
						$(loader_div).html("");
						
						//student details
						name_data = student_name + "<br> (Year: " + year + ", Class: " + current_class + ")";
						
						$("#student_full_names").html(name_data);
						$("#total_score").html(total_score);
						$("#mean_score").html(mean_score);
						$("#mean_points").html(mean_points);
						$("#mean_grade").html(mean_grade);
						
						
						//print out the data
						if (data.rows[0].student_results.rows.length == 0) {
							
							//no results
							$(loader_div).html("<tr><td colspan='5' class='text-center text-danger no-records'><h3 class='no-results'>No Payments Found</h3></td></tr>");
														
							//hide create pdf button										
							$("#report-summary").addClass("hidden");
							
						} else {
						
							$.each(data.rows[0].student_results.rows, function(index, item){
								var row = create_student_result_entry(item);
								$(loader_div).append(row);
							});
							//show create pdf button
							$("#report-summary").removeClass("hidden");
							
						}
						
						//load results item history
						load_results_history(id);
					
						//hide no results
						$(".no-results").addClass("hidden");
						
						//unhide hidden data
						$(".item-details").removeClass("hidden");							
													
					}
					
					function load_results_history(id)
					{
													
						//load fees history grid
						listUrl = "<?=CURRENT_API_PATH?>fetchStudentResultsHistory";
						editUrl = "";
						param_name = "id";
						param_value = id;
						param_name2 = "admin";
						param_value2 = "1";
						param_name3 = "user_id";
						param_value3 = "<?=USER_ID?>"; 
						
						if ($("#mybootgrid-history")) { $("#mybootgrid-history").bootgrid("destroy"); }

						loadGridHistory(listUrl,editUrl,param_name, param_value, param_name2, param_value2, param_name3, param_value3);
													
					}
					
					
					//get items to show
					function load_results_list(params)
					{
	
						var loader_div = "#results-list";
						var url = "<?=CURRENT_API_PATH?>getResultsGridListing";
							
						load_overlay(loader_div);
				
						fetching = true;
						
						$.ajax({
							url: url,
							type: 'POST',
							data: params,
							success : function(data) {
								
								hide_overlay(loader_div);
								
								load_student_results_form_data(data);
																								
							},
							error : function(xhr, statusText, error) { 
								console.log("Error! Could not retrieve the data.");
							}
						});
														
					}
					
					function showSummaryResults(params) {
						$.ajax({
							type: "POST",
							url: "<?=CURRENT_API_PATH?>fetchStudentResults",
							//data: { "reg_no": reg_no, "year": year, "term": term, "sch_id": sch_id },
							data: params,							
							success: function(data) {
	
								//show data if it exists
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
						
						year = $('#year').val();
						//refresh results
						fetchResults(year);					
						
				    });
					
					$(document).on('change','#student_id',function(){
						
						student_id = $('#student_id').val();
						//refresh results
						fetchResults("", student_id);					
						
				    });
					
					$(document).on('change','#term',function(){
						
						term = $('#term').val();
						//refresh results
						fetchResults("", "", term);		
						
				    });
					//end onchange of year and term 
					
					//load student results
					//fetchResults();
					
					//END SHARED FUNCS
				
				<?php } ?>
				
				
				<?php if ($show_results_list) { ?>
					
					//load results grid
					loadResultsGrid(); 
					
					//current_class change
					$(document).on('change','#current_class',function(e){
						e.preventDefault();
						current_class = $(this).val();
						//load data
						loadResultsGrid(current_class);
					});
					
					//stream change
					$(document).on('change','#stream',function(e){
						e.preventDefault();
						stream = $(this).val();
						loadResultsGrid("", stream);
					});
					
					//year change
					$(document).on('change','#year',function(e){
						e.preventDefault();
						year = $(this).val();
						loadResultsGrid("", "", year);
					}); 
					
					//term change
					$(document).on('change','#term',function(e){
						e.preventDefault();
						term = $(this).val();
						loadResultsGrid("", "", "", term);
					}); 
					
					//FORM FILTER results list
					function loadResultsGrid(current_class, stream, year, term) {
						
						if ($("#mybootgrid")) { $("#mybootgrid").bootgrid("destroy"); }
						
						sch_id = $("#sch_id").val();
						if (!current_class) { current_class = $("#current_class").val(); }
						if (!stream) { stream = $("#stream").val(); }
						if (!year) { year = $("#year").val(); }
						if (!term) { term = $("#term").val(); }
						
						user_id = $("#user_id").val(); 
						admin = $("#admin").val();
											
						//show data 
						listUrl = "<?=CURRENT_API_PATH?>getResultsGridListing";
						editUrl = "";
						param_name = "sch_id";
						param_value = sch_id;
						param_name2 = "current_class";
						param_value2 = current_class;
						param_name3 = "stream";
						param_value3 = stream;
						param_name4 = "user_id";
						param_value4 = user_id;
						param_name5 = "admin";
						param_value5 = "1";
						param_name6 = "year";
						param_value6 = year;
						param_name7 = "term";
						param_value7 = term;
						loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, param_name3, param_value3, param_name4, param_value4, param_name5, param_value5, param_name6, param_value6, param_name7, param_value7);
		
					}	
					
					//FORM ADD result
					$(".form-new-result").submit(function(e){
						e.preventDefault();
						
						var loader_div = ".form-new-result";
						load_overlay(loader_div);
						
						var dataString = new FormData($('.form-new-result')[0]);
						$.ajax({
							type: "POST",
							url: "<?=CURRENT_API_PATH?>createStudentResult",
							data: dataString,
							dataType: "json",
							async: true,
							cache: false,
							contentType: false,
							processData: false,
							success: function(data) {
								
								var result_div = $(this).closest('form').find(".resultdiv");
								var message = data.message;
								
								hide_overlay(loader_div);
								
								//prepend only if its not existing
								if (data.error) {
	
									message_type = "error";
									error_dialog_timeout = 5000;
									error_autoclose = "yes";
																	
									//show error message
									//(title, message, close_dialog_text, close_dialog_css, timeout, type, autoclose)
									//showMessage("Error", message, "Close", "btn-danger", error_dialog_timeout, message_type, error_autoclose);
									generateNotyMessage("error", message, '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
									
								} else {
									//show success message
									/*result_div.html("<div class='alert alert-success login wow fadeIn animated text-center padding-20'>" + data.message + "</div>")
												.hide().prependTo(".resultdiv").slideDown("slow");
									slide_up(".resultdiv", 1000, 4000, "fadeOut");*/ //hide after 4 seconds
									
									//clear form
									$("#score_item").val("");
									$("#subject_item").val("");
									
									generateNotyMessage("success", message, '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
									
									//reload results grid
									if ($("#mybootgrid")) { $("#mybootgrid").bootgrid("reload"); }
									
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
							url: "<?=CURRENT_API_PATH?>editSingleResult",
							data: dataString,
							dataType: "json",
							//async: true,
							cache: false,
							contentType: false,
							processData: false,
							success: function(data) {
																
								hide_overlay(loader_div);
								message = data.message;
								id = data.id;
								
								if (data.error) {
									
									generateNotyMessage("error", message, '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
																		
								} else {
									//close colorbox here									
									$(this).colorbox.close();
									
									generateNotyMessage("success", message, '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
									
									//reload results grid
									if ($("#mybootgrid")) { $("#mybootgrid").bootgrid("reload"); }
									
									//reload results history grid
									if ($("#mybootgrid-history")) { $("#mybootgrid-history").bootgrid("reload"); }
									
									//fetch data and reload result
									var params = { "id": id };
									load_results_list(params);									
									
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
						
						var html =     ' <tr>';
						html = html +  '  <td>' + item.name + '</td>';
						html = html +  '  <td>' + item.score + '</td>';
						html = html +  '  <td>' + item.grade + '</td>';
						html = html +  '  <td><button type="button" class="btn btn-xs btn-default command-edit edit-data-row" data-pk-val="' + item.id + '"  id="edit-' + item.id + '" data-row-name="grade: ' + item.name + '" data-pk="id"><span class="fa fa-pencil"></span></button></td>';
						html = html +  '  <td><button type="button" class="btn btn-xs btn-default command-delete remove-data-row" data-pk-val="' + item.id + '" data-row-name="grade: ' + item.name + '" data-pk="id"><span class="fa fa-trash-o"></span></button></td>';
						html = html +  ' </tr>';
							
						return html;
						
					}
					
					
					
					//fetch results
					function fetchResults(year, student_id, term){
						
						var term, year, student_id, sch_id, reg_no;
						
						if ((!term) && ($("#term").length)) { term = $("#term").val(); }
						if ((!year) && ($("#year").length)) { year = $("#year").val(); }
						if ((!student_id) && ($("#student_id").length)) { student_id = $("#student_id").val(); }
						if ((!sch_id) && ($("#sch_id").length)) { sch_id = $("#sch_id").val(); }
						if ((!reg_no) && ($("#reg_no").length)) { reg_no = $("#reg_no").val(); }
						single_student_result = $("#single_student_result").val();
					
						var params = { "sch_id": sch_id, "reg_no": reg_no, "year": year, "term": term, "student_id": student_id, "single_student_result": single_student_result };
					
						load_results_list(params);
						
						//load results history
						//load_results_history();
						
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
							$.post("<?=CURRENT_API_PATH?>deleteResultRecord", {'id' : result_id, 'sch_id' : <?=$sch_id?>}, 
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
				
					//on click show pdf button
					$(document).on('click','.show_pdf_link',function(){
						
						sch_id = $("#sch_id").val();
						reg_no = $("#reg_no").val();
						year = $("#fee_year").val();
						term = $("#term").val();
						student_id = $("#student_id").val();
						//load pdf creation console
						var pdfCreationURL = "<?=SITEPATH?>admin/savepdf.php?student_id=" + student_id + "&year=" + year + "&term=" + term + "&item_type=fees";
						window.open(pdfCreationURL);
						
					});
					
					
					function load_fees_details_form_data(data){
		
						var id = data.rows[0].id;
						//set the id onto hidden field
						$("#fee_id").val(id);
												
						//load fees
						var loader_div = "#fees-data";
						
						$(loader_div).html(""); 
						
						
						//load summary data
						var fees_paid_fmt2 = data.rows[0].fees_paid_fmt2;
						var fees_bal_fmt2 = data.rows[0].fees_bal_fmt2;
						var total_fees_fmt2 = data.rows[0].total_fees_fmt2;
						var student_name = data.rows[0].name;
						var year = data.rows[0].year;
						var current_class = data.rows[0].current_class;
						//var stream = data.rows[0].stream;
						
						//student details
						name_data = student_name + "<br> (Year: " + year + ", Class: " + current_class + ")";
						
						$("#student_name_right").html(name_data);
						
						$("#fees_total").html(total_fees_fmt2);
						$("#fees_paid").html(fees_paid_fmt2);
						$("#fees_balance").html(fees_bal_fmt2);
						
						
						//print out the data
						if (data.rows[0].fee_payments.rows.length == 0) {
							
							//no results
							$(loader_div).html("<tr><td colspan='7' class='text-center text-danger no-records'><h3 class='no-results'>No Payments Found</h3></td></tr>");
							
							//hide create pdf button										
							$("#report-summary").addClass("hidden");
							
						} else {
						
							//$(loader_div).html("");
							
							$.each(data.rows[0].fee_payments.rows, function(index, item){
								var row = create_student_fee_entry(item);
								$(loader_div).append(row);
							});
							
							//show create pdf button
							$("#report-summary").removeClass("hidden");
							
						}
						
									
						
						//load fees item history
						load_fee_payment_history(id);
											
						//hide no results
						$(".no-results").addClass("hidden");
						
						//unhide hidden data
						$(".item-details").removeClass("hidden");
						
					}
					
					function load_fee_payment_history(id)
					{
						
						//load fees payments history grid
						listUrl = "<?=CURRENT_API_PATH?>fetchStudentFeePaymentsHistory";
						editUrl = "";
						param_name = "id";
						param_value = id;
						param_name2 = "admin";
						param_value2 = "1";
						param_name3 = "user_id";
						param_value3 = "<?=USER_ID?>";
												
						//$("#grid").bootgrid("destroy").bootgrid("search", "serach phrase");
						$("#mybootgrid-history").bootgrid("destroy");
						
						loadGridHistory(listUrl,editUrl,param_name, param_value, param_name2, param_value2, param_name3, param_value3, "", "");
													
					}
											
					//get items to show
					function load_fees_list(params)
					{
	
						//loadFeeSummaryListing(params);
						
						fetchingFees=false;
						
						//var loader_div = "#fees-container";
						var loader_div = "#fees-data";
						//var url = "<?//=CURRENT_API_PATH?>fetchStudentFeePayments";
						var url = "<?=CURRENT_API_PATH?>getFeesGridListing";
							
						if(fetchingFees==false) //we are not loading
						{
							
							$("#report-summary").addClass("hidden");
							
							$(loader_div).html(loader_object);
							//load_overlay(loader_div);
					
							fetchingFees = true;
																												
							$.ajax({
								url: url,
								type: 'POST',
								data: params,
								success : function(data) {
									
									$(loader_div).html(""); 
									
									//hide_overlay(loader_div);
									
									//load summary data
									//hide_overlay(loader_div);
								
									//load summary data
									var fees_paid_fmt2 = data.rows[0].fees_paid_fmt2;
									var fees_bal_fmt2 = data.rows[0].fees_bal_fmt2;
									var total_fees_fmt2 = data.rows[0].total_fees_fmt2;
									var student_name = data.rows[0].name;
									var year = data.rows[0].year;
									var current_class = data.rows[0].current_class;
									//var stream = data.rows[0].stream;
									
									//student details
									name_data = student_name + "<br> (Year: " + year + ", Class: " + current_class + ")";
									
									$("#student_name_right").html(name_data);
									
									$("#fees_total").html(total_fees_fmt2);
									$("#fees_paid").html(fees_paid_fmt2);
									$("#fees_balance").html(fees_bal_fmt2);
									
									
									//print out the data
									if (data.rows.length == 0) {
										
										//no results
										$(loader_div).html("<tr><td colspan='7' class='text-center text-danger no-records'><h3 class='no-results'>No Payments Found</h3></td></tr>");
										
										//$("#fees_total").html(data.fees_summary.total_fees);
										//$("#fees_paid").html(data.fees_summary.fees_paid);
										//$("#fees_balance").html(data.fees_summary.fees_bal);
										
										//hide create pdf button										
										$("#report-summary").addClass("hidden");
										
									} else {
									
										//$(loader_div).html("");
										
										$.each(data.rows[0].fee_payments.rows, function(index, item){
											var row = create_student_fee_entry(item);
											$(loader_div).append(row);
										});
										
										//show create pdf button
										$("#report-summary").removeClass("hidden");
										
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
					
					
					//summary
					function loadFeeSummaryListing(params)
					{
													
						var loader_div = "#fees-data";
						var url = "<?=CURRENT_API_PATH?>getFeesGridListing";
						
						load_overlay(loader_div);
																																
						$.ajax({
							url: url,
							type: 'POST',
							data: params,
							success : function(data) {
								
								hide_overlay(loader_div);
								
								//load summary data
								var fees_paid_fmt2 = data.rows[0].fees_paid_fmt2;
								var fees_bal_fmt2 = data.rows[0].fees_bal_fmt2;
								var total_fees_fmt2 = data.rows[0].total_fees_fmt2;
								var student_name = data.rows[0].name;
								var year = data.rows[0].year;
								var current_class = data.rows[0].current_class;
								//var stream = data.rows[0].stream;
								
								//student details
								name_data = student_name + "<br> (Year: " + year + ", Class: " + current_class + ")";
								
								$("#student_name_right").html(name_data);
								
								$("#fees_total").html(total_fees_fmt2);
								$("#fees_paid").html(fees_paid_fmt2);
								$("#fees_balance").html(fees_bal_fmt2);
														
							},
							error : function(xhr, statusText, error) { 
								console.log("Error! Could not retrieve the data.");
							}
						});
															
					}
					
					
					function showSummaryFees(sch_id, student_id, year) {
						$.ajax({
							type: "POST",
							url: "<?=CURRENT_API_PATH?>fetchStudentFees",
							data: { "student_id": student_id, "year": year, "school_id": sch_id, "show_student_fees": "1" },							
							success: function(data) {
	
								//prepend only if its not existing
								if (!data.fees_summary.error) {
									//set static values
									$("#fees_total").html(data.fees_summary.total_fees);
									$("#fees_paid").html(data.fees_summary.fees_paid);
									$("#fees_balance").html(data.fees_summary.fees_bal);
									//console.log(" data.mean_score - " + data.mean_score);
									
									//load student name popup
									$("#popup_student_name").html(data.fees_summary.full_names);
									
									//show student name on right side pane
									$("#student_name_right").html(data.fees_summary.full_names);
									
								} 
	
							}
						});
												
					}
					
					
					//load results grid
					loadFeesGrid(); 
					
					//current_class change
					$(document).on('change','#current_class',function(e){
						e.preventDefault();
						current_class = $(this).val();
						//load data
						loadFeesGrid(current_class);
					});
					
					//stream change
					$(document).on('change','#stream',function(e){
						e.preventDefault();
						stream = $(this).val();
						loadFeesGrid("", stream);
					});
					
					//year change
					$(document).on('change','#year',function(e){
						e.preventDefault();
						year = $(this).val();
						loadFeesGrid("", "", year);
					}); 
					
					//FORM FILTER fees list
					function loadFeesGrid(current_class, stream, year) {
						
						if ($("#mybootgrid")) { $("#mybootgrid").bootgrid("destroy"); }
						
						sch_id = $("#sch_id").val();
						if (!current_class) { current_class = $("#current_class").val(); }
						if (!stream) { stream = $("#stream").val(); }
						if (!year) { year = $("#year").val(); }
						
						user_id = $("#user_id").val(); 
						admin = $("#admin").val();
											
						//show data 
						listUrl = "<?=CURRENT_API_PATH?>getFeesGridListing"; 
						editUrl = "";
						param_name = "sch_id";
						param_value = sch_id;
						param_name2 = "current_class";
						param_value2 = current_class;
						param_name3 = "stream";
						param_value3 = stream;
						param_name4 = "user_id";
						param_value4 = user_id;
						param_name5 = "admin";
						param_value5 = "1";
						param_name6 = "year";
						param_value6 = year;
						loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, param_name3, param_value3, param_name4, param_value4, param_name5, param_value5, param_name6, param_value6);
		
					}
					
					function load_fees_history()
					{
						
						var student_id = $("#student_id").val();
						var fee_year = $("#fee_year").val();
						
						//load fees history grid
						listUrl = "<?=CURRENT_API_PATH?>fetchStudentFeePaymentsHistory";
						editUrl = "";
						param_name = "student_id";
						param_value = student_id;
						param_name2 = "admin";
						param_value2 = "1";
						param_name3 = "user_id";
						param_value3 = "<?=USER_ID?>";
						param_name4 = "fee_year";
						param_value4 = fee_year;

						loadGridHistory(listUrl,editUrl,param_name, param_value, param_name2, param_value2, param_name3, param_value3, param_name4, param_value4);
													
					}
							
					//show fees in colorbox
					//set the data
					$(document).on('click','#add_fee_payment',function(){
					
						$.colorbox({
		
							inline:true, 
							href: "#add_fee_payment_popup",
							scrolling: true,
							width: '600px',
							height: '400px',
							onComplete: function() {
								$(this).colorbox.resize();
							}
						
						});
					
					});
						
					//DIFFERENT FUNCS
					//load data
					function create_student_fee_entry (item) {
											
						var html =     ' <tr>';
						html = html +  '  <td>' + item.id + '</td>';
						html = html +  '  <td align="right">' + item.payment_amount_fmt + '</td>';
						html = html +  '  <td>' + item.payment_mode + '</td>';
						html = html +  '  <td>' + item.payment_paid_at_fmt + '</td>';
						html = html +  '  <td>' + item.payment_paid_by + '</td>';
						html = html +  '  <td><button type="button" class="btn btn-xs btn-default command-delete edit-fee-data-row" data-pk-val="' + item.payment_id + '"  id="edit-' + item.payment_id + '" data-row-name="Amount: ' + item.payment_amount_fmt2 + ", Paid via: " + item.payment_mode + '" data-pk="id"><span class="fa fa-pencil"></span></button></td>';
						html = html +  '  <td><button type="button" class="btn btn-xs btn-default command-delete remove-fee-data-row" data-pk-val="' + item.payment_id + '" data-row-name="' + item.payment_amount_fmt2 + '", Paid via: "' + item.payment_mode + '" data-pk="id"><span class="fa fa-trash-o"></span></button></td>';
						html = html +  ' </tr>';
							
						return html;
						
					}
					
					//fetch fees
					function fetchFees(){
						
						sch_id = $("#school-select").val();
						year = $("#fee_year").val();
						student_id = $("#student_id").val();
					
						var loader_div = "#fees-data";
						var url = "<?=CURRENT_API_PATH?>fetchStudentFeePayments";
						var params = { "sch_id": sch_id, "year": year, "student_id": student_id, "admin": "1", "user_id": "<?=USER_ID?>"};
						
						//load year popup data
						$("#popup_year").html(year);
						
						//popup form
						$("#student_id_edit_popup").val(student_id);
						$("#fee_year_edit_popup").val(year);
						
						//show result summary data
						//showSummaryFees(sch_id, student_id, year);
					
						load_fees_list(params);
						
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
							url: "<?=CURRENT_API_PATH?>createStudentFee",
							data: dataString,
							dataType: "json",
							async: true,
							cache: false,
							contentType: false,
							processData: false,
							success: function(data) {
								
								var result_div = $(this).closest('form').find(".resultdiv");
								
								message = data.message;
								
								hide_overlay(loader_div);
								
								//prepend only if its not existing
								if (data.error) {
	
									message_type = "error";
									error_dialog_timeout = 5000;
									error_autoclose = "yes";
																	
									//show error message
									//(title, message, close_dialog_text, close_dialog_css, timeout, type, autoclose)
									//showMessage("Error", message, "Close", "btn-danger", error_dialog_timeout, message_type, error_autoclose);
									generateNotyMessage("error", message, '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
									
								} else {
									//show success message
									/*result_div.html("<div class='alert alert-success login wow fadeIn animated text-center padding-20'>" + data.message + "</div>")
												.hide().prependTo(".resultdiv").slideDown("slow");
									slide_up(".resultdiv", 1000, 4000, "fadeOut");*/ //hide after 4 seconds
									
									generateNotyMessage("success", message, '<?=NOTY_MESSAGE_LOCATION?>', 'y', 7000);
									
									//clear form
									$("#paid_by_item").val("");
									$("#amount_item").val("");
									$("#payment_date_item").val("");
									//$("#payment_mode_item").val("");
									
									//reload results
									//fetchFees();
									
									//reload data
									$("#mybootgrid").bootgrid("reload"); 
									
									//if((select_id) && ($("#mybootgrid"))) { $("#mybootgrid").bootgrid("select", select_id); }
									
									//load fees data
									//params = {"id": select_id};
									load_fees_list();
						
									$("#mybootgrid-history").bootgrid("reload");
									
									//close colorbox
									setTimeout(function() {
										$(this).colorbox.close();
									}, 1000);
									
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
							url: "<?=CURRENT_API_PATH?>editSingleFee",
							data: dataString,
							dataType: "json",
							async: true,
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
																		
									//reload data
									$("#mybootgrid").bootgrid("reload"); 
									//if((select_id) && ($("#mybootgrid"))) { $("#mybootgrid").bootgrid("select", select_id); }
									$("#mybootgrid-history").bootgrid("reload");
									
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
							
							sch_id = $("#school-select").val();
							//DELETE record
							$.post("<?=CURRENT_API_PATH?>deleteFeeRecord", {'id' : id, 'sch_id' : sch_id}, 
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
												
												//reload data
												$("#mybootgrid").bootgrid("reload"); 
												//if((select_id) && ($("#mybootgrid"))) { $("#mybootgrid").bootgrid("select", select_id); }
												$("#mybootgrid-history").bootgrid("reload");
												
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
					listUrl = "<?=CURRENT_API_PATH?>fetchSchoolGridListing";
					editUrl = "<?=SITEPATH?>admin/view-schools";
					param_name = "user_id";
					param_value = "<?=USER_ID?>";
					param_name2 = "admin";
					param_value2 = "1";
					loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, "", "", "", "");
				
				<?php } ?>
				
				<?php if ($show_students_list) { ?>
				
					//load students grid
					top_school_id = $("#top_school_id").data("sch-id");
					listUrl = "<?=CURRENT_API_PATH?>fetchStudentGridListing";
					editUrl = "<?=SITEPATH?>admin/view-students";
					param_name = "sch_id";
					param_value = top_school_id;
					param_name2 = "admin";
					param_value2 = "1";
					
					<?php if ($show_contacts_list) { ?>
						
						loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, "", "", "", "", "", "", "", "", true, "#users_selected", "#selected", 1);
						
					<?php } else { ?>
					
						loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, "", "", "", "");
						
						$("#mybootgrid-history").bootgrid();
						
					<?php } ?>				
					
				    
				
				<?php } ?>
				
				
				<?php if ($show_fees_report || $show_income_reports_list || $show_sms_inbox || $show_results_list || $show_fees_list || $show_single_school || $show_mpesa_inbox) { ?>
				
				//date clicks/ changes
				$(document).on('change','#start_date',function(){
					if ($("#start_date").val() != null) { $("#clear_start_date").removeClass("hidden"); } else { $("#clear_start_date").addClass("hidden"); }
				});
				$(document).on('change','#end_date',function(){
					if ($("#end_date").val() != null) { $("#clear_end_date").removeClass("hidden"); } else { $("#clear_end_date").addClass("hidden"); }
				});
				
				$(document).on('click','#clear_start_date',function(){
					$("#start_date").val(""); $("#clear_start_date").addClass("hidden");
				});
				
				$(document).on('click','#clear_end_date',function(){
					$("#end_date").val(""); $("#clear_end_date").addClass("hidden");
				});
				
				//date clicks/ changes
				$(document).on('change','#start_date_3',function(){
					if ($("#start_date_3").val() != null) { $("#clear_start_date_3").removeClass("hidden"); } else { $("#clear_start_date_3").addClass("hidden"); }
				});
				$(document).on('change','#end_date_3',function(){
					if ($("#end_date_3").val() != null) { $("#clear_end_date_3").removeClass("hidden"); } else { $("#clear_end_date_3").addClass("hidden"); }
				});
				
				$(document).on('click','#clear_start_date_3',function(){
					$("#start_date_3").val(""); $("#clear_start_date_3").addClass("hidden");
				});
				
				$(document).on('click','#clear_end_date_3',function(){
					$("#end_date_3").val(""); $("#clear_end_date_3").addClass("hidden");
				});
				//end date clicks/ changes
				
				//on click show pdf button
				$(document).on('click','.show_pdf',function(e){
					
					e.preventDefault();
					
					item_type = $(this).data("item-type");
					
					sch_id = $("#sch_id").val();
					stream = $("#stream").val();
					start_date = $("#start_date").val();
					end_date = $("#end_date").val();
					status = $("#status").val();
					current_class = $("#current_class").val();
					user_id = $("#user_id").val();
					reg_no = $("#reg_no").val();
					term = $("#term").val();
					year = $("#year").val();
					student_id = $("#student_id").val();
					result_id = $("#result_id").val();
					fee_id = $("#fee_id").val();
					silent = $("#silent").val();
					admin = $("#admin").val();
					
					//load pdf creation console
					var pdf_creation_url = "<?=SITEPATH?>admin/reports/savepdf.php?start_date=" + start_date + "&end_date=" + end_date + "&stream=" + stream + "&user_id=" + user_id + "&sch_id=" + sch_id + "&status=" + status + "&current_class=" + current_class + "&reg_no=" + reg_no + "&admin=" + admin + "&item_type=" + item_type + "&silent=" + silent + "&term=" + term + "&year=" + year + "&student_id=" + student_id + "&result_id=" + result_id + "&fee_id=" + fee_id;
					//window.open(pdf_creation_url);
					
					window.location.assign(pdf_creation_url);
					
					
				});
				
				//create excel download
				$(document).on('click','.export_excel',function(e){
					
					e.preventDefault();
					
					item_type = $(this).data("item-type");
					
					sch_id = $("#sch_id").val();
					stream = $("#stream").val();
					start_date = $("#start_date").val();
					end_date = $("#end_date").val();
					status = $("#status").val();
					current_class = $("#current_class").val();
					user_id = $("#user_id").val();
					reg_no = $("#reg_no").val();
					term = $("#term").val();
					year = $("#year").val();
					student_id = $("#student_id").val();
					result_id = $("#result_id").val();
					fee_id = $("#fee_id").val();
					admin = $("#admin").val();
					
					//load pdf creation console
					var excel_creation_url = "<?=SITEPATH?>admin/reports/saveexcel.php?start_date=" + start_date + "&end_date=" + end_date + "&stream=" + stream + "&user_id=" + user_id + "&sch_id=" + sch_id + "&status=" + status + "&current_class=" + current_class + "&reg_no=" + reg_no + "&admin=" + admin + "&item_type=" + item_type + "&term=" + term + "&year=" + year + "&student_id=" + student_id + "&result_id=" + result_id + "&fee_id=" + fee_id;
					//window.open(pdf_creation_url);
					
					window.location.assign(excel_creation_url);
					
					
				});
				
			<?php } ?>
			
			
			<?php if ($show_fees_report) { ?>
				
				//load fees rpt
				loadFeesReport();
				
				//school change
				$(document).on('change','#school-select',function(e){
					e.preventDefault();
					e.stopPropagation();
					sch_id = $(this).val();
					//load data
					loadFeesReport(sch_id);
				});
				
				//class change
				$(document).on('change','#current_class',function(e){
					//e.preventDefault();
					e.stopPropagation();
					current_class = $(this).val();
					//load data
					loadFeesReport("", current_class);
				}); 
				
				//stream change
				$(document).on('change','#stream',function(e){
					//e.preventDefault();
					e.stopPropagation();
					stream = $(this).val();
					//load data
					loadFeesReport("", "", stream);
				});
				
				//start_date change
				$(document).on('change','#start_date',function(e){
					//e.preventDefault();
					e.stopPropagation();
					start_date = $(this).val();
					//load data
					loadFeesReport("", "", "", start_date);
				}); 
				
				//end_date change
				$(document).on('change','#end_date',function(e){
					//e.preventDefault();
					e.stopPropagation();
					end_date = $(this).val();
					//load data
					loadFeesReport("", "", "", "", end_date);
				});
				
				//reg_no change
				$(document).on('change','#reg_no',function(e){
					//e.preventDefault();
					e.stopPropagation();
					reg_no = $(this).val();
					//load data
					loadFeesReport("", "", "", "", "", reg_no);
				});		
				
				//clear start date
				$(document).on('click','#clear_start_date',function(e){
					e.preventDefault();
					e.stopPropagation();
					loadFeesReport();
				});
				
				//clear end date
				$(document).on('click','#clear_end_date',function(e){
					e.preventDefault();
					loadFeesReport();
				}); 
				
				//FORM FILTER fees REPORT
				//frm.unbind('submit').bind("submit", function (ev){
				$(".filter-fee-report").unbind('submit').bind("submit", function (e){
				//$(".filter-fee-report").submit(function(e){
					e.preventDefault();
					e.stopPropagation();
					loadFeesReport();
				});
				//END FILTER fees REPORT
				
				//FORM FILTER fees sold REPORT
				function loadFeesReport(sch_id, current_class, stream, start_date, end_date, reg_no) {
					
					var sch_id, current_class, start_date, end_date, stream, reg_no;
					
					if (!sch_id) { sch_id = $("#sch_id").val(); }
					if (!current_class) { current_class = $("#current_class").val(); }
					if (!start_date) { start_date = $("#start_date").val(); }
					if (!end_date) { end_date = $("#end_date").val(); }
					if (!stream) { stream = $("#stream").val(); }
					if (!reg_no) { reg_no = $("#reg_no").val(); }
					
					user_id = $("#user_id").val(); 
					item_type = $("#item_type").val(); 
					admin = $("#admin").val();					
					
					//show data 
					url = "<?=CURRENT_API_PATH?>getFeesGridListing";
					editUrl = "";
					param_name = "sch_id";
					param_value = sch_id;
					param_name2 = "start_date";
					param_value2 = start_date;
					param_name3 = "end_date";
					param_value3 = end_date;
					param_name4 = "user_id";
					param_value4 = user_id;
					param_name5 = "admin";
					param_value5 = "1";
					param_name6 = "current_class";
					param_value6 = current_class;
					param_name7 = "stream";
					param_value7 = stream;
					param_name8 = "reg_no";
					param_value8 = reg_no;
					param_name9 = "item_type";
					param_value9 = item_type;
					
					if ($("#mybootgrid")) { $("#mybootgrid").bootgrid("destroy"); }
	
					loadGrid(url, "", param_name, param_value, param_name2, param_value2, param_name3, param_value3, param_name4, param_value4, param_name5, param_value5, param_name6, param_value6, param_name7, param_value7, param_name8, param_value8, param_name9, param_value9);
					
					//load summary
					var params = { "sch_id": sch_id, "start_date": start_date, "end_date": end_date, "user_id": user_id, "admin": "1", "current_class": current_class, "stream": stream, "reg_no": reg_no };
					fetching = false;
					loader_div = "#report_total_amount";
					loadFeeReportSummary(loader_div, params);
							
				}
				//END FILTER fee REPORT				
				
				//get summary to show
				function loadFeeReportSummary(loader_div, params)
				{

					url = "<?=CURRENT_API_PATH?>getFeePaymentsGridListing";
					
					if(fetching==false) //we are not loading
					{
						
						load_overlay(loader_div);
				
						fetching = true;
						
						$.ajax({
							url: url,
							type: 'POST',
							data: params,
							success : function(data) {
								
								hide_overlay(loader_div);
																																			
								//print out the data
								if (data.total > 0) {
									
									$(loader_div).html(data.totalSumFmt2);
									$("#report-summary").removeClass("hidden");
									
								} else {
									
									$(loader_div).html("");
									$("#report-summary").addClass("hidden");
									
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
				
				<?php } ?>
				
				
				<?php if ($show_admin_home || $show_fees_list || $show_students_list || $show_results_list || $show_bulk_sms || $show_fees_report || $show_chat_list || $show_parents_list || $show_mpesa_inbox) { ?>

					$(document).on('change','#school-select',function(){
						//submit the form
						$(this).closest('form').trigger('submit');
						
				    });

				<?php } ?>
				
				
				<?php if ($show_contacts_list && !$show_students_list) { ?>
				
					//load subjects grid
					top_sch_id = $("#top_school_id").data("sch-id");
					listUrl = "<?=CURRENT_API_PATH?>fetchContactGridListing";
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
				
				<?php if ($show_sms_balance) { ?>
					
					//load sms balance
					loadSchoolSMSBalance();
					
					//load school sms balance
					function loadSchoolSMSBalance(){
		
						loader_div = "#bulk_sms_balance";
						load_overlay(loader_div);
					
						loader_div2 = "#bulk_sms_balance2";
						load_overlay(loader_div2);
												
						//school id
						sch_id = $("#school-select").val();
		
						$.ajax({
							
							url: "<?=CURRENT_API_PATH?>getBulkSmsBalance",
							type: "POST",
							data: {'sch_id': sch_id, 'user_id': "<?=USER_ID?>", 'admin': "1"},
							dataType: "json",
							success: function(data) {
										
								hide_overlay(loader_div);
							
								hide_overlay(loader_div2);
								
								//prepend only if its not existing
								if (data.error) {
									
									//show error message
									showQuickMessage("Error", data.message, <?=MESSAGE_DIALOG_TIMEOUT_LONG?>, "error", "");
									
									//disable sms form
									$(".form-send-bulk-sms :input").prop("disabled", true);
									
									//show overlay div
									$(".overlay-div").removeClass("hidden");
									
								}  else {
									
									//update bulk sms balance
									$(loader_div).html(data.balance);
									$(loader_div2).html(data.balance);
									
									//hide overlay div
									$(".overlay-div").addClass("hidden");		
									
								}
		
							}
							
						});	
						
					}
					
				<?php } ?>
				
				
				<?php if ($show_parents_list) { ?>

					//load students list
					loadStudentsList();
					
					//load sent parent requests list
					loadSentParentRequestsList();
										
					//offer change
					$(document).on('change','#current_class',function(e){
						e.preventDefault();
						current_class = $(this).val();
						//load data
						loadStudentsList();
					});
					
					//client_id change
					$(document).on('change','#stream',function(e){
						e.preventDefault();
						stream = $(this).val();
						loadStudentsList("", stream);
					}); 
					
					
					//FORM FILTER students list
					function loadStudentsList(current_class, stream, student_ids) {
						
						if ($("#mybootgrid")) { $("#mybootgrid").bootgrid("destroy"); }
						
						sch_id = $("#school-select").val();
						if (!student_ids) { student_ids = $("#student_ids").val(); }
						if (!current_class) { current_class = $("#current_class").val(); }
						if (!stream) { stream = $("#stream").val(); }
						
						user_id = $("#user_id").val(); 
						admin = $("#admin").val();
											
						//show data 
						listUrl = "<?=CURRENT_API_PATH?>fetchStudentGridListing";
						editUrl = "";
						param_name = "sch_id";
						param_value = sch_id;
						param_name2 = "student_ids";
						param_value2 = student_ids;
						param_name3 = "current_class";
						param_value3 = current_class;
						param_name4 = "stream";
						param_value4 = stream;
						param_name5 = "user_id";
						param_value5 = user_id;
						param_name6 = "admin";
						param_value6 = "1";
						loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, param_name3, param_value3, param_name4, param_value4, param_name5, param_value5, param_name6, param_value6);
		
					}
					
					//load sent parent request list
					function loadSentParentRequestsList() {
						
						if ($("#mybootgrid2")) { $("#mybootgrid2").bootgrid("destroy"); }
						
						sch_id = $("#school-select").val();						
						user_id = $("#user_id").val(); 
						admin = $("#admin").val();
											
						//show data 
						listUrl = "<?=CURRENT_API_PATH?>fetchSentSmsGridListing";
						editUrl = "";
						param_name = "sch_id";
						param_value = sch_id;
						param_name2 = "user_id";
						param_value2 = user_id;
						param_name3 = "admin";
						param_value3 = "1";
						param_name4 = "sms_type_id";
						param_value4 = "<?=ADD_PARENT_REQUEST_SMS?>";
						loadGrid2(listUrl,editUrl,param_name, param_value, param_name2, param_value2, param_name3, param_value3, param_name4, param_value4);
		
					}
					
					
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
					listUrl = "<?=CURRENT_API_PATH?>fetchSubjectGridListing";
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

				<?php } ?>
				
				<?php if ($show_grading_list) { ?>
									
					loadScoreGrading();
					
					function loadScoreGrading(top_level_id) {
						
						if ($("#mybootgrid")) { $("#mybootgrid").bootgrid("destroy"); }
						
						//load total score grading grid
						if (!top_level_id) { top_level_id = $("#top_level_id").data("level-id"); }
						listUrl = "<?=CURRENT_API_PATH?>fetchScoreGradeGridListing";
						editUrl = "";
						param_name = "level_id";
						param_value = top_level_id;
						param_name2 = "";
						param_value2 = "";
						loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, "", "", "", "");
						
					}
					
				    $(document).on('change','#level-select',function(){
						//submit the form
						//$(this).closest('form').trigger('submit');
						top_level_id = $(this).val();
						loadScoreGrading(top_level_id);
						
				    });

				<?php } ?>
				
				<?php if ($show_total_grading_list) { ?>
									
					loadTotalScoreGrading();
					
					function loadTotalScoreGrading(top_level_id) {
						
						if ($("#mybootgrid")) { $("#mybootgrid").bootgrid("destroy"); }
						
						//load total score grading grid
						if (!top_level_id) { top_level_id = $("#top_level_id").data("level-id"); }
						listUrl = "<?=CURRENT_API_PATH?>fetchTotalScoreGradeGridListing";
						editUrl = "";
						param_name = "level_id";
						param_value = top_level_id;
						param_name2 = "";
						param_value2 = "";
						loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, "", "", "", "");
						
					}
					
				    $(document).on('change','#level-select',function(){
						//submit the form
						//$(this).closest('form').trigger('submit');
						top_level_id = $(this).val();
						loadTotalScoreGrading(top_level_id);
						
				    });

				<?php } ?>
				
				<?php if ($show_users_list) { ?>
				
					//load users grid
					listUrl = "<?=CURRENT_API_PATH?>fetchUserGridListing";
					editUrl = "";
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
							url: "<?=CURRENT_API_PATH?>updateGroupPermissions",
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
							$.post("<?=CURRENT_API_PATH?>deleteGroup", {'field_name' : field_name, 'field_value' : field_value, 'table_name' : table_name}, 
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

				
				<?php
				 
					if ($show_bootgrid_1_multiple) {
						$show_bootgrid_1_multiple = "true"; 
					} else {
						$show_bootgrid_1_multiple = "false"; 
					}
					
					if ($show_bootgrid_2_multiple) {
						$show_bootgrid_2_multiple = "true"; 
					} else {
						$show_bootgrid_2_multiple = "false"; 
					}
					
					if ($show_bootgrid_3_multiple) {
						$show_bootgrid_3_multiple = "true"; 
					} else {
						$show_bootgrid_3_multiple = "false"; 
					}
					
					if ($show_bootgrid_history_multiple) {
						$show_bootgrid_history_multiple = "true"; 
					} else {
						$show_bootgrid_history_multiple = "false"; 
					}
					
				?>
			
				//loadgrid
				function loadGrid(listUrl, editUrl, param_name, param_value, param_name2, param_value2, param_name3, param_value3, param_name4, param_value4, param_name5, param_value5, param_name6, param_value6, param_name7, param_value7, param_name8, param_value8, param_name9, param_value9, param_name10, param_value10, show_search_box, limited_count, count_div, selected_div, multiselect)
				{
					
					var selectedRows;
					var the_row_count;
					var select_value = false;
					if (!limited_count) {
						<?php $the_row_count = "50, 25, 10, 100, -1"; ?>
					} else {
						<?php $the_row_count = "25, 10, 50, 100, -1"; ?>	
					}

					/*------------------------------------------ MAIN GRID ------------------------------------------*/
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
						selection:true,
						sorting:true,
						multiSelect: <?=$show_bootgrid_1_multiple?>,
						keepSelection: true,
						//navigation: 2,
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
							if ((param_name5 != "") && (param_value5 != "")) {
								request[param_name5] = param_value5;
							}
							if ((param_name6 != "") && (param_value6 != "")) {
								request[param_name6] = param_value6;
							}
							if ((param_name7 != "") && (param_value7 != "")) {
								request[param_name7] = param_value7;
							}
							if ((param_name8 != "") && (param_value8 != "")) {
								request[param_name8] = param_value8;
							}
							if ((param_name9 != "") && (param_value9 != "")) {
								request[param_name9] = param_value9;
							}
							if ((param_name10 != "") && (param_value10 != "")) {
								request[param_name10] = param_value10;
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
							"views": function(column, row)
							{
								return "<a href=''" + row.id + "\" class=\"btn btn-xs btn-default edit-row noclick views\" data-type=\"iframe\"  data-row-name=\"" + row.name + "\" data-pk-val=\"" + row.id + "\"><span class=\"fa fa-search\"></span></a>";
							},
							"status-links": function(column, row)
							{
								
								if (row.status == <?=ACTIVE_STATUS?>) {
										return "<span class='label label-success' title='<?=ACTIVE_TEXT?>'><?=ACTIVE_TEXT?></span>";
									
								} else if (row.status == <?=INACTIVE_STATUS?>) {
										return "<span class='label label-danger' title='<?=INACTIVE_TEXT?>'><?=INACTIVE_TEXT?></span>";
									
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
									
								} else if (row.status == <?=ACCOUNT_EXISTS_STATUS?>) {
										return "<span class='label label-info' title='<?=ACCOUNT_EXISTS_STATUS_TEXT?>'><?=ACCOUNT_EXISTS_STATUS_TEXT?></span>";
										
								} else if (row.status == <?=REQUEST_SENT_STATUS?>) {
										return "<span class='label label-warning' title='<?=REQUEST_SENT_STATUS_TEXT?>'><?=REQUEST_SENT_STATUS_TEXT?></span>";
									
								} else if (row.status == <?=NOT_SUBSCRIBED_STATUS?>) {
										return "<span class='label label-danger' title='<?=NOT_SUBSCRIBED_STATUS_TEXT?>'><?=NOT_SUBSCRIBED_STATUS_TEXT?></span>";
										
								} else if (row.status == <?=SUBSCRIBED_STATUS?>) {
										return "<span class='label label-success' title='<?=SUBSCRIBED_STATUS_TEXT?>'><?=SUBSCRIBED_STATUS_TEXT?></span>";
									
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
						 
					/*------------------------------------------ MAIN GRID ------------------------------------------*/
						
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
							var user_id = "<?=USER_ID?>";
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
						
						selected_row_ids = $("#mybootgrid").bootgrid("getSelectedRows");
						selected_row_count = stringCount(selected_row_ids + '', ",");
						
						/*var rowIds = [];
						for (var i = 0; i < rows.length; i++)
						{
							rowIds.push(rows[i].id);
						}
						
						row_ids = rowIds.join(",");*/
						
						
	
						//console.log("row id eh : " + selected_row_ids + " == row count : " + selected_row_count);
						
						<?php if ($show_selected_items) { ?>
						
							load_bulk_sms_details_form_data(selected_row_ids, selected_row_count);
							
							
						<?php } else { ?>
						
							loadFormData(row_id);
							
						<?php } ?>
				
					}).on("deselected.rs.jquery.bootgrid", function (e, rows) {
						
						var row_id;
						for (var i = 0; i < rows.length; i++) {
							row_id = rows[i].id;
						}
						
						selected_row_ids = $("#mybootgrid").bootgrid("getSelectedRows");
						selected_row_count = stringCount(selected_row_ids + '', ",");
						
						/*var rowIds = [];
						for (var i = 0; i < rows.length; i++)
						{
							rowIds.push(rows[i].id);
						}
						
						row_ids = rowIds.join(",");*/
						
						
	
						//console.log("row id eh : " + selected_row_ids + " == row count : " + selected_row_count);
						
						<?php if ($show_selected_items) { ?>
						
							load_bulk_sms_details_form_data(selected_row_ids, selected_row_count);
							
						<?php } ?>
				
					});
					
				}
				//end loadgrid
				
				
				//LOADGRID2
				function loadGrid2(listUrl, editUrl, param_name, param_value, param_name2, param_value2, param_name3, param_value3, param_name4, param_value4, param_name5, param_value5, param_name6, param_value6, param_name7, param_value7, param_name8, param_value8, param_name9, param_value9, param_name10, param_value10, show_search_box, limited_count, count_div, selected_div, multiselect)
				{
					
					var selectedRows;
					var the_row_count;
					var select_value = false;
					if (!limited_count) {
						<?php $the_row_count = "50, 25, 10, 100, -1"; ?>
					} else {
						<?php $the_row_count = "25, 10, 50, 100, -1"; ?>	
					}

					/*------------------------------------------ MAIN GRID ------------------------------------------*/
					$("#mybootgrid2").on("initialize.rs.jquery.bootgrid", function (e) {
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
			
					var grid = $("#mybootgrid2").bootgrid({
						ajax: true,
						selection:true,
						sorting:true,
						multiSelect: <?=$show_bootgrid_2_multiple?>,
						keepSelection: true,
						//navigation: 2,
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
							if ((param_name5 != "") && (param_value5 != "")) {
								request[param_name5] = param_value5;
							}
							if ((param_name6 != "") && (param_value6 != "")) {
								request[param_name6] = param_value6;
							}
							if ((param_name7 != "") && (param_value7 != "")) {
								request[param_name7] = param_value7;
							}
							if ((param_name8 != "") && (param_value8 != "")) {
								request[param_name8] = param_value8;
							}
							if ((param_name9 != "") && (param_value9 != "")) {
								request[param_name9] = param_value9;
							}
							if ((param_name10 != "") && (param_value10 != "")) {
								request[param_name10] = param_value10;
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
							"views": function(column, row)
							{
								return "<a href=''" + row.id + "\" class=\"btn btn-xs btn-default edit-row noclick views\" data-type=\"iframe\"  data-row-name=\"" + row.name + "\" data-pk-val=\"" + row.id + "\"><span class=\"fa fa-search\"></span></a>";
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
						 
					/*------------------------------------------ MAIN GRID ------------------------------------------*/
						
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
							var user_id = "<?=USER_ID?>";
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
						
						selected_row_ids = $("#mybootgrid2").bootgrid("getSelectedRows");
						selected_row_count = stringCount(selected_row_ids + '', ",");
						
						loadFormData2(row_id);
							
				
					}).on("deselected.rs.jquery.bootgrid", function (e, rows) {
						
						var row_id;
						for (var i = 0; i < rows.length; i++) {
							row_id = rows[i].id;
						}
						
						selected_row_ids = $("#mybootgrid2").bootgrid("getSelectedRows");
						selected_row_count = stringCount(selected_row_ids + '', ",");
				
					});
					
				}
				//END LOADGRID2
				
				
				//LOADGRID3
				function loadGrid3(listUrl, editUrl, param_name, param_value, param_name2, param_value2, param_name3, param_value3, param_name4, param_value4, param_name5, param_value5, param_name6, param_value6, param_name7, param_value7, param_name8, param_value8, param_name9, param_value9, param_name10, param_value10, show_search_box, limited_count, count_div, selected_div, multiselect)
				{
					
					var selectedRows;
					var the_row_count;
					var select_value = false;
					if (!limited_count) {
						<?php $the_row_count = "50, 25, 10, 100, -1"; ?>
					} else {
						<?php $the_row_count = "25, 10, 50, 100, -1"; ?>	
					}

					/*------------------------------------------ MAIN GRID ------------------------------------------*/
					$("#mybootgrid3").on("initialize.rs.jquery.bootgrid", function (e) {
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
			
					var grid = $("#mybootgrid3").bootgrid({
						ajax: true,
						selection:true,
						sorting:true,
						multiSelect: <?=$show_bootgrid_3_multiple?>,
						keepSelection: true,
						//navigation: 2,
						rowSelect: true,
						rowCount: [<?=$the_row_count?>],
						searchSettings: {
							delay: 300
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
							if ((param_name5 != "") && (param_value5 != "")) {
								request[param_name5] = param_value5;
							}
							if ((param_name6 != "") && (param_value6 != "")) {
								request[param_name6] = param_value6;
							}
							if ((param_name7 != "") && (param_value7 != "")) {
								request[param_name7] = param_value7;
							}
							if ((param_name8 != "") && (param_value8 != "")) {
								request[param_name8] = param_value8;
							}
							if ((param_name9 != "") && (param_value9 != "")) {
								request[param_name9] = param_value9;
							}
							if ((param_name10 != "") && (param_value10 != "")) {
								request[param_name10] = param_value10;
							}
							
							return request;
						}
						
						
						,
						formatters: {
							"commands": function(column, row)
							{
								return "<button type=\"button\" class=\"btn btn-xs btn-default command-delete remove-row\" data-pk-val=\"" + row.id + "\" data-row-name=\"" + row.name + "\" data-pk=\"id\"><span class=\"fa fa-trash-o\"></span></button>";
							},
							"links": function(column, row)
							{
								return "<a href=\"" + editUrl + "/" + row.id + "\" class=\"btn btn-xs btn-default edit-row noclick\" data-type=\"iframe\"  data-row-name=\"" + row.name + "\" data-pk-val=\"" + row.id + "\"><span class=\"fa fa-pencil\"></span></a>";
							},
							"views": function(column, row)
							{
								return "<a href=''" + row.id + "\" class=\"btn btn-xs btn-default edit-row noclick views\" data-type=\"iframe\"  data-row-name=\"" + row.name + "\" data-pk-val=\"" + row.id + "\"><span class=\"fa fa-search\"></span></a>";
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
			
						}
						 
						
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
							var user_id = "<?=USER_ID?>";
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
						
						selected_row_ids = $("#mybootgrid3").bootgrid("getSelectedRows");
						selected_row_count = stringCount(selected_row_ids + '', ",");
						
						loadFormData3(row_id);
							
				
					}).on("deselected.rs.jquery.bootgrid", function (e, rows) {
						
						var row_id;
						for (var i = 0; i < rows.length; i++) {
							row_id = rows[i].id;
						}
						
						selected_row_ids = $("#mybootgrid3").bootgrid("getSelectedRows");
						selected_row_count = stringCount(selected_row_ids + '', ",");
				
					});
					
				}
				//END LOADGRID3
								
				
				function loadGridHistory(listUrl, editUrl, param_name, param_value, param_name2, param_value2, param_name3, param_value3, param_name4, param_value4, param_name5, param_value5, param_name6, param_value6, param_name7, param_value7, param_name8, param_value8, param_name9, param_value9, param_name10, param_value10, show_search_box, limited_count, count_div, selected_div, multiselect)
				{
					
					var selectedRows;
					var the_row_count;
					if (!limited_count) {
						<?php $the_row_count = "50, 25, 10, 100, -1"; ?>
					} else {
						<?php $the_row_count = "50, 10, 25, 100, -1"; ?>	
					}
					
					/*------------------------------------------ MAIN GRID ------------------------------------------*/
					$("#mybootgrid-history").on("initialize.rs.jquery.bootgrid", function (e) {
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
			
					var grid = $("#mybootgrid-history").bootgrid({
						ajax: true,
						selection:true,
						multiSelect: <?=$show_bootgrid_history_multiple?>,
						keepSelection: true,
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
							if ((param_name5 != "") && (param_value5 != "")) {
								request[param_name5] = param_value5;
							}
							if ((param_name6 != "") && (param_value6 != "")) {
								request[param_name6] = param_value6;
							}
							if ((param_name7 != "") && (param_value7 != "")) {
								request[param_name7] = param_value7;
							}
							if ((param_name8 != "") && (param_value8 != "")) {
								request[param_name8] = param_value8;
							}
							if ((param_name9 != "") && (param_value9 != "")) {
								request[param_name9] = param_value9;
							}
							if ((param_name10 != "") && (param_value10 != "")) {
								request[param_name10] = param_value10;
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
							"views": function(column, row)
							{
								return "<a href=''" + row.id + "\" class=\"btn btn-xs btn-default command-view noclick views\" data-type=\"iframe\" title='View " + row.name + "'  data-row-name=\"" + row.name + "\" data-pk-val=\"" + row.id + "\"><span class=\"fa fa-search\"></span></a>";
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
						}).end().find(".command-view").on("click", function(e)
						{
							e.preventDefault();
							var field_name = $(this).parent().closest('div').data("tbl-pk");
							var field_value = $(this).data("pk-val");
							var full_title = $(this).data("row-name");
							var table_name = $(this).parent().closest('div').data("tbl");
							console.log(field_name + " - " + field_value + " - " + full_title + " - " + table_name);
							//edit record
							//viewRecord(field_name, field_value, table_name, full_title);
						}).end().find(".command-delete").on("click", function(e)
						{						
							var field_name = $(this).parent().closest('div').data("tbl-pk");
							var cat_char = $(this).parent().closest('div').data("cat-char");
							var user_id = "<?=USER_ID?>";
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
						//loadFormData(row_id);
				
					});
					
			}
				
			//JQUERY GRID
			
			
			function stringCount(haystack, needle) {
				if (!needle || !haystack) {
					return false;
				}
				else {
					var words = haystack.split(needle),
						count = {};
					/*for (var i = 0, len = words.length; i < len; i++) {
						if (count.hasOwnProperty(words[i])) {
							count[words[i]] = parseInt(count[words[i]], 10) + 1;
						}
						else {
							count[words[i]] = 1;
						}
					}*/
					//return count;
					return words.length;
				}
				//haystack.split(needle).length;
				
			}
			
			
			function loadFormData(row_id){
				
				loader_div = ".item-details";
				sch_id = $("#school-select").val( );

				load_overlay(loader_div);
				
				<?php 
					
					if ($show_schools_list) { $url = CURRENT_API_PATH . "fetchSchoolGridListing"; } 
					if ($show_students_list && !$show_contacts_list) { $url = CURRENT_API_PATH . "fetchStudentGridListing"; } 
					if ($show_fees_list) { $url = CURRENT_API_PATH . "getFeesGridListing"; } 
					if ($show_subjects_list) { $url = CURRENT_API_PATH . "fetchSubjectGridListing"; }
					if ($show_results_list) { $url = CURRENT_API_PATH . "getResultsGridListing"; }
					if ($show_grading_list) { $url = CURRENT_API_PATH . "fetchScoreGradeGridListing"; }
					if ($show_total_grading_list) { $url = CURRENT_API_PATH . "fetchTotalScoreGradeGridListing"; }
					if ($show_parents_list) { $url = CURRENT_API_PATH . "fetchStudentGridListing"; } 
					if ($show_single_school) { $url = CURRENT_API_PATH . "fetchActivitiesGridListing"; } 
					if ($show_mpesa_inbox) { $url = CURRENT_API_PATH . "fetchMPESAInbox"; } 					 
					
				?>
				
				$.ajax({
					
					url: "<?=$url?>",
					type: "POST",
					data: {'id': row_id, 'sch_id': sch_id, 'user_id': "<?=USER_ID?>", 'admin': "1"},
					dataType: "json",
					success: function(data) {

						//$(loader_div).html("");
						hide_overlay(loader_div);
						
						//prepend only if its not existing
						if (data.error) {
							
							//show error message
							//showQuickMessage(title, message, timeout, type, autoclose)
							showQuickMessage("Error", data.message, <?=MESSAGE_DIALOG_TIMEOUT?>, "error", "yes"); 
							
						}  else {
							
							<?php if ($show_schools_list) { ?>load_school_details_form_data(data);<?php } ?>
							<?php if ($show_students_list && !$show_contacts_list) { ?>load_student_details_form_data(data);<?php } ?>
							<?php if ($show_fees_list) { ?>load_fees_details_form_data(data);<?php } ?>
							<?php if ($show_subjects_list) { ?>load_subject_details_form_data(data);<?php } ?>
							<?php if ($show_results_list) { ?>load_student_results_form_data(data);<?php } ?>
							<?php if ($show_grading_list) { ?>load_score_grades_form_data(data);<?php } ?>
							<?php if ($show_total_grading_list) { ?>load_total_score_grades_form_data(data);<?php } ?>
							<?php if ($show_parents_list) { ?>load_parents_form_data(data);<?php } ?>
							<?php if ($show_single_school) { ?>load_school_activities_form_data(data);<?php } ?>
							<?php if ($show_mpesa_inbox) { ?>load_mpesa_inbox_form_data(data);<?php } ?>
														
						}

					}
					
				});	
				
			}
			
			function loadFormData2(row_id){
				loader_div = ".item-details2";
				sch_id = $("#school-select").val( );
				//console.log(est_id);
				load_overlay(loader_div);
				
				<?php 
					
					if ($show_sms_inbox) { $url = CURRENT_API_PATH . "fetchSMSInbox"; }
					if ($show_parents_sent_sms_list) { $url = CURRENT_API_PATH . "fetchSentSmsGridListing"; } 
					
				?>
				
				$.ajax({
					
					url: "<?=$url?>",
					type: "POST",
					data: {'id': row_id, 'sch_id': sch_id, 'admin': '1', 'user_id': "<?=USER_ID?>"},
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
							
							<?php if ($show_sms_inbox) { ?>load_sms_inbox_form_data(data);<?php } ?>
							<?php if ($show_parents_sent_sms_list) { ?>load_parents_sms_list_form_data(data);<?php } ?>
														
						}

					}
					
				});	
				
			}
			
			function loadFormData3(row_id){
				loader_div = ".item-details3";
				sch_id = $("#school-select").val( );
				//console.log(est_id);
				load_overlay(loader_div);
				
				<?php 
					
					if ($show_sms_outbox) { $url = CURRENT_API_PATH . "fetchSentSmsGridListing"; }
					
				?>
				
				$.ajax({
					
					url: "<?=$url?>",
					type: "POST",
					data: {'id': row_id, 'sch_id': sch_id, 'user_id': "<?=USER_ID?>"},
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
							
							<?php if ($show_sms_outbox) { ?>load_sms_outbox_form_data(data);<?php } ?>
														
						}

					}
					
				});	
				
			}
			
			//load item images
			function loadItemImages(loader_div, item_cat, item_id){

				load_overlay(loader_div);
				$(loader_div).html("");

				$.ajax({
					
					url: "<?=CURRENT_API_PATH?>getItemImagesNew",
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

			
			<?php if ($show_schools_list || $show_single_school) { ?>
			
				function load_school_details_form_data(data){
	
					var id = data.rows[0].id;
					var sch_name = data.rows[0].sch_name;
					var sch_first_name = data.rows[0].sch_first_name;
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
					//console.log(province);
					
					$("#id_edit").val(id);
					$("#id_edit2").val(id);
					$("#sch_name_edit").val(sch_name);
					$("#sch_name_edit2").html(sch_name);
					$("#sch_first_name_edit").val(sch_first_name);
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
					loadItemImages("#item-images", "<?=SCHOOL_PROFILE_PHOTO?>", id)
					
				}
			
			
				function load_school_activities_form_data(data){
	
					var id = data.rows[0].id;
					var name = data.rows[0].name;
					var start_at = data.rows[0].start_at_fmt;
					var end_at = data.rows[0].end_at_fmt;
					var description = data.rows[0].description;
					var venue = data.rows[0].venue;
					
					$("#id_edit2").val(id);
					$("#name_edit2").val(name);
					$("#start_at_edit2").val(start_at);
					$("#end_at_edit2").val(end_at);
					$("#venue_edit2").val(venue);
					$("#description_edit2").val(description);
					
					//hide no results
					$(".no-results2").addClass("hidden");
					
					//unhide hidden data
					$(".item-details2").removeClass("hidden");
					
					//load images data
					$("#item_title2").val(data.rows[0].name);
					$("#category2").val("<?=SCHOOL_ACTIVITY_PHOTO?>");
					$("#category_id2").val(data.rows[0].id);				
					
					//load user images
					loadItemImages("#item-images2", "<?=SCHOOL_ACTIVITY_PHOTO?>", id)
					
				}
			
			<?php } ?>
			
			
			
			<?php if ($show_single_school) { ?>
			
				loadSchoolInfo();
				
				function loadSchoolInfo() {
				
					loader_div = ".form-edit-school";
					load_overlay(loader_div);
					sch_id = $("#school-select").val();	
					params = { "admin": "1", "user_id": "<?=USER_ID?>", "id": sch_id };			
					
					$.ajax({
						url: "<?=CURRENT_API_PATH?>fetchSchoolGridListing",
						type: "POST",
						data: params,
						success: function(data) {
							
							hide_overlay(loader_div);
							
							error = data.error;
							message = data.message;
							
							if (error) {
								
								showQuickMessage("Error", data.message, "", "error", ""); 
								
							} else {
							
								//load school data
								load_school_details_form_data(data);
							
							}
							
						}
						
					});
				
				}				
				//LOAD ACTIVITIES
				
				//load sms inbox list
				loadActivities();
				
				//start_date change
				$(document).on('change','#start_date',function(e){
					e.preventDefault();
					start_date = $(this).val();
					//load data
					loadActivities(start_date);
				});
				
				//end_date change
				$(document).on('change','#end_date',function(e){
					e.preventDefault();
					end_date = $(this).val();
					loadActivities("", end_date);
				}); 
				
				//clear start date
				$(document).on('click','#clear_start_date',function(e){
					e.preventDefault();
					loadActivities();
				});
				
				//clear end date
				$(document).on('click','#clear_end_date',function(e){
					e.preventDefault();
					loadActivities();
				}); 
				
				//FORM FILTER students list
				function loadActivities(start_date, end_date) {
					
					if ($("#mybootgrid")) { $("#mybootgrid").bootgrid("destroy"); }
					
					sch_id = $("#school-select").val();
					if (!start_date) { start_date = $("#start_date").val(); }
					if (!end_date) { end_date = $("#end_date").val(); }
					
					user_id = $("#user_id").val(); 
					admin = $("#admin").val();
										
					//show data 
					listUrl = "<?=CURRENT_API_PATH?>fetchActivitiesGridListing";
					editUrl = "";
					param_name = "sch_id";
					//param_value = "";
					param_value = sch_id;
					param_name2 = "start_date";
					param_value2 = start_date;
					param_name3 = "end_date";
					param_value3 = end_date;
					param_name4 = "user_id";
					param_value4 = user_id;
					param_name5 = "admin";
					param_value5 = "1";
					loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, param_name3, param_value3, param_name4, param_value4, param_name5, param_value5);
	
				}
				
				//END LOAD ACTIVITITES
			
			<?php } ?>
			
			
			<?php if ($show_mpesa_inbox) { ?>
												
				//load on bootgrid select
				function load_mpesa_inbox_form_data(data){
	
					var id = data.rows[0].id;
					var name = data.rows[0].name;
					var received_at_fmt = data.rows[0].received_at_fmt;
					var mpesa_code = data.rows[0].mpesa_code;
					var paybill_no = data.rows[0].paybill_no;
					var sender_no = data.rows[0].sender_no;
					var amount_fmt2 = data.rows[0].amount_fmt2;
					var student_full_names = data.rows[0].student_full_names;
					var reg_no = data.rows[0].reg_no;
					var current_class = data.rows[0].current_class;
					var stream = data.rows[0].stream;
					
					//assign values to fields
					$("#id_edit").val(id);
					$("#client_names_edit").html(name);
					$("#sender_no_edit").html(sender_no);
					$("#mpesa_code_edit").html(mpesa_code);
					$("#amount_edit").html(amount_fmt2);
					$("#received_edit").html(received_at_fmt);
					$("#reg_no_edit").html(reg_no);
					$("#student_full_names_edit").html(student_full_names);
					$("#current_class_edit").html(current_class + " " + stream);
					
					//hide no results
					$(".no-results").addClass("hidden");
					
					//unhide hidden data
					$(".item-details").removeClass("hidden");
					
				}
				
				//load sms inbox list
				loadMpesaInbox();
				
				//start_date change
				$(document).on('change','#start_date',function(e){
					e.preventDefault();
					start_date = $(this).val();
					//load data
					loadMpesaInbox(start_date);
				});
				
				//end_date change
				$(document).on('change','#end_date',function(e){
					e.preventDefault();
					end_date = $(this).val();
					loadMpesaInbox("", end_date);
				}); 
				
				//clear start date
				$(document).on('click','#clear_start_date',function(e){
					e.preventDefault();
					loadMpesaInbox();
				});
				
				//clear end date
				$(document).on('click','#clear_end_date',function(e){
					e.preventDefault();
					loadMpesaInbox();
				}); 
				
				//FORM FILTER students list
				function loadMpesaInbox(start_date, end_date) {
					
					if ($("#mybootgrid")) { $("#mybootgrid").bootgrid("destroy"); }
					
					sch_id = $("#sch_id").val();
					if (!start_date) { start_date = $("#start_date").val(); }
					if (!end_date) { end_date = $("#end_date").val(); }
					
					user_id = $("#user_id").val(); 
					admin = $("#admin").val();
					
					var loader_div = "#page-container";
					load_overlay(loader_div);
										
					//check whether paybill data exists
					$.ajax({
						url: "<?=CURRENT_API_PATH?>isPaybillValid",
						type: "POST",
						data: { "sch_id": sch_id, "admin": "1", "user_id": "<?=USER_ID?>" },
						dataType: "json",
						success: function(data) {
							
							hide_overlay(loader_div);
							
							error = data.error;
							message = data.message;
							
							//paybill data exists, load grid data
							if (!error){
	
								//show data 
								listUrl = "<?=CURRENT_API_PATH?>fetchMPESAInbox";
								editUrl = "";
								param_name = "sch_id";
								//param_value = "";
								param_value = sch_id;
								param_name2 = "start_date";
								param_value2 = start_date;
								param_name3 = "end_date";
								param_value3 = end_date;
								param_name4 = "user_id";
								param_value4 = user_id;
								param_name5 = "admin";
								param_value5 = "1";
								loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, param_name3, param_value3, param_name4, param_value4, param_name5, param_value5);
								
								//hide overlay div
								$(".overlay-div").addClass("hidden");
								
							} else {
								
								//call error bootstrap message
								showQuickMessage("Error", message, <?=MESSAGE_DIALOG_TIMEOUT_LONG?>, "error", "");
								
								//disable div plus all descendants
								//$("#mpesa-container *").prop('disabled',true);
								$("#mpesa-container").find("*").prop("disabled", true);
								
								//show overlay div
								$(".overlay-div").removeClass("hidden");
									
								
							}
							
						}
					});  
					
					
	
				}
				
			<?php } ?>
			
			
			<?php if ($show_sms_inbox) { ?>
								
				//load on bootgrid select
				function load_sms_inbox_form_data(data){
	
					if (data) {
						
						var id = data.rows[0].id;
						var name = data.rows[0].name;
						var que_date_fmt = data.rows[0].que_date_fmt;
						var msg_text = data.rows[0].msg_text;
						var level = data.rows[0].level;
						//console.log(data);
						
						$("#id_edit").val(id);
						$("#source_edit").html(name);
						$("#phone_number_edit").val(name);
						$("#que_date_edit").html(que_date_fmt);
						$("#message_edit").html(msg_text);
						$("#sch_level_edit").val(level);
						
						//hide no results
						$(".no-results2").addClass("hidden");
						
						//unhide hidden data
						$(".item-details2").removeClass("hidden");
					
					}
					
				}
				
				//load sms inbox list
				loadSMSInbox();
				
				//start_date change
				$(document).on('change','#start_date',function(e){
					e.preventDefault();
					start_date = $(this).val();
					//load data
					loadSMSInbox(start_date);
				});
				
				//end_date change
				$(document).on('change','#end_date',function(e){
					e.preventDefault();
					end_date = $(this).val();
					loadSMSInbox("", end_date);
				}); 
				
				//clear start date
				$(document).on('click','#clear_start_date',function(e){
					e.preventDefault();
					loadSMSInbox();
				});
				
				//clear end date
				$(document).on('click','#clear_end_date',function(e){
					e.preventDefault();
					loadSMSInbox();
				}); 
				
				//FORM FILTER students list
				function loadSMSInbox(start_date, end_date) {
					
					if ($("#mybootgrid2")) { $("#mybootgrid2").bootgrid("destroy"); }
					
					sch_id = $("#sch_id").val();
					if (!start_date) { start_date = $("#start_date").val(); }
					if (!end_date) { end_date = $("#end_date").val(); }
					
					user_id = $("#user_id").val(); 
					admin = $("#admin").val();
										
					//show data 
					listUrl = "<?=CURRENT_API_PATH?>fetchSMSInbox";
					editUrl = "";
					param_name = "sch_id";
					//param_value = "";
					param_value = sch_id;
					param_name2 = "start_date";
					param_value2 = start_date;
					param_name3 = "end_date";
					param_value3 = end_date;
					param_name4 = "user_id";
					param_value4 = user_id;
					param_name5 = "admin";
					param_value5 = "1";
					loadGrid2(listUrl,editUrl,param_name, param_value, param_name2, param_value2, param_name3, param_value3, param_name4, param_value4, param_name5, param_value5);
	
				}
				
			<?php } ?>
			
			
			
			<?php if ($show_sms_outbox) { ?>
								
				//load on bootgrid select
				function load_sms_outbox_form_data(data){
	
					if (data) {
						
						var id = data.rows[0].id;
						var name = data.rows[0].phone_number;
						var que_date_fmt = data.rows[0].created_at;
						var msg_text = data.rows[0].msg_text;
						var status_text = data.rows[0].status_text;
						//console.log(data);
						
						$("#id_edit_3").val(id);
						$("#source_edit_3").html(name);
						$("#phone_number_edit_3").val(name);
						$("#que_date_edit_3").html(que_date_fmt);
						$("#message_edit_3").html(msg_text);
						$("#status_edit_3").html(status_text);
						
						//hide no results
						$(".no-results3").addClass("hidden");
						
						//unhide hidden data
						$(".item-details3").removeClass("hidden");
					
					}
					
				}
				
				//load sms inbox list
				loadSMSOutbox();
				
				//start_date change
				$(document).on('change','#start_date_3',function(e){
					e.preventDefault();
					start_date = $(this).val();
					//load data
					loadSMSOutbox(start_date);
				});
				
				//end_date change
				$(document).on('change','#end_date_3',function(e){
					e.preventDefault();
					end_date = $(this).val();
					loadSMSOutbox("", end_date);
				}); 
				
				//clear start date
				$(document).on('click','#clear_start_date_3',function(e){
					e.preventDefault();
					loadSMSOutbox();
				});
				
				//clear end date
				$(document).on('click','#clear_end_date_3',function(e){
					e.preventDefault();
					loadSMSOutbox();
				}); 
				
				//FORM FILTER students list
				function loadSMSOutbox(start_date, end_date) {
					
					if ($("#mybootgrid3")) { $("#mybootgrid3").bootgrid("destroy"); }
					
					sch_id = $("#sch_id").val();
					if (!start_date) { start_date = $("#start_date").val(); }
					if (!end_date) { end_date = $("#end_date").val(); }
					
					user_id = $("#user_id").val(); 
					admin = $("#admin").val();
										
					//show data 
					listUrl = "<?=CURRENT_API_PATH?>fetchSentSmsGridListing";
					editUrl = "";
					param_name = "sch_id";
					param_value = sch_id;
					param_name2 = "start_date";
					param_value2 = start_date;
					param_name3 = "end_date";
					param_value3 = end_date;
					param_name4 = "user_id";
					param_value4 = user_id;
					param_name5 = "admin";
					param_value5 = "1";
					loadGrid3(listUrl,editUrl,param_name, param_value, param_name2, param_value2, param_name3, param_value3, param_name4, param_value4, param_name5, param_value5);
	
				}
				
			<?php } ?>
				
											
			<?php if ($show_sms_textbox) { ?>
				
				//HANDLE SMS MESSAGES
				var $remaining = $('#remaining'),
					$messages = $('#messages');
				
				//bulk sms
				$('#sms_message').keyup(function(){
					var chars = this.value.length,
						messages = Math.ceil(chars / 160),
						remaining = messages * 160 - (chars % (messages * 160) || messages * 160);
					$remaining.text(remaining);
					$messages.text(messages);
				});
				
				var $remaining2 = $('#remaining2'),
					$messages2 = $('#messages2');
				//send single sms
				$('#sms_message2').keyup(function(){
					var chars = this.value.length,
						messages2 = Math.ceil(chars / 160),
						remaining2 = messages2 * 160 - (chars % (messages2 * 160) || messages2 * 160);
					$remaining2.text(remaining2);
					$messages2.text(messages2);
				});
				//END HANDLE SMS MESSAGES
								
				
			<?php } ?>
			
			
			<?php if ($show_selected_items) { ?>
			
				function load_bulk_sms_details_form_data(selected_row_ids, selected_row_count){
						
					if (!selected_row_count){ selected_row_count = "0"; }
					
					$("#users_selected").html(selected_row_count);
					$("#selected").val(selected_row_ids);
					
					if ($("#selected_student_ids").length) {
						$("#selected_student_ids").val(selected_row_ids);
					}
					//alert(selected_row_ids);
					
				}
			
			<?php } ?>
			
			
			<?php if ($show_bulk_sms) { ?>
				
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
				
				//check checkbox select
				$('#enter_phone_numbers').change(function() {
					
					if($(this).is(":checked")) {
						
						//show enter numbers div
						$('#enter_numbers_div').removeClass("hidden");
						//uncheck bootstap selected items
						$("#mybootgrid").bootgrid("deselect");
						$("#users_selected").html("0");
						$("#selected").val("");
						
						//hide query box
						$("#sms-query").addClass("hidden");
						//hide student list
						$("#contactsHeight2").addClass("hidden");
						
						//auto select memo radio
						$("#memo").prop("checked", true);
						
						//set title
						$("#messageTypeTitle").html("Send Message");
						
						//hide message select radios
						$("#messageTypeRadios").addClass("hidden");
						
						//show memo fields, hide others
						 $("#memo-fields").removeClass("hidden");
						 $("#results-fields").addClass("hidden");
						 $("#fees-fields").addClass("hidden");
						
						
					} else {
						
						//hide enter numbers div
						$('#enter_numbers_div').addClass("hidden");
						$("#enter_contacts_field").val("");
						
						//show query box
						$("#sms-query").removeClass("hidden");
						//show student list
						$("#contactsHeight2").removeClass("hidden");
						
						//set title
						$("#messageTypeTitle").html("Select Type of Message To Send");
						
						//show message select radios
						$("#messageTypeRadios").removeClass("hidden");
						
					}
					
				});
				
				//handle keypress on enter contacts field				
				/*$('#enter_contacts_field').on("input", function() {
					var dInput = this.value;
					console.log(dInput);
					//$(".dDimension:contains('" + dInput + "')").css("display","block");
				});
				
				$(document).keypress(function(e) {
					if(e.which == 13) {
						alert('You pressed enter!');
					}
				});
				*/
				
				//execute after a delay of 1250ms of no keypress
				$('#enter_contacts_field').keyup(debounce(function(){				
					
					var dInput = $(this).val();
					updateMessageCount(dInput);
								
				}, 1250, false));
				
				//detect copy pasted text
				$("#enter_contacts_field").bind('paste', function() {
					
					var dInput = $(this).val();
					updateMessageCount(dInput);
					
				});
				
				function updateMessageCount(inputData) {
					
					var strings;
					input = cleanText(inputData); //remove last comma or whitespace
					//if( input.indexOf(',') != -1 ){ //find commas
					//}
					
					if (input) {
						//strings = input.split(',');
						//strings = input.split(/[\s,]+/);
						strings = input.split(/[\ \n\,\s]+/);
						strlen = strings.length;
						//update contacts number field
						$("#users_selected").html(strlen);
					} else {
						$("#users_selected").html("0");	
					}				
						
				}
				
				function cleanText(inputData){
					input = inputData.replace(/,\s*$/, ""); //remove last comma or whitespace
					return input;
				}


				//load students list
				loadStudentsList();
				
				//load sms balance
				//loadSchoolSMSBalance(sch_id);
				
				//offer change
				$(document).on('change','#current_class',function(e){
					e.preventDefault();
					current_class = $(this).val();
					//load data
					loadStudentsList();
				});
				
				//client_id change
				$(document).on('change','#stream',function(e){
					e.preventDefault();
					stream = $(this).val();
					loadStudentsList("", stream);
				}); 
				
				
				//FORM FILTER students list
				function loadStudentsList(current_class, stream, student_ids) {
					
					if ($("#mybootgrid")) { $("#mybootgrid").bootgrid("destroy"); }
					
					sch_id = $("#school-select").val();
					if (!student_ids) { student_ids = $("#student_ids").val(); }
					if (!current_class) { current_class = $("#current_class").val(); }
					if (!stream) { stream = $("#stream").val(); }
					
					user_id = $("#user_id").val(); 
					admin = $("#admin").val();
										
					//show data 
					listUrl = "<?=CURRENT_API_PATH?>fetchStudentGridListing";
					editUrl = "";
					param_name = "sch_id";
					param_value = sch_id;
					param_name2 = "student_ids";
					param_value2 = student_ids;
					param_name3 = "current_class";
					param_value3 = current_class;
					param_name4 = "stream";
					param_value4 = stream;
					param_name5 = "user_id";
					param_value5 = user_id;
					param_name6 = "admin";
					param_value6 = "1";
					loadGrid(listUrl,editUrl,param_name, param_value, param_name2, param_value2, param_name3, param_value3, param_name4, param_value4, param_name5, param_value5, param_name6, param_value6);
	
				}
				
									
			<?php } ?>
						
			
				
				<?php if ($show_students_list) { ?>
					
					function load_student_details_form_data(data){
			
							var id = data.rows[0].id;
							var full_names = data.rows[0].full_names;
							var reg_no = data.rows[0].reg_no;
							var index_no = data.rows[0].index_no;
							var dob = data.rows[0].dob;
							var admin_date = data.rows[0].admin_date;
							var nationality = data.rows[0].nationality;
							var religion = data.rows[0].religion;
							var current_class = data.rows[0].current_class;
							var stream = data.rows[0].stream;
							var house = data.rows[0].house;
							var club = data.rows[0].club;
							var disability = data.rows[0].disability;
							var gender = data.rows[0].gender;
							var previous_school = data.rows[0].previous_school;
							var guardian_name = data.rows[0].guardian_name;
							var guardian_address = data.rows[0].guardian_address;
							var guardian_phone = data.rows[0].guardian_phone;
							var guardian_occupation = data.rows[0].guardian_occupation;
							var guardian_id_card = data.rows[0].guardian_id_card;
							var guardian_relation = data.rows[0].guardian_relation;
							var constituency = data.rows[0].constituency;
							var email = data.rows[0].email;
							var county = data.rows[0].county;
							var town = data.rows[0].town;
							var location = data.rows[0].location;
							var village = data.rows[0].village;
							var student_profile = data.rows[0].student_profile;
							
							$("#id_edit").val(id);
							$("#full_names_edit").val(full_names);
							$("#reg_no_edit").val(reg_no);
							$("#index_no_edit").val(index_no);
							$("#dob_edit").val(dob);
							$("#admin_date_edit").val(admin_date);
							$("#nationality_edit").val(nationality);
							$("#religion_edit").val(religion);
							$("#current_class_edit").val(current_class);
							$("#stream_edit").val(stream);
							$("#house_edit").val(house);
							$("#club_edit").val(club);
							$("#disability_edit").val(disability);
							$("#gender_edit").val(gender);
							$("#previous_school_edit").val(previous_school);
							$("#guardian_name_edit").val(guardian_name);
							$("#guardian_address_edit").val(guardian_address);
							$("#guardian_phone_edit").val(guardian_phone);
							$("#guardian_occupation_edit").val(guardian_occupation);
							$("#guardian_id_card_edit").val(guardian_id_card);
							$("#guardian_relation_edit").val(guardian_relation);
							$("#constituency_edit").val(constituency);
							$("#email_edit").val(email);
							$("#county_edit").val(county);
							$("#town_edit").val(town);
							$("#location_edit").val(location);
							$("#village_edit").val(village);
							$("#student_profile_edit").val(student_profile);
						
							//hide no results
							$(".no-results").addClass("hidden");
							
							//unhide hidden data
							$(".item-details").removeClass("hidden");
							
							//load images data
							$("#item_title").val(full_names);
							$("#category").val("<?=STUDENT_PROFILE_PHOTO?>");
							$("#category_id").val(data.rows[0].id);				
							
							//load user images
							loadItemImages("#item-images", "<?=STUDENT_PROFILE_PHOTO?>", id)
							
							//load student item history
							load_student_history(id);
							
							function load_student_history(id)
							{
								
								//load student history grid
								listUrl = "<?=CURRENT_API_PATH?>fetchStudentHistory";
								editUrl = "";
								param_name = "id";
								param_value = id;
								param_name2 = "admin";
								param_value2 = "1";
								param_name3 = "user_id";
								param_value3 = "<?=USER_ID?>";
														
								//$("#grid").bootgrid("destroy").bootgrid("search", "serach phrase");
								if ($("#mybootgrid-history")) { $("#mybootgrid-history").bootgrid("destroy"); }
								
								loadGridHistory(listUrl,editUrl,param_name, param_value, param_name2, param_value2, param_name3, param_value3, "", "");
															
							}	
							
						}
					
				<?php } ?>
				
				
				
				<?php if ($show_subjects_list) { ?>
				
					function load_subject_details_form_data(data){
		
						//"id":13,"name":"Chemistry","short_name":"Chem","code":"che","level_id":1,"level":
						//"Secondary School","status":1,"status_name":"Active"
 
						var id = data.rows[0].id;
						var name = data.rows[0].name;
						var short_name = data.rows[0].short_name;
						var code = data.rows[0].code;
						var level_id = data.rows[0].level_id;
						var level = data.rows[0].level;
						var status = data.rows[0].status;
						
						$("#id_edit").val(id);
						$("#subject_name_edit").val(name);
						$("#short_name_edit").val(short_name);
						$("#code_edit").val(code);
						$("#level_edit").val(level_id);
						$("#status_edit").val(status);						
											
						//hide no results
						$(".no-results").addClass("hidden");
						
						//unhide hidden data
						$(".item-details").removeClass("hidden");
						
						//console.log("id - " + id);
						
						//load fee payment item history
						load_subject_history(id);
						
					}
					
					function load_subject_history(id)
					{
						
						//load fees payments history grid
						listUrl = "<?=CURRENT_API_PATH?>fetchSubjectHistory";
						editUrl = "";
						param_name = "id";
						param_value = id;
						param_name2 = "admin";
						param_value2 = "1";
						param_name3 = "user_id";
						param_value3 = "<?=USER_ID?>";
												
						//$("#grid").bootgrid("destroy").bootgrid("search", "serach phrase");
						$("#mybootgrid-history").bootgrid("destroy");
						
						loadGridHistory(listUrl,editUrl,param_name, param_value, param_name2, param_value2, param_name3, param_value3, "", "");
													
					}
									
				<?php } ?>
				
				
				
				<?php if ($show_parents_sent_sms_list) { ?>
				
					function load_parents_sms_list_form_data(data){
 
						if (data) {
						
							var id = data.rows[0].id;
							var name = data.rows[0].phone_number;
							var que_date_fmt = data.rows[0].created_at;
							var msg_text = data.rows[0].msg_text;
							var status_text = data.rows[0].status_text;
							//console.log(data);
							
							$("#id_edit").val(id);
							$("#source_edit").html(name);
							$("#phone_number_edit").val(name);
							$("#que_date_edit").html(que_date_fmt);
							$("#message_edit").html(msg_text);
							$("#status_edit").html(status_text);
							
							//hide no results
							$(".no-results").addClass("hidden");
							
							//unhide hidden data
							$(".item-details").removeClass("hidden");
						
						}
												
					}
							
				<?php } ?>
				
				
				<?php if ($show_total_grading_list) { ?>
				
					function load_total_score_grades_form_data(data){
 
						var id = data.rows[0].id;
						var min = data.rows[0].min;
						var max = data.rows[0].max;
						var grade = data.rows[0].grade;
						var points = data.rows[0].points;
						var level_id = data.rows[0].level_id;
						var level = data.rows[0].level;
						
						$("#id_edit").val(id);
						$("#min_edit").val(min);
						$("#max_edit").val(max);
						$("#grade_edit").val(grade);
						$("#points_edit").val(points);
						$("#level_edit").val(level_id);
											
						//hide no results
						$(".no-results").addClass("hidden");
						
						//unhide hidden data
						$(".item-details").removeClass("hidden");
												
						//load total score grade item history
						load_total_score_grade_history(id);
						
					}
					
					function load_total_score_grade_history(id)
					{
						
						//load fees payments history grid
						listUrl = "<?=CURRENT_API_PATH?>fetchTotalScoreGradeHistory";
						editUrl = "";
						param_name = "id";
						param_value = id;
						param_name2 = "admin";
						param_value2 = "1";
						param_name3 = "user_id";
						param_value3 = "<?=USER_ID?>";
												
						$("#mybootgrid-history").bootgrid("destroy");
						
						loadGridHistory(listUrl,editUrl,param_name, param_value, param_name2, param_value2, param_name3, param_value3, "", "");
													
					}
									
				<?php } ?>
				
				
				<?php if ($show_grading_list) { ?>
				
					function load_score_grades_form_data(data){
 
						var id = data.rows[0].id;
						var min = data.rows[0].min;
						var max = data.rows[0].max;
						var grade = data.rows[0].grade;
						var points = data.rows[0].points;
						var level_id = data.rows[0].level_id;
						var level = data.rows[0].level;
						
						$("#id_edit").val(id);
						$("#min_edit").val(min);
						$("#max_edit").val(max);
						$("#grade_edit").val(grade);
						$("#points_edit").val(points);
						$("#level_edit").val(level_id);
											
						//hide no results
						$(".no-results").addClass("hidden");
						
						//unhide hidden data
						$(".item-details").removeClass("hidden");
												
						//load score grade item history
						load_score_grade_history(id);
						
					}
					
					function load_score_grade_history(id)
					{
						
						//load fees payments history grid
						listUrl = "<?=CURRENT_API_PATH?>fetchScoreGradeHistory";
						editUrl = "";
						param_name = "id";
						param_value = id;
						param_name2 = "admin";
						param_value2 = "1";
						param_name3 = "user_id";
						param_value3 = "<?=USER_ID?>";
												
						//$("#grid").bootgrid("destroy").bootgrid("search", "serach phrase");
						$("#mybootgrid-history").bootgrid("destroy");
						
						loadGridHistory(listUrl,editUrl,param_name, param_value, param_name2, param_value2, param_name3, param_value3, "", "");
													
					}
									
				<?php } ?>

	
		//});
		
	});
	
	

    </script>
