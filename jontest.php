<?php
	require_once "functions.php";
	$gameid = $_POST["g"];
	$playerid = $_POST["p"];
	$decka = $_POST["da"];
	$deckb = $_POST["db"];
	if (initState($gameid, $decka, $deckb)):
?>
<!DOCTYPE html>
<html ng-app>
	<head>
		<link rel="stylesheet" href="style.css" />
		<script type="text/javascript">
			var GAMEID = "<?php echo $gameid; ?>";
			var PLAYERID = "<?php echo $playerid; ?>";
			var OPPONENTID = "<?php echo $playerid=='a'?'b':'a'; ?>";
		</script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.21/angular.min.js"></script>
		<script type="text/javascript" src="ng.js"></script>
	</head>
	<body ng-controller="NGCtrl" ng-keypress="handleKey($event)">
		<div id="opponent">
			<div class="loot">
				<div class="cardback" ng-show="!op.loot.revealed">
					Loot Deck: {{op.loot.length}}
				</div>
			</div>
			<div class="discard">
				<div class="cardback">
					Discard: {{op.discard.length}}
				</div>
			</div>
			<div class="playdeck">
				<div class="cardback" ng-show="!op.deck.revealed">
					Play Deck: {{op.deck.length}}
				</div>
			</div>
			<div class="hand">
				<div class="cardback">
					Hand: {{op.hand.length}}
				</div>
			</div>
			<div class="played">
				<div class="cardback" ng-repeat="c in op.played|filter:{dispose: true}">
					Disposing {{c.target}}!
				</div>
				<div class="cardfront" ng-repeat="c in op.played|filter:{dispose: false}">
					<p class="top">
						({{c.cost}})
						{{c.points}}
						<span style="float:right">{{c.type}}</span>
					</p>
					<p class="topbox">
					</p>
					<p class="middle">
						{{getName(c)}}
					</p>
					<p class="bottombox">
						{{getText(c)}}
						<br />
						{{getLuck(c)}}
					</p>
					<p class="bottom">
					</p>
				</div>
			</div>
		</div>
		<div id="middle">
			<div id="kicks" ng-click="handleClick(kicks[0], 'Kick Stack')"  ng-class="{target: kicks[0].id==targetID}">
				<div class="cardback" ng-show="!kicks.revealed">
					(3)
					<br />
					+2 Umph
					<br />
					Kicks: {{kicks.length}}
				</div>
				<div class="cardfront" ng-show="kicks.revealed">
					<p class="top">
						({{kicks[0].cost}})
						{{kicks[0].points}}
						<span style="float:right">{{kicks[0].type}}</span>
					</p>
					<p class="topbox">
					</p>
					<p class="middle">
						{{getName(kicks[0])}}
					</p>
					<p class="bottombox">
						{{getText(kicks[0])}}
						<br />
						{{getLuck(kicks[0])}}
					</p>
					<p class="bottom">
					</p>
				</div>
			</div>
			<div id="lineup">
				<div class="cardfront" ng-repeat="c in lineup" ng-click="handleClick(c, c.id, 'd')" ng-class="{target: c.id==targetID}">
					<p class="top">
						({{c.cost}})
						{{c.points}}
						<span style="float:right">{{c.type}}</span>
					</p>
					<p class="topbox">
					</p>
					<p class="middle">
						{{getName(c)}}
					</p>
					<p class="bottombox">
						{{getText(c)}}
						<br />
						{{getLuck(c)}}
					</p>
					<p class="bottom">
					</p>
				</div>
			</div>
		</div>
		<div id="me">
			<div class="played">
				<div class="cardback" ng-repeat="c in me.played|filter:{dispose: true}" ng-click="handleClick(c, c.id, 'd')" ng-class="{target: c.id==targetID}">
					Disposing {{c.target}}!
				</div>
				<div class="cardfront" ng-repeat="c in me.played|filter:{dispose: false}" ng-click="handleClick(c, c.id, 'd')" ng-class="{target: c.id==targetID}">
					<p class="top">
						({{c.cost}})
						{{c.points}}
						<span style="float:right">{{c.type}}</span>
					</p>
					<p class="topbox">
					</p>
					<p class="middle">
						{{getName(c)}}
					</p>
					<p class="bottombox">
						{{getText(c)}}
						<br />
						{{getLuck(c)}}
					</p>
					<p class="bottom">
					</p>
				</div>
			</div>
			<div class="loot" ng-click="handleClick(me.loot[0], 'Loot Deck', 'l')" ng-class="{target: me.loot[0].id==targetID}">
				<div class="cardback" ng-show="!me.loot.revealed">
					Loot Deck: {{me.loot.length}}
				</div>
			</div>
			<div class="discard" ng-click="handleClick(me.discard[0], 'Discard Pile')" ng-class="{target: me.discard[0].id==targetID}">
				<div class="cardback">
					Discard: {{me.discard.length}}
				</div>
			</div>
			<div class="playdeck" ng-click="handleClick(me.deck[0], 'Play Deck', '1')" ng-class="{target: me.deck[0].id==targetID}">
				<div class="cardback" ng-show="!me.deck.revealed">
					Play Deck: {{me.deck.length}}
				</div>
			</div>
			<div class="hand">
				<div class="cardfront" ng-repeat="c in me.hand" ng-click="handleClick(c, c.id, 'p')" ng-class="{target: c.id==targetID}">
					<p class="top">
						({{c.cost}})
						{{c.points}}
						<span style="float:right">{{c.type}}</span>
					</p>
					<p class="topbox">
					</p>
					<p class="middle">
						{{getName(c)}}
					</p>
					<p class="bottombox">
						{{getText(c)}}
						<br />
						{{getLuck(c)}}
					</p>
					<p class="bottom">
					</p>
				</div>
			</div>
		</div>
		<div id="side" ng-show="side.length>0">
			<div class="cardfront" ng-repeat="c in side" ng-click="handleClick(c, c.id)" ng-class="{target: c.id==targetID}">
				<p class="top">
					({{c.cost}})
					{{c.points}}
					<span style="float:right">{{c.type}}</span>
				</p>
				<p class="topbox">
				</p>
				<p class="middle">
					{{getName(c)}}
				</p>
				<p class="bottombox">
					{{getText(c)}}
					<br />
					{{getLuck(c)}}
				</p>
				<p class="bottom">
				</p>
			</div>
		</div>
		<div id="meta">
			<b>Current Turn:</b> <select ng-model="meta.turn" ng-change="resolveAction('meta', 'turn', meta.turn)"><option>A</option><option>B</option></select>
			<b>Score A:</b> <input type="number" min="0" max="50" ng-model="meta.score_a" ng-change="resolveAction('meta', 'score_a', meta.score_a)" />
			<b>Score B:</b> <input type="number" min="0" max="50" ng-model="meta.score_b" ng-change="resolveAction('meta', 'score_b', meta.score_b)" />
		</div>
		<div id="menu" ng-show="menu_open">
			<ul id="menu-ul">
				<li><b>{{targetLabel}}</b></li>
				<li ng-click="resolveActionAndClose('into', targetID, PLAYERID+'.hand')">Draw/Move to Hand (1/5)</li>
				<li ng-click="resolveActionAndClose('into', targetID, PLAYERID+'.played')">Put into Play (P)</li>
				<li ng-click="resolveActionAndClose('into', targetID, PLAYERID+'.discard')">Discard/Buy/Gain (D)</li>
				<li ng-click="resolveActionAndClose('into', targetID, 'lineup')">Add to the Line Up (L/])</li>
				<li ng-click="resolveActionAndClose('into', targetID, 'side')">Reveal(R)</li>
				<li ng-click="resolveActionAndClose('shuffleAll', targetID, PLAYERID+'.discard')">Put Area into Discard (DEL)</li>
				<li ng-click="resolveActionAndClose('bot', targetID, PLAYERID+'.loot')">Put on Bottom of Loot Deck (B)</li>
				<li ng-click="resolveActionAndClose('top', targetID, PLAYERID+'.loot')">Put on Top of Loot Deck</li>
				<li ng-click="resolveActionAndClose('top', targetID, PLAYERID+'.deck')">Put on Top of Play Deck</li>
				<li ng-click="resolveActionAndClose('top', targetID, OPPONENTID+'.loot')">Put on Top of Opponent's Loot Deck</li>
				<li ng-click="resolveActionAndClose('top', targetID, 'kicks')">Put on Top of Kick Deck</li>
				<li ng-click="resolveActionAndClose('bot', targetID, PLAYERID+'.deck')">Put on Bottom of Play Deck</li>
				<li ng-click="resolveActionAndClose('bot', targetID, 'kicks')">Put on Bottom of Kick Deck</li>
				<li ng-click="resolveActionAndClose('shuffle', targetID, PLAYERID+'.deck')">Shuffle into Play Deck</li>
				<li ng-click="resolveActionAndClose('shuffle', targetID, PLAYERID+'.loot')">Shuffle into Loot Deck</li>
				<li ng-click="resolveActionAndClose('shuffle', targetID, 'kicks')">Shuffle into Kick Deck</li>
				<li ng-click="resolveActionAndClose('shuffleAll', targetID, PLAYERID+'.played')">Put Area into Play</li>
				<li ng-click="resolveActionAndClose('shuffleAll', targetID, 'side')">Reveal Area</li>
				<li ng-click="resolveActionAndClose('shuffleAll', targetID, PLAYERID+'.deck')">Shuffle Area onto Bottom of Play Deck</li>
				<li ng-click="resolveActionAndClose('shuffleAll', targetID, PLAYERID+'.loot')">Shuffle Area onto Bottom of Loot Deck</li>
				<li ng-click="resolveActionAndClose('shuffleAll', targetID, 'kicks')">Shuffle Area onto Bottom of Kick Deck</li>
				<li ng-click="resolveActionAndClose('dispose', targetID, t.id)" ng-repeat="t in lineup">Dispose onto {{t.id}}</li>
			</ul>
		</div>
	</body>
</html>
<?php
	else:
?>
<h1>Failed to initialize the game. Please feed me a stray cat.</h1>
<?php
	endif;
?>