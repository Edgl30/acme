
<form method="POST" action="guardar_conductor.php">
    <label for="cedula">Documento de identidad:</label>
    <input
        type="text"
        id="cedula"
        name="cedula"
        required
        inputmode="numeric"
        pattern="[0-9]+"
        title="Ingrese solamente números."
        maxlength="20"
        placeholder="Ej. 1023456789"
    >

    <label for="primer_nombre">Primer nombre:</label>
    <input
        type="text"
        id="primer_nombre"
        name="primer_nombre"
        required
        pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ ]+"
        title="Ingrese solamente letras y espacios."
        maxlength="60"
    >

    <label for="segundo_nombre">Segundo nombre (opcional):</label>
    <input
        type="text"
        id="segundo_nombre"
        name="segundo_nombre"
        pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ ]*"
        title="Ingrese solamente letras y espacios."
        maxlength="60"
    >

    <label for="apellidos">Apellidos:</label>
    <input
        type="text"
        id="apellidos"
        name="apellidos"
        required
        pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ ]+"
        title="Ingrese solamente letras y espacios."
        maxlength="120"
    >

    <label for="direccion">Dirección:</label>
    <input
        type="text"
        id="direccion"
        name="direccion"
        required
        maxlength="180"
    >

    <label for="telefono">Teléfono:</label>
    <input
        type="tel"
        id="telefono"
        name="telefono"
        required
        inputmode="numeric"
        pattern="[0-9]{10}"
        title="Ingrese exactamente 10 dígitos numéricos."
        maxlength="10"
        minlength="10"
        placeholder="Ej. 3001234567"
    >

    <label for="ciudad">Ciudad:</label>
    <input
        type="text"
        id="ciudad"
        name="ciudad"
        required
        pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ ]+"
        title="Ingrese solamente letras y espacios."
        maxlength="80"
    >

    <button type="submit">Guardar conductor</button>
</form>
