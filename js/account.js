function sendRequest(method, url, onLoad, body) {
  const token = document.cookie.match(/token=(.*$)/)[1];
  const request = new XMLHttpRequest();
  request.open(method, url);
  request.setRequestHeader("Authorization", "Bearer " + token);
  if (body === undefined) {
    request.send();
  } else {
    request.send(JSON.stringify(body));
  }
  request.addEventListener("load", () => onLoad(request));
}

function validatePassword(password, onLoad) {
  sendRequest("POST", "/api/account/valid", (request) => onLoad(request), {
    password: password,
  });
}

const usernameForm = document.getElementById("username-form");
usernameForm.addEventListener("submit", (event) => {
  event.preventDefault();
  const data = new FormData(usernameForm);
  validatePassword(data.get("password"), (request) => {
    response = JSON.parse(request.response);
    const message = document.getElementById("username-error-message");
    if (response.valid) {
      message.style.display = "none";
      sendRequest("PUT", "/api/account/username", () => {}, {
        username: data.get("username"),
      });
    } else {
      message.style.display = "block";
    }
  });
});

const passwordForm = document.getElementById("password-form");
passwordForm.addEventListener("submit", (event) => {
  event.preventDefault();
  const data = new FormData(passwordForm);
  validatePassword(data.get("old-password"), (request) => {
    response = JSON.parse(request.response);
    const message = document.getElementById("password-error-message");
    if (response.valid) {
      message.style.display = "none";
      sendRequest("PUT", "/api/account/password", () => {}, {
        password: data.get("new-password"),
      });
    } else {
      message.style.display = "block";
    }
  });
});
