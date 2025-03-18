document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let correo = document.getElementById('correo').value;
    let password = document.getElementById('password').value;

    fetch('http://localhost/ApiRest_ConexionBD/backend/controllers/LoginController.php', {
        method: 'POST',
        body: JSON.stringify({ correo, password }),
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            window.location.href = "index.html";
        }
    });
});
