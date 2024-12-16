var resourceData = "";
function displayResources() {
    $('#moreResources').html(resourceData);
}

//use PHP to fetch that information out of the database and display it on the frontend
function fetchResources() {
    return new Promise((resolve, reject) => {
        $.ajax({
    
            url: "display.php",
            type: "GET",
            data: {option: "", action: ""},
            dataType: "html", 
            success: function(response) {
                console.log(response);
                //insert the HTML into a container
                resourceData = response;
                resolve(response);
            },
            error: function(error) {
                reject(new Error("Error fetching resources: ", error));
            }
        });
    })
}

$(document).ready(async function(){
    try {
        await fetchResources();
        displayResources();
    }
    catch(error) {
        console.error("Error fetching resources: ", error);
    }

    // $.ajax({
    //     url: "initial.php",
    //     type: "POST",
    //     data: {option: "", action: ""},
    //     dataType: "json",
    //     success: function(response){
    //         console.log(response);
    //     }
    // });

    //toggle between showing the contents in the dropdown
    $(".dropdown").click(function(){
        $("#options").toggle();
    });   

    $("#formButton").click(function() {
        formId = document.getElementById("approvalFormContainer");
        formId.style.display = "block";
    });

    $("#closeButton").click(function() {
        formId = document.getElementById("approvalFormContainer");
        formId.style.display = "none";
    });


    //when likes is clicked, show the posts sorted by most liked
    $("#likes").click(function(){
        $.ajax({
        
            url: "display.php",
            type: "GET",
            data: {option: "likes", action: ""},
            dataType: "html", 
            success: function(response) {
                console.log(response);
                //insert the HTML into a container
                $('#moreResources').html(response);
        
            }

        });

    });


    //when date-posted is clicked, show the posts sorted by most recent
    $("#date").click(function(){
        //use PHP to fetch that information out of the database and display it on the frontend
        $.ajax({
        
        url: "display.php",
        type: "GET",
        data: {option: "date", action: ""},
        dataType: "html", 
        success: function(response) {
            console.log(response);
            //insert the HTML into a container
            $('#moreResources').html(response);
    
        }
    });

    });

    //delete a post from the database
    $(document).on('click', '.trash', function() {
        //check if the user is sure about deleting

        //get the id name of the div it is wrapped around
        var deleteElement = $(this).closest("div").attr("id");

        var headerText = $(this).closest("div").find("h2").text();


        $('#dialogTwo p').text('Are you sure you want to delete "' + headerText + '"?');

        $( "#dialogTwo" ).dialog({
            buttons: [
                {
                    text: "Yes",
                    click: function() {
                        $(this).dialog( "close" );
                        
                        $.ajax({
                            url: "display.php",
                            type: "GET",
                            data: {option: "", action: "", deletePost: deleteElement},
                            dataType: "html", 
                            success: function(response) {
                                console.log(response);
                                //insert the HTML into a container
                                $('#moreResources').html(response);
                        
                            }

                        });

                    }
            
                },
                {
                    text: "No",
                    click: function() {
                        $( this ).dialog( "close" );
                        
                    }
            
                }
            ]
        });

    });


    
    //performs ajax request to update like count in the database
    function removeLikeRequest(id, result){
        return new Promise((resolve, reject) => {
            $.ajax({//update database with the new change
                url: "update.php",
                type: "POST",
                data: JSON.stringify({object: id, likeCount: result}),
                contentType: "application/json",
                success: function(response){
                    console.log(response);
                    resolve(response);
                },
                error: function(message){
                    console.log(message.status + message.error);
                    reject(new Error("Error updating the like count"));
                }
            });
        });
    }
    //performs ajax request to update like count in database
    function addLikeRequest(id, result){
        return new Promise((resolve, reject) => {        
            $.ajax({ //update database with the new change
                url: "update.php",
                type: "POST",
                data: JSON.stringify({object: id, likeCount: result}),
                contentType: "application/json",
                success: function(response){
                    console.log(response);
                    resolve(response);
                },
                error: function(message){
                    console.log(message.status + message.error);
                    reject(new Error("Error updating the like count"));
                }
            });
        });
    }
    //to be used when a health resource is liked
    async function fetchLikedHealthData(id){
        return new Promise((resolve, reject) => {
            $.ajax({ //display new change
                url: "display.php",
                type: "GET",
                data: {option: "", action: "like", target: id},
                dataType: "html", 
                success: function(response) {
                    console.log(response);
                    //insert the HTML into a container
                    $('#moreResources').html(response);
                    resolve(response);
                },
                error: function(message){
                    console.log(message.status + message.error);
                    reject(new Error("Error updating the like count"));
                }
            });
        });
    }

    //to be used when a health resource is unliked
    function fetchUnlikedHealthData(id){
        return new Promise((resolve, reject) => {
            $.ajax({ //display new change
                url: "display.php",
                type: "GET",
                data: {option: "", action: "unlike", target: id},
                dataType: "html", 
                success: function(response) {
                    console.log(response);
                    //insert the HTML into a container
                    $('#moreResources').html(response);
                    resolve(response);
                },
                error: function(message){
                    console.log(message.status + message.error);
                    reject(new Error("Error fetching updated health likes"));
                }
            });
        });    
    }

    //update database with newly removed like
    async function removeLike(id, result){
        try{
            await removeLikeRequest(id, result);
            await fetchUnlikedHealthData(id);
        }
        catch{
            console.error(error);
        }
    }

    //update database with newly added like
    async function addLike(id, result){
        try{
            await addLikeRequest(id, result);
            await fetchLikedHealthData(id);
        }
        catch{
            console.error(error);
        }
    }


    //fills the leaf or defill it, implemented like counter next to it
    //used to like post/remove like

    $("#moreResources").on('click', function(event){
        //if like button is clicked...
        if (event.target.classList.contains('material-symbols-outlined')){
            //console.log(event.target.classList);

            var parent = event.target.parentElement;
            var trueLikeCountElement = parent.querySelector('.true-count');
            var trueLikeCount = trueLikeCountElement.innerHTML;
            //if health resource is already liked -> remove like
            if (event.target.classList.contains('fillLeaf')){
                let result = parseInt(trueLikeCount) - parseInt("1");
                console.log(Number.isInteger(result));
                let id = parseInt(event.target.id);
                console.log(result);
                console.log(Number.isInteger(id));
                event.target.classList.remove('fillLeaf');
                removeLike(id, result);
                event.target.classList.remove('fillLeaf');
            }
            //if health resource is yet to be liked -> add like
            else {
                let result = parseInt(trueLikeCount) + parseInt("1");
                console.log(Number.isInteger(result));
                let id = parseInt(event.target.id);
                console.log(result);
                console.log(Number.isInteger(id));
                console.log(id);
                event.target.classList.add('fillLeaf');
                addLike(id, result);
                event.target.classList.add('fillLeaf');
            }
        }
    });
        

        // if (event.target && event.target.classList.contains('material-symbols-outlined')){
            
        //     $(this).toggleClass("fillLeaf");
        //     var parent = $(this).parentElement.id;

        //     //obtain the current like count
        //     var trueLikeCountElement = parent.querySelector('true-count');
        //     var trueLikeCount = trueLikeCountElement.innerHTML;

        //     if ($(this).hasClass('fillLeaf')) {
        //         //liking 
        //         trueLikeCount += 1;
        //     } else {
        //         //removing like
        //         trueLikeCount -= 1;
        // }

        //     //update the like count display
        //     trueLikeCountElement.innerHTML = trueLikeCount;

        //     //update the icon's content (if needed)
        //     var displayLikeCount = parent.querySelector('true-count');
        //     displayLikeCount.innerHTML = "psychiatry " + trueLikeCount;
        //     console.log(trueLikeCount);
        //}
        
    

        

    

     

});
