<?php

namespace nu;

class Math {
	function mySqrt($number) {
	    $a = $number;
	    $xk = $number;

		for ( $i = 0; $i < log($number, 2)+2; $i++ )
    		$xk = (1 / 2) * ( $xk + ( $a / $xk ) );

    	return $xk;
	}
}
