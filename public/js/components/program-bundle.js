import IconProgramBundle from './icon-bundle-program.js';
import newProgramForm from './new-program-form.js';
import OutBundle from './out-bundle.js';

export default class ProgramBundle {

    constructor(device_id, config){
        this.device_id = device_id;
        this.value = 0;
        this.content = `<div class="program-selector-bundle border_1" id="program-selector-bundle">
                            <div id='program-cont' style='display:none;'>
                                <div class="program-label-selector-bundle ">
                                    <label class="label-program-selector label" for="out">Programas</label>
                                </div>
                                <select class="program-selector form-control" name="program" id="program"></select>
                                <input type="hidden" id="program_id" name="program_id" value="">
                                <input type="hidden" id="device_id" name="device_id" value="">
                                <br>
                                <button type='button' class='btn btn-primary mr-2' id='load-program'>Cargar programa</button>
                                <div class='error' id="program-error"style='display:none;'></div>
                            </div>
                        </div>`;
        this.action;
        this.programs;
        this.program_id;
        let thisclass = this;
        this.config = config;
        $('#program-form').append(this.content);
        /*MENU DE ICONOS NEW, EDIT, ERASE PROGRAM */
        this.iconProgramBundle = new IconProgramBundle();  
        this.iconProgramBundle.newEvent().click(()=>thisclass.newProgram());
        this.iconProgramBundle.eraseEvent().click(()=>thisclass.eraseProgram()); 
        this.iconProgramBundle.editEvent().click(()=>thisclass.editProgram());
        /*FORM NEW/MODIFY NAME */
        this.formEdit = new newProgramForm();
        this.formEdit.accept().click(()=>this.updateProgram());
        this.formEdit.cancel().click(()=>this.endEditProgram());

        let getProgram = this.getPrograms(device_id);// pide los programs a la BD.
        getProgram.then(json => {
            this.programs = json;
            console.log('programs: ',this.programs);
            if(this.programs.length == 0){
                this.showError('No hay programas creados aun');
            } else {
            // /*MENU OUTPUT */
                // this.outBundle = new OutBundle(this.programs[0], this.config); //si hay programas crea las 
                this.update();
            }
        })

        let program_id_ref = $('#program_id');
        this.selector = $('#program');
        this.selector.change(()=>{  //selector de salida
            this.value = this.selector.val();
            program_id_ref.val(this.programs[this.value].id);
            this.program_id = program_id_ref.val();
            console.log('program_id: ', this.program_id);
            this.update();
        })
        this.loadProgramButton = $('#load-program');
        this.loadProgramButton.click(()=>{  //boton de cargar programa
            let load_program = this.loadProgram();
            load_program.then(json=>console.log('Load Program: ', json));
        })
    }

    update(){
        let selected;
        let firstTime = false;
        if(typeof this.value === "undefined"){
            firstTime = true;
        }
        this.selector.html('');
        for (let index = 0; index < this.programs.length; index++) {
            if(firstTime){
                this.value = 0; //programs[0].id
            } 
            if(this.value == index){ // programs[index].id
                selected = 'selected';
            } else {
                selected = '';
            }
            this.selector.append(`<option value="${index}" ${selected}>${this.programs[index].name}</option>`); //${programs[index].id}
        }
        /*MENU OUTPUT */
        if(typeof this.outBundle !== "undefined"){
            this.outBundle.remove();
        };
        this.outBundle = new OutBundle(this.programs[this.value], this.config); //si hay programas crea las output
        this.program_id = this.programs[this.value].id;
        this.iconProgramBundle.update(this.programs);
        this.iconProgramBundle.show();
        this.show();
    }

    loadProgram(){
        let url = `loadProgram/${this.device_id}/${this.program_id}`;
        console.log('Fetch load program: ', url);
        return fetch(url)//todos los programas
        .then(data =>data.json())
        .catch(error => console.error(error))
    }

    getPrograms(device_id){
        return fetch(`getPrograms/${device_id}`)//todos los programas
        .then(data =>data.json())
        .catch(error => console.error(error))
    }

    newProgram(){
        this.hide();
        this.formEdit.show();
        this.formEdit.value('');
        this.iconProgramBundle.hide();
        this.outBundle.hide();
        this.action = 'new';
    }

    eraseProgram(){
        console.log('erase program')
        this.hide();
        this.formEdit.show();
        this.formEdit.message(`¿Seguro desea borrar el programa: ${this.programs[this.value].name}?`);
        this.iconProgramBundle.hide();
        this.outBundle.hide();
        this.action = 'delete';
    }

    editProgram(){
        console.log('edit program');
        this.hide();
        this.formEdit.show();
        this.formEdit.value($('#program option:selected').text());
        this.iconProgramBundle.hide();
        this.outBundle.hide();
        this.action = 'edit';
    }

    endEditProgram(){
        this.show();
        this.formEdit.hide();
        this.iconProgramBundle.show();
        this.outBundle.show();
    }

    updateProgram(){ //cambia el nombre al programa
        let form = $('#program-form').serialize();
        console.log(form);
        switch(this.action){
            case 'edit':
                $.post('saveProgram', form)
                    .done(program=>{
                        this.programs[this.value] = program;
                        console.log('El nombre se cambio correctamente', this.programs);
                        this.endEditProgram();
                        this.update(this.programs, this.outputs);
                    })
                    .fail(()=>{
                        console.log("error al cambiar nombre");
                    })
            break;
            case 'new':
                $.post('newProgram', form)
                .done(program=>{
                    console.log('Nuevo Programa creado', program);
                    this.programs.push(program);
                    this.value = this.programs.length - 1;
                    console.log('Nuevo Programa creado', this.programs);
                    this.endEditProgram();
                    this.update();
                })
                .fail(()=>{
                    console.log("error al cambiar nombre");
                })
            break;
            case 'delete':
                $.post('deleteProgram', form)
                .done(()=>{
                    let deleted = this.programs.splice(this.value,1);
                    this.value = 0; //selecciona el primer programa de la lista.
                    console.log('Programa borrado', deleted);
                    this.endEditProgram();
                    this.update(this.programs, this.outputs);
                })
                .fail(()=>{
                    console.log("error al cambiar nombre");
                })
                // .always(()=>console.log("finished"))
            break;
            default:
                console.log('error');
            break;
        }
    }

    change(){
        return $('#program');
    }
    outSelector(){
        return this.outBundle.change();
    }

    hide(){
        $('#program-cont').slideUp();
    }

    show(){
        $('#program-cont').slideDown();
    }

    setValue(val){
        this.value = val;
    }

    getValue(){
        return this.programs[this.value].id;
    }

    showError(errorText){
        $('#program-error').text(errorText);
        $('#program-error').fadeIn();//No hay programas creados aun
    }

}