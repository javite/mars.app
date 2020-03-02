export default class IconOutputBundle {
    content = `
    <div class="icon-bundle-output">
        <img src="./images/edit_2.svg" id="btn-edit-day" class="icon-out" style="display:none" role="button" aria-pressed="false">
    </div>
    `;
    clickState = false;
    self;
    
    constructor(){
        $('#day').prepend(this.content);
        this.self = $('#btn-edit-day');
        this.self.click(()=>this.click());
        
    }

    clickEvent(){
        return this.self;
    }

    update(){
 
    }

    hide(){
        this.self.slideUp();
    }

    show(){
        this.self.slideDown();
    }

    click(){
        let pressed = (this.self.attr("aria-pressed") === "true");
        this.self.attr("aria-pressed",!pressed);
        this.clickState = !pressed;
    }

    getState(){
        return this.clickState;
    }

}