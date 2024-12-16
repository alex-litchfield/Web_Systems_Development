Ameya's README

9/22/24:
Created HTML structure, along with some CSS styling for the Event's planner page. I didn't find a part
here where I felt that I struggled. 

9/29/24:
Created homepage HTML structure and CSS styling. One point I struggled with was figuring out how to make 
it so that the first div took up the whole screen, so that a second div could be added that would show up 
once a user scrolls. I searched on stack overflow, and edited their code a bit, so that our vision for the
homepage could be fulfilled.  


10/13/24:
Worked on Google Calendar API. Created ability to login to API and display calendar contents. However 
here I ran into issues regarding CORS window.opener issues. I first asked help from Aleksandr, then 
Dr. Callahan, and was still not able to find a fix to this issue. 


10/20/24:
I did more research into why I was getting CORS issues, and realized that the easiest fix to these issues was to utilize Node.js. I then asked Dr. Callahan if I were able to use this package, but
he recommended to utilize PHP instead, which I plan on trying to implement next time.
I also worked on the Here Maps API implementation. I displayed the map, and created a function
to perform reverse geocoding (displaying a marker based on long and lat values). I didn't have
issues here, as the documentation was very detailed. The only part I did have to look outside of the
documentation was for changing the Icon of the marker. I found a good stack overflow page that had
what I needed. 

10/21/24:
Today I restyled the Event Hub page to make it looker more professional/cleaner. I also implemented the 
Google Calendar API, utilizing PHP (which fixed the CORS issues). I also started to restyle the website,
as the purple color scheme looked bad/unprofessional. Today I also asked Alkesandr to help me download
Composer. I also had a lot of trouble downloading the google-api-php client, and tried to fix this issue with
Aleksandr. He reccommended a couple things (changing library version to an older version), but that ended up not working.
We also tried to download Composer to XAMPP, to see if that would help us download the library. Alas, this was to no avail. 

10/22/24:
Today I tried to switch to the Outlook mail API, however that proved to be too difficult, so I decided to 
create the PHP server so that I could bypass the CORS issue that I am getting from the Google Calendar API.
I again had difficulty downloading the library, but this time I noticed that the error stated that the inputted
file name was too long for my compiler to read. I Googled how to fix this issue, and changed a setting on my 
computer, that allowed it to read really long names. After that, I was able to download the library fine. 
I then tried hosting the server, and noticed that none of the css styling was working. I was messing around with 
the website(changing paths), and noticed that the paths to access the different tabs in the navbar were incorrect.
Instead of the link taking you to for instance eventPlanner/index.html, it would just take you to eventPlanner. This
automatically loaded index.html, and looked fine locally. However, for some reason, when looking at this file on the
php server, because the url didn't end with "index.html", the CSS styling was not loaded. I fixed all the paths, and
the website looked fine again. The last issue that I had was figuring out how to commit my changes to the GitHub. Because
the library downloaded some php files, my GitHub desktop wasn't allowing me to push these changes to the remote VM. As a result,
I had to ssh into my azure instance, cd into /var/www/html, and then do "git add .". I was facing issues, because I cd into 
/var/www/html/groupProject. I did "cd ..", then ran the git add and git pull, and then all of my changes were pushed to the
remote GitHub branch. 

10/23/24:
Today I FINALLY made the Google Calendar API work! At first I tried to just look at the google-api-php client api documentation
in order to figure out how to work with this API. However, that did not help at all, and I was stuck on how to fix this issue for hours. 
I would look through their exapmle of connecting to OAuth, along with the full code examples of websites they made. 
After realizing this the documentation was no help, I decided to look through YouTube to find out how to 
connect to OAuth using the php client. I found this one person who linked her website, and I followed those steps. 

10/24/24:
Today I fixed the issue where the google-api-php client was not working on the VM. I asked Dr. Callahan to help me today,
and first we thought the issue was with security in the vm. To figure this out, we cloned the team17 groupProject directory
into my personal vm. We saw that it was not a security issue, so we looked into the PHP code of my index.ph in the groupProject
directory. I went line by line and delete some php sections of the code to see where the issue was coming from. I found out that 
it was because my vm couldn't connect to the /vendor/autoloader path. This made us realize that composer wasn't on my vm. I then
followed a tutorial on installing composer locally, and it finally worked. Now the APIs work on the vm. 

*Also note, I merged a lot of my own changed today, because Dr. Callahan said I could in order to test my APIs on the vm.*

Today I also styled the events that were returned by the API, so that it looked nice. 

11/10/24:
Today I updated the code Nelson made for the login and signup features. He created the hashing, and inputing values 
into the database. Today, I made this information functional, using session data. I made it so that when user is logged in,
if they are the events planner page, they will no longer see the home page or the sign in option. Instead, the sign in 
option will now state logout. I only made this work on the events planner page, as I wanted to get my groups input on whether
I should implement this on all pages or not. I didn't struggle too much today, but this did take a long time for me to implement,
as I wasnt sure how to use session data. What helped me was looking through W3 schools' documentation. Additionally, printing out 
the session data helped me alos notice when and when my code was not working. 

11/21/24:
Today I updated the display of the calendar code. Now it will display the actual Googele Calendar, instead of just the events in the
calendar. I didn't really have much trouble with this. I watched a YouTube video and was able to pretty quickly implement this feature. 
This is the personal Google Calendar of the user.

11/26/24:
Today I fixed the issue that arose after one of my teammates pushed their changes. The issue was that for some reason, after my teammate 
pushed their changes, the map would not display anymore. I tried a lot of things, from changing the window.onload to an element after
the DOM loaded. However that didn't work. I then looked through my index.php file, fixed indentation, and now the map displays properly.
Today I also helped my Alex fix issues with session data. Also, in the events planner page, I added a button that will show up in the 
club description div box. It doesn't have functionality yet, but it will eventaully allow users to add the club's meeting time to their 
personal calendar. 

11/27/24:
Today I added functionality for the clubs location to be displayed on the map. When you click a club, it will display the location of that 
club, along with your own location. To do this, I had to add a new column in the clubs table in the database. I also added a new table in the database, that would hold the lat and long of a location. I had issues here with trying to figure out how to change the background of the 
marker on the map. I was able to figure this out, by looking at a stackoverflow website, which described how to do CSS styling in JS. 
I then realized that I would want this data to not be static, so I deteled the static JS dictionary, and created a table in the database,
that holds the longitude and latitude of all the club locations. I had a lot of trouble at first with trying to figure out why my map would
stop working when I would add a map marker at the location of the club. At first I thought it was because my code wasn't fetching the data 
before the data was being displayed. To fix this, I edited my fetch code to make a promise. However, once this did not fix it, I printed out the longitude and latitudes. I then realized when inserting the longitude and latitude into the database, I swapped the values (ie I set 
longitude equal to latitude, and latitude equal to longitude). I fixed this and now the functionality works. I also added a php file the different locations on campus. Once you run this php file, it will delete all rows in the locationLatLong table. It will then repopulate 
this database with the locations written in clubLocations.json. 

11/28/24:
Today I added functionality for users to add club meeting times to their calendar. To make this work, I had to add two new columns to the
clubs table (startTim, and endTime). The first issue I had with this was that, since the "Add to calendar" button was written into the html
using JS, I wasn't able to use PHP in order to write into the users' Google Calendar. To fix this issue, I made it so that the Add to calendar
button will call a php file, which is responsible for creating an event in the Google Calendar. The next issue was that my php file would keep
returning an error anytime I would call it. To figure out where in this code the function was breaking, I would write: 
  echo json_encode(['success' => 'Made it']);
after each major part of the function. Doing this allowed me to notice that one, my file pathing for connecting to the autoloader.php, and 
client secret were incorrect. The second issue that I saw through using this method was that my first if statement had a typo, meaning that
the if statement would never be entered. 

11/29/24:
Today I added functionality for admins to delete clubs. I also added ability to reset the database with clubs from a json file. I also 
added a column in the database that holds the day of the week that each club meets. I also added the day the club meets on the UI. I didn't 
really have trouble with this implementation, as I just referred to previously written code (the reset DB was something I worked on yesterday as well). 

11/30/24:
Added security implementation, so now only admins can reset database.

12/07/24:
Today I got fixed the bug in the event planner page. For some, whenever users would refresh the page after signing into their 
google account, the connection to the Google API would be severed. At first, I thought it was just an issue on the VM, so I was 
trying to see if messing with the session variables would fix this. For instance, I tried to have checks such that if session data
was not set, it would make you loggin to your Google account again. However this ended up not working. Then, after a while of doing this
I went to check if I could mess with the code on my local. I then started to see this issue on my local as well. But this time, the error 
code was being shown. I then search this code up on stack overflow, and found out that I had a buffer issue. I added ob_clean() and 
ob_end_flush(), and was able to fix my code. Today I also make the event planner page look better. When the middle column would increase
in height, the left column would not increase with it, but now it does. I also got rid of some borders, and scroll-bars to make the page
look more clean.

12/08/24:
Today I worked on the styling of the event planner page. I also tried to fix the numerous amounts of inputs that would be inserted 
by the zap report. The zap report said that there were 0 red flags, however there were still ZAP data inside of the database.
However, after a couple hours of testing (I edited code, ran sql injections by hand, and re-ran ZAP), I realized that the inputs
are not an indicator of an sql injection. Rather, it was that these were the inputs ZAP made to test if our website had any red
flags. 

12/09/24:
Today I set up environment variables. Today I also fixed the bug in the discussion forum, where if users didn't login through using the 
sign in button, they would not be able to view messages on the discussion forum. I realized pretty quickly what the issue was, after
looking at the console log and noticing that one of the json arrays returned by a php call was null. I went through the php file that
was being called, and noticed that the reason this was occuring was because a variable was initialized out of scope. I fixed this, and
then noticed another issue; the delete button will still show up even if a user is not logged in. I looked at the code that displayed
the 'X', and noticed that it just checked if a json obj was returned, and not if the json obj had a valid email. I changed this, and the
bug was fixed. 

Resources:
https://stackoverflow.com/questions/28071170/how-to-make-a-div-full-screen-and-scrollable
https://www.w3schools.com/css/css3_buttons.asp

https://stackoverflow.com/questions/62421695/google-calendar-api-php

https://www.youtube.com/watch?v=Q-498CAa1xE&themeRefresh=1

https://developers.google.com/calendar/api/guides/overview

https://www.w3schools.com/jsref/jsref_tolocalestring_number.asp

https://www.w3schools.com/jsref/met_document_getelementsbyclassname.asp

https://www.youtube.com/watch?v=c2b2yUNWFzI

https://medium.com/@aniruddhafc/getting-started-with-here-maps-a-step-by-step-guide-d524ceb5f162

https://medium.com/@dtkatz/3-ways-to-fix-the-cors-error-and-how-access-control-allow-origin-works-d97d55946d9

https://stackoverflow.com/questions/58372337/how-to-fix-cors-error-request-doesnt-pass-access-control-check

https://www.here.com/docs/

https://stackoverflow.com/questions/57792763/how-to-do-labeled-markers-in-here-maps-with-javascript-api

https://www.w3schools.com/css/css3_flexbox_container.asp

https://www.w3schools.com/css/css_border.asp

https://www.w3schools.com/cssref/pr_border-width.php

https://www.w3schools.com/css/css_align.asp

https://colibriwp.com/blog/soft-color-palette/

https://github.com/googleapis/google-api-php-client

https://getcomposer.org/

https://www.autodesk.com/support/technical/article/caas/sfdcarticles/sfdcarticles/The-Windows-10-default-path-length-limitation-MAX-PATH-is-256-characters.html

https://www.youtube.com/watch?v=yrFr5PMdk2A

https://github.com/mikepatrick/php-google-calendar/blob/master/index.php

https://codewithsusan.com/notes/youtube-api-php-oauth-connection

https://www.youtube.com/watch?v=AU_YLT7LmEA

https://getcomposer.org/doc/00-intro.md

Aleksandr

Dr. Callahan

https://www.w3schools.com/php/func_date_date.asp

https://www.w3schools.com/php/php_sessions.asp

https://stackoverflow.com/questions/38790552/xampp-issue-localhost-showing-unwanted-apache2-ubuntu-default-page

https://stackoverflow.com/questions/10596218/how-to-write-javascript-code-inside-php

https://www.youtube.com/watch?v=fqPXx03Rl2Y

https://stackoverflow.com/questions/6840326/how-can-i-create-and-style-a-div-using-javascript

https://stackoverflow.com/questions/48274331/insert-event-in-google-calender-using-php

https://developers.google.com/calendar/api/guides/recurringevents#php

https://www.w3schools.com/jsref/jsref_parseint.asp

https://stackoverflow.com/questions/3715047/how-to-reload-a-page-using-javascript

https://www.w3schools.com/jsreF/met_win_alert.asp

https://stackoverflow.com/questions/9707693/warning-cannot-modify-header-information-headers-already-sent-by-error

https://stackoverflow.com/questions/791231/force-sidebar-height-100-using-css-with-a-sticky-bottom-image