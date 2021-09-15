const form = document.getElementById("formulario");
const view = document.getElementById("view_pass")
const pass = document.getElementById("password")

form.addEventListener("submit", function (e) {
    e.preventDefault();
    let ruta = form.getAttribute("url");
    if(!ruta) return alert("No se pudo conectar con el servidor");
    const data = new FormData(form);

    fetch(`./controllers/${ruta}.php`, 
    {
        method: "POST",
        body: data
    })
    .then((res)=>{
        if(res.ok){
            return res.json();
        }else{
            throw  res.json();
        }
    })
    .then(data => alerta(data))
    .catch((err)=>alerta(err))
});

view.addEventListener("click", function () {
    let clase = view.getAttribute("class").trim().split(" ");
    if(clase.includes("bx-dizzy")){
        view.setAttribute("class", "bx bx-meh-blank")
        pass.setAttribute("type", "text")
    }else{
        view.setAttribute("class", "bx bx-dizzy")
        pass.setAttribute("type", "password")
    }
})

async function alerta(msg) {
    let message = await msg;
    if(message.username){
        message = message.username
        alert(`Bienvenido ${message}`)
        window.location.href = "./";
    }else{
        message = message.message
        alert(message)
    }

    let modal = `
    <div>
        <h3>Bienvenido ${msg}</h3>
    </div>
    `
}