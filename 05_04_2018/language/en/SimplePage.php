<?php

$lang = array_merge($lang, array (
  'l_service_title' => 'Service options',
  'l_site_options' => 'Website options, service #',
  'l_for_site' => 'for site %s',
  'l_service_name' => '(%s)',
  'l_service_created' => 'Web-site%ssuccessfully created. Service #%d%s, access key is <br><br><span style="font-size: 1.2em;"><b>%s</b></span><br><br>',
  'l_service_created_title' => 'Web-site :SITENAME: has been successfully added.',
  'l_service_created_js' => 'Service :SERVICE_ID:, access key is <br><br><span style="font-size: 1.2em;"><b>:KEY:</b></span><br><br>Enter the Access key in the plugin settings.',
  'l_manuals_title' => 'CleanTalk Anti-Spam Installation Manuals',
  'l_order_by' => 'Order by',
  'l_order_popularity' => 'Popularity',
  'l_order_name' => 'A -> Z',
  'l_setup_key' => 'Enter Access key in the %s settings <br><br><h4>%s</h4>',
  'l_setup_key2' => 'How to install the CleanTalk Anti-Spam Plugin',
  'l_service_delete' => 'Delete service #%d%s%s?',
  'l_service_num' => 'Service #',
  'l_site_protection' => 'Website anti-spam protection',
  'l_new_site' => 'New website',
  'l_new_sites' => 'New websites',
  'l_settings' => 'Settings',
  'l_hostname' => 'Site URL',
  'l_hostnames' => 'Sites URLs',
  'l_hostnames_hint' => 'Allowed division addresses with ",", ";", space and new line. List limit is %d urls.',
  'l_service_name_page' => 'Service name (optional)',
  'l_service_name_example' => 'For example - Blog, Forum, Portal etc.',
  'l_cms' => 'CMS',
  'l_response_lang' => 'Service response language',
  'l_api_response_lang' => 'API response language',
  'l_stop_list_enable' => 'Enable comments test via stop list',
  'l_allow_links_enable' => 'Allow HTTP links in comments',
  'l_send_log_to_email' => 'Send a copy of AntiSpam log to my email.',
  'l_send_log_to_email_hint' => 'For each contact form message marked as Not spam in the <a href="my/show_requests?int=week">AntiSpam log</a> you will have a copy of the record to %s.',
  'l_sms_test_enable' => 'Enable new registrations test via SMS code',
  'l_service_updated' => 'Service updated. Changes concerning anti-spam service take effect within 10 minutes.',
  'l_security_updated' => 'Service updated. Changes concerning security service take effect within 10 minutes.',
  'l_yes_delete' => 'Yes, delete',
  'l_service_deleted' => 'Service successfully deleted.',
  'l_services_overlimit' => 'Switch to more powerful package!<br> <span class="hint_black">Web-sites used %d, limit by current package is %d.</span>',
  'l_move_to_spam_enable' => 'SPAM comment action',
    'l_move_to_spam_folder' => 'Move to Spam folder',
    'l_move_to_spam_hint' => 'All spam comments will be placed to the folder "Spam" in the WordPress Comments section except comments with Stop-Words. Stop-Word comments will be always stored in the "Pending" folder.',
    'l_move_to_trash' => 'Move to Trash',
    'l_move_to_trash_hint' => 'All spam comments will be placed to the folder "Trash" in the WordPress Comments section except comments with Stop-Words. Stop-Word comments will be always stored in the "Pending" folder.',
    'l_ban_comment' => 'Ban comment without move to WordPress backend',
    'l_ban_comment_hint' => 'All spam comments will be deleted permanently without going to the WordPress backend except comments with Stop-Words. Stop-Word comments will be always stored in the "Pending" folder. What comments were blocked and banned can be seen in the Anti-Spam Log.',
    'l_smart_comments_filter' => 'Smart spam comments filter',
    'l_smart_comments_filter_hint' => 'Automatic spam will be placed to the folder "Trash", other spam sent to "Spam" folder in the WordPress Comments section.',
  'l_stop_list' => 'Stop list',
  'l_stop_list_enable_hint' => 'Matched comments from <a href="/my/show_private?service_id=%d&service_type=antispam&add_word=1&record_type=8">Stop list</a> will be sent for manual moderation. This option can be used for filtration of rude or offensive comments.',
  'l_allow_links_enable_hint' => 'Comments with links to not <a href="/blacklists" target="_blank">blacklisted web-sites</a> will be allowed for publication. If the option is disabled, all comments with links will be sent to moderation.',
  'l_new_website_hint' => '',
  'l_oftop_enable' => 'Off-topic detection',
  'l_offtop_enable_hint' => 'Offtops will be sent for manual check.',
  'l_choose_service' => 'Choose website',
  'l_api_language_hint' => 'Language is used to give a website visitor hints how to resolve anti-spam protection issue if any.',
  'l_subscribes_title' => 'Email subscription',
  'l_subscribe' => 'Subscribe',
  'l_subscribes_changed' => 'Subscription has changed!',
  'l_go_postman' => 'Go to email subscription!',
  'l_token_auth_disabled' => 'Password less authorisation has been disabled in account settings. Please login with password.',
  'l_token_auth_disabled_by_sites' => 'Passwordless authorisation has been disabled because of the service policy. Please log in using password.<br /><br />To allow passwordless authorization, please, set option \'Allow login to Dashboard with secret token\' in the <a href="/my/profile">account settings</a>.',
  'l_login_page' => 'Log in page',
  'l_afl_title' => 'Affiliate program',
  'l_accounts_list' => 'Your referrals',
  'l_pays_list' => 'Pays by your referrals',
  'l_new_account' => 'Create a new referral',
  'l_connected' => 'Registered',
  'l_last_seen' => 'Last seen',
  'l_commission' => 'Commission',
  'l_sum' => 'Sum',
  'l_no_ppays' => 'No pays by affiliate program',
  'l_no_paccounts' => 'No sub accounts',
  'l_com_info' => 'Rates - %d%% from the first payment%s%s',
	'l_com_info_accounts' => ' (first %d paid users by affiliate program)',
	'l_com_info_level_2' => ', %d%% from first payment (more then %d paid users by affiliate program)',
    'l_com_info_sub' => ', %d%% from subsequent payments.',
  'l_transfer_to_ct' => 'CleanTalk balance',
  'l_pay_for_ct' => 'Pay for CleanTalk service',
  'l_to_paypal' => 'To PayPal',
  'l_withdraw_info' => 'Transfer to PayPal from $50 available on the balance. Please send a request to <a href="/my/support">support</a>.',
  'l_affiliate_links' => 'Affiliate links',
  'l_html_code' => 'HTML code',
  'l_example' => 'Example',
  'l_confirm_ct' => 'Transfer money to CleanTalk account?',
  'l_agree' => 'Yes, transfer',
  'l_account' => 'Account',
  'l_transfer' => 'Transfer',
  'l_transfer_to' => 'Transfer to',
  'l_transfer_to_ym' => 'Yandex.Money',
  'l_transfer_to_pp' => 'PayPal',
  'l_agree_transfer' => 'Transfer $%.2f to <b>%s %s</b>?',
  'l_agree_transfer_ct' => 'Transfer to balance <b>$%.2f</b>?',
  'l_transfer_complete' => '$%.2f successfully transfered to %s %s!',
  'l_transfer_complete_ct' => '$%.2f transfered to CleanTalk balance, amount of balance is $%.2f.',
  'l_balance_hint' => '<a href="/my/bill/recharge">Subscribe for a new period</a>',
  'l_pay_success_title' => 'Payment received',
  'l_paid_till_partners' => 'Paid till',
  'l_bonuses_page_title' => 'Could you help? We\'ll be very grateful if you invite your friends.',
  'l_bonuses_page_trial_title' => '',
  'l_bonuses_months_premium' => 'Don\'t miss up to 21 free months that available for premium accounts.',
  'l_take_free_months' => 'Take free months',
  'l_bonus_activate_notice' => 'Bonus activated %s. %d months added. Thank you!',
  'l_bonus_early_pay_title' => 'Pay early (+%d months)',
  'l_bonus_early_pay_desc' => 'Pay before the end of the trial and get %d free months added to your license.',
  'l_bonus_review_title' => 'Review CleanTalk (+%d months)',
  'l_bonus_review_desc' => 'Review and give us 5 stars to get %d free months added to your license.',
  'l_bonus_friend_title' => '',
  'l_bonus_friend_desc' => 'To help the project - give your friends +%d months free. When a friend signs up and pays using your link we\'ll give both of you free months.',
  'l_bonus_twitter_desc' => 'Tweet us and get a free month! Bonus will be automatically applied to your license within 10-15 minutes after tweet.',
  'l_bonus_facebook_desc' => 'Like our page on FB to get a free month! The Bonus will be automatically applied to your license after you like our page. Follow the link and click Like.',
  'l_bonus_linkedin_desc' => 'Share our page on LinkedIn to get a free month!! The bonus will be automatically applied to your license after you shared our page. Follow the link and click Share.',
  'l_bonus_no_bonus_title' => 'Help others learn about us!',
  'l_bonus_review_external_desc' => 'Post a review about your experience with CleanTalk on your website (board), send us a link to the review. We will happy to assign extra free months to your license.',
  'l_send_the_link' => 'Send the link',
  'l_bonus_title_free_months' => '<span class="s24pt_text">+%d</span> Free Months',
  'l_free_months_activate_notice' => '%d free months activated',
  'l_friend_link' => 'Here is your unique link to the CleanTalk home page',
  'l_friend_buttons' => 'Use buttons or link to share with your friends',
  'l_share_title' => 'Join CleanTalk. Anti spam for websites - simple, reliable, professional. +2 free months bonus for you and me.',
  'l_share_title_twitter' => 'How to stop spam bots? Retweet! ti#%s',
  'l_share_title_twitter_hoster' => '%s would be great to have as your feature for #hosting customers! ti#%s',

  'l_social_review_hint' => 'Or post review on Facebook (or any social network) and <a href="mailto:%s">send us</a> the URL to review.',
  'l_share_desc' => '',
  'l_bonus_friend_stat' => 'Signed friends %d, paid friends %d.',
  'l_fill_account' => 'Specify an account number, please!',
  'l_unknown_transfer' => 'Unknown request!',
  'l_empty_balance' => 'Insufficient funds!',
  'l_transfer_error' => 'Payment error:',
  'l_balance_negative' => 'Insufficient funds in our account. We need 1-2 business days to transfer money manually to your account. Sorry for the inconvenience!',
  'l_need_auth_title' => 'Need auth at Control panel.',
  'l_auth_cp' => 'Please login to Control panel.',
  'l_show_spambots_check_hint' => 'Redirecting to <a href="%s">Bulk spambots search</a> in %d seconds.',

  'l_words_stop_list_notice' => 'Please purchase extra package to filter comments with the stop list.<br />%s',
  'l_server_response_addon_notice' => 'Please purchase extra package to show a message to forbidden visitors.<br />%s',
  'l_grants_addon_notice' => 'Please purchase extra package to grant a web-sites to other accounts.<br />%s',

  'l_server_response_title' => 'Message for forbidden visitors',

  'l_server_response_notice' => 'If a visitor gets a forbidden message, the plugin will show the message from the field above. Message is limited with %d characters (include HTML tags). Allowed HTML tags: &lt;a&gt;, &lt;p&gt;, &lt;br&gt; and &lt;br /&gt;. For example: "Your registration has been denied, please contact the site administrator at %s."',
  'l_log_restriction' => "Don't save approved requests",
  'l_log_restriction_hint' => 'By choosing this option all emails, nicknames and messages will be deleted from approved registrations, comments, orders and contact messages.',

  'l_compensation_label' => 'Compensation',
  'l_review_external_label' => 'Review on a website',
  'l_friend_label' => 'Friend signup',
  'l_early_pay_label' => 'Early pay',
  'l_review_label' => 'Review',
  'l_twitter_label' => 'Tweet',
  'l_other_label' => 'Other',
  'l_facebook_label' => 'Facebook post',
  'l_linkedin_label' => 'LinkedIn post',
  'l_activated_bonuses_title' => 'Activated bonuses',
  'l_free_months_bonus_table' => 'Free months',
  'l_valid_till' => 'License valid till',
  'l_bonus_name' => 'Bonus name',
  'l_activated' => 'Activated',
  'l_no_free_bonuses' => 'No bonuses available.',
  'l_ufbtext' => 'If you see filtration mistake in your log mark it as Spam/Not spam
                        and we will add +1 day for every IP/email. Be cautious - IP/email will be added
                        to your Black & White lists and will be always blocked.',
  'l_ufbdays' => 'Your <span class="fs30px">%s</span> bonus days from feedback.',
  'l_ufbdays_hintrough' => 'After achieving %s days +%s month bonus will be 
                      applied to your.',
  'l_save' => 'Save',
  'l_grants_title' => 'Grants',
  'l_grants_site' => 'Site',
  'l_grants_date' => 'Grant date',
  'l_grants_type' => 'Grant type',
  'l_grants_account' => 'Grant account',
  'l_grants_edit' => 'Edit',
  'l_grants_delete' => 'Delete',
  'l_grants_new' => 'New grant',
  'l_grants_service' => 'Service',
  'l_grants_read' => 'Read',
  'l_grants_readwrite' => 'Read & Write',
  'l_grants_account' => 'Account',
  'l_fill_message' => 'Please choose site and fill Account field.',
  'l_wrong_email' => 'Wrong email format.',
  'l_wrong_user' => "User with given email doesn't exist.",
  'l_grants_success' => 'Grant saved. To check it please go to Dashboard under <b>%s</b> account.',
  'l_cant_save' => 'Issue with grant saving - possibly such grant already exists.',
  'l_grants_read_write' => 'Read & Write',
  'l_grants_read' => 'Read',
  'l_grants_edit_tpl' => 'Edit grant',
  'l_grants_delete_success' => 'Grant successfully deleted.',
  'l_cant_delete' => 'There is an issue with grant deleting.',
  'l_are_you_sure' => 'Are you sure?',
  'l_write_off' => 'Turn write off',
  'l_grants_writeoff_success' => 'Write successfully turned off.',
  'l_grants_writeoff_fail' => 'There is an issue with turning write off.',
  'l_grants_description' => 'Use this feature to give other CleanTalk users access to your sites (logs, management and other).',
  'l_granted_services' => 'Delegated sites',
  'l_grants_actions' => 'Actions',
  'l_grants_account_placeholder' => 'Email of the target account',
  'l_security_breach_grant' => 'Possible security breach! Please contact support.',
    'l_wp_attention' => '<b>ATTENTION WordPress users!</b> To close the payment banner in WordPress back-end please save plugin settings (WordPress console -> Settings -> CleanTalk) or wait up to 30 minutes the banner will be closed by the plugin automatically.',
  'l_choose_transfer_type' => 'Choose transfer type',
    'l_bonuses_alert' => '<strong>Attention!</strong> The bonuses are available to use only on annual packages.',
    'l_spambots_check_redirect' => 'You will be automatically redirected to the "Bulk spam bots search" page in the black list. If after 10 seconds this ha not happened, click <a href="/spambots-check">this link</a>.',
    'l_goto_dashboard' => 'Go to API Dashboard',

    'l_apply_for_all' => 'Apply for all services',
    'l_apply_for_all_hint' => 'Specified settings (except URL and Site name) will be applied to all sites in your account.',
    'l_apply_for_all_confirm' => 'Are you sure you want to apply settings for all sites?'
));
