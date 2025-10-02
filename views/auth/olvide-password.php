<h1 class="nombre-pagina">Olvide mi Password</h1>
<p class="descripcion-pagina">Reestaablece tu Password escribiendo tu email a continuación</p>

<?php
include_once __DIR__ . '/../templates/alertas.php';
?>
<form class="formulario" action="/olvide" method="POST">

    <div class="campo">
        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            placeholder="Tu Email">
    </div>

    <input type="submit" class="boton" value="Enviar Instrucciones">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
    <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crear una cuenta</a>
</div>