<?php
require_once('product_table.php');
class promocodeController
{
    public function browse($pageurl){
        global $PAGE, $OUTPUT;
       
        }
        $SELECT="p.promoid, p.type, p.expire, p.enddate,p.status,  '' as sold, p.discount";
        $FROM=" {promocode} p ";
        $WHERE=implode(" AND ", $filter);
        $GROUPBY=" ";
        $table = new promocode_table("promocode_report");
        $table->is_downloading($download, 'promocodereport', 'promocodereport');
        $table->is_downloadable(true);
        $table->setsearchtext($customsearch);
       
        }
    }
}