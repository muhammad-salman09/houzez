<?php
/**
 * Template Name: JSON Test
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 16/12/15
 * Time: 3:27 PM
 */
get_header();


//$json = '{"id":"WH-4XW35687TY1255833-2TG709360S901793M","event_version":"1.0","create_time":"2018-11-14T07:25:34.996Z","resource_type":"Agreement","event_type":"BILLING.SUBSCRIPTION.CREATED","summary":"A billing subscription was created","resource":{"agreement_details":{"outstanding_balance":{"value":"0.00"},"num_cycles_remaining":"0","num_cycles_completed":"0","next_billing_date":"2018-11-14T10:00:00Z","final_payment_due_date":"1970-01-01T00:00:00Z","failed_payment_count":"0"},"description":"Recurring agreement Test","links":[{"href":"api.sandbox.paypal.com/v1/payments/billing-agreements/I-PTNC5CEMLCNW","rel":"self","method":"GET"}],"shipping_address":{"recipient_name":"test buyer","line1":"1 Main St","city":"San Jose","state":"CA","postal_code":"95131","country_code":"US"},"id":"I-PTNC5CEMLCNW","state":"Active","payer":{"payment_method":"paypal","status":"verified","payer_info":{"email":"waqasriaz977-buyer@gmail.com","first_name":"test","last_name":"buyer","payer_id":"D6ECAXNLE6CGA","shipping_address":{"recipient_name":"test buyer","line1":"1 Main St","city":"San Jose","state":"CA","postal_code":"95131","country_code":"US"}}},"plan":{"curr_code":"USD","links":[],"payment_definitions":[{"type":"REGULAR","frequency":"Day","frequency_interval":"1","amount":{"value":"15.00"},"cycles":"0","charge_models":[{"type":"TAX","amount":{"value":"2.00"}},{"type":"SHIPPING","amount":{"value":"1.00"}}]}],"merchant_preferences":{"setup_fee":{"value":"0.00"},"auto_bill_amount":"YES","max_fail_attempts":"0"}},"start_date":"2018-11-14T08:00:00Z"},"links":[{"href":"https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-4XW35687TY1255833-2TG709360S901793M","rel":"self","method":"GET"},{"href":"https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-4XW35687TY1255833-2TG709360S901793M/resend","rel":"resend","method":"POST"}]}';

$json = '{"id":"WH-49483479LH073601X-6LY060926K516644K","event_version":"1.0","create_time":"2018-11-19T13:35:03.850Z","resource_type":"sale","event_type":"PAYMENT.SALE.COMPLETED","summary":"Payment completed for $ 1.99 USD","resource":{"id":"3LW4497090769582Y","state":"completed","amount":{"total":"1.99","currency":"USD","details":{}},"payment_mode":"INSTANT_TRANSFER","protection_eligibility":"ELIGIBLE","protection_eligibility_type":"ITEM_NOT_RECEIVED_ELIGIBLE,UNAUTHORIZED_PAYMENT_ELIGIBLE","transaction_fee":{"value":"0.36","currency":"USD"},"billing_agreement_id":"I-LH2H6HGC2TR6","create_time":"2018-11-19T13:34:38Z","update_time":"2018-11-19T13:34:38Z","links":[{"href":"https://api.sandbox.paypal.com/v1/payments/sale/3LW4497090769582Y","rel":"self","method":"GET"},{"href":"https://api.sandbox.paypal.com/v1/payments/sale/3LW4497090769582Y/refund","rel":"refund","method":"POST"}]},"links":[{"href":"https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-49483479LH073601X-6LY060926K516644K","rel":"self","method":"GET"},{"href":"https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-49483479LH073601X-6LY060926K516644K/resend","rel":"resend","method":"POST"}]}';

$webhook_data = json_decode($json);
echo '<pre>';
print_r($webhook_data);

/*echo $webhook_data->resource_type;

$resource_type = $webhook_data->resource_type;
$event_type = $webhook_data->event_type;
$state = $webhook_data->resource->state;
$amount = $webhook_data->resource->amount->total;
$profile_id = $webhook_data->resource->billing_agreement_id;

echo $resource_type.' '.$event_type.' '.$state.' '.$amount.' '.$profile_id;*/

/*$now = time();
echo(date("Y-m-d h:s:i",$now));*/
//echo $now;

/*$u_array = array();
$u_array['my_id'] = '1234';
$u_array['name'] = 'Waqas';

update_user_meta( 1, 'paypal_recurring_test', $u_array );*/
$profile_id = 'I-167RTGAK5ED7';
echo houzez_retrive_user_by_profile($profile_id);

get_footer(); ?>



