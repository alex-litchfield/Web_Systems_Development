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
            var analysis1 = responseData.Amendment14.analysis1;
            var analysis2 = responseData.Amendment14.analysis2;
            var analysis3 = responseData.Amendment14.analysis3;
            var analysis4 = responseData.Amendment14.analysis4;
            var analysis5 = responseData.Amendment14.analysis5;
            var section1 = responseData.Amendment14.section1;
            var section2 = responseData.Amendment14.section2;
            var section3 = responseData.Amendment14.section3;
            var section4 = responseData.Amendment14.section4;
            var section5 = responseData.Amendment14.section5;
            $(".amendmentOut1").text(section1);
            $(".amendmentOut2").text(section2);
            $(".amendmentOut3").text(section3);
            $(".amendmentOut4").text(section4);
            $(".amendmentOut5").text(section5);
            $("#analyze1").text(analysis1);
            $("#analyze2").text(analysis2);
            $("#analyze3").text(analysis3);
            $("#analyze4").text(analysis4);
            $("#analyze5").text(analysis5);
        }
    });
});

