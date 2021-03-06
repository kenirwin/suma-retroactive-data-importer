# Suma Retroactive Data Importer

**Suma Retroactive Data Importer** is a tool for retroactively submitting data into **Suma: A Space Assessment Toolkit** (https://github.com/cazzerson/Suma). Suma is a space usage/statistics package; it is natively intended for real-time data submission. This import generator allows the user to input data hours, days, weeks, or even years after the fact. 

## Demo

See a demo of the Suma Retroactive Data Importer functionality at: http://www6.wittenberg.edu/lib/ken/demo/suma-retroactive-data-importer . Note: Suma Retroactive Data Importer sends data directly into Suma; the demo disables the direct submission feature and instead only shows the JSON-formatted data required to complete the submission. Suma Retroactive Data Importer would typically be configured to send the data directly into Suma.

## Installation

Install the Suma Retroactive Data Importer in a folder *outside* of the main Suma web space. Copy the **config-sample.php** file to be **config.php**, then configure the **config.php** file with the URL for your Suma Server (usually named "sumaserver".) 

### Security

Suma Retroactive Data Importer should be installed in a secure directory, as anyone with access to the page will be able to submit data directly into Suma. Consult your system adminitstrator for the most sensible access restriction in your environment. 

This program takes advantage of the fact that Suma allows submission of JSON-formatted requests directly via an HTTP call. It would be wise, though not strictly necessary, to restrict Suma Server access as well. Since calls to the Suma Server will be made in the background, password-restricted access is unsuitable. IP-based access restrictions will be more effective. To do this, add a few lines to the **.htaccess** file in your **sumaserver** directory (this is in the filespace for the main Suma project):
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

Suma allows many different kinds of counts and this import generator may not support all of them. This tool is currently set up to support:
* input from multiple initiatives
* multiple counts from one time slot at multiple locations. 
* inputing time, date, locations, and counts are set by the user on the input web page (index.php), eg: Date: 2014-09-04; Time: 9:30pm; Locations: First, Second, Third Floors; Date: 2014-09-04 [Demo Data #1]
* multiple 'activities' (using Suma's term): e.g. a **Reference** question answered by a **librarian** over the **phone** in **under a minute**, at the **Reference desk**; Time: 9:30pm; Date: 2014-09-04 [Demo Data #2] 

Using this input, the Suma Retroactive Data Importer creates JSON formatted data and submits it using Suma's API. The JSON format it uses is describe here: https://github.com/cazzerson/Suma/issues/17 


## Demo

You can see a demo of the import generator at: http://www6.wittenberg.edu/lib/ken/demo/suma-retroactive-data-importer

Because this demo  does not actually submit anything to Suma, you may feel free to play with the demo without undue consequences. 

You can see some test output from the demos described above: 
* Demo Data #1: http://www6.wittenberg.edu/lib/ken/demo/suma-retroactive-data-importer/index.php?initiative=1&date=10%2F01%2F2014&time=9am&counts[3]=5&counts[4]=35&counts[5]=32&counts[6]=3&counts[7]=14&submit-suma-importer=Submit+Query

* Demo Data #2: http://www6.wittenberg.edu/lib/ken/demo/suma-retroactive-data-importer/index.php?initiative=3&date=10%2F01%2F2014&time=9%3A30pm&counts[9]=2&who_answered_question[]=16&who_answered_question[]=17&type[]=19&medium[]=24&duration[]=28&activity_group_names=who_answered_question%3Btype%3Bmedium%3Bduration&submit-suma-importer=Submit+Query

## Credits

* Ken Irwin (kirwin AT wittenberg DOT edu) - initial creator

## Acknowledgements
* Jason Casden & Bret Davidson are the project lead and technical lead for the original Suma project. They have both been very helpful in encouraging and facilitating work on the Suma Retroactive Data Importer. Special thanks to Bret for some extra help understanding the Suma API.
