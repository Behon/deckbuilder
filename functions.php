<?php
	function saveState($state, $meta, $gameid){
		$data = "";
		foreach ($state as $id => $card){
			if ($data != ""){
				$data .= "\n";
			}

			$zone = $card["zone"];
			$pos  = $card["pos"];
			$data .= "$id\t$zone\t$pos";
			if (isset($card["disposed"]) && $card["disposed"]!=""){
				$target = $card["disposed"];
				$data .= "\t$target";
			}
		}

		foreach ($meta as $key => $val){
			$data .= "\n:META\t$key\t$val";
		}

		$file = "gamestates/$gameid.csv";
		file_put_contents($file, $data);
	}

////////////////////////////////////////////////////////////////////////////////

	function initState($gameid, $da, $db){
		if (file_exists("gamestates/$gameid.csv")) return true;

		$packs_json = file_get_contents("pack_data.json");
		if ($packs_json === false) die("{}");
		$packs = json_decode($packs_json, true)["packs"];
		$pack_ids = array_keys($packs);
		shuffle($pack_ids);
		$state = array();
		$decks = array(
			"v" => array("VS", "VP", "VJ"),
			"t" => array("TI", "TP", "TJ"),
			"a" => array("AI", "AS", "AJ"),
			"r" => array("RI", "RS", "RP"),
			"i" => array("TI", "AI", "RI"),
			"s" => array("VS", "AS", "RS"),
			"p" => array("VP", "TP", "RP"),
			"j" => array("VJ", "TJ", "AJ")
		);

		if (in_array($da, array_keys($decks))){
			$pack_ids[0] = $decks[$da][0];
			$pack_ids[1] = $decks[$da][1];
			$pack_ids[2] = $decks[$da][2];
		}

		if (in_array($db, array_keys($decks))){
			$pack_ids[3] = $decks[$db][0];
			$pack_ids[4] = $decks[$db][1];
			$pack_ids[5] = $decks[$db][2];
		}

		// Starting Decks
		for ($i=0; $i < 7; ++$i){
			$state["PUNCH-$i"] = array("zone"=>"a.deck", "pos"=>$i);
		}

		for ($i=7; $i < 10; ++$i){
			$state["VULN-$i"] = array("zone"=>"a.deck", "pos"=>$i);
		}

		for ($i=10; $i < 17; ++$i){
			$state["PUNCH-$i"] = array("zone"=>"b.deck", "pos"=>$i-10);
		}

		for ($i=17; $i < 20; ++$i){
			$state["VULN-$i"] = array("zone"=>"b.deck", "pos"=>$i-10);
		}

		// Kicks
		for ($i=0; $i < 50; ++$i){
			$state["KICK-$i"] = array("zone"=>"kicks", "pos"=>$i);
		}

		// Loot Decks
		$pos = 0;
		$pid = "a";
		for ($p=0; $p < 6; ++$p){
			if ($p == 3){
				$pos = 0;
				$pid = "b";
			}

			foreach ($packs[$pack_ids[$p]] as $card_row){
				list($id, $amt, $cost, $points) = $card_row;
				if ($id != "?"){
					for ($x=0; $x < $amt; ++$x){
						$state["$id-$x"] = array("zone"=>"$pid.loot", "pos"=>$pos);
						++$pos;
					}
				}
			}
		}

		$meta = array();

		saveState($state, $meta, $gameid);
		return true;
	}

////////////////////////////////////////////////////////////////////////////////

	function loadState($gameid){
		$file = "gamestates/$gameid.csv";
		$csv = file_get_contents($file);
		if ($csv === false) die("{}");
		$rows = explode("\n", $csv);
		$state = array();
		$meta = array();
		foreach ($rows as $row){
			$cols = explode("\t", $row);
			if (count($cols) == 3){
				list($id, $zone, $pos) = $cols;
				$target = false;
			} else {
				list($id, $zone, $pos, $target) = $cols;
			}

			if ($id == ":META"){
				$meta[$zone] = $pos;
			} else if ($id !== "?"){
				$state[$id] = array();
				$state[$id]["zone"] = $zone;
				$state[$id]["pos"] = $pos;
				if ($target){
					$state[$id]["disposed"] = $target;
				}
			}
		}

		return array($state, $meta);
	}

////////////////////////////////////////////////////////////////////////////////

	function echoState($state, $meta, $playerid){
		$packs_json = file_get_contents("pack_data.json");
		$cards_json = file_get_contents("card_data.json");
		if ($packs_json === false || $cards_json === false) die("{}");
		$packs = json_decode($packs_json, true);
		$cards = json_decode($cards_json, true)["cards"];
		$players = array("a" => "op", "b" => "me");
		if ($playerid == "a"){
			$players = array("a" => "me", "b" => "op");
		}

		$data = array(
			"t" => microtime(true),
			"meta" => $meta,
			"op" => array(
				"hand" => array(),
				"discard" => array(),
				"played" => array(),
				"loot" => array(),
				"deck" => array(),
			),
			"me" => array(
				"hand" => array(),
				"discard" => array(),
				"played" => array(),
				"loot" => array(),
				"deck" => array(),
			),
			"lineup" => array(),
			"kicks" => array(),
			"side" => array()
		);

		foreach ($state as $id => $card){
			$core_id = strstr($id, "-", true);
			foreach ($packs["packs"] as $type => $pack){
				foreach ($pack as $card_row){
					list($subid, $amt, $cost, $points) = $card_row;
					if ($subid == $core_id){
						$element = array(
							"id" => $id,
							// "name" => ($cards[$core_id]["name"] == "?") ? $id : $cards[$core_id]["name"],
							"cost" => $cost,
							"type" => $type,
							"points" => str_repeat("*", $points),
							// "text" => $cards[$core_id]["text"],
							"dispose" => isset($card["disposed"]) && $card["disposed"]!="",
							"target" => isset($card["disposed"]) && $card["disposed"]!=""? $card["disposed"] : ""
						);

						if ($card["zone"][1] == "."){
							$player = $players[$card["zone"][0]];
							$data[$player][substr($card["zone"], 2)][$card["pos"]] = $element;
						} else {
							$data[$card["zone"]][$card["pos"]] = $element;
						}
					}
				}
			}

			foreach ($packs["basic"] as $card_row){
				list($subid, $cost, $points) = $card_row;
				if ($subid == $core_id){
					$element = array(
						"id" => $id,
						// "name" => $cards[$core_id]["name"],
						"cost" => $cost,
						"type" => "",
						"points" => str_repeat("*", $points),
						// "text" => $cards[$core_id]["text"],
						"dispose" => isset($card["disposed"]) && $card["disposed"]!="",
						"target" => isset($card["disposed"]) && $card["disposed"]!=""? $card["disposed"] : ""
					);

					if ($card["zone"][1] == "."){
						$player = $players[$card["zone"][0]];
						$data[$player][substr($card["zone"], 2)][$card["pos"]] = $element;
					} else {
						$data[$card["zone"]][$card["pos"]] = $element;
					}
				}
			}
		}

		ksort($data["me"]["hand"]);
		$data["me"]["hand"] = array_values($data["me"]["hand"]);
		ksort($data["me"]["discard"]);
		$data["me"]["discard"] = array_values($data["me"]["discard"]);
		ksort($data["me"]["played"]);
		$data["me"]["played"] = array_values($data["me"]["played"]);
		ksort($data["me"]["loot"]);
		$data["me"]["loot"] = array_values($data["me"]["loot"]);
		ksort($data["me"]["deck"]);
		$data["me"]["deck"] = array_values($data["me"]["deck"]);

		ksort($data["op"]["hand"]);
		$data["op"]["hand"] = array_values($data["op"]["hand"]);
		ksort($data["op"]["discard"]);
		$data["op"]["discard"] = array_values($data["op"]["discard"]);
		ksort($data["op"]["played"]);
		$data["op"]["played"] = array_values($data["op"]["played"]);
		ksort($data["op"]["loot"]);
		$data["op"]["loot"] = array_values($data["op"]["loot"]);
		ksort($data["op"]["deck"]);
		$data["op"]["deck"] = array_values($data["op"]["deck"]);

		ksort($data["lineup"]);
		$data["lineup"] = array_values($data["lineup"]);
		ksort($data["kicks"]);
		$data["kicks"] = array_values($data["kicks"]);
		ksort($data["side"]);
		$data["side"] = array_values($data["side"]);

		$json = json_encode($data);
		echo $json;
	}
?>