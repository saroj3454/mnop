<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * My Moodle -- a user's personal dashboard
 *
 * - each user can currently have their own page (cloned from system and then customised)
 * - only the user can see their own dashboard
 * - users can add any blocks they want
 * - the administrators can define a default site dashboard for users who have
 *   not created their own dashboard
 *
 * This script implements the user's view of the dashboard, and allows editing
 * of the dashboard.
 *
 * @package    moodlecore
 * @subpackage my
 * @copyright  2010 Remote-Learner.net
 * @author     Hubert Chathi <hubert@remote-learner.net>
 * @author     Olav Jordan <olav.jordan@remote-learner.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use jenn0pal\Paymongo\Paymongo;
use local_portal\util\secured\UnsafeCrypto;
require_once('../../../config.php');
require_once($CFG->dirroot . '/my/lib.php');
require_once($CFG->dirroot."/local/ecommerce/classes/models/cartModel.php");
redirect_if_major_upgrade_required();
require_login();
 $Pass = UnsafeCrypto::AUTH_KEY;
$id = UnsafeCrypto::decrypt(required_param("id", PARAM_RAW),$Pass,true);
$recieptdata=$DB->get_record_sql("SELECT pp.*,d.name as departmentname from {payment_paymongo} as pp LEFT JOIN {department} as d on pp.department_id=d.id where pp.id='".$id."'");

$cart = new cartModel();
$cartdata = $cart->getcartdetails($recieptdata->cartid);
if(empty($cartdata)){
   redirect($CFG->wwwroot.'/my', 'Invalid Reciept', null, \core\output\notification::NOTIFY_ERROR);
}
if(!($cartdata->userid == $USER->id || is_siteadmin())){
   redirect($CFG->wwwroot.'/my', 'Invalid Access', null, \core\output\notification::NOTIFY_ERROR);
}
$PAGE->requires->jquery();
$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/local/ecommerce/js/reciept.js'));


$pagetitle = "Reciept";
$url = '/local/ecommerce/reciept/index.php';
$PAGE->set_url($url, $params);
$PAGE->set_pagelayout('base');
$PAGE->set_subpage($currentpage->id);
$PAGE->set_title($pagetitle);
$PAGE->set_heading($header);
echo $OUTPUT->header();
$cartdata->billingdate = date("d F Y", $recieptdata->transaction_date);
$vat = 0;
$subtotal = 0;  
$vatpercent = 0;
$shipping = 0;
$discount = 0;
$havediscount = false;
$havevat = true;
foreach ($cartdata->allitems as $key => $item) {
    $subtotal+= $item->itemfinalprice;
$item->itemfinalprice=number_format($item->itemfinalprice, 2, ".", ",");
 if($item->itemfinalprice > 0){$discount = $item->discount;}  

}
$vat = ($subtotal * $vatpercent/100);
$shipping = ($subtotal>0?$shipping:0);
$total = ($subtotal + $vat + $shipping) - $discount;



$cartdata->billingno=$recieptdata->billing_no_prefix."".$recieptdata->id;
$cartdata->departmentname=$recieptdata->departmentname;
$cartdata->chapters=$recieptdata->team;
$cartdata->address_1=$recieptdata->address_1;
$cartdata->address_2=$recieptdata->address_2;
$cartdata->phonenumber=$recieptdata->phonenumber;
 switch ($recieptdata->payment_method) {
                      case 1:
                        $method="Card";
                        break;
                      case 2:
                        $method="GCash";
                        break;
                      case 3:
                        $method="Manual";
                        break;
                      default:
                        $method="Not Choose payment method";
     }
$cartdata->method=$method;  
$cartdata->sitelogo = $CFG->wwwroot."/local/ecommerce/assests/images/elearnlogo.png";

$cartdata->vat = number_format($vat, 2, ".", ",");
$cartdata->subtotal = number_format($subtotal, 2, ".", ",");
$cartdata->total = number_format($total, 2, ".", ",");
$cartdata->discount = number_format($discount, 2, ".", ",");
$cartdata->havediscount = number_format($havediscount, 2, ".", ",");
$cartdata->havevat = number_format($havevat, 2, ".", ",");

$cartdata->shipping = number_format($shipping, 2, ".", ",");
$cartdata->vatpercent = number_format($vatpercent, 2, ".", ",");

echo $OUTPUT->render_from_template('local_ecommerce/billing-statement', $cartdata);
echo $OUTPUT->footer();
