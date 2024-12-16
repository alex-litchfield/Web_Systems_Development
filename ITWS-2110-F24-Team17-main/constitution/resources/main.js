// generates eagle confetti
function createEagleConfetti() {
  const eagle = document.createElement('div');
  eagle.classList.add('eagle');
  eagle.style.left = Math.random() * window.innerWidth + 'px'; 
  eagle.style.top = '-50px'; 

  document.body.appendChild(eagle);

  // animate eagle
  const fallDuration = Math.random() * 5 + 3; // fall duration between 3 and 8 seconds
  const rotation = Math.random() * 360; // random rotation angle
  eagle.style.transition = `transform ${fallDuration}s linear`;

  // set the end position of the eagle
  setTimeout(() => {
    eagle.style.transform = `translateY(${window.innerHeight + 50}px) rotate(${rotation}deg)`;
  }, 100); // small delay for animation to start

  // remove the eagle after it falls off the screen
  setTimeout(() => {
    eagle.remove();
  }, fallDuration * 1000);
}

// generate multiple eagle confetti every 500 milliseconds
setInterval(createEagleConfetti, 500);