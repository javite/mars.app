
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
        form = $('#program-form').serialize();
        // fetch('saveProgram', {
        //     method: 'POST',
        //     body: form
        // })
        // .then(data=>data.text())
        // .then(data=>console.log(data))
        console.log(form);
        $.post('newProgram', form)
            .done(prog_id=>console.log(prog_id));
    })

});