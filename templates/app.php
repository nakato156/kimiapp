<section class="Theapp">
    <section class="App">
        <img src="../../apps/img/<?=$img;?>" alt="applicacion-<?=$name?>">
        <div class="infoApp">
            <h3><?=$name;?></h3>
            <p>Version: <?=$version;?></p>
            <p>Tipo: <?=$type;?></p>
        </div>
        <button><a href="../../apps/app/<?=$appA;?>" style="color:#000;" download="<?=$appA;?>">Descargar</a></button>
    </section>
    <section class="addInfo">
        <div class="descripcion">
            <h4>Descripción</h4>
            <?=$descripcion;?>
        </div>
        <div class="infoAuthor">
            <h4>Información adicional</h4>
            <p>Nombre del author: <?=$author?></p>
        </div>
    </section>
</section>