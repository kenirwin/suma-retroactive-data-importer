# Suma Retroactive Data Importer

**Suma Retroactive Data Importer** is a tool for retroactively submitting data into **Suma: A Space Assessment Toolkit** (https://github.com/cazzerson/Suma). Suma is a space usage/statistics package; it is natively intended for real-time data submission. This import generator allows the user to input data hours, days, weeks, or even years after the fact. 

## Demo

See a demo of the Suma Retroactive Data Importer functionality at: http://www6.wittenberg.edu/lib/ken/demo/suma-import-generator . Note: Suma Retroactive Data Importer sends data directly into Suma; the demo disables the direct submission feature and instead only shows the JSON-formatted data required to complete the submission. Suma Retroactive Data Importer would typically be configured to send the data directly into Suma.

## Installation

Install the Suma Retroactive Data Importer in a folder *outside* of the main Suma web space. Copy the config-sample.php file to be config.php, then configure the config.php file with the URL for your Suma Server. 

### Suma Server Security

This program takes advantage of the fact that Suma allows submission of JSON-formatted requests directly via an HTTP call. It would be wise, though not strictly necessary, to restrict Suma Server access. To do this, add a few lines to the .htaccess directory:
```
# ALLOW USER BY IP
<Limit GET POST>
 order deny,allow
 deny from all
 allow from YOUR.LOCAL.IP.ADDRESS
</Limit>
```

The sumaserver URL needs to be accessible by the web/tablet client as well, so if you can use an IP range to identify several allowable sources of input, you can format the IP address as xxx.xxx instead of xxx.xxx.xxx.xxx


## Usage

This tool is set up to take head-count data from essentially a single moment / a few minutes from multiple locations. Suma allows many different kinds of counts and this import generator may not support all of them. This tool is currently set up to support:
* input from multiple initiatives
* multiple counts from one time slot at multiple locations. 
* inputing time, date, locations, and counts are set by the user on the input web page (index.php), eg: Date: 2014-09-04; Time: 9:30pm; Locations: First, Second, Third Floors; Date: 2014-09-04 [Demo Data #1]
* multiple 'activities' (using Suma's term): e.g. a **Reference** question answered by a **librarian** over the **phone** in **under a minute**, at the **Reference desk**; Time: 9:30pm; Date: 2014-09-04 [Demo Data #2] 

Using this input, the Suma Retroactive Data Importer creates JSON formatted data and submits it using Suma's API. The JSON format it uses is describe here: https://github.com/cazzerson/Suma/issues/17 


## Demo

You can see a demo of the import generator at: http://www6.wittenberg.edu/lib/ken/demo/suma-import-generator

Because the generator does not actually submit anything to Suma, you may feel free to play with the demo without undue consequences. 

You can see some test output from the demos described above: 
* Demo Data #1: http://www6.wittenberg.edu/lib/ken/demo/suma-import-generator/index.php?date=09%2F04%2F2014&time=9%3A30pm&counts[3]=5&counts[4]=3&counts[5]=61&counts[6]=24&counts[7]=32 
* Demo Data #2: http://www6.wittenberg.edu/lib/ken/demo/suma-import-generator/index.php?initiative=3&date=09%2F04%2F2014&time=9%3A30pm&counts[9]=1&activities[]=16&activities[]=19&activities[]=26&activities[]=27

## Credits

* Ken Irwin (kirwin AT wittenberg DOT edu) - initial creator

## Acknowledgements
* Jason Casden & Bret Davidson are the project lead and technical lead for the original Suma project. They have both been very helpful in encouraging and facilitating work on the Suma Retroactive Data Importer. Special thanks to Bret for some extra help understanding the Suma API.
