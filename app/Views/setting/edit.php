<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title"><?php echo $setting['setting_name']; ?></h4>
</div>
<?php echo form_open('setting/update', array('id' => 'frmEdit','enctype' => 'multipart/form-data')); ?>
<div class="modal-body">
    <input type="hidden" name="setting_id" value="<?php echo base64_encode($setting['setting_id']); ?>" />
    <div class="form-group">
        <?php if ($setting['setting_name'] != 'Address') { ?>
            <?php if ($setting['setting_id'] != '7') { ?>
                <input type="text" class="form-control" name="setting_value" value="<?php echo $setting['setting_value']; ?>" />
            <?php } else { ?>
                 <input type="file" class="form-control" id="image" name="setting_value"  />
                 <p class="error">Allowed Types: .jpg .jpeg .png .bmp</p>
            <?php } ?>
        <?php } else { ?>
            <textarea class="form-control" rows="4" name="setting_value"><?php echo $setting['setting_value']; ?></textarea>
        <?php } ?>
    </div>

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
    required: true <?php if ($setting['setting_id'] == 2): echo ','; ?>
        email : true <?php endif; ?><?php if ($setting['setting_id'] == 7): echo ','; ?>
        required : false <?php endif; ?>
    }
    },
            messages: {
            setting_value: {
            required: "Please enter <?php echo ucfirst($setting['setting_name']); ?>" <?php if ($setting['setting_id'] == 2): echo ','; ?>
                email : "Please enter valid email" <?php endif; ?>
            }
            }
    });
</script>