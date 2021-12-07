# Game-Library
A database to track video game cost, play time, and other statistics. 

## History
This project started as a single google sheet where I tracked my video game purchases and time played. Over time I added so much statistical analysis functions that it overwhelmed the google sheet. After several iterations of optimization on Google Sheets, I re-built the whole thing using PHP any MySQL. It has been maintained for some time by direct edits with no version control. I am now adding it to GitHub in an attempt to bring a little more structure to the development process as well as integrate unit tests.

## Repository Structure
This is actually two different applications that interact with eachother. The server application is a collection of PHP scripts that present the core functionality of tracking the data and doing statistical analysis. The server includes insert functions and some edit ability but no deletes or complex edits are available at this time. For that you would need to interact with the database directly.

The client application is newer and it is two python scripts backed by a JSON config file that you can run locally. 
- launchapp.py launches a game by ID as recorded in the data.json file and starts a clock to track how long you are playing. It also collects some info at the end like rating and keywords before uploading the history record to the database. It does this by interacting with the php script that saves a history record and triggers it as if a form was submitted.
- buildshortcuts.py fills the shortcuts folder with valid windows shortcuts for each game listed in data.json that is also currently installed as determied by the presence of the game file listed in data.json. Each shortcut will call launchapp.py using the appropriate ID.
