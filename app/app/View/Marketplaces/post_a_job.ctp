<?php
echo $this->Html->script('chosen.jquery.min.js?v=1.7.0'); 
echo $this->Html->css('chosen.css?v=1.7.0');
?>
<?php 
echo $this->Html->script(array('jquery.mask.min.js?v=0.0.0'), false); 
echo $this->Html->script(array('jquery.colorbox-min.js?v=0.0.0'), false); 
echo $this->Html->script(array('marketplaces_post_a_job.js?v=0.0.0'), false); 
?>
            <div class="col-md-6">
<?php
echo $this->Form->create('Consumer', array('url'=>array('controller'=>'marketplaces', 'action' => 'post_a_job')));
echo $this->Form->input(
        'Request.description', 
        array(
            'div'=>array('class'=>'form-group'), 
            'class' => 'form-control',
            'label'=> array('text' => __('Describe what needs to be done'), 'class' => 'control-label'),
            'type' => 'textarea',
            'rows' => '8',
            ));
$selectOption = ['0' => __('(choose a municipality)')];
if (!empty($municipalities)) {
    asort($municipalities);
    $municipalities = $selectOption + $municipalities;
} else {
    $municipalities = $selectOption;
    
}
echo $this->Form->input(
        'Request.municipality_id', 
        array(
            'div'=>array('class'=>'form-group'), 
            'class' => 'form-control chosen-select',
            'label'=> array('text' => __('Municipality'), 'class' => 'control-label'),
            'type' => 'select',
            'options' => $municipalities,
            ));
?>
            <?php
            if (!$knownUser):
            ?>
                    <h3 class="form-title"><?php echo __('Create an account'); ?></h3>

                    <p><?php echo __('Already have an account?'); ?> <?php                                                    
                            echo $this->Html->link(
                                __('Login'), 
                                array('controller' => 'users', 'action' => 'login'), 
                                array(
                                    'escape' => false, 
                                    'title' => __('Login'),
                                )
                            );
                        ?></p>
                <?php
                    echo $this->Form->input('user_name', 
                            array(
                                'div'=>array(
                                    'class'=>'form-group',
                                    ), 
                                'label'=> array(
                                    'text' => __('Name'), 
                                    'class' => 'control-label',
                                    ), 
                                'required'=>true, 
                                'class' => 'form-control',
                                ));
                    echo $this->Form->input('email', array(
                        'div'=>array(
                            'class'=>'form-group',
                            ), 
                        'label'=> array(
                            'text' => __('Email'), 
                            'class' => 'control-label'
                            ), 
                        'required'=>true, 
                        'class' => 'form-control'
                        ));
                    echo $this->Form->input('phone', array(
                        'div'=>array(
                            'class'=>'form-group',
                            ), 
                        'label'=> array(
                            'text' => __('Phone Number'), 
                            'class' => 'control-label'
                            ), 
                        'required'=>true, 
                        'class' => 'form-control phone'
                        ));
                    
                    $link = $this->Html->link($this->Html->tag('span', __('terms of service')), "#terms_of_service_content", array('escape' => false,  'id' => 'show_terms_of_service', 'class' => 'terms_of_service'));
                    $label = sprintf("I agree to the %s", $link);                   
                    
                    echo $this->Form->input('agreed', array(
                        'div'=>array(
                            'class'=>'form-group',
                            ), 
                        'type' => 'checkbox',
                        'class' => 'check',
                        'label' => false,
                        'before' => '<label for="ConsumerAgreed">',
                        'after' =>  '&nbsp;' . $label . '</label>',
                        'checked' => false,
                        'required'=>true, 
                        ) );

                else:
                    echo $this->Form->hidden('user_id');
                endif;
                echo $this->Form->submit(__('Post My Job'), array(
                        'div'=>array('class'=>'form-group'), 
                        'class' => 'btn btn-default',
                        'alt' => __('Post My Job'),
                        'title' => __('Post My Job'),
                        ));
                echo $this->Form->end();
                ?>

                            </div>
            <!-- sidebar -->
            <div class="col-offset-2 col-md-4 pull-right">
            
            </div>
            <!-- /sidebar -->
            <div style="display: none">
                <div id='terms_of_service_content' style='padding:10px; background:#fff;'>
                    <img src="/img/logo.png">
                            <h1>Terms and Conditions for Home Owner Request</h1>
                            <p>Thank you for submitting a renovation project request to Easy Job Quote. We look forward to helping you find a qualified contractor to complete your job.</p>
                            <p>By accepting these terms and conditions you understand that Easy Job Quote is a service provided to homeowners and tradespeople alike. We do not price or compete or do the renovations.</p>
                            <p>Our Project Developer will visit your home, take pictures, measurements and draw up a description of the scope of work of your project, much as an estimator for a contracting company would prepare. We will then present your project to our member contractors to bid upon the job, through our website. Our service saves you time and effort in seeking a contractor with our one visit system. The time and money the contractors save can be passed directly to you. The full legal contract provided will give you peace of mind and a strong start to your project.</p>
                            <p>Easy Job Quote will charge a home visit fee of $149.00 CDN + GST, for this service, due at the time of the visit. Expect at least one hour, often two to complete this interview. This is your time to ask any questions, propose any ideas, and discuss any aspect of the job with an experienced contractor who will not be bidding upon your job – a safe, neutral, unpressured conversation.</p>
                            <p>Easy Job Quote will not bid, nor interfere in pricing of the work. We encourage fair pricing in the market place, and all contractors know they are in competition for your job. We do not obligate you to choose one of our member bids, nor do we obligate our members to bid.</p>
                            <p>We will book your home visit at the earliest convenient time for you and our Project Developer. We will prepare the tender and submit it to you for approval prior to presenting to our members. Upon your approval, the job will be presented to our members on the next Monday morning. Bidding is open for at least one week to allow fair access to all qualified members. You are then able to view and select your contractor. We appreciate your understanding that this process takes time. The time between your request, our visit and tender preparation, the bidding, and your contractor selection will take approximately two weeks.</p>
                            <p>Easy Job Quote interviews all member contractors. We verify their qualifications, and insurance. We have interviewed their past clients, and ask you, our new client, to provide your own review upon completion of your project. This allows for a clear, accountable assessment of our members work. We have saved you the time, and potential frustration of completing your own due diligence, and interviews.</p>
                            <p>Welcome to Easy Job Quote. More than just a handshake.</p>
                </div>
            </div>