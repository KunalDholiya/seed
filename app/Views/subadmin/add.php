
<?php echo $header; ?>
<?php echo $sidebar; ?>
<div class="content-page">
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Sub Admin</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('SubAdmin'); ?>">Sub Admin</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Sub Admin</li>
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
                                        <?php echo form_open('SubAdmin/addnew/', array('id' => 'addfrm', 'class' => '', 'method' => 'POST', 'enctype' => 'multipart/form-data')); ?>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Firstname<span class="error">*</span></label>
                                                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Firstname">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Lastname<span class="error">*</span></label>
                                                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Lastname">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Email<span class="error">*</span></label>
                                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Phone<span class="error">*</span></label>
                                                    <input type="text" class="form-control" id="contact_no" name="contact_no" placeholder="Phone">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Deposit<span class="error">*</span></label>
                                                    <select class="form-control" name="deposit" id="deposit">
                                                        <option value="">Choose One</option>
                                                        <option value="0">Disable</option>
                                                        <option value="1">Enable</option>
                                                    </select>
                                                    <label for="transaction" class="error"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Payout<span class="error">*</span></label>
                                                    <select class="form-control" name="payout" id="payout">
                                                        <option value="">Choose One</option>
                                                        <option value="0">Disable</option>
                                                        <option value="1">Enable</option>
                                                    </select>
                                                    <label for="add_member" class="error"></label>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">View, Remove Member<span class="error">*</span></label>
                                                    <select class="form-control" name="view_member[]" id="view_member" multiple>
                                                        <option value="">Choose One</option>
                                                        <option value="View">View</option>
                                                        <option value="Add">Add</option>
                                                        <option value="Edit">Edit</option>
                                                        <option value="Remove">Remove</option>
                                                    </select>
                                                    <label for="view_member" class="error"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Run full reports <span class="error">*</span></label>
                                                    <select class="form-control" name="full_report" id="full_report">
                                                        <option value="">Choose One</option>
                                                        <option value="0">Disable</option>
                                                        <option value="1">Enable</option>
                                                    </select>
                                                    <label for="full_report" class="error"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Run partial reports<span class="error">*</span></label>
                                                    <select class="form-control" name="partial_report" id="partial_report">
                                                        <option value="">Choose One</option>
                                                        <option value="0">Disable</option>
                                                        <option value="1">Enable</option>
                                                    </select>
                                                    <label for="partial_report" class="error"></label>
                                                </div>
                                            </div>
                 

                                        </div>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light m-t-10">Add Sub Admin</button>
                                        <a class="btn btn-default waves-effect waves-light m-t-10" href="<?php echo base_url('SubAdmin'); ?>">Cancel</a>
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
           
             $('#view_member').select2({
                placeholder: 'Choose One',
                width: 'resolve' // need to override the changed default
            });
            $(function () {
                $("#datepicker5").datepicker({
                    format: "yyyy-mm-dd",
                    //maxDate: moment()
                });
            });
            function getcourse() {
                var university_id = $('#university_id').val();
                $.ajax({
                    url: "<?php echo base_url() . 'SubAdmin/getcourse' ?>",
                    type: "POST",
                    dataType: "json",
                    data: {'<?php echo csrf_token(); ?>': '<?php echo csrf_hash(); ?>', 'university_id': university_id},
                    //     catch : false,
                    success: function (data) {

                        if (data.status == 'success') {
                            $('#course_id').empty();
                            for (i = 0; i < data.location.length; i++) {

                                $("#course_id").append('<option value="' + data.location[i]['course_id'] + '">' + data.location[i]['name'] + ' - ' + data.location[i]['week'] + ' Week</option>');
                            }

                        }

                    }
                });
            }
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
                        required: true,
                        email: true,
                        remote: {
                            url: "<?php echo site_url('SubAdmin/emailExits') ?>",
                            type: "post",
                            data: {
                                email: function () {
                                    return $("#email").val();

                                },

                                '<?php echo csrf_token(); ?>': '<?php echo csrf_hash(); ?>'
                            }
                        }
                    },
                    username: {
                        required: true,

                        remote: {
                            url: "<?php echo site_url('SubAdmin/usernameExits') ?>",
                            type: "post",
                            data: {
                                username: function () {
                                    return $("#username").val();

                                },

                                '<?php echo csrf_token(); ?>': '<?php echo csrf_hash(); ?>'
                            }
                        }
                    },
                    contact_no: {
                        required: true,

                    },
                    zipcode: {
                        required: true,
                        number: true,
                        minlength: 5
                    },
                    city: {
                        required: true,
                    },
                    address1: {
                        required: true,
                    },
                    country_id: {
                        required: true,
                    },

                    state_id: {
                        required: true,

                    },
                    deposit: {
                        required: true,
                    },
                    payout: {
                        required: true,
                    },
                    view_member: {
                        required: true,
                    },
                    full_report: {
                        required: true,
                    },
                    partial_report: {
                        required: true,
                    },
                    image: {
                        dimention: [600, 600]
                    },
                },
                messages: {
                    firstname: "Sub Admin name is required",
                    lastname: "Lastname is required",
                    email: {
                        required: "Email is required.",
                        remote: "Email already exists."
                    },
                    username: {
                        required: "Username is required.",
                        remote: "Username already exists."
                    },
                    contact_no: {
                        required: "Phone no. is required.",
                    },
                    address1: {
                        required: "Address Line1 is required.",
                    },
                    country_id: {
                        required: "Country is required.",
                    },
                    state_id: {
                        required: "State is required.",
                    },
                    zipcode: {
                        required: "Zipcode is required.",
                        minlength: "Please enter atleast 5 digit."

                    },
                    city: {
                        required: "City is required.",
                    },
                    deposit: {
                        required: "Deposit is required.",
                    },
                    payout: {
                        required: "Payout is required.",
                    },
                    view_member: {
                        required: "View, Remove Member is required.",
                    },
                    full_report: {
                        required: "Run full reports is required.",
                    },
                    partial_report: {
                        required: "Run partial reports is required.",
                    },
                    

                },
                //                errorPlacement: function (error, element) {
                //                    error.insertAfter($(element).parent('div')).addClass('control-label');
                //                }
            });
        </script>