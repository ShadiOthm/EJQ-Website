                        <div class="col-md-9">
                                <div class="content-box-edition<?php echo (empty($showCompanyDisclosure)?'': ' force_show') ?>" id="update_company_disclosure">
                                 <?php echo $this->Element('contractors/update_company_disclosure'); ?>
                                </div>
                                <div id="company_disclosure">
                                    <h4><?php echo __("Company Disclosure:"); ?><?php
                echo $this->Html->link(
                        $this->Html->image("/img/icon-edit.png", array('title' => __('update company disclosure'))),
                        '#show_update_company_disclosure',
                        array(
                            'escape' => false,
                            'title' => __('Edit company disclosure'),
                            'id' => 'show_update_company_disclosure',
                            ));
                 ?></h4>
<?php
    if (empty($provider['Contractor']['0']['years_in_business'])
        && empty($provider['Contractor']['0']['number_of_employees'])
        && !isset($provider['Contractor']['0']['business_licence'])
        && !isset($provider['Contractor']['0']['work_safe_BC'])
        && empty($provider['Contractor']['0']['insurance_amount'])
        && empty($provider['Contractor']['0']['insurance_provider'])
        && !isset($provider['Contractor']['0']['bbb_complaints'])
        ):
?>
                                <p id="name_position"><?php echo __("Please provide information about the company"); ?></p>
<?php
    else:
?>

<?php
        if (!empty($provider['Contractor']['0']['years_in_business'])):
?>
                                <p id="years_in_business"><strong><?php echo __("In business since (YYYY):"); ?></strong> <?php echo $provider['Contractor']['0']['years_in_business']; ?></p>
<?php
        endif;
?>
<?php
        if (!empty($provider['Contractor']['0']['number_of_employees'])):
?>
                                <p id="number_of_employees"><strong><?php echo __("Number of Employees:"); ?></strong> <?php echo $provider['Contractor']['0']['number_of_employees']; ?></p>
<?php
        endif;
?>
<?php
        if (isset($provider['Contractor']['0']['business_licence'])):
?>
                                <p id="business_licence"><strong><?php echo __("Registered business with business licence:"); ?></strong> <?php echo ($provider['Contractor']['0']['business_licence']?__('Yes'):__('No')); ?></p>
<?php
        endif;
?>
<?php
        if (!empty($provider['Contractor']['0']['municipality_name'])):
?>
                                <p id="municipality_name"><strong><?php echo __("Municipality:"); ?></strong> <?php echo $provider['Contractor']['0']['municipality_name']; ?></p>
<?php
        endif;
?>
<?php
        if (isset($provider['Contractor']['0']['work_safe_BC'])):
?>
                                <p id="work_safe_BC"><strong><?php echo __("Business in good standing with WorkSafe BC:"); ?></strong> <?php echo ($provider['Contractor']['0']['work_safe_BC']?__('Yes'):__('No')); ?></p>
<?php
        endif;
?>
<?php
        if (!empty($provider['Contractor']['0']['insurance_amount'])):
?>
                                <p id="insurance_amount"><strong><?php echo __("Amount of Liability Insurance Coverage:"); ?></strong> <?php
                App::uses('CakeNumber', 'Utility');
                echo CakeNumber::format($provider['Contractor']['0']['insurance_amount'], array(
                    'places' => 2,
                    'before' => 'CAD ',
                    'escape' => false,
                    'decimals' => '.',
                    'thousands' => ','
                ));
?>
<?php
        endif;
?>
<?php
        if (!empty($provider['Contractor']['0']['insurance_provider'])):
?>
                                <p id="insurance_provider"><strong><?php echo __("Liability Insurance Provider:"); ?></strong> <?php echo $provider['Contractor']['0']['insurance_provider']; ?></p>
<?php
        endif;
?>
<?php
        if (isset($provider['Contractor']['0']['bbb_complaints'])):
?>
                                <p id="bbb_complaints"><strong><?php echo __("BBB Complaints:"); ?></strong> <?php echo ($provider['Contractor']['0']['bbb_complaints']?__('Yes'):__('No')); ?></p>
<?php
            if (!empty($provider['Contractor']['0']['bbb_complaints'])):
?>
                                <p id="bbb_observation"><?php echo $provider['Contractor']['0']['bbb_observation']; ?></p>
<?php
            endif;
?>
<?php
        endif;
?>
<?php
        if (!empty($provider['Contractor']['0']['services_list_description'])):
?>
                                <p id ="services_list"><strong><?php echo __("Job categories:"); ?></strong> <?php echo $provider['Contractor']['0']['services_list_description']; ?></p>
<?php
        endif;
?>
<?php
    endif;
?>
                                </div>
                                <hr />
                        </div>