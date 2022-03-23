                    <div class="content-box tab-pane fade<?php echo (empty($activeTab) || $activeTab == 'evaluation' ? ' in active' : ''); ?>" id="description" role="tabpanel">   
                        <div class="col-md-12">
                                <div id="review_info">
                                    <?php
                                    if (!empty($tenderInfo['Review'])):
                                    ?>
                                    <h4><?php echo __("Tender Review:"); ?></h4>
                                    <table class="table table-striped" id="ratings">
                                        <thead>
                                            <tr>
                                                <th class="col-lg-3">&nbsp;</th>
                                                <th class="col-lg-2 text-center"><?php echo  __('Rating'); ?></th>
                                                <th class="col-lg-6"><?php echo __('Comment'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo __('Punctuality'); ?></td>
                                                <td class="text-center"><?php echo $tenderInfo['Review']['punctuality_rating']; ?></td>
                                                <td><?php echo $tenderInfo['Review']['punctuality_comment']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Behaviour'); ?></td>
                                                <td class="text-center"><?php echo $tenderInfo['Review']['behaviour_rating']; ?></td>
                                                <td><?php echo $tenderInfo['Review']['behaviour_comment']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Cleanliness'); ?></td>
                                                <td class="text-center"><?php echo $tenderInfo['Review']['cleanliness_rating']; ?></td>
                                                <td><?php echo $tenderInfo['Review']['cleanliness_comment']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Quality Of Work'); ?></td>
                                                <td class="text-center"><?php echo $tenderInfo['Review']['quality_of_work_rating']; ?></td>
                                                <td><?php echo $tenderInfo['Review']['quality_of_work_comment']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Likelihood To Recommend'); ?></td>
                                                <td class="text-center"><?php echo $tenderInfo['Review']['likelihood_to_recommend_rating']; ?></td>
                                                <td><?php echo $tenderInfo['Review']['likelihood_to_recommend_comment']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <?php else: ?>
                                     <?php echo $this->Element('jobs/update_review'); ?>
                                    <?php endif; ?>
                                </div>
                        </div>                 	
                    </div>