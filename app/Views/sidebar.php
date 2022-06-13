<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <div class="user-details">

            <div class="user-info">
                <div class="dropdown">
                    <div class="portal">Admin</div>
                </div>
            </div>
        </div>
        <div id="sidebar-menu">
            <ul>
                <li> <a href="<?php echo base_url('Dashboard'); ?>" class="waves-effect"><i class="fa fa-home"></i><span> Dashboard</span></a></li>
                <li> <a href="<?php echo base_url('Setting'); ?>" class="waves-effect"><i class="fa fa-cog"></i><span> Setting</span></a></li>
                <li> <a href="<?php echo base_url('TransactionSetting'); ?>" class="waves-effect"><i class="fa fa-cogs"></i><span> Transaction Setting</span></a></li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-envelope"></i> <span>Email
                            Templates</span> <span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo site_url('Emailformat'); ?>">Admin</a> </li>
                        <li><a href="<?php echo site_url('Emailformat/subadmin'); ?>">Sub Admin</a></li>
                    </ul>
                </li>
                <?php if ($admindetail['role'] == 1) { ?>
                    <li> <a href="<?php echo base_url('SubAdmin'); ?>" class="waves-effect"><i class="fa fa-user"></i><span> Sub Admin</span></a></li>
                <?php } ?>

                <li> <a href="<?php echo base_url('Members'); ?>" class="waves-effect"><i class="fa fa-users"></i><span> Members</span></a></li>

<!--                    <li> <a href="<?php echo base_url('Transaction'); ?>" class="waves-effect"><i class="fa fa-exchange"></i><span> Transaction</span></a></li>-->

                <?php if ($role_data['deposit'] == 1) { ?>
                    <li> <a href="<?php echo base_url('Deposit'); ?>" class="waves-effect"><i class="fa fa-money"></i><span> Deposit/Seed</span></a></li>
                <?php } ?>
                <?php if ($role_data['payout'] == 1) { ?>
                    <li> <a href="<?php echo base_url('Payout'); ?>" class="waves-effect"><i class="fa fa-money"></i><span> Payout/Harvest</span></a></li>
                <?php } ?>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-file"></i> <span>Reports
                        </span> <span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
                    <ul class="list-unstyled">
                        <?php if ($role_data['full_report'] == 1) { ?>
                            <li><a href="<?php echo site_url('Report'); ?>">All Transactions Log</a> </li>
                        <?php } ?>
                        <?php if ($role_data['partial_report'] == 1) { ?>
                            <li><a href="<?php echo site_url('Report/seed'); ?>">Seed Reports</a> </li>
                        <?php } ?>
                        <?php if ($role_data['full_report'] == 1) { ?>
                            <li><a href="<?php echo site_url('Report/receipt'); ?>">Harvest Reports</a> </li>
                        <?php } ?>
<!--                            <li><a href="<?php echo site_url('Report/receipt'); ?>">Receipt Accounts</a> </li>-->

                    </ul>
                </li>

            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</div>