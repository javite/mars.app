
var lamps_container;
var wai; //web o AP
var noInstalado = false;
var user_id;
var devices = [{
    id: 0,
    user_id: 0,
    name: "Local",
    current_program_id: null,
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
//var devices = "[{\"id\":21,\"user_id\":7,\"name\":\"Local\",\"current_program_id\":null,\"model\":\"\",\"version\":\"\",\"firmware_version\":\"\",\"serial_number\":\"AB124\",\"IP\":\"192.168.4.1\",\"net_name\":\"CELES 2.4G\",\"api_token\":\"sOQHzRI6C2mApAdESvPEZ8dYk7UWMNfzaOsN8aWZVEL406oDVQA1JNp37bgD\",\"created_at\":\"2020-09-13 20:09:00\",\"updated_at\":\"2020-09-27 14:43:36\"}]";

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

    fwai();
    lamps_container = $("#lamps-container");
    btnAdd = document.getElementById('btnAdd');
    btnAdd.style.display = 'none';
    btnUpdate = document.getElementById('btnUpdate');
    btnUpdate.addEventListener('click',()=>{
        lamps_container.html("");
        fwai();
    });
    btnUpdate.style.display = 'block';
    user_id = document.getElementById('user_id').value;
    console.log(user_id);

    if(!isRunningStandalone()){
        if(!isIos()){
            window.addEventListener('beforeinstallprompt', (e)=>{
                e.preventDefault();
                deferredPrompt = e;
                btnAdd.style.display = 'block';
                btnAdd.innerHTML = 'Instalar webapp';
                btnAdd.addEventListener('click', (e)=>{
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if(choiceResult.outcome === 'accepted'){
                            console.log('User accepted');
                        }
                        deferredPrompt = null;
                    });
                });
            })
        } else {
            console.log('standalone');
            btnAdd.style.display = 'block';
            btnAdd.innerHTML = 'Instalar webapp iOS';
            btnAdd.addEventListener('click', ()=>alert("Instala la app agegando al escritorio"));
        }
    }
}
function fwai(){
    fetch("/wai")
    .then(res => {
        if(res.ok){
            return res.json();
        } else {
            throw Error(res.statusText);
        }
    })
    .then(res => {
        wai = res.conn;
        console.log('web');
        if(wai == 'web'){
            // btnUpdate.style.display = 'block';
            getDevices();
        }
    })
    .catch(error => {
        console.log(error);
        console.log('AP');
        wai = 'AP';
        // btnUpdate.style.display = 'none';
        updateUI(devices);
        window.location.href = "http://192.168.4.1";
    })
}

function getDevices(){
    fetch("/getDevices/" + user_id)
        .then(res=>{
            console.log(res.status)
            if(res.ok){
                return res.json();
            } else {
                console.log(res.status);
                // document.getElementById('error2').innerHTML = res.status;
                throw Error(res.statusText)
            }   
        })
        .then(response => {
            console.log('Succes: ', response);
            // document.getElementById('error2').innerHTML = 'get Devices';
            updateUI(response);
        })
        .catch(error => {
            console.error('Error: ', error);
            // document.getElementById('state').innerHTML = error;
        })   
}

function updateUI(_devices){ 
    _devices.forEach(element => {
        console.log(element);
        let a = `<a href=http://${element['IP']} class="col-md-4 lamp"> ${element['name']}</a>`;
        lamps_container.append(a) ;
    });
    document.getElementById('state').style.display = 'none';
}

// Call the init function as soon as the page loads
window.addEventListener("load", init, false);

        