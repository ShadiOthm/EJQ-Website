<?php echo $this->Html->script(array('marketplaces_dashboard.js?v=0.0.0'), false); ?>

        <div class="row">
            <!-- /col 1 de 1 -->
            <div class="col-md-12">

                <ul class="nav nav-tabs">
                    <li class="nav-item<?php echo (empty($activeTab) || $activeTab == 'requests' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#requests" role="tab"><?php echo __('Requests'); ?></a></li>
                    <li class="nav-item<?php echo ($activeTab == 'in_progress' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#in_progress" role="tab"><?php echo __('Tenders In Progress'); ?></a></li>
                    <li class="nav-item<?php echo ($activeTab == 'open_to_bids' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#open_to_bids" role="tab"><?php echo __('Open To Bids'); ?></a></li>
                    <li class="nav-item<?php echo ($activeTab == 'closed_to_bids' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#closed_to_bids" role="tab"><?php echo __('Closed To Bids'); ?></a></li>
                    <li class="nav-item<?php echo ($activeTab == 'jobs' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#jobs" role="tab"><?php echo __('Jobs'); ?></a></li>
                    <li class="nav-item<?php echo ($activeTab == 'invoices' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#invoices" role="tab"><?php echo __('Invoices'); ?></a></li>
                    <li class="nav-item<?php echo ($activeTab == 'contractors' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#contractors" role="tab"><?php echo __('Contractors'); ?></a></li>
                    <li class="nav-item<?php echo ($activeTab == 'estimators' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#estimators" role="tab"><?php echo __('Project Developers'); ?></a></li>
                    <li class="nav-item<?php echo ($activeTab == 'consumers' ? ' active' : ''); ?>"><a class="nav-link" data-toggle="tab" href="#consumers" role="tab"><?php echo __('Home Owners'); ?></a></li>
                </ul>

                <div class="tab-content">
                <?php echo $this->Element('marketplaces/dashboard_requests'); ?>
                <?php echo $this->Element('marketplaces/dashboard_in_progress'); ?>
                <?php echo $this->Element('marketplaces/dashboard_open_to_bids'); ?>
                <?php echo $this->Element('marketplaces/dashboard_closed_to_bids'); ?>
                <?php echo $this->Element('marketplaces/dashboard_jobs'); ?>
                <?php echo $this->Element('marketplaces/dashboard_invoices'); ?>
                <?php echo $this->Element('marketplaces/dashboard_contractors'); ?>
                <?php echo $this->Element('marketplaces/dashboard_estimators'); ?>
                <?php echo $this->Element('marketplaces/dashboard_consumers'); ?>

                </div>
            </div>
            <!-- /col 9 -->
        </div>




