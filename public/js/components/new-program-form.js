export default class newProgramForm {
    content =` <form class='edit-bundle' id='form-edit-program' style='display: none'>
                <label for="program_name">Nombre: </label>
                <input class="form-control" type="text" name="program_name" value="">
                <br>
                <button type='button' class='btn btn-primary mr-2' id='prog-name' onclick='saveProgramName()'>Guardar</button>
                <button type='button' class='btn btn-danger mr-2' id='' onclick='endEditProgram()'>Cancelar</button>
               </form>
            `;
    constructor(){
        $('#program-selector-bundle').append(this.content);
    }
    hide(){
        $('#form-edit-program').slideUp();
    }

    show(){
        $('#form-edit-program').slideDown();
    }
}