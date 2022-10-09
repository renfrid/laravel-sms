//document.ready
$(document).ready(function () {
    $('.select2').select2({
        placeholder: 'Select an option...'
    });







    /*========================= DELETE ==============================*/
    $('.delete').on('click', function (event) {
        event.preventDefault();
        const url = $(this).attr('href');
        swal({
            title: 'Delete record',
            text: 'Are you sure you want to delete record?',
            icon: 'error',
            buttons: {
                cancel: 'Cancel',
                confirm: { text: 'Yes', className: 'btn-danger' }
            },
        }).then(function (value) {
            if (value) {
                window.location.href = url;
            }
        });
    });


});