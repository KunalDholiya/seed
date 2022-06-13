
<?php echo $header; ?>
<?php echo $sidebar; ?>
<div class="content-page">
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Quotes</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('Quotes'); ?>">Quotes</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Quotes</li>
                    </ol>
            </div>
        </div>
        <div class="page-content-wrapper ">
            <div class="container">
                <div class="row">
                    <div class="col-md-12" id="error_msg_info">
                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success fade in" style="margin-top:18px;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                <strong><?php echo $this->session->flashdata('success'); ?></strong> 
                            </div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-danger fade in" style="margin-top:18px;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                <strong><?php echo $this->session->flashdata('error'); ?></strong> 
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
                                        <?php echo form_open('Quotes/editnew/' . base64_encode($info[0]['id']), array('id' => 'addfrm', 'class' => '', 'method' => 'POST', 'enctype' => 'multipart/form-data')); ?>
                                        <div class="row">
                                            
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Quotes Name<span class="error">*</span></label>
                                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $info[0]['name']; ?>" placeholder="Quotes Name">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Email<span class="error">*</span></label>
                                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $info[0]['email']; ?>" placeholder="Email">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Phone<span class="error">*</span></label>
                                                    <input type="text" class="form-control" id="contact_no" name="contact_no" value="<?php echo $info[0]['contact_no']; ?>" placeholder="Phone">
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>

                                            <div class="col-md-4">
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
                                                        <!--                                                        <option value="">Select Country</option>-->
                                                        <?php foreach ($country as $l) { ?>
                                                            <option value="<?php echo $l['id']; ?>" <?php if($l['id'] == $info[0]['country_id']){ echo 'selected'; }?>><?php echo $l['name']; ?></option>
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
                                                            <option value="<?php echo $l['id']; ?>" <?php if($l['id'] == $info[0]['state_id']){ echo 'selected'; }?>><?php echo $l['name']; ?></option>
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
                                                        <img src="<?php echo base_url() . $this->config->item('upload_path_user_thumb') . $info[0]['image']; ?>"
                                                             alt="user-img" >
<?php } ?>
                                                </div>
                                            </div>


                                        </div>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light m-t-10">Update Quotes</button>
                                        <a class="btn btn-default waves-effect waves-light m-t-10" href="<?php echo base_url('Quotes'); ?>">Cancel</a>
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
                    name: "required",

                    email: {
                        required: true,
                        email: true,
                        remote: {
                            url: "<?php echo site_url('Quotes/emailExitsedit') ?>",
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
                    
                    image: {
                        dimention: [600, 600]
                    },
                },
                messages: {
                    name: "Quotes name is required",

                    email: {
                        required: "Email is required.",
                        remote: "Email already exists."
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
                    
                   

                },
//                errorPlacement: function (error, element) {
//                    error.insertAfter($(element).parent('div')).addClass('control-label');
//                }
            });
        </script>