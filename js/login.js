const username = document.getElementById('username');
const password = document.getElementById('password');

function login() {
    var request = new XMLHttpRequest();
    request.open('POST', 'api/login');
    request.send(JSON.stringify({
        'username': username.value,
        'password': password.value,
    }));
    request.addEventListener('load', () => {
        if (request.status === 200) {
            const response = JSON.parse(request.response);
            const token = response.token;
            const expires = response.expires;
            document.cookie = 'token=' + token + '; expires=' + expires + '; samesite=lax';
            window.location.href = 'administration';
        } else {
        }
    });
}
