import ProgramBundle from './components/program-bundle.js';
import Config from './components/config.js';

var outs_names;
var days_names;
var device_id;
var config;

$(document).ready(function(){
    getCookie('device_id');
    console.log('device_id =',device_id );
    if(device_id == null){
        console.log("no hay device id");
    }
    config = new Config();
    let get_names = getNames();
    get_names.then(data => {
        outs_names = data[1];
        days_names = data[0];
        config.setDaysNames(days_names);
        config.setOutNames(outs_names);
        console.log('Days names',outs_names);
        let programBundle = new ProgramBundle(device_id, config);

    });
});

function getNames(){
    return fetch("getDaysNames")
    .then(data =>data.json())
    .catch(error => console.error(error))
}