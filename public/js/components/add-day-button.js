export default class AddDayButton{

    constructor(){
        $('#days-bundle').append("<div class='mt-4' id='buttons-days-container'></div>");
        $('#buttons-days-container').append("<div class='msg' id='msg-add' style='display: none'><span>Programar un dia: </span><img src='images/new.svg' class='icon-out' id='btn-new-day' role='button'></div>");
    }
    show(){
        $('#msg-add').fadeIn();
    }
    hide(){
        $('#msg-add').fadeOut();
    }
    click(){
        return $('#msg-add').click();
    }
}


