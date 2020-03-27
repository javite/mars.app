
$(document).ready(function(){
    // $.ajaxSetup({
    //     headers: {
    //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    //   });

    // var token = $('meta[name="_token"]').attr('content');
    // $('#program-form').prepend(`<input type="hidden" name="_token" value= ${token}>`);
    console.log('test')
    $('#submit').click(() => {
        // e.preventDefault();
        // form = '{"device_id":6, "temperature":1, "humidity":2,"soil_humidity_1":3, "api_token":"NBNWmTq7HCA2EsGYRudgwV3RMLj0Rr3AYBY3382F7C8eT0gwGmwbZUakpL05"}';
        form = $('#program-form').serialize();
        let action = $('#actions').val();
        let to = '';
        console.log(form);
        console.log(action);
        switch (action) {
            case '0':
                to = "newEvent";
                break;
            case '1':
                to = "saveEvent";
                break;
            case '2':
                to = "deleteEvent";
                break;
            default:
                break;
        }
        console.log(to);
        $.post(to, form)
            .done(prog_id=>console.log(prog_id));
            
    })


});