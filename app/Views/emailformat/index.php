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
                        <li class="breadcrumb-item active" aria-current="page">
                            <?php
                            if ($formattype == 1) {
                                echo 'Admin email templates';
                            } else if ($formattype == 2) {
                                echo 'University email templates';
                            } else if ($formattype == 3) {
                                echo 'Instructor email templates';
                            } else if ($formattype == 4) {
                                echo 'User email templates';
                            } else if ($formattype == 5) {
                                echo 'Sub Admin email templates';
                            }
                            ?>
                        </li>
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
                                            <table class="table table-striped table-bordered dataTable no-footer" id="infotable">
                                                <input type="hidden" class="form-control" id="type" name="type" value="<?php echo $formattype; ?>">

                                                <thead>
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Subject</th>
                                                        <th class="text-center">Action</th>                                       
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
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
    <script type="text/javascript">

        function load_initial_data() {
            var table = jQuery('#infotable').DataTable({
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "order": [[0, "ASC"]],
                "ajax": {
                    url: "<?php echo site_url('Emailformat/getdata'); ?>",
                    type: "GET",
                    data: function (d) {

                        d.<?php echo csrf_token(); ?> = "<?php echo csrf_hash(); ?>";
                        d.type1 = function () {
                            return $('#type').val();
                        };

                    },
                },
                "columns": [
                    {"taregts": 0, 'data': 'title'
                    },
                    {"taregts": 1, 'data': 'subject'
                    },
                    {"taregts": 2, "searchable": false, "orderable": false, "sClass": "text-center",
                        "render": function (data, type, row) {
                            var id = btoa(row.id);

                            var out = '';

                            out += '<a title="Edit Email Template" href="<?php echo site_url('Emailformat/edit/'); ?>' + id + '"><i class="glyphicon glyphicon-edit btm-view"></i></a>&nbsp;';

                            return out;
                        }

                    },
                ]
            });

        }


        $(document).ready(function () {
            load_initial_data();



        });


        function reload_transaction_table() {
            var oTable1 = $('#infotable').dataTable();
            oTable1.fnStandingRedraw();
        }
        $.fn.dataTableExt.oApi.fnStandingRedraw = function (oSettings) {
            //redraw to account for filtering and sorting
            // concept here is that (for client side) there is a row got inserted at the end (for an add)
            // or when a record was modified it could be in the middle of the table
            // that is probably not supposed to be there - due to filtering / sorting
            // so we need to re process filtering and sorting
            // BUT - if it is server side - then this should be handled by the server - so skip this step
            if (oSettings.oFeatures.bServerSide === false) {
                var before = oSettings._iDisplayStart;
                oSettings.oApi._fnReDraw(oSettings);
                //iDisplayStart has been reset to zero - so lets change it back
                oSettings._iDisplayStart = before;
                oSettings.oApi._fnCalculateEnd(oSettings);
            }

            //draw the 'current' page
            oSettings.oApi._fnDraw(oSettings);
        };






    </script>


