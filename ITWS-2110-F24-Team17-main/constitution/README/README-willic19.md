link to constitution website: https://constitution.congress.gov/constitution/

JSON work:
I created the JSON file and structured it so that there wa a menu of amendments and within each amendment is its description and analysis. The sections were separated and there is an analysis for each section, as required in the lab01 instructions. I also filled in all the amendment descriptions for all 27 amendments within the JSON file. The link above is where I found the amendment description information.

Amendments HTML work:
First I copied the formatting (made by Ameya and Grace) from the articles page and pasted it into each HTML file for all 27 amendments. I then copied the resources folder and pasted it into amendments to re-link everything and display the css and such properly. Then I adjusted the HTML based off of the JS so that the JSON would display properly. I did this by adding ids and classes that were locations for the JSON to be read into throught JS.

Amendments JS work:
I just wrote a few lines, referencing my old lab 08 from Intro (ITWS-1100). I did a GET using Ajax and JQuery to retrieve the data from the JSON file. Then I selected the individual descriptions (or sections) and analysis that were to be displayed in the HTML and used their id or class along with the .text function to display said text in the correct locations of the HTML flie.

Other:
Alex and I also attempted to recover a previous version of our GIT repo, which was unsuccessful, but a nice reminder of directory importance. It reminded us to be in /var/www/html when trying to access our GIT repo (Thank You Dr.Callahan!!!!!). Overall, Alex ended up using VSC to correct the error. We also discovered how to fix merge conflicts. I walked Alex through correcting a merge conflict by editing the content of the two conflicting versions via our team voice chat. I also walked a teammate through utilizing the ssh command and team FQDN within the command window.