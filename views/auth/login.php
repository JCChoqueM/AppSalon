<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<form class="formulario" method="post" action="/">
    <div class="campo">
        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            placeholder="Tu Email">
    </div>
    <div class="password">
        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            placeholder="Tu Password">
    </div>
    <input type="submit" class="boton" value="Iniciar Sesión">

</form>
<div class="acciones">
    <a href="/crear-cuenta">¿?Aun no tienes una cuenta? Crear una</a>
    <a href="/olvide">¿Olvidaste tu Password?</a>
</div>