export default class cardOutput{

    constructor(type){
        let thisClass = this;
        let title;
        let state;
        let img_output_card;
        let id;

        switch (type) {
            case 0:
                title = 'Iluminación';
                state = 'Encendida';
                img_output_card = 'images/lamp.png';
                id = `illumination_${type}`;
                break;
            case 1:
                title = 'Riego'; 
                state = 'Apagado';
                img_output_card = 'images/riego.png';
                id = `bomb_${type}`;
                break;
            case 2:
                title = 'Ventilador';
                state = 'Apagado';
                img_output_card = 'images/ventilador.png';    
                id = `ventilation_${type}`;
                break;
            default:
                break;
        }

        this.content =  `<div class="card shadow text-success bg-dark mb-3 text-center" id="${id}">
                            <h5 class="card-header" id="output-title">${title}</h5>
                            <div class="card-body">
                                <img class="lamp" src="${img_output_card}" alt="Card image cap">
                                <h4 class="card-text text-success mb-3">${state}</h4>
                                <a href="#" class="btn btn-secondary mb-2">Apagar</a>
                            </div>
                        </div>`;

        $('#deck-outputs').append(this.content);
        this.self = $(`#${id}`);

    }

    update(value, updated_at){
        this.self.find('#value').text(state);
    }

    hide(){
        this.selector.slideUp();
    }

    show(){
        this.selector.show();

    }


}