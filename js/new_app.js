const formapp = document.getElementById("formapp");
let container = document.getElementById("container"); 

formapp.addEventListener("submit", function (e) {
    e.preventDefault();
    modal("Procesando ....")
    if (formapp.app.value.trim() !="" && formapp.name.value.trim() !="" && formapp.descripcion.value.trim() !="" && formapp.type.value.trim() !="" && formapp.image.value !="" && /\d+\.\d+\.\d+/g.test(formapp.version.value.trim())){

        const data = new FormData(formapp);

        fetch('./controllers/data.php', 
        {
            method: "POST",
            body: data
        })
        .then(res=>modal(res.text()))
        .then(data=>{console.log(data); modal(data);})
        .catch(err=>{ console.log(err); modal(`Error: ${err}`)})
    }else{
        modal("Hay un error en los datos");
        return;
    }
});

function modal(status) {
    const md = document.getElementById("modal");
    if(md){
        container.style.display = "block";
        md.innerHTML = `<h4>${status}</h4>`
    }else{
        temp = `
        <div id="modal" style="width: 280px; height: 180px;">
            <h4>${status}</h4>
        </div>`;
        container.style.display = "block";
        container.innerHTML = temp;
    }
    setTimeout(ocultar, 2400)   
}

function ocultar() {
    container.style.display="none";
}