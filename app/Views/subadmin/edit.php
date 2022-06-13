
<?php echo $header; ?>
<?php echo $sidebar; ?>
<div class="content-page">
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Sub Admin</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('SubAdmin'); ?>">SubAdmin</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Sub Admin</li>
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
                                        <?php echo form_open('SubAdmin/editnew/' . base64_encode($info[0]['admin_id']), array('id' => 'addfrm', 'class' => '', 'method' => 'POST', 'enctype' => 'multipart/form-data')); ?>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Firstname<span class="error">*</span></label>
                                                    <input type="text" class="form-control" id="firstname" value="<?php echo $info[0]['firstname']; ?>" name="firstname" placeholder="Firstname">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Lastname<span class="error">*</span></label>
                                                    <input type="text" class="form-control" id="lastname" value="<?php echo $info[0]['lastname']; ?>" name="lastname" placeholder="Lastname">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Email<span class="error">*</span></label>
                                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $info[0]['email']; ?>" placeholder="Email">
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Phone<span class="error">*</span></label>
                                                    <input type="text" class="form-control" id="contact_no" name="contact_no" value="<?php echo $info[0]['contact_no']; ?>" placeholder="Phone">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Deposit<span class="error">*</span></label>
                                                    <select class="form-control" name="deposit" id="deposit">
                                                        <option value="">Choose One</option>
                                                        <option value="0" <?php
                                                        if (!empty($role)) {
                                                            if ($role[0]['deposit'] == 0) {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>>Disable</option>
                                                        <option value="1" <?php
                                                        if (!empty($role)) {
                                                            if ($role[0]['deposit'] == 1) {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>>Enable</option>
                                                    </select>
                                                    <label for="transaction" class="error"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Payout<span class="error">*</span></label>
                                                    <select class="form-control" name="payout" id="payout">
                                                        <option value="">Choose One</option>
                                                        <option value="0"<?php
                                                        if (!empty($role)) {
                                                            if ($role[0]['payout'] == 0) {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>>Disable</option>
                                                        <option value="1"<?php
                                                        if (!empty($role)) {
                                                            if ($role[0]['payout'] == 1) {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>>Enable</option>
                                                    </select>
                                                    <label for="add_member" class="error"></label>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">View, Remove Member<span class="error">*</span></label>
                                                    <select class="form-control" name="view_member[]" id="view_member" multiple>
                                                        <?php $member = explode(',', $role[0]['member']); ?>
                                                        <option value="">Choose One</option>
                                                        <option value="View"<?php
                                                        if (!empty($role)) {
                                                            if (in_array('View',$member)) {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>>View</option>
                                                        <option value="Add"<?php
                                                        if (!empty($role)) {
                                                            if (in_array('Add',$member)) {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>>Add</option>
                                                        <option value="Edit"<?php
                                                        if (!empty($role)) {
                                                            if (in_array('Edit',$member)) {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>>Edit</option>
                                                        <option value="Remove"<?php
                                                        if (!empty($role)) {
                                                            if (in_array('Remove',$member)) {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>>Remove</option>
                                                    </select>
                                                    <label for="view_member" class="error"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Run full reports <span class="error">*</span></label>
                                                    <select class="form-control" name="full_report" id="full_report">
                                                        <option value="">Choose One</option>
                                                        <option value="0"<?php
                                                        if (!empty($role)) {
                                                            if ($role[0]['full_report'] == 0) {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>>Disable</option>
                                                        <option value="1"<?php
                                                        if (!empty($role)) {
                                                            if ($role[0]['full_report'] == 1) {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>>Enable</option>
                                                    </select>
                                                    <label for="full_report" class="error"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Run partial reports<span class="error">*</span></label>
                                                    <select class="form-control" name="partial_report" id="partial_report">
                                                        <option value="">Choose One</option>
                                                        <option value="0"<?php
                                                        if (!empty($role)) {
                                                            if ($role[0]['partial_report'] == 0) {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>>Disable</option>
                                                        <option value="1"<?php
                                                        if (!empty($role)) {
                                                            if ($role[0]['partial_report'] == 1) {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>>Enable</option>
                                                    </select>
                                                    <label for="partial_report" class="error"></label>
                                                </div>
                                            </div>

                                            <!--                                                <div class="col-md-4">
                                                                                                <div class="form-group"> <label for="">Referred By</label>
                                                                                                    <input type="text" class="form-control" id="referred" name="referred" value="<?php echo $info[0]['referred_by']; ?>" placeholder="Referred By">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <div class="form-group"> <label for="">Join Date</label>
                                                                                                    <div class="input-group"> <input type="text" class="form-control" placeholder="yyyy-mm-dd"
                                                                                                                                     id="datepicker5" name="join_date" value="<?php
                                            if ($info[0]['join_date'] != '') {
                                                echo date('Y-m-d', strtotime($info[0]['join_date']));
                                            }
                                            ?>"> <span class="input-group-addon bg-custom b-0"><i
                                                                                                                class="mdi mdi-calendar"></i></span></div>
                                                                                                </div>
                                                                                            </div>-->
                                            <!--                                            <div class="col-md-4">
                                                                                            <div class="form-group"> <label for="">Address Line 1<span class="error">*</span></label>
                                                                                                <input type="text" class="form-control" id="address1" name="address1" value="<?php echo $info[0]['address1']; ?>" placeholder="Address Line 1">
                                                                                            </div>
                                                                                        </div>
                                            
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group"> <label for="">Address Line 2</label>
                                                                                                <input type="text" class="form-control" id="address2" name="address2" value="<?php echo $info[0]['address2']; ?>" placeholder="Address Line 2">
                                                                                            </div>
                                                                                        </div>
                                            
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group"> <label for="">Country<span class="error">*</span></label>
                                                                                                <select class="form-control" name="country_id" id="country_id">
                                                                                                                                                            <option value="">Select Country</option>
                                            <?php foreach ($country as $l) { ?>
                                                                                                                                    <option value="<?php echo $l['id']; ?>" <?php
                                                if ($l['id'] == $info[0]['country']) {
                                                    echo 'selected';
                                                }
                                                ?>><?php echo $l['name']; ?></option>
                                            <?php } ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="clearfix"></div>
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group"> <label for="">State<span class="error">*</span></label>
                                                                                                <select class="form-control" name="state_id" id="state_id" >
                                                                                                    <option value="">Select State</option>
                                            <?php foreach ($state as $l) { ?>
                                                                                                                                    <option value="<?php echo $l['id']; ?>" <?php
                                                if ($l['id'] == $info[0]['state']) {
                                                    echo 'selected';
                                                }
                                                ?>><?php echo $l['name']; ?></option>
                                            <?php } ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group"> <label for="">City<span class="error">*</span></label>
                                                                                                <input type="text" class="form-control" id="city" name="city" value="<?php echo $info[0]['city']; ?>" placeholder="City">
                                                                                            </div>
                                                                                        </div>
                                            
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group"> <label for="">Zip Code<span class="error">*</span></label>
                                                                                                <input type="text" class="form-control" id="zipcode" name="zipcode" maxlength="5" value="<?php echo $info[0]['zipcode']; ?>" placeholder="Zip Code">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="clearfix"></div>
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label for="">Profile Image</label>
                                                                                                <input type="file" class="filestyle" name="image" id="image" data-buttonbefore="true">
                                                                                                <label for="image" class="error"></label>
                                                                                                <p class="error">Allowed Types: .jpg .jpeg .png .bmp</p>
                                                                                                <p style="color:red">Image with maximumm 600 x 600 pixels dimension allowed.</p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label for=""></label>
                                            <?php if ($info[0]['image'] != '') { ?>
                                                                                                                                <img src="<?php echo base_url() . $this->config->item('upload_path_admin_thumb') . $info[0]['image']; ?>"
                                                                                                                                     alt="user-img" >
                                            <?php } ?>
                                                                                            </div>
                                                                                        </div>-->


                                        </div>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light m-t-10">Edit Sub Admin</button>
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
                            url: "<?php echo site_url('SubAdmin/emailExitsedit') ?>",
                            type: "post",
                            data: {
                                email: function () {
                                    return $("#email").val();

                                },
                                id: function () {
                                    return <?php echo $info[0]['admin_id']; ?>;
                                },
                                '<?php echo csrf_token(); ?>': '<?php echo csrf_hash(); ?>'
                            }
                        }
                    },
                    username: {
                        required: true,

                        remote: {
                            url: "<?php echo site_url('SubAdmin/usernameExitsedit') ?>",
                            type: "post",
                            data: {
                                username: function () {
                                    return $("#username").val();

                                },
                                id: function () {
                                    return <?php echo $info[0]['admin_id']; ?>;
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

                    }, deposit: {
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