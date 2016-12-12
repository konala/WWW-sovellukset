<?php
/* Contains the following functionality: Choosing a field to reserve, printing calendar with reservations, button functionality */
?>
<!-- Combobox for fields -->
<div id="fieldResDiv">
	<select id="selectField">
		<?php 
			//Fill the combobox with field values from our database
			$tmp = new Field();
			$tmp2 = $tmp->getRows();
			foreach ($tmp2 as $key => $value) {
				print <<<END
				<option value="{$value["id"]}">{$value["name"]}</option>
END;
			}
		?>
	</select>
	<button id="showResButton" onclick="showRes()" type="button">Näytä</button>
	<button id="helpButton" type="button">?</button>
	<div id="helpDiv" title="Symbolien selitykset"></div>
	<div id="loading"></div>
</div>
<script>
	//Print calendar
	function initializeCal() {
		$("#res").empty(); //Empty any data from previous visits
		var resTable = document.getElementById("res");
		resTable.innerHTML = "<tbody id='resTable'><tr id='dateRow'><th></th></tr></tbody>";
	}

	function showRes() {
		initializeCal();
		var select = document.getElementById("selectField");
		var id = select.options[select.selectedIndex].value;
		var resTable = document.getElementById("res");
		var resTableBody = document.getElementById("resTable");
		var dateRow = document.getElementById("dateRow");
		var tmp = new Date();
		var text = "";
		var dates = "";
		var colCounter = 1;
		var rowCounter = 1;

		//Initialize table data rows and row headers, assign unique ids for every cell
		for (var i = 8; i < 23; i++) {
			text += "<tr><th>" + i + ":00</th><td class='td' id='"+ (colCounter++) + "-" + (rowCounter) + "'><button class='view'><img src='mg.ico'></button><button class='tick'><img src='tick.svg'></button></td><td class='td' id='"+ (colCounter++) + "-" + (rowCounter) + "'><button class='view'><img src='mg.ico'></button><button class='tick'><img src='tick.svg'></button></td><td class='td' id='"+ (colCounter++) + "-" + (rowCounter) + "'><button class='view'><img src='mg.ico'></button><button class='tick'><img src='tick.svg'></button></td><td class='td' id='"+ (colCounter++) + "-" + (rowCounter) + "'><button class='view'><img src='mg.ico'></button><button class='tick'><img src='tick.svg'></button></td><td class='td' id='"+ (colCounter++) + "-" + (rowCounter) + "'><button class='view'><img src='mg.ico'></button><button class='tick'><img src='tick.svg'></button></td><td class='td' id='"+ (colCounter++) + "-" + (rowCounter) + "'><button class='view'><img src='mg.ico'></button><button class='tick'><img src='tick.svg'></button></td><td class='td' id='"+ (colCounter++) + "-" + (rowCounter) + "'><button class='view'><img src='mg.ico'></button><button class='tick'><img src='tick.svg'></button></td></tr>";
			colCounter = 1;
			rowCounter++;
		}
		colCounter = 1;
		//Initialize table column headers: 7 days starting from the current date
		for (var a = 0; a < 7; a++) {
			currDate = (("0" + tmp.getDate()).slice(-2) + "-" + ("0" + (tmp.getMonth()+1)).slice(-2) + "-" + tmp.getFullYear());
			dates += "<th id='"+ colCounter +"'>" + currDate + "</th>";
			tmp.setDate(tmp.getDate() + 1);
			colCounter++;
		}
		//Add the html
		resTableBody.insertAdjacentHTML('beforeend',text);
		dateRow.insertAdjacentHTML('beforeend',dates);
	} 

	$(document).ready(function(){

		//Hide the uninitialized calendarview
		$("#res").css("display", "none");
		//Help button functionality
		$("#helpButton").click(function() {
			console.log("click");
			$("#helpDiv").html("<img src='mg.ico'> Tietoa varauksesta</br><img src='tick.svg'> Varaa kenttä</br><img src='lockv2.svg' style='width: 20px; height: 20px;'> Toisen käyttäjän varaama vuoro</br><img src='del.svg'> Poista oma varaus");
			$("#helpDiv").dialog({
				buttons: {
					Sulje: function() {$(this).dialog("close");}
				},
				height: 270,
				width: 300,
				show: {
					effect: "blind",
					duration: 300
				},
				hide: {
					effect: "blind",
					duration: 300
				},
				resizable: false,
				modal: true

			});
		});
		//Show the current reservations on selected field
		$("#showResButton").click(function(){

			var field_id = $("#selectField").val();
			var startRow;
			var endRow;
			var col;
			var cell_ids = [];
			var parsedDate;
			var tmp;
			var cell;
			var user;
			var reservation_id;

			$("#loading").html("Ladataan kalenteria...");
			$("#loading").css("display", "");
			//Some AJAJ to get reservations from database
			var request = $.ajax({
				url: "fetchReservations.php",
				type: "get",
				data: {
					"field_id": field_id
				},
				dataType: "html",
				success: function(data) {
					//Calculate cell ids
					$.each(JSON.parse(data), function(i, item){
						reservation_id = item["id"]
						startRow = item.startTime-7;
						endRow = item.endTime-8;
						tmp = item.res_date.substring(8, 10);
						parsedDate = tmp + "-";
						tmp = item.res_date.substring(5, 8);
						parsedDate += tmp;
						tmp = item.res_date.substring(0, 4);
						parsedDate += tmp;
						user = item["user_id"];
						//Find the correct column
						$("th").each(function(index){
							if ($(this).text() == parsedDate) {
								col = $(this).attr("id");
							}
						});
						
						//Create array of all cells that have to be added
						for (var k = startRow; k < (endRow+1); k++) {
								var cell_id = col + "-" + k;
								cell_ids.push({
									id: cell_id,
									user: user
								});
						}
				
						//Add the cells with correct styling
						$.each(cell_ids, function(i, value){
							cell = $("#"+value["id"]);
							//Current user hasn't made the reservation
							if (value["user"] != <?php echo $_SESSION["user"]?>) {
								cell.html("<button class='view'><img src='mg.ico'></button><img class='lock' src='lockv2.svg' style='width: 40px; height: 20px;' >");
							//Current user has made the reservation himself
							} else {
								cell.html("<button class='view'><img src='mg.ico'></button><button class='delete'><img src='del.svg'></button>");
								cell.attr('own', 'own');
							}
							//Add some attributes to the cells for later use
							cell.attr('reserved', 'reserved');
							cell.attr('user', value["user"]);
							cell.attr('res_id', reservation_id);
							
						});

					});
				}
			});
			$("#loading").css("display", "none");
			$("#res").css("display", ""); //Finally display the calendar for user
		});
		//Tick button functionality
		$(document).on("click", ".tick", function(){
			var cell = $(this).closest("td").prop("id"); //Find the closest td
			cell = $("#"+cell);
			//Cell is already reserved, let user know
			if (typeof cell.attr("reserved") !== typeof undefined && cell.attr("reserved") !== false) {
				$("#feedback").html("Kenttä on jo varattu valitsemaasi aikaan!");
			//Cell is not reserved
			} else {
				//Some data parsing so database insert is possible
				var startTime = cell.attr("id").substring(2, 4);
				startTime = parseInt(startTime) + 7;
				var endTime = startTime+1;
				var field_id = $("#selectField").val();
				var tmp2 = cell.attr("id");
				tmp2 = tmp2.substring(0,1);
				var tmp_date = $("#"+tmp2).text();
				tmp2 = tmp_date.substring(6,10);
				tmp2 += tmp_date.substring(2,5);
				tmp2 += "-" + tmp_date.substring(0,2);

				//Database insert
				var request2 = $.ajax({
					url: "makeReservation.php",
					type: "get",
					data: {
						"res_date": tmp2,
						"startTime": startTime,
						"endTime": endTime,
						"field_id": field_id
					},
					dataType: "html",
					success: function(data) {
						var encodedData = JSON.parse(data);
						var text = encodedData["reservation"];
						var userId = encodedData["user_id"];
						var res_id = encodedData["reservation_id"];


						var feedback = data;
						feedback = feedback.slice(0, -3);
						user = data.slice(data.length - 3, data.length);
						$("#feedback").html(text);
						$("#feedback").dialog({
							buttons: {
								OK: function() {$(this).dialog("close");}
							},
							height: 270,
							width: 300,
							show: {
								effect: "blind",
								duration: 300
							},
							hide: {
								effect: "blind",
								duration: 300
							},
							resizable: false,
							modal: true
						});
						//Change the tick button to delete button after reservation
						cell.html("<button class='view'><img src='mg.ico'></button><button class='delete'><img src='del.svg'></button>");
						cell.attr('reserved', 'reserved');
						cell.attr('user', userId);
						cell.attr('res_id', res_id);
						cell.attr('own', 'own');
						
					
					}
				});
			}
		});

		//Magnifying glass -button functionality
		$(document).on("click", ".view", function() {
			var user_id;
			var cell = $(this).closest("td").prop("id");
			cell = $("#"+cell);
			var attr = cell.attr("user");
			if (typeof attr !== typeof undefined && attr !== false) {
				user_id = attr;
			}
			//If user is is defined
			if (typeof(user_id) != "undefined" && user_id !== null) {
				//Get the organization name with AJAJ
				var request = $.ajax({
					url: "get.php",
					type: "post",
					data: {
						"type": "1",
						"user": user_id
					},
					dataType: "html",
						success: function(data) {
							data = JSON.parse(data);
							$("#info").html("<p>Varannut seura/organisaatio: " + data[0]["organization"] + "</p>");
							$("#info").dialog({
								buttons: {
									OK: function() {$(this).dialog("close");}
								},
								height: 270,
								width: 300,
								show: {
									effect: "blind",
									duration: 300
								},
								hide: {
									effect: "blind",
									duration: 300
								},
								resizable: false,
								modal: true
							});
						}
				});
			//If user id isn't defined there is no reservation
			} else {
				$("#info").html("<p> Vuoroa ei ole vielä varattu!");
				$("#info").dialog({
					buttons: {
									OK: function() {$(this).dialog("close");}
								},
					height: 270,
					width: 300,
					show: {
						effect: "blind",
						duration: 300
					},
					hide: {
						effect: "blind",
						duration: 300
					},
					resizable: false,
					modal: true
				});
			}
			user_id = null;

		});

		//Delete button functionality
		$(document).on("click", ".delete", function() {
			var res_id = $(this).closest("td").attr("res_id");
			console.log("res_id: " + res_id);
			var cell_id = $(this).closest("td").prop("id");
			var cell = $(this).closest("td");
			var row = cell_id[cell_id.length];
			var col = cell_id.slice(0, -2);
			//AJAJ request to delete the correct reservation
			var request3 = $.ajax({
				url: "deleteRes.php",
				type: "get",
				data: {
					"res_id": res_id
				},
				dataType: "html",
				//On success, replace delete button with a tick
				success: function(data) {
					cell.html("<button class='view'><img src='mg.ico'></button><button class='tick'><img src='tick.svg'></button>");
					cell.removeAttr("reserved");
					cell.removeAttr("user");
					cell.removeAttr("res_id");
					cell.removeAttr("own");
					$("#feedback").html("Varaus peruttu!");
					$("#feedback").dialog({
						buttons: {
							OK: function() {$(this).dialog("close")}
						},
						height: 270,
						width: 300,
						show: {
							effect: "blind",
							duration: 300
						},
						hide: {
							effect: "blind",
							duration: 300
						},
						resizable: false,
						modal: true
					});
				}
			});
		});
	});
</script>
<!-- Table skeleton and divs for feedback dialogs -->
<h1> </h1>
<div id="feedback" title="Varaus"></div>
<div id="reservation-table-div"><table id="res"></table></div>
<div id="info" title="Info"></div>

<!-- Dialog styling -->
<style type="text/css">
	.ui-widget-header, .ui-state-default, ui-button {
			background: #b9cd6d;
            border: 1px solid #b9cd6d;
            color: #FFFFFF;
           
	}
</style>