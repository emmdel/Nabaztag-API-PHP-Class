<?php

include('nabaztag.class.php');

/**
 * Set serial and token variables to match your Nabaztag.
 * You can find your information by logging into
 * http://my.nabaztag.com/
 */

$serial	= '';
$token	= '';

// Collect values set in form

$phrase		= $_REQUEST['phrase'];
$left_ear	= (int) $_REQUEST['left_ear'];
$right_ear	= (int) $_REQUEST['right_ear'];
$submit_ears	= $_REQUEST['submit_ears'];
$wake_status	= (int) $_REQUEST['wake_status'];

// Create the nabaztag object

$nabaztag = new nabaztag($serial,$token);

// Make the bunny talk

if ($phrase)
	$nabaztag->speak($phrase);

// Move bunny ears

if ($submit_ears)
	$nabaztag->move_ears($left_ear,$right_ear);

echo "<h1>Nabaztag PHP Class Examples</h1>";

// Debug information

echo "<p><b>Parameters sent to the API:</b></p>";

$nabaztag->display_api_params();

echo "<p><b>The API responded:</b></p>";

$nabaztag->display_api_response();

// Display form

?>

<h2>Text to Speach Example</h2>

<form name="nabaztag_tts" action="<?=$_SERVER['PHP_SELF']?>" method="post">

<p>
<input type="text" name="phrase" size="50" value="<?=$phrase?>" /><br />
<input type="submit" name="submit_tts" value="Speak" />
</p>

</form>

<h2>Ears Example</h2>

<form name="nabaztag_ears" action="<?=$_SERVER['PHP_SELF']?>" method="post">

<p><i>Enter a positon between 0 and 16. 0 points the ear straight up.</i></p>
<p>
Left Ear: <input type="text" name="left_ear" size="3" value="<?=$left_ear?>" />
Right Ear: <input type="text" name="right_ear" size="3" value="<?=$right_ear?>" /><br />
<input type="submit" name="submit_ears" value="Move Ears" />
</p>

</form>

<?php

exit;

?>
