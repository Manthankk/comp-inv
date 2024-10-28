// script.js

// Get the modal
var modal = document.getElementById('add-item-modal');

// Get the button that opens the modal
var addItemButton = document.getElementById("add-item-button");

// Get the <span> element that closes the modal
var closeSpan = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
addItemButton.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
closeSpan.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}


function showMoreInfo(button) {
    // Find the closest parent row of the button
    var row = button.closest("tr");

    // Find the next row with class "more-info" to toggle its display
    var moreInfoRow = row.nextElementSibling;

    // Toggle the display of the "more-info" row
    if (moreInfoRow.style.display === "none" || !moreInfoRow.style.display) {
        moreInfoRow.style.display = "table-row";
    } else {
        moreInfoRow.style.display = "none";
    }
}







