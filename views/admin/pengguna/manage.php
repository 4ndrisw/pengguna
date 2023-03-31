<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if (has_permission('pengguna', '', 'create')) { ?>
                        <a href="<?php echo admin_url('pengguna/staff/member'); ?>" class="btn btn-primary tw-mb-2 sm:tw-mb-4 ">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            <?php echo _l('new_staff'); ?>
                        </a>
                <?php } ?>

                <?php if (has_permission('pengguna', '', 'view')) { ?>
                        <a href="#" class="btn btn-default tw-mb-2 sm:tw-mb-4 mleft5" data-toggle="modal" data-target="#groups">
                            <i class="fa-solid fa-layer-group tw-mr-1"></i>
                            <?php echo _l('kelompok_pegawai'); ?>
                        </a>
                <?php }?>
            </div>
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php
                        $table_data = [
                            _l('staff_dt_name'),
                            _l('staff_dt_email'),
                            _l('role'),
                            _l('staff_dt_last_Login'),
                            _l('staff_dt_active'),
                        ];
                        $custom_fields = get_custom_fields('staff', ['show_on_table' => 1]);
                        foreach ($custom_fields as $field) {
                            array_push($table_data, [
                                'name'     => $field['name'],
                                'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
                            ]);
                        }
                        render_datatable($table_data, 'staff');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete_staff" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('pengguna/staff/delete', ['delete_staff_form'])); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('delete_staff'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="delete_id">
                    <?php echo form_hidden('id'); ?>
                </div>
                <p><?php echo _l('delete_staff_info'); ?></p>
                <?php
                echo render_select('transfer_data_to', $staff_members, ['staffid', ['firstname', 'lastname']], 'staff_member', get_staff_user_id(), [], [], '', '', false);
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-danger _delete"><?php echo _l('confirm'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="groups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <?php echo _l('kelompok_pegawai'); ?>
                </h4>
            </div>
            <div class="modal-body">
                <?php if (has_permission('pjk3', '', 'create')) { ?>
                <div class="input-group">
                    <input type="text" name="kelompok_pegawai_name" id="kelompok_pegawai_name" class="form-control"
                        placeholder="<?php echo _l('kelompok_pegawai_name'); ?>">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" id="new-item-group-insert">
                            <?php echo _l('new_kelompok_pegawai'); ?>
                        </button>
                    </span>
                </div>
                <hr />
                <?php } ?>
                <div class="row">
                    <div class="container-fluid">
                        <table class="table dt-table table-items-groups" data-order-col="1" data-order-type="asc">
                            <thead>
                                <tr>
                                    <th><?php echo _l('id'); ?></th>
                                    <th><?php echo _l('kelompok_pegawai_name'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($kelompok_pegawai as $group) { ?>
                                <tr class="row-has-options" data-group-row-id="<?php echo $group['id']; ?>">
                                    <td data-order="<?php echo $group['id']; ?>"><?php echo $group['id']; ?></td>
                                    <td data-order="<?php echo $group['name']; ?>">
                                        <span class="group_name_plain_text"><?php echo $group['name']; ?></span>
                                        <div class="group_edit hide">
                                            <div class="input-group">
                                                <input type="text" class="form-control">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary update-item-group"
                                                        type="button"><?php echo _l('submit'); ?></button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row-options">
                                            <?php if (has_permission('items', '', 'edit')) { ?>
                                            <a href="#" class="edit-item-group">
                                                <?php echo _l('edit'); ?>
                                            </a>
                                            <?php } ?>
                                            <?php if (has_permission('items', '', 'delete')) { ?>
                                            | <a href="<?php echo admin_url('pengguna/delete_group/' . $group['id']); ?>"
                                                class="delete-item-group _delete text-danger">
                                                <?php echo _l('delete'); ?>
                                            </a>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            </div>
        </div>
    </div>
</div>


<?php init_tail(); ?>
<script>
$(function() {
    initDataTable('.table-staff', admin_url+'pengguna/staff/table', ['undefined'], ['undefined'], ['undefined'], [4, 'desc']);
});

function delete_staff_member(id) {
    $('#delete_staff').modal('show');
    $('#transfer_data_to').find('option').prop('disabled', false);
    $('#transfer_data_to').find('option[value="' + id + '"]').prop('disabled', true);
    $('#delete_staff .delete_id input').val(id);
    $('#transfer_data_to').selectpicker('refresh');
}


    if (get_url_param('groups_modal')) {
        // Set time out user to see the message
        setTimeout(function() {
            $('#groups').modal('show');
        }, 1000);
    }

    $('#new-item-group-insert').on('click', function() {
        var group_name = $('#kelompok_pegawai_name').val();
        if (group_name != '') {
            $.post(admin_url + 'pengguna/add_group', {
                name: group_name
            }).done(function() {
                window.location.href = admin_url + 'pengguna?groups_modal=true';
            });
        }
    });

    $('body').on('click', '.edit-item-group', function(e) {
        e.preventDefault();
        var tr = $(this).parents('tr'),
            group_id = tr.attr('data-group-row-id');
        tr.find('.group_name_plain_text').toggleClass('hide');
        tr.find('.group_edit').toggleClass('hide');
        tr.find('.group_edit input').val(tr.find('.group_name_plain_text').text());
    });

    $('body').on('click', '.update-item-group', function() {
        var tr = $(this).parents('tr');
        var group_id = tr.attr('data-group-row-id');
        name = tr.find('.group_edit input').val();
        if (name != '') {
            $.post(admin_url + 'pengguna/update_group/' + group_id, {
                name: name
            }).done(function() {
                window.location.href = admin_url + 'pengguna';
            });
        }
    });

</script>
</body>

</html>