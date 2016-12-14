<?php
//Field object to take care of displaying fields
class Field {
	private $rows;


	//Get field data from database
	public function __construct() {
		//Establish database connection
		$servername = getenv('IP');
		$username = getenv('C9_USER');
		$password = "";
		$database = "c9";
		try {
		    $db = new PDO('mysql:host=' . $servername . ';dbname=' . $database . ';charset=utf8', $username, '');
		} catch (PDOException $e) {
		    echo "Connection failed: " . $e->getMessage();
		}
		// Create and execute statement
		$stmt = $db->prepare("SELECT * FROM field;");
		$stmt->execute();
		$this->rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function createFieldTable() {
		?>
		<!-- Create table of db query results-->
		<div id="fields-table-div">
		<div id="map" title="Kartta" style="display: none; width: 100%; height: 400px; padding: 10px;"></div>
		<table id="fieldTable">
			<tr>
				<th>Kenttä</th>
				<th>Osoite</th>
				<th>Valinnat</th>
			</tr>
			<?php
			foreach ($this->rows as $key => $value) {
				print <<<END
				<tr> 
				<td class='fieldTable-firstCol'>{$value["name"]}</td>
				<td class='fieldTable-midCol'>{$value["address"]}</td>
				<td class='fieldTable-lastCol'><button address='{$value["address"]}' class='map'>Kartta</button></td>
				</tr>
END;
		
			}
			?> </table></div> 
			<!-- Google Maps API -->
			<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqLPGIbYEdPwKysM_9CryKfmoPXbu_6E8">
			</script>

			<!-- Dialog styling -->
			<style type="text/css">
				.ui-widget-header, .ui-state-default, ui-button {
						background: #b9cd6d;
			            border: 1px solid #b9cd6d;
			            color: #FFFFFF;
			           
				}
			</style>
			<script>
				$(document).ready(function(){
					//Open map when button is clicked
					$(document).on("click", ".map", function() { 
						$("#map").html("");
						var address = $(this).attr("address").replace(/ /g, "+");
						var url = "https://maps.googleapis.com/maps/api/geocode/json?address=" + address + "&key=AIzaSyBqLPGIbYEdPwKysM_9CryKfmoPXbu_6E8";
						//Geocode the address of the field
						var request = $.getJSON(url, function(data) {
							var status = data["status"];
							if (status == "OK") {
								var lat = data["results"][0]["geometry"]["location"]["lat"];
								var lng = data["results"][0]["geometry"]["location"]["lng"];
								var coord = {lat: lat, lng: lng};
								//Create map with coordinates
								var map = new google.maps.Map(document.getElementById('map'), {
										zoom: 15,
										center: coord
								});
								//Create marker for the map
								var marker = new google.maps.Marker({
									position: coord,
									map: map
								});
							} else {
								$("#map").html("Sijaintia ei löytynyt.");
							}
							
						});
							//Open dialog with the map
							$("#map").dialog({
								buttons: {
									Sulje: function() {$(this).dialog("close")}
								},
								height: 500,
								width: 400,
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
				});
			</script>
			<?php
		}

	public function getRows() {
		return $this->rows;
	}
		
		
		
	}


?>

