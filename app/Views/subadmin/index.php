
<?php echo $header; ?>
<?php echo $sidebar; ?>
<div class="content-page">
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Sub Admin <a href="<?php echo site_url('SubAdmin/add'); ?>" class="btn btn-primary waves-effect waves-light pull-right add-btn"><i class="fa fa-plus"></i> Add Sub Admin</a></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('Dashboard'); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sub Admin</li>
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
                                                        <th>No.</th>
                                                        <th>Sub Admin name</th>


<!--                                                        <th>University</th>-->
                                                        <th>Email</th>
                                                        <th>Phone No</th>
                                                        <th class="text-center">Status</th>
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

    <div class="modal fade" id="change_status" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Change Status</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <input type="hidden" id="change_status_id" />
                        <input type="hidden" id="change_status_status" />
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea id="reason" name="reason" placeholder="Enter your reason" class="form-control" ></textarea>
                            </div>
                            <h5 class="tx-cen">Are you sure you want to <span id="new_status"></span> this Sub Admin?</h5>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    <button type="button" onclick="update_status()" class="btn btn-success">Yes</button>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete Alert</h4>
                </div>
                <div class="modal-body">

                    <h5 class="tx-cen">Are you sure you want to delete this Sub Admin ?</h5>
                    <input type="hidden" value="" id="deleteid" />
                </div>
                <div class="modal-footer">
                    <a id="confirm_btn" href="#" onclick="deleterecord()" class="btn btn-danger">Yes</a>
                    <button data-dismiss="modal" class="btn btn-default">No</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="resendmodal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Reset Password</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <h5 class="tx-cen">Are you sure you want to Reset Password to <span id="passname"></span>?</h5>
                        <input type="hidden" value="" id="resendid" />

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">New Password : </label><span id="newpass"></span>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a id="confirm_btn" href="#" onclick="resendpass()" class="btn btn-primary">Send Mail</a>
                    <button data-dismiss="modal" class="btn btn-default">No</button>
                </div>
            </div>
        </div>
    </div>

    <?php echo $footer; ?>
    <script type="text/javascript">
        function load_transactiontable() {
            var table;
            var table = jQuery('#transaction').DataTable({
                "processing": true,
                "serverSide": true,
                "responsive": false,
                "order": [[0, "DESC"]],
                "ajax": {
                    url: "<?php echo site_url('SubAdmin/gettabledata'); ?>",
                    data: function (d) {
                        d.<?php echo csrf_token(); ?> = "<?php echo csrf_hash(); ?>";

                    },
                },
                "columns": [
                    {"taregts": 0, 'data': 'admin_id', "visible": false,
                    },
                    {"taregts": 1, 'data': 'firstname',
                        "render": function (data, type, row) {
                            return row.firstname + ' ' + row.lastname;
                        }
                    },
//                    {"taregts": 2, 'data': 'lastname'
//                    },
//                  
//                    {"taregts": 2, 'data': 'uni_name'
//                    },
                    {"taregts": 2, 'data': 'email'
                    },
                    {"taregts": 3, 'data': 'contact_no'
                    },
                    {"taregts": 4,
                        "data": "status", "sClass": "text-center",
                        "render": function (data, type, row) {
                            var id = btoa(row.admin_id);
                            if (data == 'Enable')
                            {
                                return '<a title="Change Status" class="btn btn-success btn-xs" data-id="' + row.admin_id + '" data-status="' + row.status + '" href="#change_status" data-toggle="modal" onclick="change_status(this)">Enable</a>';
                            } else
                            {
                                return '<a title="Change Status" class="btn btn-danger btn-xs" data-id="' + row.admin_id + '" data-status="' + row.status + '" href="#change_status" data-toggle="modal" onclick="change_status(this)">Disable</a>';
                            }
                        }
                    },
                    {"taregts": 5, "searchable": false, "orderable": false, "sClass": "text-center",
                        "render": function (data, type, row) {
                            var id = btoa(row.admin_id);
                            var out = '';

                            out += '<a title="Edit"  href="<?php echo site_url('SubAdmin/edit/'); ?>' + id + '"><i class="glyphicon glyphicon-edit btm-view"></i></a>&nbsp;';
                            //out += '<a title="Delete" href="#" onclick="deletemodal(' + row.admin_id + ')"><i class="glyphicon glyphicon-trash btm-view"></i></a>&nbsp;';
                            out += '<a title="Reset Password" href="#" onclick="resendmodal(' + row.admin_id + ', \'' + row.firstname + ' \')"><i class="glyphicon glyphicon-lock btm-view"></i></a>&nbsp;';
                            return out;
                        }
                    },
                ]
            });

        }
        $(document).ready(function () {
            load_transactiontable();
            // reload_transaction_table();
        });


        function reload_transaction_table() {
            var oTable1 = $('#transaction').dataTable();
            oTable1.fnStandingRedraw();
        }

        /* for status */
        function change_status(obj) {
            $('#change_status_id').val($(obj).data('id'));
            $('#change_status_status').val($(obj).data('status'));
            var status = '';
            if ($(obj).data('status') == 'Enable') {
                status = "Disable";
            } else {
                status = "Enable";
            }
            $('#new_status').html(status);
        }
        function update_status() {

            var id = $('#change_status_id').val();
            var status = $('#change_status_status').val();
            var reason = $('#reason').val();
            $.ajax({
                url: "<?php echo base_url() . '/SubAdmin/update_status' ?>",
                type: "POST",
                dataType: "json",
                data: {'<?php echo csrf_token(); ?>': '<?php echo csrf_hash(); ?>', id: id, status: status, reason: reason},
                catch : false,
                success: function (data) {
                    if (data.status == 'success') {
                        $('#change_status').modal('hide');
                        flash_alert_msg(data.msg, 'success', 3000);
                        reload_transaction_table();
                    } else {
                        flash_alert_msg(data.msg, 'error', 3000);
                    }

                }
            });


        }


        /* delete */
        function deletemodal(id) {
            $('#deletemodal').modal();
            $('#deleteid').val(id);

        }


        function resendmodal(id, name) {
            $('#resendmodal').modal();
            $('#resendid').val(id);
            $('#passname').html(name);
            $('#newpass').html(makeid(8));

        }
        function makeid(length) {
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }
        function resendpass() {
            var resendid = $('#resendid').val();
            var newpass = $('#newpass').html();
            $.ajax({
                url: "<?php echo base_url() . '/SubAdmin/resendPass' ?>",
                type: "POST",
                dataType: "json",
                data: {'<?php echo csrf_token(); ?>': '<?php echo csrf_hash(); ?>', id: resendid, newpass: newpass},
                catch : false,
                success: function (data) {
                    if (data.status == 'success') {
                        flash_alert_msg(data.msg, 'success', 3000);
                        $('#resendmodal').modal('hide');
                        reload_transaction_table();
                    } else {
                        flash_alert_msg(data.msg, 'error', 3000);
                    }

                }
            });
        }


        function deleterecord() {
            var deleteid = $('#deleteid').val();
            $.ajax({
                url: "<?php echo base_url() . '/SubAdmin/delete' ?>",
                type: "POST",
                dataType: "json",
                data: {'<?php echo csrf_token(); ?>': '<?php echo csrf_hash(); ?>', id: deleteid},
                catch : false,
                success: function (data) {
                    if (data.status == 'success') {
                        flash_alert_msg(data.msg, 'success', 3000);
                        $('#deletemodal').modal('hide');
                        reload_transaction_table();
                    } else {
                        flash_alert_msg(data.msg, 'error', 3000);
                    }

                }
            });
        }


    </script>