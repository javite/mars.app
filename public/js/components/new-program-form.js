export default class newProgramForm {

    constructor(){
        this.content =` <div class='edit-bundle' id='form-edit-program' style='display: none'>
                            <label for="program_name" id='label_program_name'>Nombre: </label>
                            <input class="form-control" type="text" id="program_name" name="program_name" value="">
                            <p class="message" id='message-program' style='display: none'></p>
                            <p class="error" id='error-message-program' style='display: none'></p>
                            <br>
                            <button type='button' class='btn btn-primary mr-2' id='accept-new-name'>Guardar</button>
                            <button type='button' class='btn btn-danger mr-2' id='cancel-new-name'>Cancelar</button>
                        </div>`;
        let thisClass = this;
        $('#program-selector-bundle').append(this.content);
        
    }
    hide(){
        $('#form-edit-program').slideUp();
    }

    show(){
        $('#label_program_name').show();
        $('#program_name').show();
        $('#accept-new-name').text('Guardar');
        $('#message-program').hide();
        $('#error-message-program').hide();
        $('#form-edit-program').slideDown();
    }
    accept(){
        return $('#accept-new-name');
    }
    cancel(){
        return $('#cancel-new-name');
    }
    value(value){
        $('#program_name').val(value);
    }
    message(value){
        $('#label_program_name').hide();
        $('#program_name').hide();
        $('#message-program').show();
        $('#accept-new-name').text('Aceptar');
        $('#message-program').text(value);

    }

}