document.querySelector("form").addEventListener("submit", function (e) {
  // Optional: Perform validation or any pre-submission checks here

  // Display the success alert after form submission
  alert("Sign up successful!")

  // The form will now be submitted normally without preventing the default action
})

const signup = document.getElementById("signup")
const signin = document.getElementById("signin")
const signupForm = document.getElementById("signup-form")
const signinForm = document.getElementById("signin-form")

signup.addEventListener("click", () => {
  signinForm.style.display = "none"
  signupForm.style.display = "block"
})

signin.addEventListener("click", () => {
  signinForm.style.display = "block"
  signupForm.style.display = "none"
})
