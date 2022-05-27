$(".table").on('change', '.modbus-name', function () {
    let id = $(this).attr('data-id');
    let field = 'name';
    let val = $(this).val();
    let url = '/api/modbus';

    update(id, field, val, url);
})

$(".table").on('change', '.modbus-satuan', function () {
    let id = $(this).attr('data-id');
    let field = 'satuan';
    let val = $(this).val();
    let url = '/api/modbus';

    update(id, field, val, url);
})

$(".table").on('click', '.modbus-used', function () {
    let id = $(this).attr('data-id');
    let field = 'is_used';
    let val = 0;
    let url = '/api/modbus';

    if ($(this).is(':checked')) {
        val = 1;
    } else {
        val = 0;
    }

    update(id, field, val, url)
})

$(".table-digital").on('change', '.digital-name', function () {
    let id = $(this).attr('data-id');
    let field = 'name';
    let val = $(this).val();
    let url = '/api/digital';

    update(id, field, val, url);
})

$(".table-digital").on('change', '.digital-yes', function () {
    let id = $(this).attr('data-id');
    let field = 'yes';
    let val = $(this).val();
    let url = '/api/digital';

    update(id, field, val, url);
})

$(".table-digital").on('change', '.digital-no', function () {
    let id = $(this).attr('data-id');
    let field = 'no';
    let val = $(this).val();
    let url = '/api/digital';

    update(id, field, val, url);
})

$(".table-digital").on('click', '.digital-used', function () {
    let id = $(this).attr('data-id');
    let field = 'is_used';
    let val = 0;
    let url = '/api/digital';

    if ($(this).is(':checked')) {
        val = 1;
    } else {
        val = 0;
    }

    update(id, field, val, url)
})

function update(id, field, val, url) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            id: id,
            field: field,
            val: val,
        },
        success: function (response) {
            if (response.status == 'success') {
                iziToast.success({
                    title: 'Success',
                    position: 'topRight',
                    message: response.message,
                });
            } else {
                iziToast.error({
                    title: 'Error',
                    position: 'topRight',
                    message: response.message,
                });
            }
        },
        error: function (response) {
            let message = response.responseJSON.message;

            iziToast.error({
                title: 'Error',
                position: 'topRight',
                message: message,
            });
        }
    })
}

$(".table").on('change', '.modbus-mark', function () {
    let id = $(this).attr('data-id');
    let mark = $(".mark-" + id).find(":selected").val();

    if (mark == '&') {
        $("#math-" + id).val('')
        $("#math-" + id).attr('placeholder', '16.0')
    }
})

$(".table").on('change', '.modbus-math', function () {
    let id = $(this).attr('data-id');
    let val = parseFloat($("#val-" + id).val());
    let math = parseFloat($(this).val());
    let mark = $(".mark-" + id).find(":selected").val();
    let after = 0;

    if (mark == "x") {
        after = val * math;
    }

    if (mark == ":") {
        after = val / math;
    }

    if (mark == "+") {
        after = val + math;
    }

    if (mark == "-") {
        after = val - math;
    }

    if (mark == "&") {
        let before = $(this).val()
        let max = before.split('.')[0]
        let min = before.split('.')[1]

        after = ((val - 4) / 16 * (parseFloat(max) - parseFloat(min))) + parseFloat(min);
    }

    // console.log(val, mark, math)
    $("#after-" + id).empty().val(after)

    let field = mark + ',' + math;

    $.ajax({
        url: '/api/math',
        type: 'GET',
        data: {
            id: id,
            after: after,
            math: field
        },
        success: function (response) {
            if (response.status == 'success') {
                iziToast.success({
                    title: 'Success',
                    position: 'topRight',
                    message: response.message,
                });
            } else {
                iziToast.error({
                    title: 'Error',
                    position: 'topRight',
                    message: response.message,
                });
            }
        }
    })
})

$(".table").on('click', '.device-active', function () {
    let id = $(this).attr('data-id');
    let active = 0;
    let status = '';

    if ($(this).is(':checked')) {
        active = 1;
        status = 'Active';
    } else {
        active = 0;
        status = 'Nonactive'
    }

    $.ajax({
        url: '/api/device/active',
        type: 'GET',
        data: {
            id: id,
            active: active
        },
        success: function (response) {
            if (response.status == 'success') {
                $(".label-" + id).empty().append(status)

                iziToast.success({
                    title: 'Success',
                    position: 'topRight',
                    message: response.message,
                });
            } else {
                iziToast.error({
                    title: 'Error',
                    position: 'topRight',
                    message: response.message,
                });
            }
        },
        error: function (response) {
            let message = response.responseJSON.message;

            iziToast.error({
                title: 'Error',
                position: 'topRight',
                message: message,
            });
        }
    });
})

$('.table').on('click', '.modbus-merge', function () {
    var ischecked = $(this).is(':checked');
    let id = $(this).attr('data-id')
    console.log(id)
    if (ischecked == false) {
        $('#merge-' + id).remove();
    } else {
        $('.form-merge').append('<input type="hidden" name="modbus_id[]" id="merge-' + id + '" value="' + id + '"/>');
    }
})