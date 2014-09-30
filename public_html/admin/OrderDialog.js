(function() {


	var _ = self.OrderDialog = (function() {
	
		var dialog = document.getElementById("dialogOrder"),
		
		open = function(orderId) {
			console.log("open order", orderId);
			dialog.showModal();		
		};
	
	
	
	
		return {
			open: open	
		}
	}());



}());