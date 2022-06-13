<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title"><?php echo $setting['name']; ?></h4>
</div>
<?php echo form_open('TransactionSetting/update', array('id' => 'frmEdit')); ?>
<div class="modal-body">
    <input type="hidden" name="id" value="<?php echo base64_encode($setting['id']); ?>" />
    <div class="form-group">
        <?php if ($setting['id'] != '4') { ?>
            <input type="text" class="form-control" name="setting_value" value="<?php echo $setting['value']; ?>" />
        <?php } else { ?>
            <input type="text" class="form-control" id="date_of_week" name="setting_value" value="<?php echo date('Y-m-d', strtotime($setting['value'])); ?>" />

        <?php } ?>
    </div>
    <?php if ($setting['id'] == 1) { ?>
        <div class="form-group">
            <select class="form-control" name="manual_value" id="manual_value">
                <option value="Manual" <?php
                if ($setting['manual_value'] == 'Manual') {
                    echo 'selected';
                }
                ?>>Manual</option>
                <option value="Value" <?php
                if ($setting['manual_value'] == 'Value') {
                    echo 'selected';
                }
                ?>>Value</option>
            </select>
        </div>
<?php } ?>

</div>
<div class="modal-footer">
    <div class="form-group pull-right">
        <input type="submit" class="btn btn-primary" value="Update"/>
        <button data-dismiss="modal" class="btn btn-default">No</button>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(function () {
        $("#date_of_week").datepicker({
            format: "yyyy-mm-dd",
        });
    });
    $('#frmEdit').validate({
        rules: {
            setting_value: {
                required: true<?php if ($setting['id'] != 4): echo ','; ?>
                            number : true <?php endif; ?>
            }
        },
        messages: {
            setting_value: {
                required: "Please enter <?php echo ucfirst($setting['name']); ?>",
            }
        }
    });
</script>