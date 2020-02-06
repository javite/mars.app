export default class Config {
    days_names;
    out_names;
    constructor( ){
        this.days_names;
        this.out_names;
    }

    setDaysNames(days_names){
        this.days_names = days_names;
    }
    getDaysNames(){
        return this.days_names;
    }
    setOutNames(out_names){
        this.out_names = out_names;
    }
    getOutNames(){
        return this.out_names;
    }
    isEmpty(obj) {
        for(var key in obj) {
            if(obj.hasOwnProperty(key))
            return false;
        }
        return true;
    }
}