<form action="jontest.php" method="post" onsubmit="return confirm('Do you agree to (1) not disclose any information about the game, its creators, or the website and (2) not to engage in bahavior that is harmful to the website or others?')">
	<b>Who are you?</b> <select name="p">
		<option>a</option>
		<option>b</option>
	</select>
	<b>Which deck is A playing?</b> <select name="da">
		<option value="?">Random</option>
		<option value="v">Invaders</option>
		<option value="t">Tweens</option>
		<option value="a">Artificial</option>
		<option value="r">Republicans</option>
		<option value="i">Icons</option>
		<option value="s">Suits</option>
		<option value="p">Products</option>
		<option value="j">Jovians</option>
	</select>
	<b>Which deck is B playing?</b> <select name="db">
		<option value="?">Random</option>
		<option value="v">Invaders</option>
		<option value="t">Tweens</option>
		<option value="a">Artificial</option>
		<option value="r">Republicans</option>
		<option value="i">Icons</option>
		<option value="s">Suits</option>
		<option value="p">Products</option>
		<option value="j">Jovians</option>
	</select>
	<b>Name your game:</b> <select name="g">
	<?php
		$letters = "ABCDEFGHIJKLMNPQRSTUVWXYZabedfghijkmnqrty0123456789";
		$moreletters = $letters.$letters.$letters;
		$option = substr(str_shuffle($moreletters), 0, 16);
		echo "<option value=\"$option\">$option (New Game)</option>";
		foreach (glob("gamestates/*.csv") as $filename){
			$option = strstr(basename($filename), ".", true);
			echo "<option>$option</option>";
		}
	?>
	</select>
	<input type="submit" value="Enter Game>>" />
</form>
<h1>&ldquo;Deckbuilder&rdquo;</h1>

<h2>Object of the Game</h2>
<p>Play cards to gain enough <u>Umph</u> to buy more cards. Add these cards to your
deck, then play them to buy more cards, so on and so forth. Whenever you buy or
gain a card, you gain <u>Points</u>. Be the first to reach fifty (50) <u>Points</u>
to win!

<h2>Setup</h2>
<p>&bullet; You will need 2-4 players. Each player selects three packs of cards to
make up his or her <u>Loot Deck</u>. For higher level play, take turns selecting
one pack at a time. If this is your playgroup's first time playing
&ldquo;Deckbuilder&rdquo;, or if you just want to jump straight into the game,
each player can instead choose one of the following &ldquo;starter decks.&rdquo;</p>
<p><b>The Invaders</b>&mdash;VS VP VJ</p>
<p><b>The Tweens</b>&mdash;TI TP TJ</p>
<p><b>The Artificial</b>&mdash;AI AS AJ</p>
<p><b>The Republicans</b>&mdash;RI RS RP</p>
<p><b>The Icons</b>&mdash;TI AI RI</p>
<p><b>The Suits</b>&mdash;VS AS RS</p>
<p><b>The Products</b>&mdash;VP TP RP</p>
<p><b>The Jovians</b>&mdash;VJ TJ AJ</p>
<p>&bullet; Deal each player a <u>Play Deck</u> consisting of seven (7)
Fight cards and three (3) Struggle cards. Each player then shuffles his or
her <u>Loot Deck</u> and his or her <u>Play Deck</u>.</p>
<p>&bullet; Set up the <u>Line Up</u> by setting out a facedown stack of fifty
(50) Combat Trick cards. Next, each player adds the top card of his or her
<u>Loot Deck</u> to the <u>Line Up</u>, going around the table until five (5)
cards have been added.</p>
<p>&bullet; Finally, determine who goes first. Each player draws his or her
first hand by drawing five (5) cards from the top of his or her <u>Play Deck</u>.

<h2>Gameplay</h2>
<p>&bullet; Turns in &ldquo;Deckbuilder&rdquo; consist of three (3) phases,
<u>Build</u>, <u>Actions</u>, and <u>After Actions</u>.</p>
<p>&bullet; During the <u>Build</u> phase on your turn, if there are less than five (5)
cards in the <u>Line Up</u>, add cards from the top of your <u>Loot Deck</u> until
there are.</p>
<p>&bullet; During the <u>Actions</u> phase on your turn you can play cards from
your hand, activate effects, and buy cards from the <u>Line Up</u>.</p>
<p><b>Buying Cards</b>&mdash;To buy a card
from the <u>Line Up</u>, first accumulate enough <u>Umph</u> by playing cards. Next,
spend an amount <u>Umph</u> equal to the card's <u>Cost</u>, located in the top left corner
of the card. Finally, move the purchased card to your <u>Discard Pile</u> and gain
<u>Points</u> equal to the card's <u>Worth</u>, that is, the number of stars next to
its <u>Cost</u>.</p>
<p><b>Playing Cards and Card Effects</b>&mdash;When you play a card, first put it from your hand
into play. Next, perform its effect. Effects that say &ldquo;this turn&rdquo;
continue until the end of the turn. Effects that say &ldquo;once&rdquo; do not
have to be performed immediately; you can choose to activate these effects any time
you could play a card. Note that activating the effect of a card this way does not
count as &ldquo;playing&rdquo; the card again. Effects that say &ldquo;your turn&rdquo;
happen only during your turn, but effects that say &ldquo;each turn&ldquo; happen
during each player's turn. After the effects on the card have been performed, it
remains in play.</p>
<p>&bullet; During the <u>After Actions</u>, all <u>Umph</u> is lost. Next,
effects that &ldquo;after actions&rdquo; are performed. Finally, if it is your turn,
discard any cards left in your hand and draw a hand for next turn of five (5)
cards from your <u>Play Deck</u>.</p>
<p>&bullet; Turns proceed in this fashion clockwise around the table until the
game ends. Players draw cards from their <u>Play Decks</u>, add cards
to the <u>Line Up</u> from their <u>Loot Decks</u>, and buy or gain cards to their
<u>Discard Piles</u>. Whenever a player needs a card from his or her <u>Play Deck</u>
to perform an action and that deck is empty, he or she first shuffles his or
her <u>Discard Pile</u> to create a new <u>Play Deck</u>.</p>

<h2>Card Teams and Card Types</h2>
<p>&bullet; Most cards have both a team and a type. Only Fight cards, Struggle
cards, and Combat Trick cards have neither, and only cards in <u>Accessory</u> packs do
not have a team. There are four teams, Invaders, Tweens, Artificial, and Republican.
These are written along the left side of the cards and are denoted by different
shapes in the card frames. There are also four types, Icons, Suits, Products, and
Jovians. These are also written along the left side of the cards and are denoted
by different colors in the card frames.</p>
<p>&bullet; Each pack of cards has a unique Team/Type
combination, so we've designed our cards with writing on the left side and different
colors and shapes to make sorting the packs out easier.</p>
<p>&bullet; Each Team and each Type represents how different people in the story
want to win. The Invaders want to Dominate their opponents. The Tweens want to
start a Rebellion. The Artificial want to win with Efficiency. The Republicans
want to honor their Values. Icons want to fill the world with Enchantment. Suits
want to win through Acquisition at any cost. Products want to gain large amounts
of Power. And Jovians want to fight in Numbers.</p>

<h2>Keywords</h2>
<p>&bullet; There are four keywords in &ldquo;Deckbuilder&rdquo;, <u>Maintain</u>,
<u>Dispose</u>, <u>Anarchy</u>, and <u>Lure</u>.</p>
<p>&bullet; The keyword <u>Maintain</u> means, &ldquo;After actions, leave this
card in play. Whenever you shuffle <u>Discard Pile</u> to create a new
<u>Play Deck</u>, shuffle this card in as well.&rdquo; This ability is used
to amass a collection of cards in play, allowing you plenty of card effects
to work with during your turn!</p>
<p>&bullet; The keyword <u>Dispose</u> means, &ldquo;Play this card facedown covering
a card in the <u>Line Up</u>. Once during your turn or when the covered card leaves
the <u>Line Up</u>, reveal this card, perform its effect, and discard it.&rdquo;
This effect is used when you don't yet have enough Umph
and want to dissuade your opponents from buying or gaining cards that you have
your eye on!</p>
<p>&bullet; The keyword <u>Anarchy</u> means, &ldquo;Put the top card of the
<u>Trick Deck</u> on top of any player's <u>Loot Deck</u>.&rdquo; This effect
is used to slow down your opponents by making them add Combat Trick cards to the <u>Line
Up</u> instead of cards from their <u>Loot Decks</u>, drastically reducing their
buying options!</p>
<p>&bullet; The keyword <u>Lure</u> means, &ldquo;This card costs one (1) less to
buy for each card played this turn that shares a type with it.&rdquo; This effect
is used to acquire cards at a discount&mdash;sometimes even for free!</p>

<h2>FAQ</h2>
TODO

<h2>Web Client Controls</h2>
<p>&bullet; In general, the web client can be controled by first selecting a
card or zone then selecting an action to perform on it. However, to make gameplay
bearable on the web client, we have included various keyboard shortcuts and
double-click actions.</p>
<p><b>A</b>&mdash;Display an alert box with information about the selected card.</p>
<p><b>B</b>&mdash;Move a selected card to the bottom of your <u>Loot Deck</u>.</p>
<p><b>D</b>&mdash;Move a selected card to your discard pile.</p>
<p><b>H</b>&mdash;Move a selected card to your hand.</p>
<p><b>L</b>&mdash;Move a selected card to the <u>Line Up</u>.</p>
<p><b>P</b>&mdash;Put a selected card into play.</p>
<p><b>R</b>&mdash;Reveal a selected card to all players.</p>
<p><b>1</b>&mdash;Draw a card.</p>
<p><b>5</b>&mdash;Draw five (5) cards.</p>
<p><b>]</b>&mdash;Add the top card of your <u>Loot Deck</u> to the <u>Line Up</u>.</p>
<p><b>=</b>&mdash;Shuffle your discard pile onto the bottom of your <u>Play Deck</u>.</p>
<p><b>spacebar</b>&mdash;Put your entire hand into play.</p>
<p><b>delete</b>&mdash;Move a selected card and all cards in the same zone to your discard pile.</p>
<p><b>Double Clicks</b>&mdash;Double clicking a card in your hand moves it into play;
a card in play moves it to your <u>Discard Pile</u>; a card in the <u>Line Up</u>
moves it to your <u>Discard Pile</u>; your <u>Play Deck</u> draws a card;
and your <u>Loot Deck</u> puts the top card into the <u>Line Up</u>.</p>