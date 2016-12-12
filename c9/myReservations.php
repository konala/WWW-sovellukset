<?php


?>
<script>
// Execute AJAJ-statement to get user's reservations from the database
$(document).ready(function(){
	$("#myRes").css("display", "none");
	var body = document.getElementById("myResBody");
	var request = $.ajax({
		url: "fetchMyRes.php",
		type: "get",
		data: {
			"user_id": <?php echo $_SESSION["user"]?>
		},
		dataType: "html",
		success: function(data) {
			var text = "";
			var parsed = JSON.parse(data);
			//Fill the table with data from database
			$.each(parsed["reservations"], function(i, item) {
				$.each(parsed["fields"], function(i, item2) {
					if (item["field_id"] == item2["id"]) {
						text += "<tr id='ROW" + item["id"] + "'><td>" + item2["name"] + "</td><td>" + item["startTime"] + "-" + item["endTime"] + "</td><td>" + item["res_date"] + "</td><td><button class='delOwn' res_id='" + item["id"] + "'>Peru</button></td></tr>";
					}
				});
				
			});
			body.insertAdjacentHTML('beforeend', text);
		}
	});
	$("#myRes").css("display", ""); //Show the fresh table to user

	//Removing reservation with AJAJ
	$(document).on("click", ".delOwn", function() {
		var res_id = $(this).attr("res_id"); //Reservation id is stored in delete button attributes
		var request2 = $.ajax({
			url: "deleteRes.php",
			type: "get",
			data: {
				"res_id": res_id
			},
			dataType: "html",
			success: function() {
				$("#ROW" + res_id).remove();
			}
		});
	});
});

</script>
<!-- Table skeleton -->
<div id="my-reservations-div">
<table id="myRes">
	<caption>Omat varaukset</caption>
	<tbody id="myResBody">
		<tr><th>Kenttä</th><th>Aika</th><th>Päivämäärä</th><th>Muokkaa</th></tr>
	</tbody>
</table>
</div>