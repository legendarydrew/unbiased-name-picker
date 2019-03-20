# unbiased-name-picker
[![Open Source Love](https://badges.frapsoft.com/os/mit/mit.svg?v=102)](https://github.com/ellerbrock/open-source-badge/)
[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?fid=0yx9vk&url=https%3A%2F%2Fgithub.com%2Flegendarydrew%2Funbiased-name-picker)

PHP CLI script for drawing names at random.

This script was written in response to monthly random drawings at a London-based PHP Meetup group, and an overall suspicion that many of these "random draws" are somehow rigged. Either because not every name is included in the draw, or the randomness of the draw is biased in some way.

(For the record: while my name *had* actually been drawn in one of the Meetup group's random draws, I never actually received a prize.)

## Intentions for this script

* The ability to display all the names that will be picked from.
* The ability to display already chosen names.
* The ability to pick ONE name at a time.
* That each name has an EQUAL chance of being picked.
* That each name is picked only ONCE.
* That the list of picked names can be reset.

## Usage

`php src/picker.php [option]`

If no option is specified, the script will assume **pick**.

### Options

**pick** Picks a name at random, adding it to the list of picked names.

**all** Outputs a list of names, whether picked or not. These names are stored in `src/picker.csv`.

**chosen** Outputs a list of names that have been picked by the script. These names are stored in a generated text file, `src/picker-chosen.txt`.

**list** Outputs a list of names that haven't yet been picked.

**help** Displays help for using the script, similar to what you see here.

**reset** Resets the list of picked names.

## Contribution

Feel free to contribute to and adapt this script, if you find it useful.
