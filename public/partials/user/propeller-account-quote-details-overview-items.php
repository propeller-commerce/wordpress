<?php

foreach($items as $item) { 
    if ($item->class == 'product' && $item->isBonus != 'Y') {
        apply_filters('propel_quote_details_overview_item', $item, $obj);
    }   
}