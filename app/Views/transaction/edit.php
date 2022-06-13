
<?php echo $header; ?>
<?php echo $sidebar; ?>
<div class="content-page">
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Transaction</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('Transaction'); ?>">Transaction</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Transaction</li>
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
                                        <?php echo form_open('Transaction/editnew/'. base64_encode($info[0]['id']), array('id' => 'addfrm', 'class' => '', 'method' => 'POST', 'enctype' => 'multipart/form-data')); ?>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Members<span class="error">*</span></label>
                                                    <select class="form-control" name="member_id[]" id="member_id" multiple="multiple">
                                                        <option value="">Select Members</option>
                                                        <?php 
                                                        $member_id = explode(',',$info[0]['member_id']);
                                                        foreach ($member as $l) { ?>
                                                        <option value="<?php echo $l['id']; ?>" <?php if(in_array($l['id'], $member_id)){ echo 'selected'; }?>><?php echo $l['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <label for="member_id" class="error"></label>
                                                </div>
                                            </div>
                 

                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">week<span class="error">*</span></label>
                                                    <input type="text" class="form-control" id="week" name="week" placeholder="week" value="<?php echo $info[0]['week']; ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Date of seed<span class="error">*</span></label>
                                                    <input type="text" class="form-control" id="date_of_seed" name="date_of_seed" placeholder="Date of seed" value="<?php echo date('Y-m-d', strtotime($info[0]['date_of_seed'])); ?>">
                                                </div>
                                            </div>
                                             <div class="clearfix"></div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Date of harvest<span class="error">*</span></label>
                                                    <input type="text" class="form-control" id="date_of_harvest" name="date_of_harvest" placeholder="Date of harvest" value="<?php echo date('Y-m-d', strtotime($info[0]['date_of_harvest'])); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Amount planted this week<span class="error">*</span></label>
                                                    <input type="text" class="form-control" id="amount_planted_week" name="amount_planted_week" placeholder="Amount planted this week" value="<?php echo $info[0]['amount_planted_week']; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Total amount planted to date<span class="error">*</span></label>
                                                    <input type="text" class="form-control" id="amount_planted_date" name="amount_planted_date" placeholder="Total amount planted to date" value="<?php echo $info[0]['amount_planted_date']; ?>">
                                                </div>
                                            </div>
                                              <div class="clearfix"></div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Amount of persons in harvesting this week</label>
                                                    <input type="text" class="form-control" id="week_per_person" name="week_per_person" placeholder="Amount of persons in harvesting this week" value="<?php echo $info[0]['week_per_person']; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Total harvest this week</label>
                                                    <input type="text" class="form-control" id="total_per_week" name="total_per_week" placeholder="Total harvest this week" value="<?php echo $info[0]['total_per_week']; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Church receives</label>
                                                    <input type="text" class="form-control" id="church_received" name="church_received" placeholder="Church receives" value="<?php echo $info[0]['church_received']; ?>">
                                                </div>
                                            </div>
                                               <div class="clearfix"></div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Members receives</label>
                                                    <input type="text" class="form-control" id="member_received" name="member_received" placeholder="Members receives" value="<?php echo $info[0]['member_received']; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group"> <label for="">Amount return to pool</label>
                                                    <input type="text" class="form-control" id="amount_return_to_pool" name="amount_return_to_pool" placeholder="Amount return to pool" value="<?php echo $info[0]['amount_return_to_pool']; ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="clearfix"></div>
                                            

                                        </div>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light m-t-10">Edit Transaction</button>
                                        <a class="btn btn-default waves-effect waves-light m-t-10" href="<?php echo base_url('Transaction'); ?>">Cancel</a>
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
            $('#member_id').select2({
                placeholder: 'Select Members',
                width: 'resolve' // need to override the changed default
            });
            $(function () {
                $("#date_of_seed").datepicker({
                    format: "yyyy-mm-dd",
                    //maxDate: moment()
                });
                $("#date_of_harvest").datepicker({
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
                    'member_id[]': {
                        required: true,
                    },
                    week: {
                        required : true
                    },
                    date_of_seed: {
                        required : true
                    },
                    date_of_harvest: {
                        required : true
                    },
                    amount_planted_week: {
                        number:true,
                        required : true
                    },
                    amount_planted_date: {
                        number:true,
                        required : true
                    },
                    week_per_person: {
                        number:true,
                    },
                    total_per_week: {
                        number:true,
                    },
                    church_received: {
                        number:true,
                    },
                    member_received: {
                        number:true,
                    },
                    amount_return_to_pool: {
                        number:true,
                    },
                    
                },
                messages: {
                    'member_id[]': {
                        required: "Please select members",
                    },
                   
                    week: {
                        required: "Week is required"
                    },
                    date_of_seed: {
                        required: "Date of seed is required"
                    },
                    date_of_harvest: {
                        required: "Date of harvest is required"
                    },
                    amount_planted_week: {
                        required: "Amount planted this week is required"
                    },
                    amount_planted_date: {
                        required: "Total amount planted to date is required"
                    },
                    

                },
                //                errorPlacement: function (error, element) {
                //                    error.insertAfter($(element).parent('div')).addClass('control-label');
                //                }
            });
        </script>