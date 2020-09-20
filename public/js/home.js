var lamps_container;

// This is called when the page finishes loading
function init() {
    // Assign page elements to variables
    console.log(window.location.hostname);
    lamps_container = $("#lamps-container");
    fetch("/getDevices")
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
    .catch(error => console.error('Error: ', error))
    
    
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

        