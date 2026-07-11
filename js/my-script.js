const links = document.querySelectorAll(".show-form");
const forms = document.querySelectorAll(".form");

links.forEach(link => {
  link.addEventListener("click", (event) => {
    event.preventDefault();

    // Hide all forms
    forms.forEach(form => {
      form.style.display = "none";
    });

    // Show the selected form
    const formId = event.target.dataset.form;
    document.getElementById(formId).style.display = "block";
  });
});