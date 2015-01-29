function NGCtrl ($scope, $http){
	$scope.PLAYERID = PLAYERID;
	$scope.OPPONENTID = OPPONENTID;
	$scope.GAMEID = GAMEID;
	// $scope.in_resolve = false;
	// $scope.pending_resolve = [];
	
	$scope.t = 0;
	$scope.meta = {};
	$scope.op = {deck:[], hand: [], loot:[], discard:[], played:[], faggot: true};
	$scope.me = {deck:[], hand: [], loot:[], discard:[], played:[]};
	$scope.kicks = [];
	$scope.lineup = [];
	$scope.side = [];
	$scope.card_data = {};

	$scope.targetCard = {};
	$scope.targetID = "";
	$scope.targetLabel = "";
	$scope.menu_open = false;

	////////////////////////////////////////////////////////////////////////////

	var needs_init = true;
	function initBoard(){
		needs_init = false;
		var deck_id = $scope.me.deck[0].id;
		var loot_id = $scope.me.loot[0].id;
		var kick_id = $scope.kicks[0].id;
		$scope.resolveAction("shuffleAll", deck_id, PLAYERID+".deck");
		$scope.resolveAction("shuffleAll", loot_id, PLAYERID+".loot");
		if (PLAYERID == 'a') $scope.resolveAction("shuffleAll", kick_id, "kicks");
		$scope.resolveAction("meta", PLAYERID+".init", "done");
	}

	setInterval(function(){
		console.log("Attempting to poll");
		// console.log($scope.pending_resolve);
		// if ($scope.pending_resolve.length > 0) {
		// 	var pr = $scope.pending_resolve.shift();
		// 	$scope.resolveAction(pr[0], pr[1], pr[2]);
		// } else {
			$scope.resolveAction("poll");
		// }
	}, 3000);

	$http.get("card_data.json").
	success(function(data, status, headers, config){
		console.log(data, status, headers, config);
		$scope.card_data = data;
	}).error(function(data, status, headers, config){
		console.log("Something went wrong");
	});

	$scope.resolveAction = function(action, id1, id2){
		// if ($scope.in_resolve) {
		// 	console.log("resolveAction failed - Setting timeout to retry", action, id1, id2);
		//	$scope.pending_resolve.push([action, id1, id2]);
		// 	setTimeout(function() {
		// 		$scope.resolveAction(action, id1, id2);
		// 	}, 400);
		// 	return;
		// }
		// $scope.in_resolve = true;
		$http.get("brownfox.php?g="+GAMEID+"&p="+PLAYERID+"&a="+action+"&v1="+id1+"&v2="+id2).
		success(function(data, status, headers, config){
			// console.log(data, status, headers, config);
			// $scope.in_resolve = false;
			if (data.t > $scope.t){
				for (field in data){
					$scope[field] = data[field];
				}

				$scope.meta.score_a = parseInt($scope.meta.score_a);
				$scope.meta.score_b = parseInt($scope.meta.score_b);

				if ($scope.meta[PLAYERID+".init"] !== "done" && needs_init){
					initBoard();
				}
			}
		}).error(function(data, status, headers, config){
			// $scope.in_resolve = false;
			console.log(data, status, headers, config);
			console.log("Something went wrong");
		});
	}

	$scope.resolveAction("poll");

	////////////////////////////////////////////////////////////////////////////

	$scope.resolveActionAndClose = function(action, id1, id2){
		$scope.resolveAction(action, id1, id2);
		$scope.targetCard = {};
		$scope.targetID = "";
		$scope.targetLabel = "";
		$scope.menu_open = false;
	};

	var last_time = 0;
	$scope.handleClick = function(card, label, deflt){
		$scope.targetCard = card;
		$scope.targetID = card.id;
		$scope.targetLabel = label;
		$scope.menu_open = true;
		var this_time = (new Date).getTime();
		if (this_time - last_time <= 300){
			$scope.handleKey({keyCode: deflt.charCodeAt()});
			last_time = 0;
		} else {
			last_time = this_time;
		}
	};

	$scope.handleKey = function($event){
		var keycode = $event.keyCode;
		console.log("handleKey", $event, keycode, String.fromCharCode(keycode));
		var key = String.fromCharCode(keycode).toLowerCase();
		if (keycode === 127 && $scope.targetID !== ""){ // delete
			$scope.resolveActionAndClose("shuffleAll", $scope.targetID, PLAYERID+".discard");
		} else if (keycode === 32 && $scope.me.hand.length > 0){ // space
			$scope.resolveActionAndClose("shuffleAll", $scope.me.hand[0].id, PLAYERID+".played");
		} else if (key === "5" && $scope.me.deck.length > 0){ // 5 (number row)
			$scope.resolveActionAndClose("into5", $scope.me.deck[0].id, PLAYERID+".hand");
		} else if (key === "h" && $scope.targetID !== ""){ // h
			$scope.resolveActionAndClose("into", $scope.targetID, PLAYERID+".hand");
		} else if (key === "b" && $scope.targetID !== ""){ // b
			$scope.resolveActionAndClose("bot", $scope.targetID, PLAYERID+".loot");
		} else if (key === "d" && $scope.targetID !== ""){ // d
			$scope.resolveActionAndClose("into", $scope.targetID, PLAYERID+".discard");
		} else if (key === "r" && $scope.targetID !== ""){ // r
			$scope.resolveActionAndClose("into", $scope.targetID, "side");
		} else if (key === "l" && $scope.targetID !== ""){ // l
			$scope.resolveActionAndClose("into", $scope.targetID, "lineup");
		} else if (key === "p" && $scope.targetID !== ""){ // p
			$scope.resolveActionAndClose("into", $scope.targetID, PLAYERID+".played");
		} else if (key === "=" && $scope.me.discard.length > 0){ // =
			$scope.resolveActionAndClose("shuffleAll", $scope.me.discard[0].id, PLAYERID+".deck");
		} else if (key === "1" && $scope.me.deck.length > 0){ // 1 (number row)
			$scope.resolveActionAndClose("into", $scope.me.deck[0].id, PLAYERID+".hand");
		} else if (key === "]" && $scope.me.loot.length > 0){ // 1 (number row)
			$scope.resolveActionAndClose("into", $scope.me.loot[0].id, "lineup");
		} else if (key === "a" && $scope.targetID !== ""){ // 1 (number row)
			alert($scope.getText($scope.targetCard));
		} else {
			return;
		}

		$event.preventDefault();
	}

	////////////////////////////////////////////////////////////////////////////

	$scope.getName = function(card){
		var id = card.id.substring(0, card.id.indexOf("-"));
		return $scope.card_data.cards[id].name==="?"? id : $scope.card_data.cards[id].name;
	};

	$scope.getText = function(card){
		var id = card.id.substring(0, card.id.indexOf("-"));
		return $scope.card_data.cards[id].text;
	};

	$scope.getNumber = function(num){
		return new Array(num);
	};

	$scope.getLuck = function(card){
		var id = card.id.substring(0, card.id.indexOf("-"));
		if (id == "KICK"){
			return "[" + (parseInt(card.id.substring(card.id.indexOf("-")+1)) % 5 + 1) + "]";
		} else {
			return "";
		}
	};
}