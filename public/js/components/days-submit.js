export default class DaysSubmit{
    
    content = `<div class="submit-bundle" id="submit-bundle" style='display: none'>
                <button type="button" class="btn btn-primary mr-2" id="submit"" value="submit">Enviar</button>
                <button type="button" class="btn btn-danger" id="cancel-day">Cancelar</button>
            </div>`;

    constructor(parent){
        this.parent = parent;
        this.parent.append(this.content);//botones de aceptar y cancelar en editar dias.
    }

    update(parent){
        this.parent = parent;
    }

    hide(){
        $('#submit-bundle').slideUp();
    }
    show(){
        $('#submit-bundle').slideDown();
    }
    submit(){
        return $("#submit");
    }
    cancel(){
        return $("#cancel-day");
    }

}

