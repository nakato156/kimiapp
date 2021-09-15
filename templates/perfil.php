<section class="newapp">
    <h3 style="padding-left: 10px; margin: 10px;">Agregar app</h3>
    <form id="formapp" enctype="multipart/formdata" class="formapp">
        <div style="border-top: 1px solid #cecece;">
            <label>Nombre de la Aplicación:</label><br>
            <input type="text" name="name" placeholder="Nombre de la app">
        </div>
        <div>
            <label>Versión:</label><br>
            <input type="text" name="version" placeholder="Versión">
        </div>
        <div>
            <label>Descripción:</label><br>
            <input type="text" name="descripcion" placeholder="Descripción">
        </div>
        <div>
            <label>Tipo:</label><br>
            <input type="text" name="type" placeholder="Tipo de aplicación">
        </div>
        <div>
            <label>Aplicación</label>
            <input type="file" accept=".exe,.jar" name="app" placeholder="Select app">
        </div>
        <div>
            <label>Imagen</label>
            <input type="file" accept=".jpg,.png,.jpeg" name="image" placeholder="Select image">
        </div>
        <button>Enviar</button>
    </form>
</section>
<section id="container" class="modal"></section>
</body>
<script src="./js/new_app.js"></script>