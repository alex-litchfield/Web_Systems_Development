Grace Hui readme

9/22: Worked on the health page to display the health clubs at the top and the posts at the bottom of the page.
Users can click on the post to like it, but will need to have set the counter up later. 

9/30: Worked on the professor picker page to have the user navigate which department, professor, and course
they would like to see the rating for. Also created a navbar to navigate to other pages of our website, but 
we decided to choose a different design.

10/21: Installed phpmyadmin on our VM and inserted our sql database into it.

10/22: Reworking the mental health database to hold the information of each post, which includes time created. This is necessary, so I can sort by relevance/date posted on a filter button.

10/23: I pulled our json of posts into our database and displayed it on our frontend. Also, I added the functionality of the user being able to sort through the posts by most likes and recently posted.

10/24: I created an admin page for the health page. The admin can delete post from the page, which is reflected in the database. There is another pop up to confirm that they want to delete it. Additionally, admin can add post to the page by clicking on the circle button. They fill out the form and once submitted, it will be reflected on the page and in the database.

10/25: I did some backend on the professor picker page. I grabbed information from the professors json and placed that in the frontend. All the departments are there. When you click on the department, you are redirected to a courses page that shows you all the courses in that department. For each course, the professor is listed. 

11/4: I created the reviews database. User can not view the reviews stored in the database for each professor/course selected. I started creating a form for when the user wants to enter a review. It is not fully implemented because I am waiting for Ameya to work on having the user information stored when logged in. If "Anonymous" is not checked off, then it will automatically post with the name you logged in with.

11/12: I calculated the average rating and let the user know if there are no reviews yet.

11/16: I finished the add review to the page and it saves into the database. The average rating is recalculated. I changed the form, so the user places their name if they do not want to anonymous.

11/22: Added styling for professor picker and fixed the anonymous functionality.

11/28: Added error handling for the add review form. Changed it so if they did not click anonymous, then it would take their name from session data.

11/29: If the user is signed in, then they can add a review. If they are not signed in, then there us a pop-up to redirect them to the sign in page.

12/2: Admin can delete review on the professor picker page. Only admin can do that and it updates the average on the screen along with the reviews shown. It is saved in the db

12/7: Have the admin display change when the admin logs in. When the admin clicks add resource, it will add to the page. If they weren't admin, then it will be sent to request for change. Also changed a bit of some ui, like the icon like and enlarge when hover.

12/8: Quacs added to footer for credibility. Shows where we got our data from.

12/9: Added animations for the professor index.php and review,php. When the user loads or scrolls on the departments or review posts, there is a fade in effect. I changed the filter button UI, add review UI, the icon for delete for admins, and add review form UI. The user can view the amount of stars they clicked.

10/10: fixed bug where had to reload with fade in when delete is called. Add dateposted so it can be used for filter by most recent. User can now filter button based on most recent, negative first, or positive first. I added animations and other ui element to professor picker page. Worked on UI and backend for health page, but we didn't commit it to main because it is incomplete.

None code contributions: 
Finding APIs and resources for our group. Found the HERE API for Ameya because Google Maps was asking for a credit card. Found a json of professor information from the GitHub of QUACS, so we could use that information into our pages. Found another API for chats, but not implemented yet.

Sources:
Health Page:
https://www.w3schools.com/howto/howto_css_cards.asp
https://www.vecteezy.com/free-vector/counseling (counseling image)
https://www.statology.org/mysql-insert-timestamp/ 
https://www.php.net/manual/en/mysqli-result.fetch-all.php
https://www.w3schools.com/php/php_echo_print.asp#:~:text=They%20are%20both%20used%20to,is%20marginally%20faster%20than%20print%20.
https://css-loaders.com/spinner/
https://www.w3schools.com/howto/howto_js_dropdown.asp
https://developer.mozilla.org/en-US/docs/Web/HTML/Element/textarea
https://www.w3schools.com/php/func_date_time.asp 
https://www.w3schools.com/php/php_date.asp
https://www.php.net/manual/en/function.header.php
https://api.jqueryui.com/dialog/
https://stackoverflow.com/questions/42894744/jquery-ajax-post-with-data
https://www.w3schools.com/tags/tag_textarea.asp



Professor Page:
https://www.w3schools.com/howto/howto_js_sidenav.asp
https://www.w3schools.com/bootstrap/tryit.asp?filename=trybs_ref_js_dropdown_multilevel_css&stacked=h 
https://www.geeksforgeeks.org/mysql-group_concat-function/
https://www.w3schools.com/sql/sql_like.asp
https://stackoverflow.com/questions/36722378/php-prevent-duplicates-when-building-an-array
https://www.w3schools.com/js/js_window_location.asp
https://stackoverflow.com/questions/27765666/passing-variable-through-javascript-from-one-html-page-to-another-page
https://www.w3schools.com/sql/sql_count.asp
https://www.geeksforgeeks.org/how-to-count-rows-in-mysql-table-in-php/
https://stackoverflow.com/questions/12136284/add-an-external-input-value-to-form-on-submit
https://stackoverflow.com/questions/5184856/html-vertical-align-the-text-inside-input-type-button

