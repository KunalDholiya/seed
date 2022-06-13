<?php echo $header; ?>   
<?php echo $sidebar; ?>   


<div class="content-page">
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title"></h4>
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
                   
                    <a href="<?php echo base_url('SubAdmin'); ?>">
                        <div class="col-sm-6 col-lg-3">
                            <div class="panel text-center">
                                <div class="panel-heading">
                                    <h4 class="panel-title text-muted font-light highlight-new">Sub Admin</h4>
                                </div>
                                <div class="panel-body p-t-10">
                                    <h2 class="m-t-0 m-b-15"><i class="fa fa-user text-primary m-r-10"></i><b><?php echo $total_student; ?></b></h2>

                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="<?php echo base_url('Members'); ?>">
                        <div class="col-sm-6 col-lg-3">
                            <div class="panel text-center">
                                <div class="panel-heading">
                                    <h4 class="panel-title text-muted font-light highlight-new">Members</h4>
                                </div>
                                <div class="panel-body p-t-10">
                                    <h2 class="m-t-0 m-b-15"><i class="fa fa-user text-primary m-r-10"></i><b><?php echo $total_member; ?></b></h2>

                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="<?php echo base_url('Emailformat'); ?>">
                        <div class="col-sm-6 col-lg-3">
                            <div class="panel text-center">
                                <div class="panel-heading">
                                    <h4 class="panel-title text-muted font-light highlight-new">Email Templates</h4>
                                </div>
                                <div class="panel-body p-t-10">
                                    <h2 class="m-t-0 m-b-15"><i class="fa fa-user text-primary m-r-10"></i><b><?php echo $total_email; ?></b></h2>

                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="<?php echo base_url('Deposit'); ?>">
                        <div class="col-sm-6 col-lg-3">
                            <div class="panel text-center">
                                <div class="panel-heading">
                                    <h4 class="panel-title text-muted font-light highlight-new">Transaction</h4>
                                </div>
                                <div class="panel-body p-t-10">
                                    <h2 class="m-t-0 m-b-15"><i class="fa fa-user text-primary m-r-10"></i><b><?php echo $total_transaction; ?></b></h2>

                                </div>
                            </div>
                        </div>
                    </a>
                    

                </div>


            </div>
        </div>
    </div>
    <?php echo $footer; ?>



    <script type="text/javascript">




    </script>