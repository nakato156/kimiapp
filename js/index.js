window.onload = get();
let apps = document.getElementById("aplications");
function get(){
    if(document.getElementById("aplications")){
        fetch("./controllers/data.php")
        .then((res)=> res.json())
        .then((data) => { 
            temp = "";
            data.forEach(app => {
                temp += `
                <div class="app" id="${app.id}" onclick="view('${app.id}','${app.name}')">
                    <p class="app_name">${app.name}</p>
                    <img style="width:auto; max-height: 150px;" src ="./apps/img/${app.imagen}">
                    <p class="app_type">Tipo: ${app.type}</p>
                    <p class="app_vers">Versión: ${app.version}</p>
                </div>`
            });
            apps.innerHTML = temp;
        });
    }
}
function view(id, name){
    window.location.href = `./app/${id}/${name}`
}