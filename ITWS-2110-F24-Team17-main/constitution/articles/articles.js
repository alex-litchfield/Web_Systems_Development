$(document).ready(function() {

    //get title
    var titleText = $('title').text().trim(); 

    var output = '';

    // jQuery AJAX function to get constitution text and analysis from JSON and place it into the HTML file
    $.ajax({
        type: "GET",
        url: "./articles.json",
        dataType: "json",
        success: function(responseData, status) {
            //go through each section in the JSON data
            console.log("Works");
            $.each(responseData.analysisList, function(i, section) {
                //check if we are in the right article
                if (titleText === section.article) {
                    output+='<mark>' + section.title + '</mark>' +
                            '<p>' + section.description + '</p>';
    
                }
            });

            
            $('.constitutionText').html(output);
            console.log(output);

            //on click for <mark> elements to show analysis
            $('mark').on('click', function() {
                var title = $(this).text().trim();
                //goe through each analysis
                $.each(responseData.analysisList, function(i, section) {
                    //check if we are in the right article and section
                    if (titleText === section.article && title === section.title) {
                        //get the analysis content for the clicked title
                        
                        $('.analysis-box').html('<div class="analysisNote">' +
                            '<h2 class="just-another-hand-regular">Analysis for ' + title + '</h2>' +
                            '<p class="just-another-hand-regular">' + section.analysis + '</p>' +
                            '</div>').show();
                    }
                
                });
                
            });

        },
        error: function() {
            console.log("Error loading JSON data.");
        }
    });

});
