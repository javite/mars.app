
var lamps_container;
var wai; //web o AP
var noInstalado = false;
var devices = [{
    id: 0,
    user_id: 0,
    name: "Local",
    current_program_id: 0,
    model: "---",
    version: "",
    firmware_version: null,
    serial_number: "",
    IP:"192.168.4.1",
    net_name:"",
    api_token: "",
    created_at: "2020-05-19 21:16:34",
    updated_at: "2020-08-03 20:19:30"
}];

function isRunningStandalone() {
    //IOs
    isInWebAppiOS = (window.navigator.standalone == true);
	//other
    isInWebAppChrome = (window.matchMedia('(display-mode: standalone)').matches);
    return isInWebAppChrome || isInWebAppiOS;
}

function isIos(){
    const userAgent = window.navigator.userAgent.toLowerCase();
    return /iphone|ipad|ipod/.test( userAgent );
}

// This is called when the page finishes loading
function init() {
    // Assign page elements to variables
    fetch("/wai",{
        method: 'GET'
    })
        .then(res => {
            if(res.ok){return res.json();}
        })
        .then(res => {
            wai = res.conn;
            console.log('web');
            if(wai == 'web'){getDevices();}
        })
        .catch(error => {
            console.log(error);
            console.log('AP');
            wai = 'AP';
            updateUI(devices);
        })

    lamps_container = $("#lamps-container");
    btnAdd = document.getElementById('btnAdd');
	btnAdd.style.display = 'none';
    
    if (isRunningStandalone()){
        console.log('standalone');
        standalone = true;
     } else {
        standalone = false;
    }

	window.addEventListener('beforeinstallprompt', (e)=>{
		e.preventDefault();
        deferredPrompt = e;
        console.log(deferredPrompt);
        btnAdd.style.display = 'block';
        btnAdd.addEventListener('click', (e)=>{
            if(isIos()){
                alert("Instala la app!");
            } else{
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if(choiceResult.outcome === 'accepted'){
                        console.log('User accepted');
                    }
                    deferredPrompt = null;
                });
            }
        });
	});
}

function getDevices(){
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
            document.getElementById('state').innerHTML = response;
            updateUI(response);
        })
        .catch(error => {
            console.error('Error: ', error);
            document.getElementById('state').innerHTML = error;
        })   
}

function updateUI(devices){ 
    devices.forEach(element => {
        console.log(element);
        let a = `<a href=http://${element['IP']} class="col-md-4 lamp"> ${element['name']}</a>`;
        lamps_container.append(a) ;
    });
    document.getElementById('state').style.display = 'none';
    
}

// Call the init function as soon as the page loads
window.addEventListener("load", init, false);

        