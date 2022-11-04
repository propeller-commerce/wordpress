<?php

namespace Propeller;

class PropellerHelper {
    public static function formatPrice($price) {
        return number_format($price, 2, ',', '.');
    }

    public static function formatPriceGTM($price) {
        return number_format($price, 2, '.', '.');
    }

    public static function percentage($percent, $total) {
        return ($percent / 100) * $total;
    }
    
}