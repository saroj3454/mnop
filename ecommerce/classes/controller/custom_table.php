<?php
require_once($CFG->libdir."/tablelib.php");
class custom_table extends table_sql 
{
    public $addbtntext = "";
    public $searchitem = false;
    public $searchtext = "";
    public $export = false;
    public $edit = '';
    function __construct($uniqueid) {
        parent::__construct($uniqueid);
    }
        /**
     * Take the data returned from the db_query and go through all the rows
     * processing each col using either col_{columnname} method or other_cols
     * method or if other_cols returns NULL then put the data straight into the
     * table.
     *
     * After calling this function, don't forget to call close_recordset.
     */
    public function build_table() {

        if ($this->rawdata instanceof \Traversable && !$this->rawdata->valid()) {
            return;
        }
        if (!$this->rawdata) {
            $this->rawdata = array("empty"=>array());
        }

        foreach ($this->rawdata as $row) {
            $formattedrow = $this->format_row($row);
            $this->add_data_keyed($formattedrow,
                $this->get_row_class($row));
        }
    }

    /**
     * This method actually directly echoes the row passed to it now or adds it
     * to the download. If this is the first row and start_output has not
     * already been called this method also calls start_output to open the table
     * or send headers for the downloaded.
     * Can be used as before. print_html now calls finish_html to close table.
     *
     * @param array $row a numerically keyed row of data to add to the table.
     * @param string $classname CSS class name to add to this row's tr tag.
     * @return bool success.
     */
    function add_data($row, $classname = '') {
        if (!$this->setup) {
            return false;
        }
        if (!$this->started_output) {
            $this->start_output();
        }
        if(isset($this->rawdata['empty'])){
            return false;
        }

        if ($this->exportclass!==null) {
            if ($row === null) {
                $this->exportclass->add_seperator();
            } else {
                $this->exportclass->add_data($row);
            }
        } else {
            $this->print_row($row, $classname);
        }
        return true;
    }

    function start_html() {
        global $OUTPUT;
        echo html_writer::start_tag('div', array('class' => 'custom_table'));
        // Render the dynamic table header.
        echo $this->get_dynamic_table_html_start();

        // Render button to allow user to reset table preferences.
        echo $this->render_reset_button();

        // Do we need to print initial bars?
        $this->print_initials_bar();

        // Paging bar
        // if ($this->use_pages) {
        //     $pagingbar = new paging_bar($this->totalrows, $this->currpage, $this->pagesize, $this->baseurl);
        //     $pagingbar->pagevar = $this->request[TABLE_VAR_PAGE];
        //     echo $OUTPUT->render($pagingbar);
        // }

        echo html_writer::start_tag('div', array('class' => 'custom_table_header'));
        if($this->searchitem){
            echo '<form method = "get" action="'.$this->baseurl->__toString().'"><div class="input-group">
    <input type="text" name="customsearch" class="form-control" placeholder="Search" value="'.$this->searchtext.'">
    <div class="input-group-btn">
      <button class="btn btn-default" type="submit">
        <i class="fa fa-search"></i>
      </button>
    </div>
  </div></form>';
        }

        echo html_writer::start_tag('div', array('class' => 'custom_table_header_action'));
        if($this->export && !isset($this->rawdata['empty'])){
            echo $this->download_buttons();
        }
        if(!empty($this->addbtntext)){
            echo html_writer::nonempty_tag("button", $this->addbtntext, array("id"=>"model_addproduct", "class"=>"product-modal"));
        }
        echo html_writer::end_tag('div');
        echo html_writer::end_tag('div');

        $this->wrap_html_start();
        // Start of main data table

        echo html_writer::start_tag('div', array('class' => 'no-overflow'));
        echo html_writer::start_tag('table', $this->attributes);


    }
    
    /**
     * This function is not part of the public api.
     */
    function finish_html() {
        global $OUTPUT, $PAGE;

        if (!$this->started_output) {
            //no data has been added to the table.
            $this->print_nothing_to_display();

        } else {
            // Print empty rows to fill the table to the current pagesize.
            // This is done so the header aria-controls attributes do not point to
            // non existant elements.
            $emptyrow = array_fill(0, count($this->columns), '');
            while ($this->currentrow < $this->pagesize) {
                $this->print_row($emptyrow, 'emptyrow');
            }

            echo html_writer::end_tag('tbody');
            echo html_writer::end_tag('table');
            echo html_writer::end_tag('div');
            $this->wrap_html_finish();

            // Paging bar
            if(in_array(TABLE_P_BOTTOM, $this->showdownloadbuttonsat)) {
                echo $this->download_buttons();
            }

            if($this->use_pages) {
                $pagingbar = new paging_bar($this->totalrows, $this->currpage, $this->pagesize, $this->baseurl);
                $pagingbar->pagevar = $this->request[TABLE_VAR_PAGE];
                echo $OUTPUT->render($pagingbar);
            }

            // Render the dynamic table footer.
            echo $this->get_dynamic_table_html_end();
            echo html_writer::end_tag('div');
        }
    }
    /*
    |--------------------------------------------------------------------------
    | Getters and Setters
    |--------------------------------------------------------------------------
    |
    | The following lines contains getters and setters for the singleton object
    |
    */
    /**
     * @param int $export
     */
    protected function setexport($export = false): void
    {
        $this->export = $export;
    }
    /**
     * @param int $searchitem
     */
    protected function setsearchitem($searchitem = false): void
    {
        $this->searchitem = $searchitem;
    }
    /**
     * @param int $addbtntext
     */
    protected function setaddbtntext($addbtntext = ""): void
    {
        $this->addbtntext = $addbtntext;
    }
    /**
     * @param int $searchtext
     */
    public function setsearchtext($searchtext = ""): void
    {
        $this->searchtext = $searchtext;
    }
}