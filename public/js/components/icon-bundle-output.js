export default class IconOutputBundle {

    constructor(parent){
        this.content = `<div class="icon-bundle-output" id="icon-bundle-output" style="display:none" >
                            <img src="./images/edit_2.svg" id="btn-edit-day" class="icon-out" role="button" aria-pressed="false">
                            <img src="./images/bin.svg" id="btn-erase-day" class="icon-out" role="button" >
                        </div>`;
        this.clickState = false;
        this.self;
        this.parent = parent;
        this.parent.prepend(this.content);
        this.self = $('#icon-bundle-output');
        // this.self.click(()=>this.click());
        
    }

    clickEvent(){
        return this.self;
    }

    update(parent){
        this.parent = parent;
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