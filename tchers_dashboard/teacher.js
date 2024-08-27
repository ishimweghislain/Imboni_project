
function showContent(contentId) {
  // Hide all content sections
  document.getElementById('teachersContent').classList.remove('show');
  document.getElementById('coursesContent').classList.remove('show');
  document.getElementById('updatesContent').classList.remove('show');
  
  // Hide default content
  document.getElementById('defaultContent').style.display = 'none';
  
  // Show the selected content with animation
  document.getElementById(contentId).classList.add('show');
}

function hideContent(contentId) {
  // Hide the selected content
  document.getElementById(contentId).classList.remove('show');
  
  // Show default content if all sections are closed
  if (!document.querySelector('.collapse.show')) {
    document.getElementById('defaultContent').style.display = 'block';
  }
}

// Add click event listeners to the "View All" buttons
document.addEventListener('DOMContentLoaded', function() {
  document.querySelector('.card:nth-child(1) .btn').addEventListener('click', function(e) {
    e.preventDefault();
    showContent('teachersContent');
  });
  
  document.querySelector('.card:nth-child(2) .btn').addEventListener('click', function(e) {
    e.preventDefault();
    showContent('coursesContent');
  });
  
  document.querySelector('.card:nth-child(3) .btn').addEventListener('click', function(e) {
    e.preventDefault();
    showContent('updatesContent');
  });
});

// dropdown javascript
function toggleDropdown(event) {
event.preventDefault();
var dropdown = event.target.nextElementSibling;
if (dropdown.style.display === "none" || dropdown.style.display === "") {
dropdown.style.display = "block";
} else {
dropdown.style.display = "none";
}
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
if (!event.target.matches('.btn')) {
var dropdowns = document.getElementsByClassName("dropdown");
for (var i = 0; i < dropdowns.length; i++) {
    var openDropdown = dropdowns[i];
    if (openDropdown.style.display === "block") {
        openDropdown.style.display = "none";
    }
}
}
}




const profileList = document.getElementById('profileList');
const listItems = profileList.getElementsByTagName('li');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');

let currentPage = 0;
const itemsPerPage = 3;
const totalPages = Math.ceil(listItems.length / itemsPerPage);

function showPage(page) {
for (let i = 0; i < listItems.length; i++) {
listItems[i].classList.remove('active');
}
for (let i = page * itemsPerPage; i < (page + 1) * itemsPerPage && i < listItems.length; i++) {
listItems[i].classList.add('active');
}
}

function updateButtons() {
prevBtn.disabled = currentPage === 0;
nextBtn.disabled = currentPage === totalPages - 1;
}

prevBtn.addEventListener('click', () => {
if (currentPage > 0) {
currentPage--;
showPage(currentPage);
updateButtons();
}
});

nextBtn.addEventListener('click', () => {
if (currentPage < totalPages - 1) {
currentPage++;
showPage(currentPage);
updateButtons();
}
});

// Initialize
showPage(currentPage);
updateButtons();

document.addEventListener('DOMContentLoaded', function() {
const textToType = "Welcome to a Teacher's Dashboard.";
const typingTextElement = document.getElementById('typingText');
let index = 0;

function typeText() {
if (index < textToType.length) {
let currentChar = textToType.charAt(index);
if (currentChar === '\n') {
typingTextElement.innerHTML += "<br>";
} else {
typingTextElement.innerHTML += currentChar;
}
index++;
setTimeout(typeText, 100); // Adjust typing speed by changing the delay (in milliseconds)
} else {
// Remove the blinking cursor after typing is complete
typingTextElement.style.borderRight = 'none';
}
}

typeText();
});
