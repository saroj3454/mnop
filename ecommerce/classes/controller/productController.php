<?php
require_once('product_table.php');
class productController
{
    public function browse($pageurl){
        global $PAGE, $OUTPUT;
        $download = optional_param('download', '', PARAM_ALPHA);
        $customsearch = optional_param('customsearch', '', PARAM_RAW);
        $title = "Manage Product";       
        $filter = array("1=1");
        $filterdata = array();
        $filterdata = array();
        if(!empty($customsearch)){
            array_push($filter, '(p.productid like ? OR p.category like ? OR p.type like ? OR p.price like ? )');
            array_push($filterdata , "%".$customsearch."%");
            array_push($filterdata , "%".$customsearch."%");
            array_push($filterdata , "%".$customsearch."%");
            array_push($filterdata , "%".$customsearch."%");
        }
        $SELECT="p.productid, p.category, p.type, '' as sold, p.price";
        $FROM=" {product} p ";
        $WHERE=implode(" AND ", $filter);
        $GROUPBY=" ";
        $table = new product_table("Product_report");
        $table->is_downloading($download, 'productreport', 'productreport');
        $table->is_downloadable(true);
        $table->setsearchtext($customsearch);
        if (!$table->is_downloading()) {
            $PAGE->set_title($title);
            $PAGE->set_heading($title);
            $PAGE->navbar->add($title, new moodle_url("/local/ecommerce/admin/index.php"));
            echo $OUTPUT->header();
        }
        $table->set_sql($SELECT, $FROM, $WHERE.$GROUPBY, $filterdata);
        $table->define_baseurl($pageurl);
         echo html_writer::start_tag('div', array('class' => 'manage-product container'));
        $table->out(10, true);
        echo html_writer::end_tag('div');
        if (!$table->is_downloading()) {
            echo $OUTPUT->footer();
        }
    }
}