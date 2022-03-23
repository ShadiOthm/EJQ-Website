<?php
echo $this->Html->script('chosen.jquery.min.js?v=1.7.0');
echo $this->Html->css('chosen.css?v=1.7.0');
?>
<?php 
echo $this->Html->script(array('jquery.mask.min.js?v=0.0.0'), false); 
echo $this->Html->script(array('jquery.colorbox-min.js?v=0.0.0'), false); 
echo $this->Html->script(array('marketplaces_become_a_member.js?v=0.0.0'), false); 
?>
<div class="col-md-6">
<?php
                    echo $this->Form->create('Provider', array('url'=>array('controller'=>'marketplaces', 'action' => 'become_a_member')));
                    echo $this->Form->hidden('Marketplace.id');
                    echo $this->Form->hidden('MetaProvider.id');
                    echo $this->Form->input('Contractor.name',
                            array(
                                'div'=>array(
                                    'class'=>'form-group',
                                    ),
                                'label'=> array(
                                    'text' => __('Company Name'),
                                    'class' => 'control-label',
                                    ),
                                'required'=>true,
                                'class' => 'form-control',
                                ));
                    echo $this->Form->input(
                            'Contractor.about',
                            array(
                                'div'=>array('class'=>'form-group'),
                                'class' => 'form-control',
                                'label'=> array('text' => __('About  your company'), 'class' => 'control-label'),
                                'type' => 'textarea',
                                'rows' => '8',
                                ));

                    $selectOption = ['0' => __('(inform a municipality)')];
                    if (!empty($municipalities)) {
                        asort($municipalities);
                        $municipalities = $selectOption + $municipalities;
                    } else {
                        $municipalities = $selectOption;

                    }
                    echo $this->Form->input(
                            'Contractor.municipality_id',
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
                            'text' => __('Email (used for login and get info about bids)'),
                            'class' => 'control-label'
                            ),
                        'required'=>true,
                        'class' => 'form-control'
                        ));

                    echo $this->Form->input('Contractor.phone', array(
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
                echo $this->Form->submit(__('Become A Member'), array(
                        'div'=>array('class'=>'form-group'),
                        'class' => 'btn btn-default',
                        'alt' => __('Become A Member'),
                        'title' => __('Become A Member'),
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
                            <h1>Marketplace Orientation</h1>
                            <p>Easy Job Quote.com is an online marketplace for home owners and contractors to meet, negotiate, and come to agreement for work to be completed. Understanding how the marketplace works will help you position yourself for success.</p>
                            <p>We’ll begin by explaining how contractors are seen in this marketplace. When a contractor applies for membership, a background check is performed. Once the background check has been performed, past home owners are contacted to write a review of that contractor. These reviews will be made available to any home owner whose tender you bid on. Once the contractor has been fully admitted into the system, they will receive new tenders on a weekly basis, as home owners request bids.</p>
                            <p>A home owner will contact Easy Job Quote.com to help them find a contractor. Our Project Developer will visit the home, and prepare the job for tender. Measurements, digital pictures, and a detailed description will be recorded in the tender. The home owner will also have the opportunity to request any conditions they wish, such as specific dates or warranty for the work completed. These tenders will be published, and made available every Monday morning for you to view.</p>
                            <p>Member contractors will have seven days to view the tenders and submit a bid. The identity of the home owner will not be viewed by the contractors. The contractors enter their bid in our system; you must enter a bid price, and either agree or disagree with any of the requested conditions. You may amend each condition you agree to. You will also be able to include any conditions you want in the contract in your bid.</p>
                            <p>Your bids will not be negotiable with the home owner during the bidding process, so it is important to place your best bid into the system. Once the tender is closed, the bids will be viewable by the home owner. The bids will be shown in the order they arrived, so the first bidder will be placed first, and the last bidder will be placed last. The home owner will be able to review your bids, as well as the reviews that past home owners have given you. Home owners will not be able to view the identity of the bidding contractor, or the identity of the home owners who wrote the reviews.</p>
                            <p>Once a home owner makes a selection, the winning contractor will be notified, and contact information will be exchanged. At this time the home owner has agreed to your proposal and you have a business agreement regarding the scope of service, price, and the conditions.</p>
                            <p>The marketplace has been designed to make it easier for contractors to win business, and make the construction phase smooth and easy. By delivering multiple jobs to bid on straight to your email inbox, we allow you to spend less time looking for work, and more time working. We allow you to develop a strong reputation simply by doing a good job on the work you win. We save you money and give you a fair marketplace to compete in.</p>
                            <h1>Bidding System Procedure</h1>
                            <p>Tenders are published every Monday at about 4:00 am PST. You will receive an email notification if there are tenders you are qualified to bid on. Tenders may be open to multiple categories. You can login to view these tenders, which will be open to bidding for seven days.</p>
                            <p>You will initially see the tenders listed on a single page. Click on any tender to open it and view the details. You will see the description, measurements, and pictures of the job site. You may submit a bid on any tender presented to you.</p>
                            <p><strong>Price:</strong> You must enter a bid price. This is the price that you will accept to perform the work described in the tender. You will be expected to pay a commission based on this price. Once work begins, you may need to deal with changes to the scope of your job, but this will be outside of the price you place on the bid.</p>
                            <p><strong>Home Owner Conditions:</strong> Home owners may request any conditions they like. You are not required to meet each one, but you are required to indicate which ones you accept, and which ones you do not. You do this by ticking the “agree” box or the “do not agree” box next to each condition. You may not enter a bid unless there is a check beside each condition. You may enter a comment next to each condition if you like.</p>
                            <p><strong>Contractor Comments:</strong> You may enter additional information in the comments section on your bid. This is your chance to add value or detail to your bid, without disclosing your company information.</p>
                            <p>Tenders close on the following Sunday, at 11:59 pm PST. All submitted bids are considered final, and must be honored should a home owner select you to perform the work.</p>
                            <p>If problems occur that prevent you from completing the work, then your first course of action should be to attempt to negotiate with the home owner to adjust the conditions of the contract so that the work can be performed.</p>
                            <p>Your bid represents your contract proposal, and when a home owners selects the bid they have agreed to your contract terms and price, and you are expected to honor your side of the contract. Happy home owners give great reviews, which will make you more competitive in the marketplace.</p>
                            <h1>Dealing with Home Owners</h1>
                            <p>Any bid you submit is a contract proposal. Should a home owner select your bid, then you have entered into a contract with the home owner. As with any contract, both parties are expected to perform to the conditions stated in the contract.</p>
                            <p>We expect every contractor to act in good faith, to make bids that they can honor, and to deal with home owners in a straight forward manner. If problems occur, it is always best to be transparent and honest with home owners. Home owners appreciate that contractors are not always able to foresee issues, and will prefer it if you involve them in finding a solution to any problems that occur.</p>
                            <h1>Commission Payments</h1>
                            <p>Contractors who win work will pay a commission of 9% to Easy Job Quote. Contractors have 30 days from selection date to make this payment, so as to allow the contractor to begin the work and make cash management easier. Contractors who are late may be suspended from the marketplace. Repayment of late commission fees will be necessary for active membership privileges to be returned. Re-occurring late payments may result in termination of membership without refund or rebate.</p>
                </div>
            </div>
            
          