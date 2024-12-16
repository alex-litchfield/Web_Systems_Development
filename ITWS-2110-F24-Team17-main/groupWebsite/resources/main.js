document.getElementById("homeSignupButton").onclick = function () {
    window.location.href = "signup/index.html";
}

document.addEventListener('DOMContentLoaded', () => {
    const landingSection = document.getElementById('landing');
    const isLoggedIn = landingSection.getAttribute('logged-in-status');
    const landingImage = document.getElementById('landingImage');
    if (isLoggedIn == 'true') {
        landingImage.style.marginBottom = "0";
    }
    else if (isLoggedIn == 'false') {
        landingImage.style.marginBottom = "30vh";
    }
});
  
document.querySelectorAll('.iconTitleBox i').forEach((iconBox) => {
    iconBox.addEventListener('mouseenter', () => {
        const popup = document.getElementById('popup');
        const iconBoxH2 = iconBox.closest('.iconTitleBox').querySelector('h2');
        const popupH2 = document.createElement('h1');
        const popupP = document.createElement('p');
        popupH2.textContent = iconBoxH2.textContent;
        popup.innerHTML = ''; // Clear any existing content inside the popup
        popup.appendChild(popupH2);
        /* Dynamically adds descriptions based on the selected item */
        if (popupH2.textContent == 'Event Hub') {
            popupP.textContent = 'While RPI itself may not host many events throughout the academic year, there are more than 200 unique clubs and organizations which are open to students on campus. '+
            'The Event Hub allows students to view information on each club/organization, such as where and when their events will be held, and can even add these events '+
            'directly to their google calendar.';
        }
        else if (popupH2.textContent == 'Professor/Course Reviews') {
            popupP.textContent = 'The Professors and Course Reviews tab provides students with the opportunity to share their experiences in their courses with different professors. This tool is essential '+
            'for students that have different learning habits and for planning their academic currciulum.';
        }
        else if (popupH2.textContent == 'Discussion Forum') {
            popupP.textContent = 'The Discussion Forum facilitates communication between students regarding their courses with certain professors. Here students can ask questions about their courses and receive '+
            'feedback from other students. Additionally, students can create their own private study groups, which can be used for group projects or chat amongst friends.';
        }
        else if (popupH2.textContent == 'Mental Health Resources') {
            popupP.textContent = 'Historically, college students on average tend be one of the most heavily affected groups by mental health problems. Often times this is due to a lack of knowledge on where to '+
            'seek help. The Mental Health Resources tab provides a list of mental health resources that have been recommended by fellow students in the RPI community.';
        }
        popup.appendChild(popupP);
        popup.style.visibility = 'visible';
        popup.style.opacity = '1';
    });

    iconBox.addEventListener('mouseleave', () => {
        const popup = document.getElementById('popup');
        popup.style.visibility = 'hidden';
        popup.style.opacity = '0';
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const landingImage = document.getElementById('landingImage');
    const popup = document.getElementById('popup');
    
    landingImage.addEventListener('mouseenter', () => {
        const popupH2 = document.createElement('h1');
        const popupP = document.createElement('p');
        popupH2.textContent = 'What is The Nexus @ RPI?';
        popup.innerHTML = ''; // Clear any existing content inside the popup
        popup.appendChild(popupH2);
        popupP.textContent = 'The Nexus is the central hub for all RPI student needs, providing both clarity and guidance to those having trouble navigating their college experience.';
        popup.appendChild(popupP);
        popup.style.visibility = 'visible';
        popup.style.opacity = '1';
        /* Changes popup position so that it doesn't overlap with the logo */
        popup.style.top = '15%';
        popup.style.left = '56%';
    });

    // Mouseleave: Hide the popup
    landingImage.addEventListener('mouseleave', () => {
        popup.style.visibility = 'hidden';
        popup.style.opacity = '0';
        /* Returns popup to its standard postion */
        popup.style.left = '30px';
        popup.style.top = '40px';
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const landing = document.getElementById("landing");
    const backgroundImage = new Image();

    // Preload the background image
    const bgImageUrl = getComputedStyle(landing).backgroundImage.slice(5, -2);
    backgroundImage.src = bgImageUrl;
    backgroundImage.onload = () => {
        landing.classList.remove("no-animate");
    };
});
