<?php
	require_once "functions.php";

	// zone names:
	// lineup, kicks, side, a.played, b.played, a.deck, b.deck, a.loot, b.loot, a.discard, b.discard, a.hand, b.hand
	function resolveAction($state, $action, $args){
		$id = $args[0];
		$old_pos = $state[$id]["pos"];
		$old_zone = $state[$id]["zone"];
		$new_zone = $args[1];
		$auto_zone = "Get in the Zone.";

		if ($action == "dispose"){
			$new_zone = substr($old_zone, 0, 2) . "played";
			$state[$id]["disposed"] = $args[1];
		} else {
			$state[$id]["disposed"] = "";
		}

		$old_cards = array();
		$new_cards = array();
		if ($old_zone == $new_zone){
			$cards = array();
			foreach ($state as $subid => $card){
				if ($card["zone"] == $old_zone){
					$cards[$card["pos"]] = $subid;
				}
			}

			ksort($cards);
			$cards = array_values($cards);
			$old_cards = $cards;
			$new_cards = $cards;
		} else {
			foreach ($state as $subid => $card){
				if ($card["zone"] == $old_zone){
					$old_cards[$card["pos"]] = $subid;
				} else if ($card["zone"] == $new_zone){
					$new_cards[$card["pos"]] = $subid;
				}
			}

			ksort($old_cards);
			ksort($new_cards);
			$old_cards = array_values($old_cards);
			$new_cards = array_values($new_cards);
		}

		if ($action == "bot" || $action == "into" || $action == "dispose"){ // stick on end
			unset($old_cards[$old_pos]);
			$old_cards = array_values($old_cards);
			$new_cards []= $id;
		} else if ($action == "into5"){ // stick it and up to 4 after it on end
			for ($i=0; $i < 5 && isset($old_cards[$old_pos+$i]); ++$i){
				$new_cards []= $old_cards[$old_pos+$i];
				unset($old_cards[$old_pos+$i]);
			}
			
			$old_cards = array_values($old_cards);
		} else if ($action == "top"){ // stick at beginning
			unset($old_cards[$old_pos]);
			$old_cards = array_values($old_cards);
			array_unshift($new_cards, $id);
		} else if ($action == "shuffle"){ // shuffle into
			/*unset($old_cards[$old_pos]);
			$old_cards = array_values($old_cards);
			$new_pos = rand(0, count($new_cards));
			array_splice($new_cards, $new_pos, 0, $id);*/
			unset($old_cards[$old_pos]);
			$old_cards = array_values($old_cards);
			$new_cards []= $id;
			shuffle($new_cards);
		} else if ($action == "shuffleAll"){ // shuffle neighbors and stick on end
			shuffle($old_cards);
			if ($old_zone != $new_zone){
				$new_cards = array_merge($new_cards, $old_cards);
				$old_cards = array();
			} else {
				$new_cards = $old_cards;
			}
		}

		foreach ($old_cards as $pos => $subid){
			$state[$subid]["zone"] = $old_zone;
			$state[$subid]["pos"]  = $pos;
		}

		foreach ($new_cards as $pos => $subid){
			$state[$subid]["zone"] = $new_zone; 
			$state[$subid]["pos"]  = $pos;
		}

		return $state;
	}

	$action = $_GET["a"];
	$gameid = $_GET["g"];
	$playerid = $_GET["p"];
	$args = array($_GET["v1"], $_GET["v2"]);

	list($state, $meta) = loadState($gameid);
	if ($action == "poll"){
		// do nothing
	} else if ($action == "meta"){
		$meta[$args[0]] = $args[1];
		saveState($state, $meta, $gameid);
	} else {
		$state = resolveAction($state, $action, $args);
		saveState($state, $meta, $gameid);
	}

	echoState($state, $meta, $playerid);
?>