<?php
/*
 * This page consists supplier dashboard section
 *  
 */

$GeneralThemeObject = new GeneralTheme();
$MembershipObject = new classMemberShip();
$getMembershipPlans = $GeneralThemeObject->getMembershipPlans();
$userDetails = $GeneralThemeObject->user_details();
$getUserMembershipDetails = $MembershipObject->getUserMembershipDetails($userDetails->data['user_id']);
$getUserPlanFormat = $MembershipObject->getUserPlanFormat($userDetails->data['user_id']);
// echo "<pre>";
// print_r($getUserMembershipDetails);
// echo "</pre>";
$currDate = strtotime(date('Y-m-d'));

$userPreviousPlan = $userDetails->data['selected_plan'];
$previousPlanDetails = $GeneralThemeObject->getMembershipPlanDetails($userPreviousPlan);
?>
<style>
        @media only screen and (min-width: 900px){
         .plan-list{height:472px;}   
        }
        
    </style>
<div class="right desktop-view">
    <div class="membership-plan-list">
        <?php if (is_array($getMembershipPlans) && count($getMembershipPlans) > 0) : ?>
            <div class="row">            
                <?php foreach ($getMembershipPlans as $eachMembershipPlan) : ?>
                    <?php $planDetails = $GeneralThemeObject->getMembershipPlanDetails($eachMembershipPlan->ID); ?>
                    <div class="col-sm-4">
                        <div class="plan-list plan-list-dasboard <?php echo (($userDetails->data['selected_plan'] == $eachMembershipPlan->ID)) ? 'active-plan-list' : ''; ?>">
                            <div class="plane-badge"><img src="<?php echo $planDetails->data['thumbnail']; ?>" alt="" /></div>
                          
                            <div class="plan-title"><?php echo $eachMembershipPlan->post_title; ?></div>
                            <div class="plan-desc"><?php echo $eachMembershipPlan->post_content; ?></div>
                            <div class="plan-deals plan-"><?php _e('No of deals: ', THEME_TEXTDOMAIN); ?><span><?php echo ($planDetails->data['no_of_acceptence'] == 100000) ? __('Unlimited', THEME_TEXTDOMAIN) : $planDetails->data['no_of_acceptence']; ?></span></div>
                            <?php if ($planDetails->data['quarterly_price'] && $planDetails->data['half_yearly_price'] && $planDetails->data['yearly_price']): ?>
                                

                                <!-- Condition for checking plan upgradation -->
                                <?php
                                if(is_array($getUserMembershipDetails) && count($getUserMembershipDetails) > 0 && $eachMembershipPlan->ID != $userDetails->data['selected_plan'] && $currDate <= $userDetails->data['selected_end_date']):
                                        ?>
                                    <div class="plan-price-list"><?php _e('Quarterly Cost: ', THEME_TEXTDOMAIN); ?><br>
                                        <span>
                                            <?php if($planDetails->data['quarterly_price'] > $getUserMembershipDetails[0]->total_price): ?>
                                                (R$ <?php echo number_format($planDetails->data['quarterly_price'], 2); ?> - R$ <?php echo number_format($getUserMembershipDetails[0]->total_price, 2); ?>) = 
                                            <?php echo 'R$ ' . number_format(($planDetails->data['quarterly_price'] - $getUserMembershipDetails[0]->total_price), 2); ?>
                                            <?php else: ?>
                                                <?php echo 'R$ ' . number_format($planDetails->data['quarterly_price'], 2); ?>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <div class="plan-price-list"><?php _e('Half-yearly Cost: ', THEME_TEXTDOMAIN); ?><br>
                                        <span>
                                            <?php if($planDetails->data['half_yearly_price'] > $getUserMembershipDetails[0]->total_price): ?>
                                                (R$ <?php echo number_format($planDetails->data['half_yearly_price'], 2); ?> - R$ <?php echo number_format($getUserMembershipDetails[0]->total_price, 2); ?>) = 
                                            <?php echo 'R$ ' . number_format(($planDetails->data['half_yearly_price'] - $getUserMembershipDetails[0]->total_price), 2); ?>
                                            <?php else: ?>
                                                <?php echo 'R$ ' . number_format($planDetails->data['half_yearly_price'], 2); ?>
                                            <?php endif; ?>
                                            </span>
                                    </div>
                                    <div class="plan-price-list"><?php _e('Annualy Cost: ', THEME_TEXTDOMAIN); ?><br>
                                        <span>
                                            <?php if($planDetails->data['yearly_price'] > $getUserMembershipDetails[0]->total_price): ?>
                                                (R$ <?php echo number_format($planDetails->data['yearly_price'], 2); ?> - R$ <?php echo number_format($getUserMembershipDetails[0]->total_price, 2); ?>) = 
                                        <?php echo 'R$ ' . number_format(($planDetails->data['yearly_price'] - $getUserMembershipDetails[0]->total_price), 2); ?>
                                            <?php else: ?>
                                                <?php echo 'R$ ' . number_format($planDetails->data['yearly_price'], 2); ?>
                                        <?php endif; ?>
                                        </span>
                                    </div>
                                    <div class="expire-date"><?php _e('N.B.- This reduced amount is showing as because you have already bought a plan and we are giving you opportunity to upgrade ypur plan by paying less amount.', THEME_TEXTDOMAIN); ?></div>
                                <?php else: ?>
                                    <div class="plan-price-list"><?php _e('Quarterly Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo 'R$ ' . number_format($planDetails->data['quarterly_price'], 2); ?></span></div>
                                    <div class="plan-price-list"><?php _e('Half-yearly Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo 'R$ ' . number_format($planDetails->data['half_yearly_price'], 2); ?></span></div>
                                    <div class="plan-price-list"><?php _e('Annualy Cost: ', THEME_TEXTDOMAIN); ?><span><?php echo 'R$ ' . number_format($planDetails->data['yearly_price'], 2); ?></span></div>
                                <?php endif; ?>
                                <!-- End of Condition for checking plan upgradation -->

                                <?php if (($userDetails->data['selected_plan'] == $eachMembershipPlan->ID) && ($currDate <= $userDetails->data['selected_end_date'])): ?>    
                                    <div class="plan-purchase"><a href="javascript:void(0);"><?php _e('Ativo', THEME_TEXTDOMAIN); ?></a></div>
                                <?php elseif (($userDetails->data['selected_plan'] == $eachMembershipPlan->ID) && ($currDate > $userDetails->data['selected_end_date'])): ?>
                                    <div class="plan-purchase"><a href="#user_plan_purchase_popup" data-toggle="modal" class="click-purchase" data-plan="<?php echo base64_encode($eachMembershipPlan->ID); ?>"><?php _e('Purchase', THEME_TEXTDOMAIN); ?></a></div>
                                <?php elseif (($userDetails->data['selected_plan'] != $eachMembershipPlan->ID) && ($getUserPlanFormat == FALSE)): ?>
                                <?php elseif (($userDetails->data['selected_plan'] == 171) && ($getUserPlanFormat == TRUE)): ?>
                                    <!--div class="plan-purchase"><a href="#user_plan_purchase_popup" data-toggle="modal" class="click-purchase" data-plan="<?php //echo base64_encode($eachMembershipPlan->ID); ?>"><?php //_e('Purchase', THEME_TEXTDOMAIN); ?></a></div-->
                                <?php else: ?>
                                    <div class="plan-purchase"><a href="#user_plan_purchase_popup" data-toggle="modal" class="click-purchase" data-plan="<?php echo base64_encode($eachMembershipPlan->ID); ?>"><?php _e('Purchase', THEME_TEXTDOMAIN); ?></a></div>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if (($userDetails->data['selected_plan'] == $eachMembershipPlan->ID) && ($currDate <= $userDetails->data['selected_end_date'])): ?>    
                                    <div class="plan-purchase"><a href="javascript:void(0);"><?php _e('Ativo', THEME_TEXTDOMAIN); ?></a></div>
                                <?php elseif (($userDetails->data['selected_plan'] == $eachMembershipPlan->ID) && ($currDate > $userDetails->data['selected_end_date'])): ?>
                                    <div class="plan-purchase"><a href="javascript:void(0);"><?php _e('Free', THEME_TEXTDOMAIN); ?></a></div>
                                <?php elseif (($userDetails->data['selected_plan'] != $eachMembershipPlan->ID) && ($getUserPlanFormat == FALSE)): ?>
                                <?php elseif (($userDetails->data['selected_plan'] != $eachMembershipPlan->ID) && ($getUserPlanFormat == TRUE)): ?>
                                    <!--<div class="plan-purchase"><a href="#user_plan_purchase_popup" data-toggle="modal" class="click-purchase" data-plan="<?php echo base64_encode($eachMembershipPlan->ID); ?>"><?php _e('Purchase', THEME_TEXTDOMAIN); ?></a></div>-->
                                <?php else: ?>
                                    <div class="plan-purchase"><a href="javascript:void(0);"><?php _e('Free', THEME_TEXTDOMAIN); ?></a></div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($userDetails->data['selected_plan'] == $eachMembershipPlan->ID && $planDetails->data['name'] != 'bronze'): ?>
                                <div class="expire-date"><?php _e('Your Plan Will Expire On: ', THEME_TEXTDOMAIN); ?> <?php echo date('d M, Y', $userDetails->data['selected_end_date']); ?></div>
                            <?php elseif ($userDetails->data['selected_plan'] == $eachMembershipPlan->ID && $planDetails->data['name'] == 'bronze'): ?>
                                <div class="expire-date"><?php _e('Lifetime duration plan', THEME_TEXTDOMAIN); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
       
            </div>
        <?php endif; ?>

    </div>
    <div class="section-heading" style="margin-bottom: 10px;">
        <h2><?php _e('Membership Transaction', THEME_TEXTDOMAIN); ?></h2>
    </div>
    <div class="table-responsive dataTable">
        <table class="table cart-table">
            <thead>
            <th><?php _e('Membership ID', THEME_TEXTDOMAIN); ?></th>
            <th><?php _e('Transaction ID', THEME_TEXTDOMAIN); ?></th>
            <th><?php _e('Price', THEME_TEXTDOMAIN); ?></th>
            <th><?php _e('Plan', THEME_TEXTDOMAIN); ?></th>
            <th><?php _e('Duration', THEME_TEXTDOMAIN); ?></th>
            <th><?php _e('Paid On', THEME_TEXTDOMAIN); ?></th>
            <th><?php _e('Next payment', THEME_TEXTDOMAIN); ?></th>
            </thead>
            <tbody>
                <?php if (is_array($getUserMembershipDetails) && count($getUserMembershipDetails) > 0): ?>
                    <?php foreach ($getUserMembershipDetails as $eachMembership): ?>

                        <tr>
                            <td><?php echo $eachMembership->order_id; ?></td>
                            <td><?php echo $eachMembership->transaction_id; ?></td>
                            <td><?php echo 'R$ ' . number_format($eachMembership->total_price, 2); ?></td>
                            <td><?php echo get_the_title($eachMembership->plan_id); ?></td>
                            <td><?php echo date('d M, Y', $eachMembership->plan_start_date) . ' - ' . date('d M, Y', $eachMembership->plan_end_date); ?></td>
                            <td><?php echo date('d M, Y', $eachMembership->payment_date); ?></td>
                            <td><?php echo date('d M, Y', $eachMembership->next_payment_date); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7"><div class="alert alert-danger" style="text-align: left;"><?php _e('No payment history found.', THEME_TEXTDOMAIN); ?></div></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
<?php

