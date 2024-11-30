<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the LDS as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.ldsengineers.com/ >.

/**
 * This file replaces the legacy STATEMENTS section in db/install.xml,
 * lib.php/modulename_install() post installation hook and partially defaults.php
 *
 * @package   local_ecommerce
 * @auther   Sushil/Saroj
 * @copyright Lds
 * @license   https://www.ldsengineers.com/  
 */

defined('MOODLE_INTERNAL') || die();

/**
 * This is called at the beginning of the uninstallation process to give the module
 * a chance to clean-up its hacks, bits etc. where possible.
 *
 * @return bool true if success
 */
function local_ecommerce_uninstall() {
    global $CFG, $DB;
      $dbman = $DB->get_manager();
      $categorytypetable = new xmldb_table('categorytype');
      $producttypetable = new xmldb_table('producttype');
      $carttable = new xmldb_table('cart');
      $cartitemtable = new xmldb_table('cartitem');
      $producttable = new xmldb_table('product');
      $promocodetable = new xmldb_table('promocode');
      $payment_history_details_table = new xmldb_table('payment_hst_details');
      if($dbman->table_exists($payment_history_details_table)){
      	$dbman->drop_table($payment_history_details_table, $continue=true, $feedback=true);
      }
      if($dbman->table_exists($promocodetable)){
      	$dbman->drop_table($promocodetable, $continue=true, $feedback=true);
      }
      if($dbman->table_exists($producttable)){
      	$dbman->drop_table($producttable, $continue=true, $feedback=true);
      }
      if($dbman->table_exists($cartitemtable)){
      	$dbman->drop_table($cartitemtable, $continue=true, $feedback=true);
      }
      if($dbman->table_exists($carttable)){
      	$dbman->drop_table($carttable, $continue=true, $feedback=true);
      }
      if($dbman->table_exists($producttypetable)){
      	$dbman->drop_table($producttypetable, $continue=true, $feedback=true);
      }
      if($dbman->table_exists($categorytypetable)){
      	$dbman->drop_table($categorytypetable, $continue=true, $feedback=true);
      }
    return true;

}
