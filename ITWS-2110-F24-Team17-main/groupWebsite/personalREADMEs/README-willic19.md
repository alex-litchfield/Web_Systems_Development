Catherine Williams README

(9/22) I created the Health page json file and filled it with descriptions and club names. I also filled in all information inside the html for the three posters. I then researched new mental health resources and placed them inside the health json file.

(10/02) I created the html and CSS for the right side of the page. I made a split screen effect and put boxes and circles into the right side. These are to be filled with data from the json file I imagine, but not sure what the plan is yet. I will meet with Grace later in the week to discuss this!

(10/02) I structured the page to look exactly like the balsalmiq, except the colors because they were not pretty. They are still not pretty, but they are also not as bad as before. I linked all the css help that I found online.

(10/23 and 10/24) I implemented functions to further encrypt login and sign in data. Any kind of tags are now stripped from user input of strings and email validation has been implemented. I also implemented a system that tells users if their email is invalid and redirects them back to the signup page until their email is valid.

(10/24) I adjusted the Healthpage css and javascript so that formatting would display nicely. The on load function is still a bit off, but I have been trying to fix that as well.

(11/14) Created tables and input fake data. Created folder for admin approval files. Created index.html inside said folder.

(11/15) Read in data from the club approval table and displayed them on the index.html page. Did some formatting.

(11/16) Read in the health approval table and displayed. Made formatting actually nice with hover effects to enlarge and create the dropdown effects. Created buttons and started work on delete functionality.

(11/17) Completed delete functionality for clubs. Fixing bugs on health delete functionality.

(11/18) Completed delete functionality on health (I had a typo in the php file). Reformatted with css and added more on hover functionality (buttons have effects now like pointer, color changes, and size changes). It doesn't look awful anymore, so that's good.

(11/19) Fixed functionality on club and resource approvals. Page is now fully functional. Fixing styling.

(11/30) Created a form for clubs to be put into the club approval table from the event hub page.

(12/01) The club adding form hides and displays properly. I created a working form for the health page approval table and formatted it as a popup. Fixed dynamic display of health page by utilizing promise with fetching data.

(12/07) I started this before, but I can't remember what day. I made a JSON file with weekdays and read them into the adminApproval.js file so that I could use the proper weekday for the clubs data and pass that into the addClubs.php file. This fixes the display on the eventPlanner page so that clubs add to their respective days on google calendar. I also made it so the time inputs on the add club form located on the eventPlanner page are in a specific format. I hardcoded in the weekdays dropdown because they are always going to remain the same as they are added to a json file and not mysql anyways. I dynamically read in the locations from mysql using php and JS to create the locations dropdown menu.

Cited Resources(a lot of w3schools):
https://www.w3schools.com/howto/howto_css_split_screen.asp#:~:text=/*%20Split%20the%20screen%20in%20half%20*/.split%20{%20height:%20100%;
https://www.w3schools.com/howto/howto_css_circles.asp#:~:text=To%20create%20a%20circle,%20use%20the%20border-radius%20property%20and%20set
Flex:
https://www.w3schools.com/csS/css3_flexbox_container.asp
https://creazilla-store.fra1.digitaloceanspaces.com/icons/3206641/plus-circle-icon-md.png
https://i.pinimg.com/736x/a1/6c/0e/a16c0e05cce0241448d299e75835e03e.jpg
Stripping (sanitizing): https://stackoverflow.com/questions/48639248/what-is-difference-between-php-strip-tags-and-filter-var-function
email validator: https://www.w3schools.com/php/filter_validate_email.asp
https://www.w3schools.com/php/php_mysql_delete.asp
https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_custom_scrollbar2
https://www.w3schools.com/jsref/tryit.asp?filename=tryjsref_element_setattribute2
https://www.w3schools.com/php/php_mysql_delete.asp
https://www.w3schools.com/php/php_mysql_insert_multiple.asp
https://www.w3schools.com/howto/howto_js_popup.asp
https://www.w3schools.com/html/html_form_elements.asp
https://www.w3schools.com/html/html_form_input_types.asp#gsc.tab=0
https://www.w3schools.com/tags/att_input_type_time.asp#gsc.tab=0
