document.addEventListener("DOMContentLoaded", () => {

  // section toggle
  const buttons = document.querySelectorAll(".toggle-btn");
  const sections = document.querySelectorAll(".data-holder");

  // Retrieve the last selected section from localStorage
  const lastSelectedButtonId = localStorage.getItem("lastSelectedSection");

  // If a section was previously selected, display it
  if (lastSelectedButtonId) {
      const lastButton = document.getElementById(lastSelectedButtonId);
      if (lastButton) {
          buttons.forEach(button => button.classList.remove("active", "btn-primary"));
          sections.forEach(section => section.classList.remove("d-block"));

          document.querySelector(`.${lastButton.id}`).classList.add("d-block");
          lastButton.classList.add("active", "btn-primary");
      }
  } else {
      // If no section was selected before, show the first section by default
      buttons[0].classList.add("active", "btn-primary");
      sections[0].classList.add("d-block");
  }

  // Add event listeners to buttons
  buttons.forEach(button => {
      button.addEventListener("click", () => {

          // Remove active class and hide all sections
          buttons.forEach(btn => btn.classList.remove("active", "btn-primary"));
          sections.forEach(section => section.classList.remove("d-block"));

          // Add active class and show the selected section
          document.querySelector(`.${button.id}`).classList.add("d-block");
          button.classList.add("active", "btn-primary");

          // Save the selected section in localStorage
          localStorage.setItem("lastSelectedSection", button.id);
      });
  });



// Reusable function to handle form toggling and submission
function handleFormToggleAndSubmission(formSelector, toggleButtonSelector) {
  const form = document.querySelector(formSelector);
  const toggleButton = document.getElementById(toggleButtonSelector);

  // Hide the form after submission
  form.addEventListener("submit", function () {
      form.classList.add("d-none");
  });

  // Toggle the visibility of the form on button click
  toggleButton.addEventListener("click", function () {
      form.classList.toggle("d-none");
  });
}

// Call the function for each form and its corresponding button
handleFormToggleAndSubmission(".create-account-form", "create-account-toggle");
handleFormToggleAndSubmission(".create-client-form", "create-client-toggle");
handleFormToggleAndSubmission(".create-voiture-form", "create-voiture-toggle");
handleFormToggleAndSubmission(".create-contrat-form", "create-contrat-toggle");
  
});
