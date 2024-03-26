const toggleForm = () => {
  const container = document.querySelector('.container');
  container.classList.toggle('active');

};
var password = "";
function ValidatePassword() {
  function containsLowercase(input) {
    var lowercaseRegex = /[a-z]/;

    return lowercaseRegex.test(input);
  }
  function containsUppercase(input) {
    var uppercaseRegex = /[A-Z]/;

    return uppercaseRegex.test(input);
  }
  function containsNumeric(input) {
    var numericRegex = /[0-9]/;

    return numericRegex.test(input);
  }
  function containsSpecialCharacter(input) {
    var specialCharacterRegex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
    return specialCharacterRegex.test(input);
  }

  password = $(this).val();

  $("#Length").removeClass(password.length > 6 ? "glyphicon-remove" : "glyphicon-ok");
  $("#Length").addClass(password.length > 6 ? "glyphicon-ok" : "glyphicon-remove");
  if (containsLowercase(password)) {
    document.getElementById("LowerCase").style.color = "green";
  }
  if (containsNumeric(password)) {
    document.getElementById("Numbers").style.color = "green";

  }
  if (containsUppercase(password)) {
    document.getElementById("UpperCase").style.color = "green";
  }
  if (containsSpecialCharacter(password)) {
    document.getElementById("Symbols").style.color = "green";
  }
}

/*Bind our event to key up for the field. It doesn't matter if it's delete or not*/
$(document).ready(function () {
  $("#NewPassword").on('keyup', ValidatePassword);

});
var passwordField = document.getElementById("NewPassword");
var confirmPasswordField = document.getElementById("ConfirmPassword");

confirmPasswordField.addEventListener("input", function () {
  document.getElementById("Match").style.display = "block";
  if (passwordField.value === confirmPasswordField.value) {
    document.getElementById("Match").style.color = "green";
    document.getElementById("Match").innerHTML = "Password Matched";
    document.getElementById("SignUp-btn").style.cursor = "pointer"
  } else {
    document.getElementById("Match").style.color = "red";
    document.getElementById("Match").innerHTML = "Password Does Not Match";
    document.getElementById("SignUp-btn").style.cursor = "not-allowed"
  }
});

document.getElementById("Email").addEventListener("input", function () {
  document.getElementById("Valid-Email").style.display = "block";
  var email = document.getElementById("Email").value;
  var booking_email = email.toString();
  console.log(booking_email);
  if (booking_email == '' || booking_email.indexOf('@') == -1 || booking_email.indexOf('.') == -1) {

    document.getElementById("Valid-Email").style.color = "Red";
    document.getElementById("Valid-Email").innerHTML = "InValid Email";
  }
  else {
    document.getElementById("Valid-Email").style.color = "green";
    document.getElementById("Valid-Email").innerHTML = "Valid Email";

  }
}) 