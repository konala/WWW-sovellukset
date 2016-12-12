//Small screen menu button functionality
$(document).ready(function() {
	var subMenu = $(".navigation");
		//Clicking menu button opens up the whole menu
		$("#menuButton").on("click", function(e) {
			e.stopPropagation();
			subMenu.fadeToggle({
				duration: 200
			});
		});
	
	changeMenu();
	//On window resize check if menu style needs to be changed
	$(window).resize(function() {
		changeMenu();
	});
	function changeMenu() {
		var windowWidth = $(window).width();
		//Create menu for small screen
		if (windowWidth <= 800 && !($("#menuButton").attr("visible"))) {
			$("#menuButton").attr("visible", "visible");
			$("#menuButton").css("display", "");
			$(".navigation").css("display", "none");
		//Create menu for big screen
		} else if (windowWidth > 800 && $("#menuButton").attr("visible")){
			$("#menuButton").removeAttr("visible");
			$("#menuButton").css("display", "none");
			$(".navigation").css("display", "");
		}
		
	}
});

