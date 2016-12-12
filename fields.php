<?php
//Field object to take care of displaying fields
class Field {
	private $rows;


	//Get field data from database
	public function __construct() {
		// Open database connection
		try {
			$db = new PDO('mysql:host=localhost;dbname=football;charset=utf8', 'www', 'www');
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
				<td class='fieldTable-lastCol'><button class='weather'>Sää</button><button class='map'>Kartta</button></td>
				</tr>
END;
		
			}
			?> </table></div> <?php
		}

	public function getRows() {
		return $this->rows;
	}
		
		
		
	}


?>

