document.getElementById("contactForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    fetch("contact.php", {
        method: "POST",
        body: formData,
    })
        .then(response => response.text())
        .then(data => {
            document.getElementById("formResponse").textContent = data;
            document.getElementById("formResponse").style.display = "block";
            form.reset(); // Reset the form
        })
        .catch(error => {
            document.getElementById("formResponse").textContent = "An error occurred. Please try again.";
            document.getElementById("formResponse").style.display = "block";
        });
});

