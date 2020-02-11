import IconProgramBundle from './icon-bundle-program.js';
import newProgramForm from './new-program-form.js';
import OutBundle from './out-bundle.js';

export default class ProgramBundle {
    value;
    content = `
    <div class="program-selector-bundle border_1" id="program-selector-bundle">
        <div id='program-cont' style='display:none;'>
            <div class="program-label-selector-bundle ">
                <label class="label-program-selector label" for="out">Programas</label>
            </div>
            <select class="program-selector form-control" name="program" id="program"></select>
            <input type="hidden" id="program_id" name="program_id" value="">
            <input type="hidden" id="device_id" name="device_id" value="6">
            <div class='error' id="program-error"style='display:none;'></div>
        </div>
    </div>
    `;
    naction;
    constructor(config){
        let thisclass = this;
        $('#program-form').append(this.content);
        $('#program').change(()=>thisclass.setValue($('#program').val()));//eventor de cambio en el selector 
        /*MENU DE ICONOS NEW, EDIT, ERASE PROGRAM */
        this.iconProgramBundle = new IconProgramBundle();  
        this.iconProgramBundle.newEvent().click(()=>thisclass.newProgram());
        this.iconProgramBundle.eraseEvent().click(()=>thisclass.eraseProgram()); 
        this.iconProgramBundle.editEvent().click(()=>thisclass.editProgram());
        /*FORM NEW/MODIFY NAME */
        this.formEdit = new newProgramForm();
        this.formEdit.accept().click(()=>this.updateProgram());//
        this.formEdit.cancel().click(()=>this.endEditProgram());
        /*MENU OUTPUT */
        this.outBundle = new OutBundle(config);
        this.outBundle.show();

    }

    update(programs, outputs){
        this.programs = programs;
        this.outputs = outputs;
        let thisclass = this;
        let selected;
        let firstTime = false;
        if(typeof this.value === "undefined"){
            firstTime = true;
        }
        this.selector = $('#program');
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
        this.program_id = $('#program_id');
        this.program_id.val(this.programs[this.value].id); //guarda el id del programa
        this.selector.val(thisclass.value); // actualiza el selector con el valor de Value. Se guarda el index de programas.
        this.iconProgramBundle.update(this.programs);
        this.iconProgramBundle.show();
        this.outBundle.update(this.outputs);//acualiza select de salidas
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
        this.hide();
        this.formEdit.show();
        this.formEdit.message(`¿Seguro desea borrar el programa: ${this.programs[this.value].name}?`);
        this.iconProgramBundle.hide();
        this.outBundle.hide();
        this.action = 'delete';
        console.log('erase program')
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
                    // .always(()=>console.log("finished"))
            break;
            case 'new':
                $.post('newProgram', form)
                .done(program=>{
                    console.log('Nuevo Programa creado', program);
                    this.programs.push(program);
                    this.value = this.programs.length-1;
                    console.log('Nuevo Programa creado', this.programs);
                    this.endEditProgram();
                    this.update(this.programs, this.outputs);
                })
                .fail(()=>{
                    console.log("error al cambiar nombre");
                })
                // .always(()=>console.log("finished"))
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
    createNewProgram(){ //crea un nuevo programa
        
    }

    cancelProgram(){ //cancela la creacion o cambio de nombre de programa
        
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
        $('#program_id').val(this.programs[this.value].id);
    }
    getValue(){
        return this.programs[this.value].id;
    }

    showError(errorText){
        $('#program-error').text(errorText);
        $('#program-error').fadeIn();//No hay programas creados aun
    }

}