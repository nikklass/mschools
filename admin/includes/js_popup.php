
<script type="text/javascript" language="javascript">
				
		jQuery(document).ready(function($){
			
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
					
							action: function(dialogRef){
								dialogRef.close();
							}
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
						}]
						
					});
					dialog.open();
					if (autoclose == "yes") {
						setTimeout(function() {
							dialog.close();
						}, timeout);
					}
				}
			
				
	
});

</script>