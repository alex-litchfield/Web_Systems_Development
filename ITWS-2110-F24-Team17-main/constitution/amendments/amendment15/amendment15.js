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
            var analysis1 = responseData.Amendment15.analysis1;
            var analysis2 = responseData.Amendment15.analysis2;
            var description1 = responseData.Amendment15.section1;
            var description2 = responseData.Amendment15.section2;
            $(".amendmentOut1").text(description1);
            $(".amendmentOut2").text(description2);
            $("#analyze1").text(analysis1);
            $("#analyze2").text(analysis2);
        }
    });
});

