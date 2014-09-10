# Suma Import Generator

**Suma Import Generator** takes form input and output it as JSON-formatted data to be imported into **Suma: A Space Assessment Toolkit** (https://github.com/cazzerson/Suma). 

# Disclaimer

Suma itself is a beautifully-written piece of software written by a handful of coders. This import generator is quick hack *not* written by the Suma team, and does not directly integrate into Suma. Instead, it returns JSON formatted to be compatible with Suma's "Direct JSON Import" function. It could be a lot better, and improvements and contributions would be most welcome.

## Demo

See a demo of the Suma Import Generator functionality at: http://www6.wittenberg.edu/lib/ken/sumaimport

## Installation

Install the Suma Import Generator in a folder *outside* of the main Suma web space. Configure the connect.php file with information to connect to your Suma MySQL server. In the index.php file, update the $initiative variable to reflect the initiative you want to update. 

## Usage

This tool is set up to take head-count data from essentially a single moment / a few minutes from multiple locations. Suma allows many different kinds of counts and this import generator does not support all of them. This tool is currently set up to support multiple counts from one time slot at multiple locations. The initiative number is a variable set in the first few lines of the index.php. The time, date, locations, and counts are set by the user on the input web page (index.php), eg: Date: 2014-09-04; Time: 9:30pm; Locations: First, Second, Third Floors; Date: 2014-09-04

Using this input, the Suma Import Generator creates JSON data formatted for input using Suma's built-in "Direct JSON Import" function in the sumaserver/admin panel. The import format is described here: https://github.com/cazzerson/Suma/issues/17 

The import generator only creates JSON data to be copied and pasted into Suma's Direct JSON Import tool -- it does **not** submit data to Suma.

## Demo

You can see a demo of the import generator at: http://www6.wittenberg.edu/lib/ken/sumaimport

Because the generator does not actually submit anything to Suma, you may feel free to play with the demo without undue consequences. 

You can see some test output from that same demo at: http://www6.wittenberg.edu/lib/ken/sumaimport/index.php?date=09%2F04%2F2014&time=9%3A30pm&counts[3]=5&counts[4]=3&counts[5]=61&counts[6]=24&counts[7]=32 

## Credits

* Ken Irwin (kirwin AT wittenberg DOT edu) - initial creator