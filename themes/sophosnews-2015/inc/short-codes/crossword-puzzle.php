<?php

namespace Sophos\Shortcode\Crossword;


// Add the shortcode
add_shortcode('crossword', function ( $atts ) {

	$atts = shortcode_atts([
		'crosswordwidth'	 => 0,
		'crosswordheight'	 => 0,
		'words'  	 		 => 0,
		'wordlength' 		 => [],
		'word'		 		 => [],
		'clue'		 		 => [],
		'answerhash' 		 => [],
		'wordx' 	 		 => [],
		'wordy' 	 		 => [],
		'lasthorizontalword' => 0,
		'onlycheckonce' 	 => 0,
	], $atts, 'crossword');

	ob_start();

	?><style type="text/css">

	@media screen and (max-width: 780px) {
		article.post table.ecw-wrapper {
			margin: 0 auto;
			width: auto;
		}

		.ecw-crosswordarea, .ecw-messagearea {
			display: block;
			width: 100%;
		}

		.ecw-messagearea {
			padding: 0 !important;
			margin-top: 1em;
		}

		.ecw-crosswordarea .ecw-box {
			width: 6vw;
			height: 6vw;
		}
	}

	@media screen and (min-width: 781px) {
		article.post table.ecw-wrapper {
			margin: 0;
			width: auto;
		}

		.ecw-messagearea {
			padding: 0 0 0 1em !important;
		}

		.ecw-crosswordarea .ecw-box {
			height: 2vw;
			width: 2vw;
			max-height: 25px;
			max-width: 25px;
		}
	}

	@media screen and (min-width: 1200px) {
		.ecw-messagearea {
			padding: 0 0 0 1em !important;
		}

		.ecw-crosswordarea .ecw-box {
			height: 25px;
			width: 25px;
		}
	}

	article.post table.ecw-wrapper {
		width: auto;
	}

	#crossword {
		display: none;
		border-collapse: collapse;
		margin: .5em;
	}

	#checkbutton {
		display: block;
		margin: 0 auto;
		background: rgb(240,240,240);
	}

	.ecw-messagearea body,
	.ecw-messagearea h1,
	.ecw-messagearea h2,
	.ecw-messagearea h3,
	.ecw-messagearea h4,
	.ecw-messagearea h5,
	.ecw-messagearea h6,
	.ecw-messagearea p,
	.ecw-messagearea .ecw-wordinfo,
	.ecw-messagearea .ecw-wordlabel,
	.ecw-messagearea .ecw-cluebox,
	.ecw-messagearea .ecw-input,
	.ecw-messagearea .ecw-worderror,
	.ecw-messagearea .ecw-answerbox {
	     font-family: "Segoe UI", "Franklin Gothic Medium", "Arial", sans-serif !important;
	}

	.ecw-messagearea h1,
	.ecw-messagearea h2,
	.ecw-messagearea h3,
	.ecw-messagearea h4,
	.ecw-messagearea h5,
	.ecw-messagearea h6 {
	     color: #5d6a86;
	}

	.ecw-messagearea {
	     cursor: default;
	     font-size: small;
	}

	article.post td {
		padding: 0;
		border: none;
	}

	.ecw-answerbox {
	     color: black;
	     background-color: #FFFAF0;
	     border-color: #808080;
	     border-style: solid;
	     border-width: 1px;
	     display: block;
	     padding: .75em;
	}

	.ecw-crosswordarea .ecw-box {
	     border-style: solid;
	     border-width: 1px;
	     cursor: pointer;
	     font-size: .12in;
	     font-weight: bold;
	     overflow: hidden;
	     text-align: center;
		 padding: 0;
		 vertical-align: middle;
	}

	article.post td.ecw-boxcheated_sel {
	     background-color: #FFF1D7;
	     border-color: #C00000;
	     color: #2080D0;
	}

	article.post td.ecw-boxcheated_unsel {
	     background-color: #ffffff;
	     border-color: #606060;
	     color: #2080D0;
	}

	article.post td.ecw-boxerror_sel {
	     background-color: #FFF1D7;
	     border-color: #C00000;
	     color: #BF0000;
	}

	article.post td.ecw-boxerror_unsel {
	     background-color: #FFF0F0;
	     border-color: #606060;
	     color: #BF0000;
	}

	article.post td.ecw-boxnormal_sel {
	     background-color: #FFF1D7;
	     border-color: #C00000;
	     color: #000000;
	}

	article.post td.ecw-boxnormal_unsel {
	     background-color: #ffffff;
	     border-color: #606060;
	     color: #000000;
	}

	.ecw-button {
	     width: 64pt;
	}

	.ecw-cluebox {
	     border-bottom-width: 1px;
	     border-color: #c0c0c0;
	     border-left-width: 0px;
	     border-right-width: 0px;
	     border-style: solid;
	     border-top-width: 1px;
	     margin-top: 1em;
	     padding-bottom: .5em;
	     padding-left: 0pt;
	     padding-right: 0pt;
	     padding-top: .5em;
		 font-size: small;
	}

	.ecw-crosswordarea {
		border: 2px solid #808080 !important;
	     background-color: #D0D8E0;
	     padding: 0;
	     font-family: "Segoe UI", "Verdana", "Arial", sans-serif;
	     font-size: small;
		 vertical-align: top;
		 overflow: hidden;
	}

	.ecw-copyright {
	     margin-top: 1em;
	     font-size: x-small;
	     font-family: "Segoe UI", "Franklin Gothic Medium", "Arial", sans-serif;
	}

	input.ecw-input,
	button.ecw-input {
		 margin: 0;
	}

	.ecw-wordlabel {
	     text-transform: uppercase;
	     margin: 0 !important;
		 font-size: 1rem !important;
	}

	.ecw-wordinfo {
	     font-size: 8pt;
	     color: #808080;
	}

	.ecw-worderror {
	     color: #c00000;
	     font-weight: bold;
	     display: none;
	     margin-top: 1em;
	}

	</style>
	<div id="waitmessage" class="ecw-answerbox"> This interactive crossword puzzle requires JavaScript and any recent web browser, including Windows Internet Explorer, Mozilla Firefox, Google Chrome, or Apple Safari. If you have disabled web page scripting, please re-enable it and refresh the page. If this web page is saved on your computer, you may need to click the yellow Information Bar at the top or bottom of the page to allow the puzzle to load.
	</div>
		<table class="ecw-wrapper" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="ecw-crosswordarea">
					<script type="text/javascript">
					    CrosswordWidth     = <?php echo esc_js( $atts['crosswordwidth'] ); ?>;
					    CrosswordHeight    = <?php echo esc_js( $atts['crosswordheight'] ); ?>;
					    Words 			   = <?php echo esc_js( $atts['words'] ); ?>;
					    WordLength 		   = <?php echo esc_js( json_encode( array_map( function ( $a ) { return (int) $a; }, empty( $atts['wordlength'] ) ? [] : str_getcsv( $atts['wordlength'], ",", "'" ) ) ) ); ?>;
					    Word 			   = <?php echo esc_js( json_encode( empty( $atts['word'] ) ? [] : str_getcsv( $atts['word'], ",", "'" ) ) ); ?>;
						Clue			   = <?php echo wp_kses( json_encode( empty( $atts['clue'] ) ? [] : array_map( 'stripcslashes', str_getcsv( $atts['clue'], ",", "'" ) ) ), [] ); ?>;
					    AnswerHash 		   = <?php echo esc_js( json_encode( array_map( function ( $a ) { return (int) $a; }, empty( $atts['answerhash'] ) ? [] : str_getcsv( $atts['answerhash'], ",", "'" ) ) ) ); ?>;
					    WordX 			   = <?php echo esc_js( json_encode( array_map( function ( $a ) { return (int) $a; }, empty( $atts['wordx'] ) ? [] : str_getcsv( $atts['wordx'], ",", "'" ) ) ) ); ?>;
					    WordY 			   = <?php echo esc_js( json_encode( array_map( function ( $a ) { return (int) $a; }, empty( $atts['wordy'] ) ? [] : str_getcsv( $atts['wordy'], ",", "'" ) ) ) ); ?>;
					    LastHorizontalWord = <?php echo esc_js( $atts['lasthorizontalword'] ); ?>;
					    OnlyCheckOnce 	   = <?php echo esc_js( $atts['onlycheckonce'] ); ?>;

						// EclipseCrossword (C) Copyright 2000-2013 Green Eclipse.
						// The puzzle itself remains the property of its creator. Do not remove this copyright notice.

						var BadChars = "`~!@^*()_={[}]\\|:;\"',<>/?";

						var TableAcrossWord, TableDownWord;
						var CurrentWord, PrevWordHorizontal, x, y, i, j;
						var CrosswordFinished, Initialized;

						// Check the user's browser and then initialize the puzzle.
						if (document.getElementById("waitmessage") != null)
						{
						    document.getElementById("waitmessage").innerHTML = "Please wait while the crossword is loaded...";

						    // Current game variables
						    CurrentWord = -1;
						    PrevWordHorizontal = false;

						    // Create the cell-to-word arrays.
						    TableAcrossWord = new Array(CrosswordWidth);
						    for (var x = 0; x < CrosswordWidth; x++) TableAcrossWord[x] = new Array(CrosswordHeight);
						    TableDownWord = new Array(CrosswordWidth);
						    for (var x = 0; x < CrosswordWidth; x++) TableDownWord[x] = new Array(CrosswordHeight);
						    for (var y = 0; y < CrosswordHeight; y++)
						        for (var x = 0; x < CrosswordWidth; x++)
						        {
						            TableAcrossWord[x][y] = -1;
						            TableDownWord[x][y] = -1;
						        }

						    // First, add the horizontal words to the puzzle.
						    for (var i = 0; i <= LastHorizontalWord; i++)
						    {
						        x = WordX[i];
						        y = WordY[i];
						        for (var j = 0; j < WordLength[i]; j++)
						        {
						            TableAcrossWord[x + j][y] = i;
						        }
						    }

						    // Second, add the vertical words to the puzzle.
						    for (var i = LastHorizontalWord + 1; i < Words; i++)
						    {
						        x = WordX[i];
						        y = WordY[i];
						        for (var j = 0; j < WordLength[i]; j++)
						        {
						            TableDownWord[x][y + j] = i;
						        }
						    }

						    // Now, insert the crossword table.
						    document.writeln("<table id=\"crossword\" cellpadding=\"0\" cellspacing=\"0\">");
						    for (var y = 0; y < CrosswordHeight; y++)
						    {
						        document.writeln("<tr>");
						        for (var x = 0; x < CrosswordWidth; x++)
						        {
						            if (TableAcrossWord[x][y] >= 0 || TableDownWord[x][y] >= 0)
						                document.write("<td id=\"c" + PadNumber(x) + PadNumber(y) + "\" class=\"ecw-box ecw-boxnormal_unsel\" onclick=\"SelectThisWord(event);\">&nbsp;</td>");
						            else
						                document.write("<td><\/td>");
						        }
						        document.writeln("<\/tr>");
						    }
						    document.writeln("<\/table>");

						    // Finally, show the crossword and hide the wait message.
						    Initialized = true;
						    document.getElementById("waitmessage").style.display = "none";
						    document.getElementById("crossword").style.display = "block";
						}

						// * * * * * * * * * *
						// Event handlers

						// Raised when a key is pressed in the word entry box.
						function WordEntryKeyPress(event)
						{
						    if (CrosswordFinished) return;
						    // Treat an Enter keypress as an OK click.
						    if (CurrentWord >= 0 && event.keyCode == 13) OKClick();
						}

						// * * * * * * * * * *
						// Helper functions

						// Called when we're ready to start the crossword.
						function BeginCrossword()
						{
						    if (Initialized)
						    {
						        document.getElementById("welcomemessage").style.display = "";
						        document.getElementById("checkbutton").style.display = "";
						    }
						}

						// Returns true if the string passed in contains any characters prone to evil.
						function ContainsBadChars(theirWord)
						{
							return !/^[a-z\-]+$/i.test(theirWord);
						}

						// Pads a number out to three characters.
						function PadNumber(number)
						{
						    if (number < 10)
						        return "00" + number;
						    else if (number < 100)
						        return "0" + number;
						    else
						        return "" +  number;
						}

						// Returns the table cell at a particular pair of coordinates.
						function CellAt(x, y)
						{
						    return document.getElementById("c" + PadNumber(x) + PadNumber(y));
						}

						// Deselects the current word, if there's a word selected.  DOES not change the value of CurrentWord.
						function DeselectCurrentWord()
						{
						    if (CurrentWord < 0) return;
						    var x, y, i;

						    document.getElementById("answerbox").style.display = "none";
						    ChangeCurrentWordSelectedStyle(false);
						    CurrentWord = -1;

						}

						// Changes the style of the cells in the current word.
						function ChangeWordStyle(WordNumber, NewStyle)
						{
						    if (WordNumber< 0) return;
						    var x = WordX[WordNumber];
						    var y = WordY[WordNumber];

						    if (WordNumber<= LastHorizontalWord)
						        for (i = 0; i < WordLength[WordNumber]; i++)
						            CellAt(x + i, y).className = NewStyle;
						    else
						        for (i = 0; i < WordLength[WordNumber]; i++)
						            CellAt(x, y + i).className = NewStyle;
						}

						// Changes the style of the cells in the current word between the selected/unselected form.
						function ChangeCurrentWordSelectedStyle(IsSelected)
						{
						    if (CurrentWord < 0) return;
						    var x = WordX[CurrentWord];
						    var y = WordY[CurrentWord];

						    if (CurrentWord <= LastHorizontalWord)
						        for (i = 0; i < WordLength[CurrentWord]; i++)
						            CellAt(x + i, y).className = CellAt(x + i, y).className.replace(IsSelected ? "_unsel" : "_sel", IsSelected ? "_sel" : "_unsel");
						    else
						        for (i = 0; i < WordLength[CurrentWord]; i++)
						            CellAt(x, y + i).className = CellAt(x, y + i).className.replace(IsSelected ? "_unsel" : "_sel", IsSelected ? "_sel" : "_unsel");
						}

						// Selects the new word by parsing the name of the TD element referenced by the
						// event object, and then applying styles as necessary.
						function SelectThisWord(event)
						{
						    if (CrosswordFinished) return;
						    var x, y, i, TheirWord, TableCell;

						    // Deselect the previous word if one was selected.
						    document.getElementById("welcomemessage").style.display = "none";
						    if (CurrentWord >= 0) OKClick();
						    DeselectCurrentWord();

						    // Determine the coordinates of the cell they clicked, and then the word that
						    // they clicked.
						    var target = (event.srcElement ? event.srcElement: event.target);
						    x = parseInt(target.id.substring(1, 4), 10);
						    y = parseInt(target.id.substring(4, 7), 10);

						    // If they clicked an intersection, choose the type of word that was NOT selected last time.
						    if (TableAcrossWord[x][y] >= 0 && TableDownWord[x][y] >= 0)
						        CurrentWord = PrevWordHorizontal ? TableDownWord[x][y] : TableAcrossWord[x][y];
						    else if (TableAcrossWord[x][y] >= 0)
						        CurrentWord = TableAcrossWord[x][y];
						    else if (TableDownWord[x][y] >= 0)
						        CurrentWord = TableDownWord[x][y];

						    PrevWordHorizontal = (CurrentWord <= LastHorizontalWord);

						    // Now, change the style of the cells in this word.
						    ChangeCurrentWordSelectedStyle(true);

						    // Then, prepare the answer box.
						    x = WordX[CurrentWord];
						    y = WordY[CurrentWord];
						    TheirWord = "";
						    var TheirWordLength = 0;
						    for (i = 0; i < WordLength[CurrentWord]; i++)
						    {
						        // Find the appropriate table cell.
						        if (CurrentWord <= LastHorizontalWord)
						            TableCell = CellAt(x + i, y);
						        else
						            TableCell = CellAt(x, y + i);
						        // Add its contents to the word we're building.
						        if (TableCell.innerHTML != null && TableCell.innerHTML.length > 0 && TableCell.innerHTML != " " && TableCell.innerHTML.toLowerCase() != "&nbsp;")
						        {
						            TheirWord += TableCell.innerHTML.toUpperCase();
						            TheirWordLength++;
						        }
						        else
						        {
						            TheirWord += "&bull;";
						        }
						    }

						    document.getElementById("wordlabel").innerHTML = TheirWord;
						    document.getElementById("wordinfo").innerHTML = ((CurrentWord <= LastHorizontalWord) ? "Across, " : "Down, ") + WordLength[CurrentWord] + " letters.";
						    document.getElementById("wordclue").innerHTML = Clue[CurrentWord];
						    document.getElementById("worderror").style.display = "none";
						    //###// document.getElementById("cheatbutton").style.display = (Word.length == 0) ? "none" : "";
						    if (TheirWordLength == WordLength[CurrentWord])
						        document.getElementById("wordentry").value = TheirWord.replace(/&AMP;/g, '&');
						    else
						        document.getElementById("wordentry").value = "";

						    // Finally, show the answer box.
						    document.getElementById("answerbox").style.display = "block";
						    try
						    {
						        document.getElementById("wordentry").focus();
						        document.getElementById("wordentry").select();
						    }
						    catch (e)
						    {
						    }

						}

						// Called when the user clicks the OK link.
						function OKClick()
						{
						    var TheirWord, x, y, i, TableCell;
						    if (CrosswordFinished) return;
						    if (document.getElementById("okbutton").disabled) return;

						    // First, validate the entry.
						    TheirWord = document.getElementById("wordentry").value.toUpperCase();
						    if (TheirWord.length == 0)
						    {
						        DeselectCurrentWord();
						        return;
						    }
						    if (ContainsBadChars(TheirWord))
						    {
						        document.getElementById("worderror").innerHTML = "The word that you typed contains invalid characters.  Please type only letters in the box above.";
						        document.getElementById("worderror").style.display = "block";
						        return;
						    }
						    if (TheirWord.length < WordLength[CurrentWord])
						    {
						        document.getElementById("worderror").innerHTML  = "You did not type enough letters.  This word has " + WordLength[CurrentWord] + " letters.";
						        document.getElementById("worderror").style.display = "block";
						        return;
						    }
						    if (TheirWord.length > WordLength[CurrentWord])
						    {
						        document.getElementById("worderror").innerHTML = "You typed too many letters.  This word has " + WordLength[CurrentWord] + " letters.";
						        document.getElementById("worderror").style.display = "block";
						        return;
						    }

						    // If we made it this far, they typed an acceptable word, so add these letters to the puzzle and hide the entry box.
						    x = WordX[CurrentWord];
						    y = WordY[CurrentWord];
						    for (i = 0; i < TheirWord.length; i++)
						    {
						        TableCell = CellAt(x + (CurrentWord <= LastHorizontalWord ? i : 0), y + (CurrentWord > LastHorizontalWord ? i : 0));
						        TableCell.innerHTML = TheirWord.substring(i, i + 1);
						    }
						    DeselectCurrentWord();
						}

						// Called when the "check puzzle" link is clicked.
						function CheckClick()
						{
						    var i, j, x, y, UserEntry, ErrorsFound = 0, EmptyFound = 0, TableCell;
						    if (CrosswordFinished) return;
						    DeselectCurrentWord();

						    for (y = 0; y < CrosswordHeight; y++)
						    for (x = 0; x < CrosswordWidth; x++)
						        if (TableAcrossWord[x][y] >= 0 || TableDownWord[x][y] >= 0)
						        {
						            TableCell = CellAt(x, y);
						            if (TableCell.className == "ecw-box ecw-boxerror_unsel") TableCell.className = "ecw-box ecw-boxnormal_unsel";
						        }

						    for (i = 0; i < Words; i++)
						    {
						        // Get the user's entry for this word.
						        UserEntry = "";
						        for (j = 0; j < WordLength[i]; j++)
						        {
						            if (i <= LastHorizontalWord)
						                TableCell = CellAt(WordX[i] + j, WordY[i]);
						            else
						                TableCell = CellAt(WordX[i], WordY[i] + j);
						            if (TableCell.innerHTML.length > 0 && TableCell.innerHTML.toLowerCase() != "&nbsp;")
						            {
						                UserEntry += TableCell.innerHTML.toUpperCase();
						            }
						            else
						            {
						                UserEntry = "";
						                EmptyFound++;
						                break;
						            }
						        }
						        UserEntry = UserEntry.replace(/&AMP;/g, '&');
						        // If this word doesn't match, it's an error.
						        if (HashWord(UserEntry) != AnswerHash[i] && UserEntry.length > 0)
						        {
						            ErrorsFound++;
						            ChangeWordStyle(i, "ecw-box ecw-boxerror_unsel");
						        }
						    }

						    // If they can only check once, disable things prematurely.
						    if ( OnlyCheckOnce )
						    {
						        CrosswordFinished = true;
						        document.getElementById("checkbutton").style.display = "none";
						    }

						    // If errors were found, just exit now.
						    if (ErrorsFound > 0 && EmptyFound > 0)
						        document.getElementById("welcomemessage").innerHTML = ErrorsFound + (ErrorsFound > 1 ? " errors" : " error") + " and " + EmptyFound + (EmptyFound > 1 ? " incomplete words were" : " incomplete word was") + " found.";
						    else if (ErrorsFound > 0)
						        document.getElementById("welcomemessage").innerHTML = ErrorsFound + (ErrorsFound > 1 ? " errors were" : " error was") + " found.";
						    else if (EmptyFound > 0)
						        document.getElementById("welcomemessage").innerHTML = "No errors were found, but " + EmptyFound + (EmptyFound > 1 ? " incomplete words were" : " incomplete word was") + " found.";

						    if (ErrorsFound + EmptyFound > 0)
						    {
						        document.getElementById("welcomemessage").style.display = "";
						        return;
						    }

						    // They finished the puzzle!
						    CrosswordFinished = true;
						    document.getElementById("checkbutton").style.display = "none";
						    document.getElementById("congratulations").style.display = "block";
						    document.getElementById("welcomemessage").style.display = "none";
						}

						// Called when the "cheat" link is clicked.
						function CheatClick()
						{
						    if (CrosswordFinished) return;
						    var OldWord = CurrentWord;
						    document.getElementById("wordentry").value = Word[CurrentWord];
						    OKClick();
						    ChangeWordStyle(OldWord, "ecw-box ecw-boxcheated_unsel");
						}

						// Returns a one-way hash for a word.
						function HashWord(Word)
						{
						    var x = (Word.charCodeAt(0) * 719) % 1138;
						    var Hash = 837;
						    var i;
						    for (i = 1; i <= Word.length; i++)
						        Hash = (Hash * i + 5 + (Word.charCodeAt(i - 1) - 64) * x) % 98503;
						    return Hash;
						}
					</script>
				</td>
				<td class="ecw-messagearea" valign="top">
					<div id="welcomemessage" class="ecw-answerbox" style="display:none;">
						<h3>Welcome!</h3>
						<p>Click a word in the puzzle to get started.</p>
					</div>
					<div id="answerbox" class="ecw-answerbox" style="display:none;">
						<h3 id="wordlabel" class="ecw-wordlabel"> &nbsp;</h3>
						<div id="wordinfo" class="ecw-wordinfo"> </div>
						<div id="wordclue" class="ecw-cluebox"> </div>
						<div style="margin-top: 1em;">
						    <input class="ecw-input" id="wordentry" type="text" size="24" style="font-weight: bold; text-transform:uppercase;" onkeypress="WordEntryKeyPress(event)" onchange="WordEntryKeyPress(event)" />
						</div>
						<div id="worderror" class="ecw-worderror"></div>
						<table border="0" cellspacing="0" cellpadding="0" width="100%" style="margin-top:1em;">
							<tbody>
								<tr>
									<!-- ### <td>
									<button id="cheatbutton" type="button" class="ecw-input ecw-button" onclick="CheatClick();">Solve</button>
									</td>   -->
									<td align="left">
										<button id="okbutton" type="button" class="ecw-input ecw-button" onclick="OKClick();" style="font-weight: bold;">OK</button> &nbsp;
										<button id="cancelbutton" type="button" class="ecw-input ecw-button" onclick="DeselectCurrentWord();">Cancel</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="congratulations" class="ecw-answerbox" style="display:none;">
						<h3>Congratulations!</h3>
						<p>You have completed this <a href="http://www.eclipsecrossword.com" style="color: black; text-decoration:none;">crossword puzzle</a>. Don't forget to take a screenshot and send it to tips@sophos.com if you want to try to win a T-shirt!</p>
					</div>
					<div style="margin-top: 1em;">
						<button id="checkbutton" type="button" onclick="CheckClick();" style="display: none;">Check puzzle</button>
					</div>
				</td>
			</tr>
		</table>
	<script type="text/javascript">
	BeginCrossword();
	</script>
	<!-- Created with EclipseCrossword, (C) Copyright 2000-2013 Green Eclipse.  eclipsecrossword.com --><?php

	return ob_get_clean();
});
