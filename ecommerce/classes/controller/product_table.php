
<?php
require_once($CFG->dirroot."/local/ecommerce/classes/controller/custom_table.php");
class product_table extends custom_table 
{
    public $starttime = 0;
    function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array('select', 'productid', 'category', 'type', 'sold', 'price', 'Actions');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array('<input type="checkbox" data-type="selector" data-id="0"/>', 'Product id', 'Company', 'Type', "Sold", "Price", 'Action');
        $this->define_headers($headers);
        $this->no_sorting('select');
        $this->no_sorting('actions');
        $this->setexport(true);
        $this->setsearchitem(true);
        $this->setaddbtntext("Add Product");
    }
    function col_select($value) {
        return '<input type="checkbox" data-type="selector" data-id="'.$value->id.'"/>';
    }
    function col_actions($value) {
        return '                                       
        <div class="dropdown">
          <button class="btn btn-sm btn-info dropdown-toggle dropdown-toggle-split" type="button" data-toggle="dropdown">
          <span class="caret">:</span></button>
          <ul class="dropdown-menu">
            <li><a href="javascript:void(0);" editproduct data-id = "'.$value->id.'">Edit Product</a></li>
            <li><a href="javascript:void(0);" deleteproduct data-id = "'.$value->id.'">Delete</a></li>
          </ul>
       
      </div>';
    }
}
