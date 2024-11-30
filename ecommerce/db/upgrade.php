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



defined('MOODLE_INTERNAL') || die();

function xmldb_local_ecommerce_upgrade($oldversion) {
      global $CFG, $DB;
    $dbman = $DB->get_manager();

    $categorytypetable = new xmldb_table('categorytype');
    $producttypetable = new xmldb_table('producttype');
    $carttable = new xmldb_table('cart');
    $cartitemtable = new xmldb_table('cartitem');
    $producttable = new xmldb_table('product');
    $promocodetable = new xmldb_table('promocode');
    $payment_history_details_table = new xmldb_table('payment_hst_details');
    $pay_mongo_table= new xmldb_table('payment_paymongo');

    if($oldversion<2022061606){


		if(!$dbman->table_exists($payment_history_details_table)){
		$payment_history_details_table->add_field('id', XMLDB_TYPE_INTEGER, '18', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$payment_history_details_table->add_field('amount', XMLDB_TYPE_NUMBER,'5,2',null,null, null,null,'id');
		$payment_history_details_table->add_field('payment_method', XMLDB_TYPE_CHAR,'255',null,null, null,null,'amount');
		$payment_history_details_table->add_field('userid', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'payment_method');
		$payment_history_details_table->add_field('status', XMLDB_TYPE_INTEGER,'1',null, XMLDB_NOTNULL, null, '0', 'userid');
		$payment_history_details_table->add_field('date_of_transaction',XMLDB_TYPE_INTEGER,'18',null,null, null,null,'status');
		$payment_history_details_table->add_field('createddate',XMLDB_TYPE_INTEGER,'18',null,null, null,null,'date_of_transaction');
		$payment_history_details_table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $dbman->create_table($payment_history_details_table);
		}else{

			

			$field = new xmldb_field('amount', XMLDB_TYPE_NUMBER,'5,2',null,null, null,null,'id'); 
		if (!$dbman->field_exists($payment_history_details_table, $field)) {
			  $dbman->add_field($payment_history_details_table, $field);
		}
		$field = new xmldb_field('payment_method', XMLDB_TYPE_CHAR,'255',null,null, null,null,'amount'); 
		if (!$dbman->field_exists($payment_history_details_table, $field)) {
			  $dbman->add_field($payment_history_details_table, $field);
		}

        $field = new xmldb_field('userid', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'payment_method'); 
		if (!$dbman->field_exists($payment_history_details_table, $field)) {
			  $dbman->add_field($payment_history_details_table, $field);
		}

		$field = new xmldb_field('status', XMLDB_TYPE_INTEGER,'1',null, XMLDB_NOTNULL, null, '0', 'userid'); 
		if (!$dbman->field_exists($payment_history_details_table, $field)) {
			  $dbman->add_field($payment_history_details_table, $field);
		}

		$field = new xmldb_field('date_of_transaction',XMLDB_TYPE_INTEGER,'18',null,null, null,null,'status'); 
		if (!$dbman->field_exists($payment_history_details_table, $field)) {
			  $dbman->add_field($payment_history_details_table, $field);
		}
		$field = new xmldb_field('createddate',XMLDB_TYPE_INTEGER,'18',null,null, null,null,'date_of_transaction'); 
		if (!$dbman->field_exists($payment_history_details_table, $field)) {
			  $dbman->add_field($payment_history_details_table, $field);
		}

		}


      if(!$dbman->table_exists($promocodetable)){
      		$promocodetable->add_field('id', XMLDB_TYPE_INTEGER, '18', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
      	 $promocodetable->add_field('promoid', XMLDB_TYPE_CHAR,'255',null,XMLDB_NOTNULL, null,null,'id');
      	 $promocodetable->add_field('type', XMLDB_TYPE_CHAR,'255',null,XMLDB_NOTNULL, null,null,'promoid');
      	 $promocodetable->add_field('discount', XMLDB_TYPE_NUMBER,'5,0',null,null, null,null,'type');
      	 $promocodetable->add_field('startdate',XMLDB_TYPE_INTEGER,'18',null,null, null,null,'discount');
      	 $promocodetable->add_field('enddate',XMLDB_TYPE_INTEGER,'18',null,null, null,null,'startdate');
      	 $promocodetable->add_field('status',XMLDB_TYPE_INTEGER,'9',null,null, null,null,'enddate');
      	 $promocodetable->add_field('createdby', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'status');
	      $promocodetable->add_field('createddate', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'createdby');
	      $promocodetable->add_field('modifiedby', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'createddate');
	      $promocodetable->add_field('modifieddate', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'modifiedby');
	     $promocodetable->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
	     $dbman->create_table($promocodetable);

      }else{
       $field = new xmldb_field('promoid', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'id'); 
		if (!$dbman->field_exists($promocodetable, $field)) {
			  $dbman->add_field($promocodetable, $field);
		}
		       $field = new xmldb_field('type', XMLDB_TYPE_CHAR,'255',null,XMLDB_NOTNULL, null,null,'promoid'); 
		if (!$dbman->field_exists($promocodetable, $field)) {
			  $dbman->add_field($promocodetable, $field);
		}
		       $field = new xmldb_field('discount', XMLDB_TYPE_NUMBER,'5,0',null,null, null,null,'type'); 
		if (!$dbman->field_exists($promocodetable, $field)) {
			  $dbman->add_field($promocodetable, $field);
		}


		       $field = new xmldb_field('startdate',XMLDB_TYPE_INTEGER,'18',null,null, null,null,'discount'); 
		if (!$dbman->field_exists($promocodetable, $field)) {
			  $dbman->add_field($promocodetable, $field);
		}


		       $field = new xmldb_field('enddate',XMLDB_TYPE_INTEGER,'18',null,null, null,null,'startdate'); 
		if (!$dbman->field_exists($promocodetable, $field)) {
			  $dbman->add_field($promocodetable, $field);
		}


		       $field = new xmldb_field('status',XMLDB_TYPE_INTEGER,'9',null,null, null,null,'enddate'); 
		if (!$dbman->field_exists($promocodetable, $field)) {
			  $dbman->add_field($promocodetable, $field);
		}



		       $field = new xmldb_field('createdby', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'status'); 
		if (!$dbman->field_exists($promocodetable, $field)) {
			  $dbman->add_field($promocodetable, $field);
		}
		       $field = new xmldb_field('createddate', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'createdby'); 
		if (!$dbman->field_exists($promocodetable, $field)) {
			  $dbman->add_field($promocodetable, $field);
		}
		       $field = new xmldb_field('modifiedby', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'createddate'); 
		if (!$dbman->field_exists($promocodetable, $field)) {
			  $dbman->add_field($promocodetable, $field);
		}

		   $field = new xmldb_field('modifiedby', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'createddate'); 
		if (!$dbman->field_exists($promocodetable, $field)) {
			  $dbman->add_field($promocodetable, $field);
		}



      }




      if(!$dbman->table_exists($cartitemtable)){
      	 $cartitemtable->add_field('id', XMLDB_TYPE_INTEGER, '18', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
      	 $cartitemtable->add_field('cartid', XMLDB_TYPE_INTEGER,'18',null,XMLDB_NOTNULL, null,null,'id');
      	 $cartitemtable->add_field('producttype', XMLDB_TYPE_INTEGER,'4',null,XMLDB_NOTNULL, null,null,'cartid');
      	 $cartitemtable->add_field('itemid', XMLDB_TYPE_INTEGER,'18',null,XMLDB_NOTNULL, null,null,'producttype');
      	 $cartitemtable->add_field('quantity', XMLDB_TYPE_INTEGER,'9',null,XMLDB_NOTNULL, null,'1','itemid');
      	 $cartitemtable->add_field('rating', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'quantity');
      	 $cartitemtable->add_field('appliedpromo', XMLDB_TYPE_INTEGER,'9',null,null, null,null,'rating');
      	 $cartitemtable->add_field('appliedpromodata',XMLDB_TYPE_TEXT,null,null,null, null,null,'appliedpromo');
      	 $cartitemtable->add_field('addedby',XMLDB_TYPE_INTEGER,'18',null,null, null,null,'appliedpromodata');
      	 $cartitemtable->add_field('addeddate',XMLDB_TYPE_INTEGER,'18',null,null, null,null,'addedby');
      	 $cartitemtable->add_field('modifieddate',XMLDB_TYPE_INTEGER,'18',null,null, null,null,'addeddate');
      	 $cartitemtable->add_field('deleted',XMLDB_TYPE_INTEGER,'4',null,XMLDB_NOTNULL, null,'0','modifieddate');
	     $cartitemtable->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
	     $dbman->create_table($cartitemtable);
      }else{
      	$field = new xmldb_field('cartid', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'id'); 
		if (!$dbman->field_exists($cartitemtable, $field)) {
			  $dbman->add_field($cartitemtable, $field);
		}
		$field = new xmldb_field('producttype', XMLDB_TYPE_INTEGER,'4',null,XMLDB_NOTNULL, null,null,'cartid'); 
		if (!$dbman->field_exists($cartitemtable, $field)) {
			  $dbman->add_field($cartitemtable, $field);
		}
		$field = new xmldb_field('itemid', XMLDB_TYPE_INTEGER,'18',null,XMLDB_NOTNULL, null,null,'producttype'); 
		if (!$dbman->field_exists($cartitemtable, $field)) {
			  $dbman->add_field($cartitemtable, $field);
		}
		$field = new xmldb_field('quantity', XMLDB_TYPE_INTEGER,'9',null,XMLDB_NOTNULL, null,'1','itemid'); 
		if (!$dbman->field_exists($cartitemtable, $field)) {
			  $dbman->add_field($cartitemtable, $field);
		}



		$field = new xmldb_field('appliedpromo', XMLDB_TYPE_INTEGER,'9',null,null, null,null,'rating'); 
		if (!$dbman->field_exists($cartitemtable, $field)) {
			  $dbman->add_field($cartitemtable, $field);
		}

		$field = new xmldb_field('appliedpromodata',XMLDB_TYPE_TEXT,null,null,null, null,null,'appliedpromo'); 
		if (!$dbman->field_exists($cartitemtable, $field)) {
			  $dbman->add_field($cartitemtable, $field);
		}


		$field = new xmldb_field('addedby',XMLDB_TYPE_INTEGER,'18',null,null, null,null,'appliedpromodata'); 
		if (!$dbman->field_exists($cartitemtable, $field)) {
			  $dbman->add_field($cartitemtable, $field);
		}

		$field = new xmldb_field('addeddate',XMLDB_TYPE_INTEGER,'18',null,null, null,null,'addedby'); 
		if (!$dbman->field_exists($cartitemtable, $field)) {
			  $dbman->add_field($cartitemtable, $field);
		}

		$field = new xmldb_field('modifieddate',XMLDB_TYPE_INTEGER,'18',null,null, null,null,'addeddate'); 
		if (!$dbman->field_exists($cartitemtable, $field)) {
			  $dbman->add_field($cartitemtable, $field);
		}

		$field = new xmldb_field('deleted',XMLDB_TYPE_INTEGER,'4',null,XMLDB_NOTNULL, null,'0','modifieddate'); 
		if (!$dbman->field_exists($cartitemtable, $field)) {
			  $dbman->add_field($cartitemtable, $field);
		}

      }








      if(!$dbman->table_exists($carttable)){
      	 $carttable->add_field('id', XMLDB_TYPE_INTEGER, '18', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
      	 $carttable->add_field('userid', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'id');
      	 $carttable->add_field('status', XMLDB_TYPE_INTEGER,'1',null, XMLDB_NOTNULL, null, '0', 'userid');
      	 $carttable->add_field('createdby', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'status');
	     $carttable->add_field('modifieddate', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'createdby');
	     $carttable->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
	     $dbman->create_table($carttable);
      }else{
      	$field = new xmldb_field('userid', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'id'); 
		if (!$dbman->field_exists($carttable, $field)) {
			  $dbman->add_field($carttable, $field);
		}
		$field = new xmldb_field('status', XMLDB_TYPE_INTEGER,'1',null, XMLDB_NOTNULL, null, '0', 'userid'); 
		if (!$dbman->field_exists($carttable, $field)) {
			  $dbman->add_field($carttable, $field);
		}
		$field = new xmldb_field('createdby', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'status'); 
		if (!$dbman->field_exists($carttable, $field)) {
			  $dbman->add_field($carttable, $field);
		}
		$field = new xmldb_field('modifieddate', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'createdby'); 
		if (!$dbman->field_exists($carttable, $field)) {
			  $dbman->add_field($carttable, $field);
		}
      }





      if(!$dbman->table_exists($categorytypetable)){
      	 $categorytypetable->add_field('id', XMLDB_TYPE_INTEGER, '18', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
      	 $categorytypetable->add_field('name', XMLDB_TYPE_CHAR,'255',null,null, null,null,'id');
      	 $categorytypetable->add_field('createdby', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'name');
	     $categorytypetable->add_field('createddate', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'createdby');
	     $categorytypetable->add_field('modifiedby', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'createddate');
	     $categorytypetable->add_field('modifieddate', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'modifiedby');
	     $categorytypetable->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
	     $dbman->create_table($categorytypetable);
      }else{
      	$field = new xmldb_field('name', XMLDB_TYPE_CHAR,'255',null,null, null,null,'id'); 
		if (!$dbman->field_exists($categorytypetable, $field)) {
			  $dbman->add_field($categorytypetable, $field);
		}
	    $field = new xmldb_field('createdby', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'name'); 
		if (!$dbman->field_exists($categorytypetable, $field)) {
		 $dbman->add_field($categorytypetable, $field);
		}
		$field = new xmldb_field('createddate', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'createdby'); 
		if (!$dbman->field_exists($categorytypetable, $field)) { 
			$dbman->add_field($categorytypetable, $field);
		}
		$field = new xmldb_field('modifiedby', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'createddate');
		if (!$dbman->field_exists($categorytypetable, $field)) {
		 $dbman->add_field($categorytypetable, $field);
		}
		$field = new xmldb_field('modifieddate', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'modifiedby');
		if (!$dbman->field_exists($categorytypetable, $field)) { 
			$dbman->add_field($categorytypetable, $field);
		}

    }



    if(!$dbman->table_exists($producttypetable)){
      	 $producttypetable->add_field('id', XMLDB_TYPE_INTEGER, '18', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
      	 $producttypetable->add_field('name', XMLDB_TYPE_CHAR,'255',null,null, null,null,'id');
      	 $producttypetable->add_field('createdby', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'name');
	     $producttypetable->add_field('createddate', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'createdby');
	     $producttypetable->add_field('modifiedby', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'createddate');
	     $producttypetable->add_field('modifieddate', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'modifiedby');
	     $producttypetable->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
	     $dbman->create_table($producttypetable);
      }else{
      	$field = new xmldb_field('name', XMLDB_TYPE_CHAR,'255',null,null, null,null,'id'); 
		if (!$dbman->field_exists($producttypetable, $field)) {
			  $dbman->add_field($producttypetable, $field);
		}
	    $field = new xmldb_field('createdby', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'name'); 
		if (!$dbman->field_exists($producttypetable, $field)) {
		 $dbman->add_field($producttypetable, $field);
		}
		$field = new xmldb_field('createddate', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'createdby'); 
		if (!$dbman->field_exists($producttypetable, $field)) { 
			$dbman->add_field($producttypetable, $field);
		}
		$field = new xmldb_field('modifiedby', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'createddate');
		if (!$dbman->field_exists($producttypetable, $field)) {
		 $dbman->add_field($producttypetable, $field);
		}
		$field = new xmldb_field('modifieddate', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'modifiedby');
		if (!$dbman->field_exists($producttypetable, $field)) { 
			$dbman->add_field($producttypetable, $field);
		}

    }







    

      if(!$dbman->table_exists($producttable)){
      	//createtable
	      $producttable->add_field('id', XMLDB_TYPE_INTEGER, '18', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
	      $producttable->add_field('companyid', XMLDB_TYPE_INTEGER,'18',null, XMLDB_NOTNULL, null, '0', 'id');
	      $producttable->add_field('productid', XMLDB_TYPE_CHAR,'50',null,null, null,null,'companyid');
	      $producttable->add_field('type', XMLDB_TYPE_CHAR,'50',null,null, null,null,'productid');
	      $producttable->add_field('category', XMLDB_TYPE_CHAR,'50',null,null, null,null,'type');
	      $producttable->add_field('name', XMLDB_TYPE_CHAR,'255',null,null, null,null,'category');
	      $producttable->add_field('description', XMLDB_TYPE_TEXT,null,null,null, null,null,'name');
	      $producttable->add_field('price', XMLDB_TYPE_NUMBER,'5,2',null,null, null,null,'description');
	      $producttable->add_field('currency', XMLDB_TYPE_CHAR,'50',null,null, null,null,'price');
	      $producttable->add_field('relatedproducts', XMLDB_TYPE_CHAR,'255',null,null, null,null,'currency');
	      $producttable->add_field('image', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'relatedproducts');
	      $producttable->add_field('promocode', XMLDB_TYPE_CHAR,'255',null,null, null,null,'image');
	      $producttable->add_field('popular', XMLDB_TYPE_INTEGER,'4',null,null, null,'0','promocode');
	      $producttable->add_field('rating', XMLDB_TYPE_INTEGER,'10',null,null, null,null,'popular');
	      $producttable->add_field('createdby', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'rating');
	      $producttable->add_field('createddate', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'createdby');
	      $producttable->add_field('modifiedby', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'createddate');
	      $producttable->add_field('modifieddate', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'modifiedby');
	      $producttable->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
          $dbman->create_table($producttable);
      }else {

          //    $field = new xmldb_field('id', XMLDB_TYPE_INTEGER,'18', null, XMLDB_NOTNULL, null,null);

          //    //$id_index = new xmldb_index('id', XMLDB_KEY_PRIMARY, ['id']);
          // if (!$dbman->field_exists($producttable, $field)) {
          // 	$producttable->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
          //     $dbman->add_field($producttable, $field);
          //      $dbman->add_index($producttable,$id_index);
          //     // $producttable->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
          // }

          $field = new xmldb_field('companyid', XMLDB_TYPE_INTEGER, '18', null, null, null, '0', 'id');
          if (!$dbman->field_exists($producttable, $field)) {
              $dbman->add_field($producttable, $field);
          }
          $field = new xmldb_field('productid', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'companyid');
          if (!$dbman->field_exists($producttable, $field)) {
              $dbman->add_field($producttable, $field);
          }
          $field = new xmldb_field('type', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'productid');
          if (!$dbman->field_exists($producttable, $field)) {
              $dbman->add_field($producttable, $field);
          }
          $field = new xmldb_field('category', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'type');
          if (!$dbman->field_exists($producttable, $field)) {
              $dbman->add_field($producttable, $field);
          }
          $field = new xmldb_field('name', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'category');
          if (!$dbman->field_exists($producttable, $field)) {
              $dbman->add_field($producttable, $field);
          }
          $field = new xmldb_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null, 'name');
          if (!$dbman->field_exists($producttable, $field)) {
              $dbman->add_field($producttable, $field);
          }
          $field = new xmldb_field('price', XMLDB_TYPE_NUMBER, '5,2', null, null, null, null, 'description');
          if (!$dbman->field_exists($producttable, $field)) {
              $dbman->add_field($producttable, $field);
          }
          $field = new xmldb_field('currency', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'price');
          if (!$dbman->field_exists($producttable, $field)) {
              $dbman->add_field($producttable, $field);
          }
          $field = new xmldb_field('relatedproducts', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'currency');
          if (!$dbman->field_exists($producttable, $field)) {
              $dbman->add_field($producttable, $field);
          }
          $field = new xmldb_field('image', XMLDB_TYPE_INTEGER, '18', null, null, null, null, 'relatedproducts');
          if (!$dbman->field_exists($producttable, $field)) {
              $dbman->add_field($producttable, $field);
          }
          $field = new xmldb_field('promocode', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'image');
          if (!$dbman->field_exists($producttable, $field)) {
              $dbman->add_field($producttable, $field);
          }
          $field = new xmldb_field('popular', XMLDB_TYPE_INTEGER, '4', null, null, null, '0', 'promocode');
          if (!$dbman->field_exists($producttable, $field)) {
              $dbman->add_field($producttable, $field);
          }
          $field = new xmldb_field('rating', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'popular');
          if (!$dbman->field_exists($producttable, $field)) {
              $dbman->add_field($producttable, $field);
          }
          $field = new xmldb_field('createdby', XMLDB_TYPE_INTEGER, '18', null, null, null, null, 'rating');
          if (!$dbman->field_exists($producttable, $field)) {
              $dbman->add_field($producttable, $field);
          }
          $field = new xmldb_field('createddate', XMLDB_TYPE_INTEGER, '18', null, null, null, null, 'createdby');
          if (!$dbman->field_exists($producttable, $field)) {
              $dbman->add_field($producttable, $field);
          }
          $field = new xmldb_field('modifiedby', XMLDB_TYPE_INTEGER, '18', null, null, null, null, 'createddate');
          if (!$dbman->field_exists($producttable, $field)) {
              $dbman->add_field($producttable, $field);
          }
          $field = new xmldb_field('modifieddate', XMLDB_TYPE_INTEGER, '18', null, null, null, null, 'modifiedby');
          if (!$dbman->field_exists($producttable, $field)) {
              $dbman->add_field($producttable, $field);
          }
      }
        // ecommerce savepoint reached.
        upgrade_plugin_savepoint(true, 2022061606, 'local', 'ecommerce');
	}


	if($oldversion<2022061608){
		$field = new xmldb_field('paymentid', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'userid'); 
		if (!$dbman->field_exists($carttable, $field)) {
			  $dbman->add_field($carttable, $field);
		}

		$field = new xmldb_field('rating', XMLDB_TYPE_INTEGER,'18',null,null, null,null,'quantity'); 
		if (!$dbman->field_exists($cartitemtable, $field)) {
			  $dbman->add_field($cartitemtable, $field);
		}
		$field = new xmldb_field('rating', XMLDB_TYPE_INTEGER,'10',null,null, null,null,'popular');
		if (!$dbman->field_exists($producttable, $field)) { 
			$dbman->add_field($producttable, $field);
		}
        // ecommerce savepoint reached.
        upgrade_plugin_savepoint(true, 2022061608, 'local', 'ecommerce');
	}

	if($oldversion<2022061612){
		
		$field_r = new xmldb_field('rating', XMLDB_TYPE_INTEGER,'10',null,null, null,null,'popular');
		if ($dbman->field_exists($producttable, $field_r)) { 
			$field = new xmldb_field('rating', XMLDB_TYPE_INTEGER,'10',null,null, null,'5','popular');
			$dbman->change_field_default($producttable, $field);
		}
        // ecommerce savepoint reached.
        upgrade_plugin_savepoint(true, 2022061612, 'local', 'ecommerce');
	}

	if($oldversion<2022061618){
		$field = new xmldb_field('billing_no_prefix', XMLDB_TYPE_CHAR,'255',null,null, null,null,'cartid'); 
		if (!$dbman->field_exists($pay_mongo_table, $field)) {
			  $dbman->add_field($pay_mongo_table, $field);
		}
        // ecommerce savepoint reached.
        upgrade_plugin_savepoint(true, 2022061618, 'local', 'ecommerce');
	}

	if($oldversion<2022061620){
		
		$field_n = new xmldb_field('price', XMLDB_TYPE_NUMBER,'5,2',null,null, null,null,'description');
		if ($dbman->field_exists($producttable, $field_n)) { 
			$field = new xmldb_field('price', XMLDB_TYPE_NUMBER,'10,2',null,null, null,null,'description');
			$dbman->change_field_default($producttable, $field);
		}
        // ecommerce savepoint reached.
        upgrade_plugin_savepoint(true, 2022061620, 'local', 'ecommerce');
	}



 // $producttable->add_field('price', XMLDB_TYPE_NUMBER,'5,2',null,null, null,null,'description');



	 return true;
}