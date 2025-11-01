function handleCredentialResponse(response) {
    fetch("/login/google", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ token: response.credential }),
        credentials: "include" // include cookies cross-origin
    })
    .then(res => {
        if (res.ok) window.location.href = "/";
        else alert("Google login failed");
    });
}
let confirmPassword = $('#confirm_password');
let password = $('#password');
let username = $('#username');
let requirements = $('.password-requirements li');
let isNotMatch = $('.is-invalid-not-match');
let isExists = $('.is-invalid-exists');
$(document).ready(() => {
    isNotMatch.hide();
    isExists.hide();
})
//check if the password contains necessary criteria
password.on('input', function() {
    const rules = [
        /.{8,}/, // at least 8 characters
        /[0-9]/, // at least 1 number
        /[A-Z]/, // at least 1 uppercase letter
        /[a-z]/, // at least 1 lowercase letter
        /[!@#$%^&*(),.?":{}|<>]/ // at least 1 special character
    ];
    requirements.each((index, item) => {
        if(rules[index].test(password.val())) {
            $(item).addClass('valid');
        } else {
            $(item).addClass('invalid');
        }
    });
});
confirmPassword.on('input', function() {
    if (confirmPassword.val() === password.val()) {
        isNotMatch.hide();
    } else {
        isNotMatch.show();
    }
});
username.on('input', function() {
    fetch(`/check-username?username=${encodeURIComponent(username.val())}`)
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            isExists.show();
        } else {
            isExists.hide();
        }
    });
});
/* Cookies have:
PHPSESSID: to know if user is logged in
Preferences: languages
Auth token: to know who is logged in
Temporary data
.....
*/