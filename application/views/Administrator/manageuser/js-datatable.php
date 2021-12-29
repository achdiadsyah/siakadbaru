
<script type="text/javascript">
var save_method;
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({
        responsive: true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?=base_url('manageuser/data/')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],

    });
});



function add_data()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Data'); // Set Title to Bootstrap modal title
    $('#password').attr('disabled', false); //set button disable

    function generatePassword() {
    var length = 10,
        charset = "0123456789",
        retVal = "";
    for (var i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
    }
    return retVal;
    }
    $('#password').val(generatePassword());

}

function edit_data(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#password').attr('disabled',true); //set button disable

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('manageuser/ajax_edit/') ?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id"]').val(data.id);
            $('[name="username"]').val(data.username);
            $('[name="name"]').val(data.name);
            $('[name="whatsapp"]').val(data.whatsapp);
            $('[name="role_id"]').val(data.role_id);
            $('[name="nik"]').val(data.nik);
            $('[name="email"]').val(data.email);
            $('[name="izin_psb"]').val(data.izin_psb);
            $('[name="status"]').val(data.status);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit User'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            mySwalalert('Gagal Mendapatkan Data', 'info');
        }
    });
}

function delete_data(id)
{
    Swal.fire({
            title: 'Anda Yakin ?',
            html: "Data yang di hapus, tidak dapat di pulihkan",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            // ajax delete data to database
            $.ajax({
                url : "<?php echo site_url('manageuser/ajax_delete') ?>/"+id,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    //if success reload ajax table
                    $('#modal_form').modal('hide');
                    reload_table();
                    mySwalalert('Berhasil Menghapus Data', 'success');
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    mySwalalert('Gagal Menghapus Data', 'error');
                }
            });
        }
    })
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

function save()
{
    $('#btnSave').text('Saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;

    if(save_method == 'add') {
        url = "<?=base_url('manageuser/ajax_add')?>";
    } else {
        url = "<?=base_url('manageuser/ajax_update')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
                mySwalalert('Berhasil Menyimpan Data', 'success');
            }

            $('#btnSave').text('Save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            mySwalalert('Gagal Menyimpan Data', 'error');
            $('#btnSave').text('Save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        }
    });
}
</script>

