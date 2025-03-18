document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const correo = document.getElementById('correo').value;
    const password = document.getElementById('password').value;
    const mensaje = document.getElementById('mensaje');

    try {
        const response = await fetch('http://localhost/ApiRest_ConexionBD/backend/controllers/LoginController.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ correo, password })
        });

        const data = await response.json();

        if (response.ok) {
            localStorage.setItem('user', JSON.stringify(data.user));
            window.location.href = "dashboard.html";
        } else {
            mensaje.textContent = data.error;
            mensaje.style.color = "red";
        }
    } catch (error) {
        mensaje.textContent = "Error en el servidor";
    }
});
