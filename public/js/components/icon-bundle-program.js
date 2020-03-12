export default class IconProgramBundle {
    
    constructor(){
        this.content = `<div class="icon-bundle-program" style='display:none;'>
                            <img src="./images/new.svg" id="btn-new-program" class="icon-out" style="" role="button" >
                            <img src="./images/edit_2.svg" id="btn-edit-program" class="icon-out" style="" role="button" >
                            <img src="./images/bin.svg" id="btn-erase-program" class="icon-out" style="" role="button" >
                        </div>`;
        $('#program-selector-bundle').prepend(this.content);
        // $('#btn-new-program').click(()=>newProgram());//onclick="newProgram()"
    }

    update(programList){
        if(programList.length == 0){
            this.add();
        } else {
            this.all();
        }
    }

    newEvent(){
        return $('#btn-new-program');
    }
    eraseEvent(){
        return $('#btn-erase-program');
    }
    editEvent(){
        return $('#btn-edit-program');
    }
    hide(){
        $('.icon-bundle-program').slideUp();
    }

    show(){
        $('.icon-bundle-program').slideDown();
    }

    add(){
        $('#btn-new-program').fadeIn();
        $('#btn-edit-program').fadeOut();
        $('#btn-erase-program').fadeOut();
    }

    all(){
        $('#btn-new-program').fadeIn();
        $('#btn-edit-program').fadeIn();
        $('#btn-erase-program').fadeIn();
    }

}