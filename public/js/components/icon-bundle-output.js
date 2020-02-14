export default class IconOutputBundle {
    content = `
    <div class="icon-bundle-output">
        <img src="./images/edit_2.svg" id="btn-edit-day" class="icon-out" style="display:none" role="button" aria-pressed="true">
    </div>
    `;
    clickState = false;
    clickEvent;
    
    constructor(){
        let thisclass = this;
        $('.day').prepend(this.content);
        this.clickEvent = $('#btn-edit-day').click(()=>this.click());
    }

    update(){
 
    }

    hide(){
        $('#btn-edit-day').slideUp();
        
    }

    show(){
        $('#btn-edit-day').slideDown();
    }

    click(){
        let pressed = ($('#btn-edit-day').attr("aria-pressed") === "true");
        $('#btn-edit-day').attr("aria-pressed",!pressed);
        this.clickState = !pressed;
        return this.clickEvent;
    }
    getState(){
        return this.clickState;
    }
}