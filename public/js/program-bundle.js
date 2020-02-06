export default class ProgramBundle {
    content = `
    <div class="program-selector-bundle border_1" id="p-s-b">
        <div class="program-label-selector-bundle ">
            <label class="label-program-selector label" for="out">Programas</label>
            <div class="icon-bundle-program">
                <img src="./images/new.svg" id="btn-new-program" class="icon-out" style="" role="button" onclick="newProgram()">
                <img src="./images/edit_2.svg" id="btn-edit-program" class="icon-out" style="" role="button" onclick="editProgram()">
                <img src="./images/bin.svg" id="btn-erase-program" class="icon-out" style="" role="button" onclick="eraseProgram()">
            </div>
        </div>
        <select class="program-selector form-control" name="program" style="" id="out"> </select>
    </div>
    `;

    state = true;
    self;

    constructor(){
        $('#program-form').append(this.content);
        this.self = $('#p-s-b');
    }

    update(option){
        let selector = $('#out');
        console.log(selector);
        for (let i = 0; i < option.length; i++) {
            let selected = '';
            if(i==2){
                selected = 'selected';
            } 
            let string = `<option value='i' ${selected}>${option[i]}</option>`;
            selector.append(string);
        }
    }

    show(){
        this.self.show();
    }

    hide(){
        this.self.hide();
    }
}