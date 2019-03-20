<?php
/**
 * Random name picker.
 * Written by Drew Maughan <drew@pzlabs.co>
 *
 * The requirements for this random name picker:
 *
 * - the ability to display all the names that will be picked from.
 * - the ability to display already chosen names.
 * - the ability to pick ONE name at a time.
 * - that each name has an EQUAL chance of being picked.
 * - that each name is picked only ONCE.
 * - that the list of picked names can be reset.
 */

$names_file  = 'picker-names.csv';
$chosen_file = 'picker-chosen.txt';

//////////////////////////////////////////////////////////////////////

$names  = [];
$chosen = [];

// Fetch the list of names to choose from, as well as the list of names already chosen (if available).
getListsOfNames();

// Based on the command line parameter (if any), perform a certain action.
// We're only supporting one flag in this simple script.
$flag = $argc > 1 ? strtolower($argv[ 1 ]) : null;
switch ( $flag )
{
	case 'all':
		// Output a list of all names, regardless of whether they have been picked or not.
		displayAllNames();
		break;

	case 'chosen':
		// Output a list of names that haven't yet been picked.
		displayChosenNames();
		break;

	case 'list':
		// Output a list of names that haven't yet been picked.
		displayAvailableNames();
		break;

	case 'help':
		displayHelp();
		break;

	case 'reset':
		resetChosen();
		break;

	case 'pick':
	default:
		// Pick a name at random.
		pickRandomName();
}
echo "\n";
exit(0);

//////////////////////////////////////////////////////////////////////

/**
 * getListsOfNames()
 * Reads a list of names to choose from, and names already chosen.
 */
function getListsOfNames()
{
	global $names, $chosen;
	global $names_file, $chosen_file;
	$names  = getDataFromFile($names_file, ',');
	$chosen = getDataFromFile($chosen_file, ',');
}

/**
 * getDataFromFile()
 * Reads data from a file.
 *
 * @param        $filename
 * @param string $delimiter
 *
 * @return array
 */
function getDataFromFile($filename, $delimiter = "\t")
{
	$output = [];
	if ( ( $handle = @fopen($filename, 'r') ) !== false )
	{
		while ( ( $data = fgetcsv($handle, 2000, $delimiter) ) !== false )
		{
			array_push($output, $data);
		}
		fclose($handle);
	}

	return $output;
}

/**
 * displayAllNames()
 * Display a list of all names defined in the names file.
 */
function displayAllNames()
{
	global $names;

	if ( count($names) )
	{
		array_map('outputNameRow', $names);
	}
	else
	{
		echo "No names to choose from.\n";
	}
}

/**
 * displayAvailableNames()
 * Display a list of names available to be chosen.
 */
function displayAvailableNames()
{
	$names = getNamesNotPicked();

	if ( count($names) )
	{
		array_map('outputNameRow', $names);
	}
	else
	{
		echo "No names to choose from.\n";
	}
}

/**
 * displayChosenNames()
 * Display a list of names that have already been chosen.
 */
function displayChosenNames()
{
	global $chosen;

	if ( count($chosen) )
	{
		array_map('outputNameRow', $chosen);
	}
	else
	{
		echo "No names have been chosen.\n";
	}
}

/**
 * getNamesNotPicked()
 * Returns a list of names that haven't already been picked.
 *
 * @return array
 */
function getNamesNotPicked()
{
	global $names, $chosen;

	$output = [];
	foreach ( $names as $row )
	{
		if ( !in_array($row, $chosen) )
		{
			array_push($output, $row);
		}
	}

	return $output;
}

/**
 * outputNameRow()
 * Echoes a formatted string representing a "chosen" name.
 *
 * @param $row
 */
function outputNameRow($row)
{
	// In our example we have a list of names with their email addresses.
	// We can use vsprintf() for pure convenience.
	echo vsprintf("%s <%s>\n", $row);
}

/**
 * pickRandomName()
 * Exactly as it suggests, picks a random name from the list of available names.
 */
function pickRandomName()
{
	$names = getNamesNotPicked();

	// OPTIONAL: shuffle the list of names for good measure.
	shuffle($names);

	if ( count($names) )
	{
		// Pick a name at random.
		// mt_rand is said to give an equal likelihood of a number being chosen.
		$index = mt_rand(1, count($names));
		$name  = $names[ $index - 1 ];

		// Add this name to the list of chosen names.
		addToChosen($name);

		// Display the chosen name!
		outputNameRow($name);
	}
	else
	{
		echo "No names to choose from.\n";
	}
}

/**
 * addToChosen()
 * Add to the file containing chosen names.
 *
 * @param $row
 */
function addToChosen($row)
{
	global $chosen_file;

	if ( ( $handle = fopen($chosen_file, 'a') ) !== false )
	{
		fwrite($handle, implode("\t", $row));
		fwrite($handle, PHP_EOL); // an appropriate end of line character.
		fclose($handle);
	}
}

/**
 * resetChosen()
 * Reset the list of chosen names by clearing it.
 */
function resetChosen()
{
	global $chosen_file;

	if ( ( $handle = fopen($chosen_file, 'w') ) !== false )
	{
		fwrite($handle, '');
		fclose($handle);
	}

	echo "List of chosen names has been reset.\n";
}

/**
 * displayHelp()
 * Displays a short description of the script, as well as the various commands.
 */
function displayHelp()
{
	global $names_file, $chosen_file;

	echo "\n";
	echo "Pick a name at random from a list.\n";
	echo "Names should be stored in a file called \"{$names_file}\", with each row in tab-delimited format.\n";
	echo "Chosen names will be stored in a file called \"{$chosen_file}\".\n";
	echo "\n";
	echo "Usage: php picker.php [option]\n";
	echo "\n";
	echo "Options:\n";
	echo "  all\t\tDisplay all available names.\n";
	echo "  chosen\tDisplay names that have been picked.\n";
	echo "  help\t\tDisplay this message.\n";
	echo "  list\t\tDisplay names that haven't yet been picked.\n";
	echo "  pick\t\tPick a name at random (default).\n";
	echo "  reset\t\tClear the list of picked names.\n";
	echo "\n";
	echo "Developed by Drew Maughan <drew@pzlabs.co>.\n";
}
