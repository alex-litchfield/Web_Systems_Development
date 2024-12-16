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
            var analysis1 = responseData.Amendment20.analysis1;
            var analysis2 = responseData.Amendment20.analysis2;
            var analysis3 = responseData.Amendment20.analysis3;
            var analysis4 = responseData.Amendment20.analysis4;
            var analysis5 = responseData.Amendment20.analysis5;
            var analysis6 = responseData.Amendment20.analysis6;
            var description1 = responseData.Amendment20.section1;
            var description2 = responseData.Amendment20.section2;
            var description3 = responseData.Amendment20.section3;
            var description4 = responseData.Amendment20.section4;
            var description5 = responseData.Amendment20.section5;
            var description6 = responseData.Amendment20.section6;
            $(".amendmentOut1").text(description1);
            $(".amendmentOut2").text(description2);
            $(".amendmentOut3").text(description3);
            $(".amendmentOut4").text(description4);
            $(".amendmentOut5").text(description5);
            $(".amendmentOut6").text(description6);
            $("#analyze1").text(analysis1);
            $("#analyze2").text(analysis2);
            $("#analyze3").text(analysis3);
            $("#analyze4").text(analysis4);
            $("#analyze5").text(analysis5);
            $("#analyze6").text(analysis6);
        }
    });
});

