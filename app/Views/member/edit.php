
<?php echo $header; ?>
<?php echo $sidebar; ?>
<div class="content-page">
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Members</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('Members'); ?>">Members</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Member</li>
                    </ol>
            </div>
        </div>
        <div class="page-content-wrapper ">
            <div class="container">
                <div class="row">
                    <div class="col-md-12" id="error_msg_info">
                        <?php if (session()->getFlashdata('success')) { ?>
                            <div class="alert alert-success fade in" style="margin-top:18px;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                <strong><?php echo session()->getFlashdata('success'); ?></strong> 
                            </div>
                        <?php } ?>
                        <?php if (session()->getFlashdata('error')) { ?>
                            <div class="alert alert-danger fade in" style="margin-top:18px;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                <strong><?php echo session()->getFlashdata('error'); ?></strong> 
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php echo form_open('Members/editnew/' . base64_encode($info[0]['id']), array('id' => 'addfrm', 'class' => '', 'method' => 'POST', 'enctype' => 'multipart/form-data')); ?>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Member name<span class="error">*</span></label>
                                                    <input type="text" class="form-control" id="firstname" value="<?php echo $info[0]['name']; ?>" name="firstname" placeholder="Member name">
                                                </div>
                                            </div>
                                           

                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $info[0]['email']; ?>" placeholder="Email">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Phone</label>
                                                    <input type="text" class="form-control" id="contact_no" name="contact_no" value="<?php echo $info[0]['contact_no']; ?>" placeholder="Phone">
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Church Member</label>
                                                    <select class="form-control" name="church_member" id="church_member">
                                                        <option value="">Choose one</option>
                                                            <option value="Yes" <?php if($info[0]['church_member'] == 'Yes'){ echo 'selected'; }?>>Yes</option>
                                                            <option value="No" <?php if($info[0]['church_member'] == 'No'){ echo 'selected'; }?>>No</option>
                                                    </select>
                                                    <label for="church_member" class="error"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Address</label>
                                                    <textarea class="form-control" id="address" name="address" placeholder="Enter Address"><?php echo $info[0]['address']; ?></textarea>
                                             
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light m-t-10">Edit Member</button>
                                        <a class="btn btn-default waves-effect waves-light m-t-10" href="<?php echo base_url('Members'); ?>">Cancel</a>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $footer; ?>
        <script>
            $(function () {
                $("#datepicker5").datepicker({
                    format: "yyyy-mm-dd",
                    //maxDate: moment()
                });
            });
            
            $('#image').change(function () {
                $('#image').removeData('imageWidth');
                $('#image').removeData('imageHeight');
                var file = this.files[0];
                var tmpImg = new Image();
                tmpImg.src = window.URL.createObjectURL(file);
                tmpImg.onload = function () {
                    width = tmpImg.naturalWidth,
                            height = tmpImg.naturalHeight;
                    $('#image').data('imageWidth', width);
                    $('#image').data('imageHeight', height);
                }
            });
            $.validator.addMethod('dimention', function (value, element, param) {
                if (element.files.length == 0) {
                    return true;
                }
                var width = $(element).data('imageWidth');
                var height = $(element).data('imageHeight');
                //alert(width);
                if (width <= param[0] && height <= param[1])
                {
                    return true;
                } else {
                    return false;
                }
            }, 'Please upload an image with maximumm 600 x 600 pixels dimension.');
            jQuery("#addfrm").validate({
                rules: {
                    firstname: "required",
                    lastname: "required",
                    email: {
                        email: true,
                        remote: {
                            url: "<?php echo site_url('Members/emailExitsedit') ?>",
                            type: "post",
                            data: {
                                email: function () {
                                    return $("#email").val();

                                },
                                id: function () {
                                    return <?php echo $info[0]['id']; ?>;
                                },
                                '<?php echo csrf_token(); ?>': '<?php echo csrf_hash(); ?>'
                            }
                        }
                    },
                    
                    
                },
                messages: {
                    firstname: "Member name is required",
                    
                    email: {
                        
                        remote: "Email already exists."
                    },
                    

                },
                //                errorPlacement: function (error, element) {
                //                    error.insertAfter($(element).parent('div')).addClass('control-label');
                //                }
            });
        </script>