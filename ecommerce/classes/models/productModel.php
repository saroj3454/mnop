<?php
class productModel
{
    public function __construct($formdata = [])
    {
    }
    public function getall(){
    	global $DB, $CFG;
    	$allproduct = $DB->get_records("product", array(), 'name', "*");
        foreach ($allproduct as $key => $product) {
            $product->images = self::get_productimage($product->id);
            $product->mainimage = $CFG->wwwroot."/local/ecommerce/assests/images/Products-image/01.png";
            if(!empty($product->images)){
                $mainimage  = $product->image?$product->image:0;
                if(isset($product->images[$mainimage])){
                    $images = $product->images[$mainimage];
                } else {
                    $images = $product->images[0];
                }
                $product->mainimage = $images['url'];
            }            
            $allproduct[$key] = $product;
        }
        return $allproduct;
    }
/**
 * @param mixed $product 
 * @param mixed $allproduct 
 * @return string*/
    public function filterall($params = null){
        global $DB, $CFG;
        $filters = array("1=1");
        $filtersdata = array();
        $ORDERBY = array();

        if(!empty($params)){
            if(!empty($params->search)){
                array_push($filters, "p.name like ?");
                $filtersdata["search"] = "%".$params->search."%";
            }
            if(!empty($params->ratings)){}
            if(!empty($params->company)){
                array_push($filters, "p.companyid in(".implode(",", $params->company).")");
                // $filtersdata[] = implode(",", $params->company);
            }
            if(!empty($params->category)){
                // $filtersdata[] = ;
                array_push($filters, "p.category in(".implode(",", $params->category).")");
            }
            if(!empty($params->type)){
                // $filtersdata[] = ;
                array_push($filters, "p.type in(".implode(",", $params->type).")");
            }
            if(!empty($params->ratings)){
                array_push($filters, "p.rating in(".implode(",", $params->ratings).")");
            }
            if(!empty($params->sortby)){
                switch ($params->sortby) {
                    case 'most_popular':
                        array_push($ORDERBY, " p.popular desc");
                        break;
                    case 'a_z':
                        array_push($ORDERBY, " p.name asc");
                        break;
                    case 'z_a':
                        array_push($ORDERBY, " p.name desc");
                        break;
                    case 'h_l':
                        array_push($ORDERBY, " p.price desc");
                        break;
                    case 'l_h':
                        array_push($ORDERBY, " p.price asc");
                        break;
                }
            }
        }
        if(empty($params->perpage)){
            $params->perpage=10;
        }   if(empty($params->page)){
            $params->page=0;
        }




        array_push($ORDERBY, " p.name");
        $ORDERBY = ' ORDER BY '. implode(",", $ORDERBY);
        // $selectquery = "SELECT p.* FROM {product} p WHERE ";
        //$selectquery = "SELECT p.*,ct.id as cartitemsproduct FROM {product} as p LEFT JOIN {cartitem} as ct on p.id=ct.itemid and ct.cartid='".$params->cartID."' LEFT JOIN mdl_cart as m on ct.cartid=m.id and m.status !='1' WHERE ";

        $selectquery = "SELECT p.*,ct.id as cartitemsproduct FROM mdl_product as p LEFT JOIN mdl_cart as mm on mm.id='".$params->cartID."'  LEFT JOIN mdl_cartitem as ct on p.id=ct.itemid and ct.cartid='".$params->cartID."' and mm.status='0' WHERE ";
        $countquery = "SELECT count(1) FROM {product} p WHERE ";
        $WHERE = implode(" AND ", $filters);
        $limitfrom = $params->perpage * $params->page;
        $allproduct = $DB->get_records_sql($selectquery.$WHERE.$ORDERBY.' limit '. $limitfrom. ", " .$params->perpage, $filtersdata );
        $totalproduct = $DB->get_field_sql($countquery.$WHERE, $filtersdata);
        foreach ($allproduct as $key => $product) {
            $product->images = self::get_productimage($product->id);
            $product->ratingdata = self::getStar($product->rating);
            $product->mainimage = $CFG->wwwroot."/local/ecommerce/assests/images/Products-image/01.png";
            $product->cartexit="";
            if(!empty($product->cartitemsproduct)){
               $product->cartexit="disabled";
            }
           


            if(!empty($product->images)){
                $mainimage  = $product->image?$product->image:0;
                if(isset($product->images[$mainimage])){
                    $images = $product->images[$mainimage];
                } else {
                    $images = $product->images[0];
                }
                $product->mainimage = $images['url'];
            }   
            $allproduct[$key] = $product;
        }
        return array("total"=>$totalproduct, "data"=>array_values($allproduct), "query"=>$selectquery.$WHERE.$ORDERBY, "filtersdata"=>$filtersdata);
    }
    /**
     * @params rate 
     * @return array 
     */
    private function getStar($rating){
        global $CFG;
        $ratingdata='';
        if(!empty($rating)){
            for($i=1;$i<=5;$i++){
                if($i<=$rating){
                   $ratingdata .='<img src="'.$CFG->wwwroot.'/local/ecommerce/assests/Icons/Star.png" alt="" style="opacity:1"/>' ;
                }else{
                    $ratingdata .='<img src="'.$CFG->wwwroot.'/local/ecommerce/assests/Icons/Star.png" alt="" style="opacity:0.5"/>' ;
                }
            }
        }else{
            $ratingdata='<img src="'.$CFG->wwwroot.'/local/ecommerce/assests/Icons/Star.png" alt="" style="opacity:1"/><img src="'.$CFG->wwwroot.'/local/ecommerce/assests/Icons/Star.png" alt="" style="opacity:1"/><img src="'.$CFG->wwwroot.'/local/ecommerce/assests/Icons/Star.png" alt="" style="opacity:1"/><img src="'.$CFG->wwwroot.'/local/ecommerce/assests/Icons/Star.png" alt="" style="opacity:1"/><img src="'.$CFG->wwwroot.'/local/ecommerce/assests/Icons/Star.png" alt="" style="opacity:1"/>';
        }
        return $ratingdata;
    }
    /**
     * @return array */
    public function getproduct($id=null){
        global $DB;
        $product = $DB->get_record("product", array("id"=>$id));
        if($product){
            $mainimage = $product->image;
            $product->images = self::get_productimage($product->id);
            if(isset($product->images[$mainimage])){
                $product->images[$mainimage]['selected'] = "checked";
            } else if(isset($product->images[0])) {
                $product->images[0]['selected'] = "checked";
            }
        }
        return $product;
    }
    /**
    * @param string $id 
    * @return array */
    public function viewproduct($id=null,$cartid=null){
        global $DB;
        $product = $DB->get_record_sql("SELECT p.*, c.name as companyname, pt.name as ptname, ct.name as categoryname
              FROM {product} p LEFT JOIN {company} c on c.id = p.companyid LEFT JOIN {producttype} pt on pt.id=p.type LEFT JOIN {categorytype} ct on ct.id=p.category where p.id=:id", array("id"=>$id));
        if($product){
            $product->images = self::get_productimage($product->id);
            $product->ratingdata = self::getStar($product->rating);
            $product->totalreviews = $DB->get_field_sql("select count(id) from {cartitem} where producttype = 0 and rating is not null and itemid = ?", array($product->id));
            $product->ratingdetails = self::get_productratingdetails($product->id);
            if(!empty($cartid)){
                 $cartexitdata=$DB->get_record_sql("SELECT p.*,ct.id as cartitemsproduct FROM mdl_product as p LEFT JOIN mdl_cart as mm on mm.id='".$cartid."'  LEFT JOIN mdl_cartitem as ct on p.id=ct.itemid and ct.cartid='".$cartid."' and mm.status='0' and p.id='".$product->id."'");
                    if(!empty($cartexitdata->cartitemsproduct)){
                         $product->cartexit="disabled";
                    }
            }
           


        }
        return $product;
    }
        /**
    * @param string $id 
    * @return array */
    public function get_productratingdetails($productid){
        global $DB;
        $allratings = array();
        $totalreviews = $DB->get_field_sql("select count(id) from {cartitem} where producttype = 0 and rating is not null and itemid = ?", array($productid));
        for ($i=5; $i >=1 ; $i--) { 
            $rating = array(
                "rate"=>$i,
                "total"=>$totalreviews,
                "review"=>$DB->get_field_sql("select count(id) from {cartitem} where producttype = 0 and rating is not null and itemid = ? and rating = ?", array($productid, $i)),
            );
            if(empty($rating['review'])){
                $rating['avg'] = 0;
            } else {
                $rating['avg'] = (intval($rating['review'])/$totalreviews * 100);
            }
            array_push($allratings, $rating);
        }
        return $allratings;
    }
        /**
    * @param string $id 
    * @return array */
    public function get_productimage($productid){
        global $DB, $CFG;
        $allfiles = $DB->get_records("files", array("component"=>"local_ecommerce", "filearea"=>"images", 'itemid' => $productid));
        $allimages = array();
        foreach ($allfiles as $key => $image) {
            if(empty($image->filesize)){ continue; }
            $allimages[] = array(
                "url"=> $CFG->wwwroot."/local/ecommerce/file.php?id=".$image->pathnamehash,
                "filename"=>$image->filename,
                "pathnamehash"=>$image->pathnamehash,
            );
        }
        return $allimages;
    }
        /**
    * @param string $productid 
    * @return array */
    public function get_mainimage($productid){
        global $DB, $CFG;
        $mainimage = $CFG->wwwroot."/local/ecommerce/assests/images/Products-image/01.png";
        $product = $DB->get_record("product", array("id"=>$productid));
        if($product){
            $allfiles = self::get_productimage($productid);
            if(!empty($allfiles)){
                $mainimage  = $product->image?$product->image:0;
                if(isset($allfiles[$mainimage])){
                    $images = $allfiles[$mainimage];
                } else {
                    $images = $allfiles[0];
                }
                $mainimage = $images['url'];
            } 
        }
        return $mainimage;
    }
        /**
    * @param string $id 
    * @return string */
    public function saveProduct($product){
    	global $DB, $USER;
    	//$product->image = 0;
        $product->relatedproducts = implode(",",$product->relatedproducts);

    	if(!empty($product->id)){
    		$product->modifiedby = $USER->id;
            //$product->image = 0;
    		$product->modifieddate = time();
            unset($product->productid);
            self::processallimages($product);
    		return $DB->update_record("product",$product);
    	} else {
    		$product->createdby = $USER->id;
    		$product->createddate = time();
    		$product->productid = $this->generateProductid();
    		$product->id = $DB->insert_record("product",$product);
            if($product->id){
                self::processallimages($product);
                return true;
            } else {
                return false;
            }

    	}
    }
        /**
    * @param string $product
    * @return bool 
    * @return array */

    public function deleteproduct($productids){
        global $DB;  
        if(!empty ($productids)){
            try {
                $allproduct = explode(",", $productids);
                foreach ($allproduct as $key => $p) {
                    $DB->delete_records("product", array("id"=>$p)); 
                }
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }
        /**
    * @param string $productids
    * @return bool 
    * @return array */

    private function processallimages($product){
        global $DB;  
        $contextid = 1;
        $images = $product->images;
        $imagesname = $product->imagesname;
        $fs = get_file_storage();
        $files = $fs->get_area_files($contextid, 'local_ecommerce', 'images', $product->id);
        foreach ($files as $key => $fileinfo) {
            if(in_array($key, $imagesname)){continue;}
            $file = $fs->get_file($fileinfo->get_contextid(), "local_ecommerce", "images", $fileinfo->get_itemid(), $fileinfo->get_filepath(), $fileinfo->get_filename());
            if ($file) {
                $file->delete();
            }
        }
        foreach ($images as $key => $image) {
            if(empty($image) || strpos($image, "http://") !== false || strpos($image, "https://") !== false ){ continue;}
            $filename = "image".$key.".jpg";
            if(isset($imagesname[$key]) && !empty($imagesname[$key])){
                $filename = $imagesname[$key];
            }
            
            $fileinfo = array(
                'contextid' => $contextid, // ID of context
                'component' => 'local_ecommerce',
                'filearea' => 'images',     // usually = table name
                'itemid' => $product->id,               // usually = ID of row in table
                'filepath' => "/".$key."/",           // any path beginning and ending in /
                'filename' => $filename); // any filename
            // Get file
            $file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'], 
                    $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);
            if ($file) {
                $file->delete();
            }
            $wsfiledata_decoded = self::prepareImagedata($image);
            $fs->create_file_from_string($fileinfo, $wsfiledata_decoded);    
        }
    }
        /**
    * @param string $product 
    * @return array */
    private function prepareImagedata($image){
        $imagedata = explode(",", $image);
        return base64_decode(array_pop($imagedata));
    }
    /**
     * @param string $image
     * @return array*/
    private function generateProductid(){
        global $DB;
        $productid = rand(1000000, 99999999);
        if($DB->record_exists("product", array("productid"=>$productid))){
            $productid = self::generateProductid();
        }
        return $productid;
    }
        /**
    * @return array */
    public function relatedAllProduct(){
        global $DB;
        $relatedproduct=$DB->get_records_sql("SELECT * FROM {course} WHERE visible=?",array(1));
        return $relatedproduct;
    }
}
    /**
    * @return array */

