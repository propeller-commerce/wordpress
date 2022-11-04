<?php

if ($data->pages >= 1) {
    foreach($orders as $order)
        apply_filters('propel_account_quotations_table_list_item', $order, $obj);

        apply_filters('propel_account_quotations_table_list_paging', $data, $obj);
}
?>