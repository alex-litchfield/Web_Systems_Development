function fetchClubApprovalData(clubApprovalData) {
    fetch('getClubApproval.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
                return;
            }
            data.forEach(pendingApproval => {
                const clubApprovalInfo = {
                    id_num: pendingApproval.id,
                    club_name: pendingApproval.club_name, 
                    members: pendingApproval.members, 
                    descriptions: pendingApproval.descriptions, 
                    locations: pendingApproval.locations,
                    dates: pendingApproval.dates,
                    startTime: pendingApproval.startTime, 
                    endTime: pendingApproval.endTime, 
                    dayOfWeek: pendingApproval.dayOfWeek
                };
                clubApprovalData.push(clubApprovalInfo);
            });
            console.log("fetched club approval data.");
            displayClubApprovalData();
        })
        .catch(error => {
            console.error("Error fetching club approval data: ", error);
        });
}

function displayClubApprovalData() {
    const approvalContainer = document.getElementById("clubApproval");
    approvalContainer.innerHTML = "";
    clubApprovalData.forEach(pendingApproval => {
        const name = pendingApproval.club_name;
        const describe = pendingApproval.descriptions;
        const members = pendingApproval.members;
        const locations = pendingApproval.locations;
        const dates = pendingApproval.dates;
        const id_num = pendingApproval.id_num;
        const startTime = pendingApproval.startTime;
        const endTime = pendingApproval.endTime;
        const dayOfWeek = pendingApproval.dayOfWeek;

        const divTag = document.createElement("div");
        const buttonDiv = document.createElement("buttonDiv");
        const dropdownDiv = document.createElement("dropdownDiv");
        const pTag1 = document.createElement("p");
        const pTag2 = document.createElement("p");
        const dropdown1 = document.createElement("p");
        const dropdown2 = document.createElement("p");
        const dropdown3 = document.createElement("p");
        const dropdown4 = document.createElement("p");
        const dropdown5 = document.createElement("p");
        const trash = document.createElement("button");
        const approved = document.createElement("button");

        divTag.classList.add("clubApprovalItem");
        buttonDiv.classList.add("buttonDiv");
        dropdownDiv.classList.add("dropdownDiv");

        pTag1.classList.add("names");
        pTag2.classList.add("descriptions");
        pTag1.textContent = name;
        pTag2.textContent = describe;

        dropdown1.classList.add("dropdown");
        dropdown1.classList.add("members");
        dropdown2.classList.add("dropdown");
        dropdown2.classList.add("locations");
        dropdown3.classList.add("dropdown");
        dropdown3.classList.add("dates");
        dropdown4.classList.add("dropdown");
        dropdown4.classList.add("timing");
        dropdown5.classList.add("dropdown");
        dropdown5.classList.add("dayOfWeek");
        trash.classList.add("deleteClub");
        trash.classList.add("clubButton");
        approved.classList.add("approveClub");
        approved.classList.add("clubButton");

        dropdown1.textContent = "Members: " + members;
        dropdown2.textContent = "Meeting Location: " + locations;
        dropdown3.textContent = "Club submitted on: " + dates;
        dropdown4.innerHTML = "Club times:<br>" + startTime + "-" + endTime;
        dropdown5.textContent = "Club meets on: " + dayOfWeek;
        trash.textContent = "Remove Club";
        approved.textContent = "Approve Club"

        trash.addEventListener("click", function() {
            deleteClubApproval(id_num);
        });
        approved.addEventListener("click", function() {
            addClubApproval(id_num, name, describe, members, locations, startTime, endTime, dayOfWeek, dates);
        })

        divTag.appendChild(pTag1);
        divTag.appendChild(pTag2);
        dropdownDiv.appendChild(dropdown1);
        dropdownDiv.appendChild(dropdown2);
        dropdownDiv.appendChild(dropdown5);
        dropdownDiv.appendChild(dropdown4);
        dropdownDiv.appendChild(dropdown3);
        buttonDiv.appendChild(trash);
        buttonDiv.appendChild(approved);
        divTag.appendChild(dropdownDiv);
        divTag.appendChild(buttonDiv);
        approvalContainer.appendChild(divTag);
    });
}

function fetchHealthApprovalData(healthApprovalData) {
    fetch('getHealthApproval.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
                return;
            }
            data.forEach(pendingApproval => {
                const healthApprovalInfo = {
                    id_num: pendingApproval.id,
                    resources: pendingApproval.resources, 
                    descriptions: pendingApproval.descriptions, 
                    posted: pendingApproval.posted
                };
                healthApprovalData.push(healthApprovalInfo);
            });
            console.log("fetched health approval data.");
            displayHealthApprovalData();
        })
        .catch(error => {
            console.error("Error fetching health approval data: ", error);
        });
}

function displayHealthApprovalData() {
    const approvalContainer = document.getElementById("healthApproval");
    approvalContainer.innerHTML = "";
    healthApprovalData.forEach(pendingApproval => {
        const name = pendingApproval.resources;
        const describe = pendingApproval.descriptions;
        const posted = pendingApproval.posted;
        const id_num = pendingApproval.id_num;

        const divTag = document.createElement("div");
        const dropdownDiv = document.createElement("dropdownDiv");
        const buttonDiv = document.createElement("dropdownDiv");
        const pTag1 = document.createElement("p");
        const pTag2 = document.createElement("p");
        const dropdown = document.createElement("p");
        const trash = document.createElement("button");
        const approved = document.createElement("button");

        divTag.classList.add("healthApprovalItem");
        dropdownDiv.classList.add("dropdownDiv");
        buttonDiv.classList.add("buttonDiv");
        pTag1.classList.add("names");
        pTag2.classList.add("descriptions");
        dropdown.classList.add("dropdown");
        dropdown.classList.add("posted");
        trash.classList.add("deleteResource");
        trash.id = "'resource" + id_num + "'";
        approved.classList.add("approveResource");

        pTag1.textContent = name;
        pTag2.textContent = describe;
        dropdown.textContent = "Resource submitted on: " + posted;
        trash.textContent = "Remove Resource";
        approved.textContent = "Approve Resource";
        
        trash.addEventListener("click", function() {
            deleteHealthApproval(id_num);
        });

        approved.addEventListener("click", function() {
            addResourceApproval(id_num, name, describe);
        })

        divTag.appendChild(pTag1);
        divTag.appendChild(pTag2);
        dropdownDiv.appendChild(dropdown);
        buttonDiv.appendChild(trash);
        buttonDiv.appendChild(approved);
        divTag.appendChild(dropdownDiv);
        divTag.appendChild(buttonDiv);
        approvalContainer.appendChild(divTag);
    });
}

function deleteClubApproval(id_num) {
    fetch('removeClub.php', {
        method: 'POST', 
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({id: id_num})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Club Approval Deleted.');
            location.reload();
        }
        else {
            alert('Could not delete Club Approval.');
        }
    })
    .catch(error => {
        console.error("Error: ", error);
    });
}

function deleteHealthApproval(id_num) {
    fetch('removeResource.php', {
        method: 'POST', 
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({id: id_num})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Resource Approval Deleted.');
            location.reload();
        }
        else {
            alert('Could not delete Resource Approval.');
        }
    })
    .catch(error => {
        console.error("Error: ", error);
    });
}

function addClubApproval(id_num, name, describe, members, locations, startTime, endTime, dayOfWeek, dates) {
    fetch('dates.json')
        .then(response => response.json())
        .then(data => {
            const monday = data.weekdays.Monday;
            const tuesday = data.weekdays.Tuesday;
            const wednesday = data.weekdays.Wednesday;
            const thursday = data.weekdays.Thursday;
            const friday = data.weekdays.Friday;
            const saturday = data.weekdays.Saturday;
            const sunday = data.weekdays.Sunday;

            const dofToWeekday = {
                "monday": monday,
                "tuesday": tuesday,
                "wednesday": wednesday,
                "thursday": thursday,
                "friday": friday,
                "saturday": saturday,
                "sunday": sunday
            };
            var dateOnly = dofToWeekday[dayOfWeek.toLowerCase()];

            fetch('addClub.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: id_num, 
                    club_name: name, 
                    descriptions: describe, 
                    members: members, 
                    locations: locations,
                    startTime: startTime,
                    endTime: endTime,
                    dayOfWeek: dayOfWeek,
                    dates: dates,
                    dateOnly: dateOnly
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Club Approved.');
                    location.reload();
                }
                else {
                    alert('Could not approve club.');
                }
            })
            .catch(error => {
                console.error("Error: ", error);
            });
        })
        .catch(error => {
            console.error("Error fetching dates json: ", error);
        })
}

function addResourceApproval(id_num, name, describe) {
    fetch('addResource.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: id_num, 
            resources: name, 
            descriptions: describe
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Resource Approved.');
            location.reload();
        }
        else {
            alert('Could not approve resource.');
        }
    })
    .catch(error => {
        console.error("Error: ", error);
    });
}

var clubApprovalData = [];
var healthApprovalData = [];
document.addEventListener('DOMContentLoaded', function() {
    fetchHealthApprovalData(healthApprovalData);
    fetchClubApprovalData(clubApprovalData);
});