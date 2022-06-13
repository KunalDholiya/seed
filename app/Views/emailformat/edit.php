<?php echo $header; ?>
<?php echo $sidebar; ?>
<?php
if ($formattype == 2) {
    $back_url = site_url('Emailformat/university');
} elseif ($formattype == 3) {
    $back_url = site_url('Emailformat/instructor');
} elseif ($formattype == 4) {
    $back_url = site_url('Emailformat/student');
} elseif ($formattype == 5) {
    $back_url = site_url('Emailformat/SubAdmin');
} else {
    $back_url = site_url('Emailformat/index');
}
?>

<?php echo $header; ?>
<?php echo $sidebar; ?>
<div class="content-page">
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Email Templates</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('Dashboard'); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">
                            <?php if ($formattype == 1) { ?>
                                <a href="<?php echo site_url('Emailformat'); ?>">Admin email templates</a>
                            <?php } else if ($formattype == 2) { ?>
                                <a href="<?php echo site_url('Emailformat/university'); ?>">University email templates</a>
                            <?php } else if ($formattype == 3) { ?>
                                <a href="<?php echo site_url('Emailformat/instructor'); ?>">Instructor email templates</a>
                            <?php } else if ($formattype == 4) { ?>
                                <a href="<?php echo site_url('Emailformat/student'); ?>">User email templates</a>
                            <?php } else if ($formattype == 5) { ?>
                                <a href="<?php echo site_url('Emailformat/SubAdmin'); ?>">Sub Admin email templates</a>
                            <?php }
                            ?>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                                    <?php
//                    if(isset($_SERVER['HTTP_REFERER'])) {
//                    $url = $_SERVER['HTTP_REFERER'];
//                    $urlnew = explode('/', $url);
//                    $last_url_params = $urlnew[6];
//                    } 
                                    $url = base_url('Emailformat/index');
                                    ?>
                                    <div class="col-xs-12">
                                        <?php echo form_open('Emailformat/update', array('class' => '', 'name' => 'addform', 'id' => 'addform')); ?>
                                        <input type="hidden" id="id" name="id" value="<?php echo base64_encode($editinfo['id']); ?>" >  
                                        <input type="hidden" id="last_url_params" name="last_url_params" value="<?php echo $url; ?>" >  
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" readonly="" id="etitle" name="etitle" placeholder="Title" class="form-control" value="<?php echo $editinfo['title']; ?>">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Subject</label>
                                                <input type="text" id="esubject" name="esubject" placeholder="Subject" class="form-control" value="<?php echo $editinfo['subject']; ?>">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Variable</label><br>
                                                <?php echo $editinfo['variables']; ?>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Email Format</label>
                                                <textarea id="eemailformat" rows="10" name="eemailformat" class="form-control"><?php echo $editinfo['emailformat']; ?></textarea>

                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-sm-12 ">
                                            <button type="submit" class="btn btn-primary pull-right ">Update</button>
                                            <a href="<?php echo $back_url; ?>" class="btn btn-default pull-right m-r-5">Cancel</a>
                                        </div>
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

        <script type="text/javascript">
            $(document).ready(function () {
               
                CKEDITOR.replace('eemailformat', {
                    enterMode: CKEDITOR.ENTER_BR
                });
               
            });

            jQuery("#addfrm").validate({
                rules: {
                    esubject: "required",
                    eemailformat: function ()
                    {
                        CKEDITOR.instances.eemailformat.updateElement();
                    },
                    //eemailformat: "required"

                },
                messages: {
                    esubject: "Subject is required",
                    eemailformat: "Email Format name is required"

                },
                errorPlacement: function (error, element) {
                    error.insertAfter($(element).parent('div')).addClass('control-label');
                }
            });
        </script>


