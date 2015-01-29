<?php
	$json = file_get_contents("card_data.json");
	$cards = json_decode($json, true);
	$json = file_get_contents("pack_data.json");
	$packs = json_decode($json, true);
	$reports = array(
		"Lure" => 0,
		"Dispose" => 0,
		"Anarchy" => 0,
		"Maintain" => 0,
		"draw" => 0,
		"umph" => 0,
		"replace" => 0
	);

	foreach ($packs["packs"] as $pid => $pack){
		foreach ($pack as $row){
			list($id, $amt, $cost, $worth) = $row;
			$card = $cards["cards"][$id];
			foreach ($reports as $key => $val){
				if (stripos($card["text"], $key) !== false){
					++$reports[$key];
				}
			}
		}
	}

	var_dump($reports);

	$qm = 0;
	$grid = array();
	foreach (array("V", "T", "A", "R") as $clan){
		$grid_row = array();
		foreach (array("I", "S", "P", "J") as $type){
			$grid_cell = array();
			$pack = $packs["packs"]["$clan$type"];
			foreach ($pack as $row){
				list($id, $amt, $cost, $worth) = $row;
				if ($id != "?"){
					$card = $cards["cards"][$id];
					$grid_cell[$id] = $card;
				} else {
					$grid_cell["?$qm"] = array("name"=>"MISSING", "text"=>"MISSING");
					++$qm;
				}
			}

			$grid_row["$clan$type"] = $grid_cell;
		}

		$grid []= $grid_row;
	}

	function codeToText($code){
		$texts = array(
			"V" => "Invaders",
			"T" => "Tweens",
			"A" => "Artificial",
			"R" => "Republicans",
			"I" => "Icons",
			"S" => "Suits",
			"P" => "Products",
			"J" => "Jovians"
		);

		$clan = $texts[$code[0]];
		$type = $texts[$code[1]];
		return "$clan | $type";
	}
?>
<table style="border-collapse:collapse">
	<?php foreach ($grid as $grid_row): ?>
	<tr>
		<?php foreach ($grid_row as $code => $grid_cell): ?>
		<td style="border:1px solid #000;vertical-align:top;width:25%">
			<p style="border-bottom:1px solid #000"><b><code>
				<?php echo codeToText($code); ?>
			</code></b></p>
			<?php foreach ($grid_cell as $id => $card): ?>
			<p><b><code>
				<?php echo $card["name"]=="?" ? $id : $card["name"]; ?>
			</code></b></p>
			<p><i><code>
				<?php echo $card["note"]; ?>
			</code></i></p>
			<p><code>
				<?php echo $card["text"]; ?>
			</code></p>
			<?php endforeach; ?>
		</td>
		<?php endforeach; ?>
	</tr>
	<?php endforeach; ?>
</table>