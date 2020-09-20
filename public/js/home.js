var lamps_container;

// This is called when the page finishes loading
function init() {
    // Assign page elements to variables
    // lamps_container = $("#lamps-container");
    fetch("http://192.168.4.1/wai",{
        method: 'GET',
        mode: 'cors'
    })
        .then(res => {
            if(res.ok){
                return res.text();
            }
        })
        .then(res => document.getElementById('state').innerHTML = res)
        .catch(error => document.getElementById('state').innerHTML = error)
    
    /*fetch("/getDevices")
    .then(res=>{
        console.log(res.status)
        if(res.ok){
            return res.json();
        } else {
            console.log(res.status);
        }   
    })
    .then(response => {
        console.log('Succes: ', response);
        updateUI(response);
    })
    .catch(error => console.error('Error: ', error))*/
    
    
}

function updateUI(devices){
    
    devices.forEach(element => {
        console.log(element);
        let a = `<a href=http://${element['IP']} class="col-md-4 lamp"> ${element['name']}</a>`;
        lamps_container.append(a) ;
    });
    
}

// Call the init function as soon as the page loads
window.addEventListener("load", init, false);

        