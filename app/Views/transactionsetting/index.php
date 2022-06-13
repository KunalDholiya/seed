<?php echo $header; ?>
<?php echo $sidebar; ?>
<div class="content-page">
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Transaction Setting</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('Dashboard'); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Transaction Setting</li>
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
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered dataTable no-footer" id="transaction">
                                                <thead>
                                                    <tr>
                                                        <th class="col-xs-8">Title</th>
                                                        <th class="col-xs-3">Manual or Value</th>
                                                        <th class="col-xs-3">Value</th>
                                                        <th class="text-center col-xs-1">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (count($settings) > 0) { ?>
                                                        <?php for ($i = 0; $i < count($settings); $i++) { ?>   
                                                            <tr class="gradeX">
                                                                <!--td><?php echo $i + 1; ?></td-->
                                                                <td><?php echo ucfirst($settings[$i]['name']); ?></td>
                                                                <?php if ($settings[$i]['id'] == 1) { ?>
                                                                    <td><?php echo ($settings[$i]['manual_value']); ?></td>
                                                                <?php } else { ?>
                                                                    <td></td>
                                                                <?php } ?>
                                                                <?php if ($settings[$i]['id'] == 4) { ?>
                                                                    <td><?php echo date('d-m-Y', strtotime($settings[$i]['value'])); ?></td>
                                                                <?php } else { ?>
                                                                    <td><?php echo ($settings[$i]['value']); ?></td>
                                                                <?php } ?>

                                                                <td class="text-center">
                                                                    <a href="#myModal" title="Edit Setting" id="edit_btn" onclick="edit_setting('<?php echo base64_encode($settings[$i]['id']); ?>');" data-toggle="modal"> <i class="glyphicon glyphicon-edit btm-view"></i></a>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?> 
                                                        <tr><td colspan="3" class="text-center"> <?php echo "No record found" ?></td></tr>
                                                    <?php } ?>  
                                                </tbody>
                                            </table>
                                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content" id="model_data">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php echo $footer; ?>

        <!--jquer, javascript and ajax-->
        <script type="text/javascript">
            function edit_setting(id)
            {

                var setting_id = id;
                $('#model_data').html('');
                $.ajax({
                    url: "<?php echo site_url() . 'TransactionSetting/update' ?>",
                    type: "POST",
                    dataType: "html",
                    data: {'<?php echo csrf_token(); ?>': '<?php echo csrf_hash(); ?>', 'id': setting_id, },
                    catch : false,
                    success: function (data) {
                        $('#model_data').append(data);

                    }
                });
            }
        </script>

