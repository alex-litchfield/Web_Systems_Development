$(document).ready(function(){
    //fills the thumbs up or defills it
    var likes = 0;
    var hasClicked = false;
    $('.material-symbols-outlined').on('click', function(){
       $(this).toggleClass("fillThumbs");
        if(!hasClicked) {
            likes += 1;
            document.getElementById("output").innerHTML = likes;
            hasClicked = true;
        } else {
            likes -= 1;
            document.getElementById("output").innerHTML = likes;
            hasClicked = false;
        }
    });

    $.ajax({
        type: "GET",
        url: "../amendments.json",
        dataType: "json",
        success: function(responseData, status) {
            var analysis = responseData.Amendment9.analysis;
            var description = responseData.Amendment9.amendment;
            $(".amendmentOut").text(description);
            $("#analyze").text(analysis);
        }
    });
});

