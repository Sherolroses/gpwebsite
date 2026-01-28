function toggleBookingForm() {
    var form = document.getElementById("booking-form");
    if (form.style.display === "none" || form.style.display === "") {
        form.style.display = "block";
    } else {
        form.style.display = "none";
    }
}

function validateForm() {
    var fullname = document.getElementById("fullname").value;
    var email = document.getElementById("email").value;
    var date = document.getElementById("date").value;
    var time = document.getElementById("time").value;

    if (fullname === "" || email === "" || date === "" || time === "") {
        alert("Please fill in all fields.");
        return false;
    }

    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }

    var today = new Date();
    var inputDate = new Date(date);
    if (inputDate.setHours(0,0,0,0) < today.setHours(0,0,0,0)) {
        alert("Please select a valid future date.");
        return false;
    }

    return true;
}

// Set today's date as the minimum selectable date and disable weekends and public holidays
window.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date');
    const today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);

    // Disable weekends (Saturday and Sunday)
    dateInput.addEventListener('input', function () {
        const selectedDate = new Date(dateInput.value);
        const day = selectedDate.getDay(); // Get the day of the week (0 = Sunday, 6 = Saturday)
        
        // If it's a weekend (Saturday or Sunday), alert and reset the input
        if (day === 6 || day === 0) {
            alert("Sorry, we do not work on weekends. Please select a weekday.");
            dateInput.value = ""; // Clear the input field
        }
    });
});
