    <section id="home_owner">
        <header id="home_owner_header">
            <h1><?php echo __("Home Owner"); ?></h1>
<?php 
            if (empty($uid)):
?>            
            <button type="button" onclick="window.location.href='<?php 
                    echo Router::url(array('controller'=>'marketplaces', 'action'=>'register_consumer', $EjqMarketplaceId))
                    ?>'">
                    <?php echo __("Home Owner Sign Up"); ?>
                </button>
<?php 
            endif;
?>            
        </header>
        <section id="home_owner_info">
            <figure id="home_owner_contact_us" class="home_info">
                <img src="/img/home-owner-contact-us.jpg">
                <figcaption>
                <h3><?php echo __("Contact Us"); ?></h3>
                <p>Let’s set up a time to meet and review your renovation or project! We take the measurements, digital photos, and draw up the plans. The tender is the next step and will include your material specifications, terms, conditions and preferred construction timeline.</p>
                </figcaption>
            </figure>
            
            <figure id="home_owner_contact_us" class="home_info">
                <img src="/img/home-owner-contractor-bid.jpg">
                <figcaption>
                <h3><?php echo __("Contractors bid"); ?></h3>
                <p>
                    Once the tender is complete we publish it in our marketplace. Contractors are all bidding on the same scope of work so you know that the costs are comparable.
                </p>
                </figcaption>
            </figure>
            
            <figure id="home_owner_contact_us" class="home_info">
                <img src="/img/home-owner-select-your-contractor.jpg">
                <figcaption>
                <h3><?php echo __("Select your Contractor"); ?></h3>
                <p>
                    Review the bids and select your contractor. Our contractors are rated based on past projects so you get insight into their punctuality, cleanliness and quality of work before you decide to work with them!
                </p>
                </figcaption>
            </figure>
            
        </section>
    </section>

    <section id="contractor">
        <header id="contractor_header">
            <h1><?php echo __("Contractor"); ?></h1>
<?php 
            if (empty($uid)):
?>            
            <button type="button" onclick="window.location.href='<?php 
                    echo Router::url(array('controller'=>'marketplaces', 'action'=>'become_a_member', $EjqMarketplaceId))
                    ?>'">
                    <?php echo __("Contractor Sign Up"); ?>
                </button>
<?php 
            endif;
?>            
        </header>
        <section id="contractor_info">
            <figure id="contractor_contact_us" class="home_info">
                <img src="/img/contractor-become-a-member.jpg">
                <figcaption>
                <h3><?php echo __("Become a Member"); ?></h3>
                <p>Let’s set up a time to meet and review your renovation or project! We take the measurements, digital photos, and draw up the plans. The tender is the next step and will include your material specifications, terms, conditions and preferred construction timeline.</p>
                </figcaption>
            </figure>
            
            <figure id="contractor_contact_us" class="home_info">
                <img src="/img/contractor-bid-on-tenders.jpg">
                <figcaption>
                <h3><?php echo __("Bid on Tenders"); ?></h3>
                <p>
                    Once the tender is complete we publish it in our marketplace. Contractors are all bidding on the same scope of work so you know that the costs are comparable.
                </p>
                </figcaption>
            </figure>
            
            <figure id="contractor_contact_us" class="home_info">
                <img src="/img/contractor-get-the-job.jpg">
                <figcaption>
                <h3><?php echo __("Get the Job"); ?></h3>
                <p>
                    Review the bids and select your contractor. Our contractors are rated based on past projects so you get insight into their punctuality, cleanliness and quality of work before you decide to work with them!
                </p>
                </figcaption>
            </figure>
            
        </section>
    </section>
