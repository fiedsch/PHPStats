<?php
/**
 * PHP Statistics Library
 *
 * Copyright (C) 2011-2012 Michael Cordingley<Michael.Cordingley@gmail.com>
 * 
 * This library is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Library General Public License as published
 * by the Free Software Foundation; either version 3 of the License, or 
 * (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Library General Public
 * License for more details.
 * 
 * You should have received a copy of the GNU Library General Public License
 * along with this library; if not, write to the Free Software Foundation, 
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 * 
 * LGPL Version 3
 *
 * @package PHPStats
 */
 
namespace PHPStats;

/**
 * Stats class
 * 
 * Static class containing a variety of useful statistical functions.  
 * Fills in where PHP's math functions fall short.  Many functions are
 * used extensively by the probability distributions.
 */
class Stats {
	//Useful to tell if a float has a mathematically integer value.
	private static function is_integer($x) {
		return ($x == floor($x));
	}

	/**
	 * Sum Function
	 * 
	 * Sums an array of numeric values.  Non-numeric values
	 * are treated as zeroes.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The sum of the elements of the array
	 * @static
	 */
	public static function sum(array $data) {
		$sum = 0.0;
		foreach ($data as $element) {
			if (is_numeric($element)) $sum += $element;
		}
		return $sum;
	}

	/**
	 * Product Function
	 * 
	 * Multiplies an array of numeric values.  Non-numeric values
	 * are treated as ones.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The product of the elements of the array
	 * @static
	 */
	public static function product(array $data) {
		$product = 1;
		foreach ($data as $element) {
			if (is_numeric($element)) $product *= $element;
		}
		return $product;
	}

	/**
	 * Average Function
	 * 
	 * Takes the arithmetic mean of an array of numeric values.
	 * Non-numeric values are treated as zeroes.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The arithmetic average of the elements of the array
	 * @static
	 */
	public static function average(array $data) {
		return self::sum($data)/count($data);
	}

	/**
	 * Geometric Average Function
	 * 
	 * Takes the geometic mean of an array of numeric values.
	 * Non-numeric values are treated as ones.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The geometic average of the elements of the array
	 * @static
	 */
	public static function gaverage(array $data) {
		return pow(self::product($data), 1/count($data));
	}

	/**
	 * Sum-Squared Function
	 * 
	 * Returns the sum of squares of an array of numeric values.
	 * Non-numeric values are treated as zeroes.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The arithmetic average of the elements of the array
	 * @static
	 */
	public static function sumsquared(array $data) {
		$sum = 0.0;
		foreach ($data as $element) {
			if (is_numeric($element)) $sum += pow($element, 2);
		}
		return $sum;
	}


	/**
	 * Sum-XY Function
	 * 
	 * Returns the sum of products of paired variables in a pair of arrays
	 * of numeric values.  The two arrays must be of equal length.
	 * Non-numeric values are treated as zeroes.
	 * 
	 * @param array $datax An array of numeric values
	 * @param array $datay An array of numeric values
	 * @return float The products of the paired elements of the arrays
	 * @static
	 */
	public static function sumXY(array $datax, array $datay) {
		$n = min(count($datax), count($datay));
		$sum = 0.0;
		for ($count = 0; $count < $n; $count++) {
			if (is_numeric($datax[$count])) $x = $datax[$count];
			else $x = 0; //Non-numeric elements count as zero.

			if (is_numeric($datay[$count])) $y = $datay[$count];
			else $y = 0; //Non-numeric elements count as zero.

			$sum += $x*$y;
		}
		return $sum;
	}

	/**
	 * Sum-Squared Error Function
	 * 
	 * Returns the sum of squares of errors of an array of numeric values.
	 * Non-numeric values are treated as zeroes.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The sum of the squared errors of the elements of the array
	 * @static
	 */
	public static function sse(array $data) {
		$average = self::average($data);
		$sum = 0.0;
		foreach ($data as $element) {
			if (is_numeric($element)) $sum += pow($element - $average, 2);
			else $sum += pow(0 - $average, 2);
		}
		return $sum;
	}

	/**
	 * Mean-Squared Error Function
	 * 
	 * Returns the arithmetic mean of squares of errors of an array
	 * of numeric values. Non-numeric values are treated as zeroes.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The average squared error of the elements of the array
	 * @static
	 */
	public static function mse(array $data) {
		return self::sse($data)/count($data);
	}

	/**
	 * Covariance Function
	 * 
	 * Returns the covariance of two arrays.  The two arrays must
	 * be of equal length. Non-numeric values are treated as zeroes.
	 * 
	 * @param array $datax An array of numeric values
	 * @param array $datay An array of numeric values
	 * @return float The covariance of the two supplied arrays
	 * @static
	 */
	public static function covariance(array $datax, array $datay) {
		return self::sumXY($datax, $datay)/count($datax) - self::average($datax)*self::average($datay);
	}

	/**
	 * Variance Function
	 * 
	 * Returns the population variance of an array.
	 * Non-numeric values are treated as zeroes.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The variance of the supplied array
	 * @static
	 */
	public static function variance(array $data) {
		return self::covariance($data, $data);
	}

	/**
	 * Standard Deviation Function
	 * 
	 * Returns the population standard deviation of an array.
	 * Non-numeric values are treated as zeroes.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The population standard deviation of the supplied array
	 * @static
	 */
	public static function stddev(array $data) {
		return sqrt(self::variance($data));
	}

	/**
	 * Sample Standard Deviation Function
	 * 
	 * Returns the sample (unbiased) standard deviation of an array.
	 * Non-numeric values are treated as zeroes.
	 * 
	 * @param array $data An array of numeric values
	 * @return float The unbiased standard deviation of the supplied array
	 * @static
	 */
	public static function sampleStddev(array $data) {
		return sqrt(self::sse($data)/(count($data)-1));
	}

	/**
	 * Correlation Function
	 * 
	 * Returns the correlation of two arrays.  The two arrays must
	 * be of equal length. Non-numeric values are treated as zeroes.
	 * 
	 * @param array $datax An array of numeric values
	 * @param array $datay An array of numeric values
	 * @return float The correlation of the two supplied arrays
	 * @static
	 */
	public static function correlation($datax, $datay) {
		return self::covariance($datax, $datay)/(self::stddev($datax)*self::stddev($datay));
	}

	/**
	 * Factorial Function
	 * 
	 * Returns the factorial of an integer.  Values less than 1 return
	 * as 1.  Non-integer arguments are evaluated only for the integer
	 * portion (the floor).  
	 * 
	 * @param int $x An array of numeric values
	 * @return int The factorial of $x, i.e. x!
	 * @static
	 */
	public static function factorial($x) {
		$sum = 1;
		for ($i = 1; $i <= floor($x); $i++) $sum *= $i;
		return $sum;
	}
	
	/**
	 * Error Function
	 * 
	 * Returns the real error function of a number.
	 * An approximation from Abramowitz and Stegun is used.
	 * Maximum error is 1.5e-7. More information can be found at
	 * http://en.wikipedia.org/wiki/Error_function#Approximation_with_elementary_functions
	 * 
	 * @param float $x Argument to the real error function
	 * @return float A value between -1 and 1
	 * @static
	 */
	public static function erf($x) {
		if ($x < 0) return -self::erf(-$x);

		$t = 1 / (1 + 0.3275911 * $x);
		return 1 - (0.254829592*$t - 0.284496736*pow($t, 2) + 1.421413741*pow($t, 3) + -1.453152027*pow($t, 4) + 1.061405429*pow($t, 5))*exp(-pow($x, 2));
	}
	
	/**
	 * Inverse Error Function
	 * 
	 * Returns the inverse real error function of a number.
	 * More information can be found at
	 * http://en.wikipedia.org/wiki/Error_function#Inverse_function
	 * 
	 * @param float $x Argument to the real error function
	 * @return float A value between -1 and 1
	 * @static
	 *
	 * Code borrowed from the Apache Project Commons Math
	 * see http://commons.apache.org/proper/commons-math/apidocs/org/apache/commons/math3/special/Erf.html
	 * and http://svn.apache.org/repos/asf/commons/proper/math/trunk/src/main/java/org/apache/commons/math3/special/Erf.java
	 * 
	 * The Apache implementation is described in the paper:
	 * http://people.maths.ox.ac.uk/gilesm/files/gems_erfinv.pdf (Approximating
	 * the erfinv function) by Mike Giles, Oxford-Man Institute of Quantitative Finance,
	 * which was published in GPU Computing Gems, volume 2, 2010.
	 * The source code is available at http://gpucomputing.net/?q=node/1828.
	 *
	 * "Translated" to PHP (2013-10-12, fiedsch@ja-eh.at)
	 */
	public static function ierf($x) {
	  
	  // [Apache version comment]
	  // beware that the logarithm argument must be
	  // commputed as (1.0 - x) * (1.0 + x),
	  // it must NOT be simplified as 1.0 - x * x as this
	  // would induce rounding errors near the boundaries +/-1

	  $w = - log((1.0 - $x) * (1.0 + $x));
	  
	  if ($w < 6.25) {
	    $w = $w - 3.125;
	    $p =  -3.6444120640178196996e-21;
	    $p =   -1.685059138182016589e-19 + $p * $w;
	    $p =   1.2858480715256400167e-18 + $p * $w;
	    $p =    1.115787767802518096e-17 + $p * $w;
	    $p =   -1.333171662854620906e-16 + $p * $w;
	    $p =   2.0972767875968561637e-17 + $p * $w;
	    $p =   6.6376381343583238325e-15 + $p * $w;
	    $p =  -4.0545662729752068639e-14 + $p * $w;
	    $p =  -8.1519341976054721522e-14 + $p * $w;
	    $p =   2.6335093153082322977e-12 + $p * $w;
	    $p =  -1.2975133253453532498e-11 + $p * $w;
	    $p =  -5.4154120542946279317e-11 + $p * $w;
	    $p =    1.051212273321532285e-09 + $p * $w;
	    $p =  -4.1126339803469836976e-09 + $p * $w;
	    $p =  -2.9070369957882005086e-08 + $p * $w;
	    $p =   4.2347877827932403518e-07 + $p * $w;
	    $p =  -1.3654692000834678645e-06 + $p * $w;
	    $p =  -1.3882523362786468719e-05 + $p * $w;
	    $p =    0.0001867342080340571352 + $p * $w;
	    $p =  -0.00074070253416626697512 + $p * $w;
	    $p =   -0.0060336708714301490533 + $p * $w;
	    $p =      0.24015818242558961693 + $p * $w;
	    $p =       1.6536545626831027356 + $p * $w;
	  } else if ($w < 16.0) {
	    $w = sqrt($w) - 3.25;
	    $p =   2.2137376921775787049e-09;
	    $p =   9.0756561938885390979e-08 + $p * $w;
	    $p =  -2.7517406297064545428e-07 + $p * $w;
	    $p =   1.8239629214389227755e-08 + $p * $w;
	    $p =   1.5027403968909827627e-06 + $p * $w;
	    $p =   -4.013867526981545969e-06 + $p * $w;
	    $p =   2.9234449089955446044e-06 + $p * $w;
	    $p =   1.2475304481671778723e-05 + $p * $w;
	    $p =  -4.7318229009055733981e-05 + $p * $w;
	    $p =   6.8284851459573175448e-05 + $p * $w;
	    $p =   2.4031110387097893999e-05 + $p * $w;
	    $p =   -0.0003550375203628474796 + $p * $w;
	    $p =   0.00095328937973738049703 + $p * $w;
	    $p =   -0.0016882755560235047313 + $p * $w;
	    $p =    0.0024914420961078508066 + $p * $w;
	    $p =   -0.0037512085075692412107 + $p * $w;
	    $p =     0.005370914553590063617 + $p * $w;
	    $p =       1.0052589676941592334 + $p * $w;
	    $p =       3.0838856104922207635 + $p * $w;
	  } else if (is_infinite($w)) {
	    $w = sqrt($w) - 5.0;
	    $p =  -2.7109920616438573243e-11;
	    $p =  -2.5556418169965252055e-10 + $p * $w;
	    $p =   1.5076572693500548083e-09 + $p * $w;
	    $p =  -3.7894654401267369937e-09 + $p * $w;
	    $p =   7.6157012080783393804e-09 + $p * $w;
	    $p =  -1.4960026627149240478e-08 + $p * $w;
	    $p =   2.9147953450901080826e-08 + $p * $w;
	    $p =  -6.7711997758452339498e-08 + $p * $w;
	    $p =   2.2900482228026654717e-07 + $p * $w;
	    $p =  -9.9298272942317002539e-07 + $p * $w;
	    $p =   4.5260625972231537039e-06 + $p * $w;
	    $p =  -1.9681778105531670567e-05 + $p * $w;
	    $p =   7.5995277030017761139e-05 + $p * $w;
	    $p =  -0.00021503011930044477347 + $p * $w;
	    $p =  -0.00013871931833623122026 + $p * $w;
	    $p =       1.0103004648645343977 + $p * $w;
	    $p =       4.8499064014085844221 + $p * $w;
	  } else {
	    // [Apache version comment]
	    // this branch does not appears in the original code, it
	    // was added because the previous branch does not handle
	    // x = +/-1 correctly. In this case, w is positive infinity
	    // and as the first coefficient (-2.71e-11) is negative.
	    // Once the first multiplication is done, p becomes negative
	    // infinity and remains so throughout the polynomial evaluation.
	    // So the branch above incorrectly returns negative infinity
	    // instead of the correct positive infinity.
	    // 
	    // p = Double.POSITIVE_INFINITY;
	    // 
	    // [PHP version comment]
	    // what would be the appropriate value here?
	    $p = (float) INFINITY; 

	  }
	  
	  return $p * $x;
	  
	}
	
	/**
	 * Gamma Function
	 * 
	 * Returns the gamma function of a number.
	 * The gamma function is a generalization of the factorial function
	 * to non-integer and negative non-integer values. 
	 * The relationship is as follows: gamma(n) = (n - 1)!
	 * Stirling's approximation is used.  Though the actual gamma function
	 * is defined for negative, non-integer values, this approximation is
	 * undefined for anything less than or equal to zero.
	 * 
	 * @param float $x Argument to the gamma function
	 * @return float The gamma of $x
	 * @static
	 */
	public static function gamma($x) {
		//Lanczos' Approximation from Wikipedia
		
		// Coefficients used by the GNU Scientific Library
		$g = 7;
		$p = array(0.99999999999980993, 676.5203681218851, -1259.1392167224028,
			 771.32342877765313, -176.61502916214059, 12.507343278686905,
			 -0.13857109526572012, 9.9843695780195716e-6, 1.5056327351493116e-7);
		 
		// Reflection formula
		if ($x < 0.5) return M_PI / (sin(M_PI*$x)*self::gamma(1-$x));
		else {
			$x--;
			$y = $p[0];
			
			for ($i = 1; $i < $g+2; $i++) $y += $p[$i]/($x+$i);
			
			$t = $x + $g + 0.5;
			return pow(2*M_PI, 0.5) * pow($t, $x+0.5) * exp(-$t) * $y;
		}
	}

	/**
	 * Log Gamma Function
	 * 
	 * Returns the natural logarithm of the gamma function.  Useful for
	 * scaling.
	 * 
	 * @param float $x Argument to the gamma function
	 * @return float The natural log of gamma of $x
	 * @static
	 */
	public static function gammaln($x) {
		//Thanks to jStat for this one.
		$cof = array(
			76.18009172947146, -86.50532032941677, 24.01409824083091,
			-1.231739572450155, 0.1208650973866179e-2, -0.5395239384953e-5);
		$xx = $x;
		$y = $xx;
		$tmp = $x + 5.5;
		$tmp -= ($xx + 0.5) * log($tmp);
		$ser = 1.000000000190015;

		for($j = 0; $j < 6; $j++ ) $ser += $cof[$j] / ++$y;

		return log( 2.5066282746310005 * $ser / $xx) - $tmp;
	}

	/**
	 * Inverse gamma function
	 * 
	 * Returns the inverse of the gamma function.  The relative error of the
	 * principal branch peaks at 1.5 near the lower bound (i.e. igamma(0.885603))
	 * and approaches zero the higher the argument to this function.
	 * The secondary branch is not fully covered by the approximation and so
	 * will have much higher error.
	 * 
	 * @param float $x The result of the gamma function
	 * @param bool $principal True for the principal branch, false for the secondary (e.g. gamma(x) where x < 1.461632)
	 * @return float The argument to the gamma function
	 * @static
	 */
	public static function igamma($x, $principal = true) {
		//Source: http://mathforum.org/kb/message.jspa?messageID=342551&tstart=0
		
		if ($x < 0.885603) return NAN;  // gamma(1.461632) == 0.885603, the positive minimum of gamma
		
		//$k = 1.461632;
		$c = 0.036534; //pow(2*M_PI, 0.5)/M_E - self::gamma($k);
		$lx = log(($x + $c)/2.506628274631); //pow(2*M_PI, 0.5)); == 2.506628274631
		return $lx / self::lambert($lx/M_E, $principal) + 0.5;
	}

	/**
	 * Digamma Function
	 * 
	 * Returns the digamma function of a number
	 * 
	 * @param float $x Argument to the digamma function
	 * @return The result of the digamma function
	 * @static
	 */
	public static function digamma($x) {
		//Algorithm translated from http://www.uv.es/~bernardo/1976AppStatist.pdf
		$s = 1.0e-5;
		$c = 8.5;
		$s3 = 8.33333333e-2;
		$s4 = 8.33333333e-3;
		$s5 = 3.968253968e-2;
		$d1 = -0.5772156649;

		$y = $x;
		$retval = 0;

		if ($y <= 0) return NAN;

		if ($y <= $s) return $d1 - 1/$y;

		while ($y < $c) {
			$retval = $retval - 1/$y;
			$y++;
		}

		$r = 1/$y;
		$retval = $retval + log($y) - 0.5 * $r;
		$r *= $r;
		$retval = $retval - $r * ($s3 - $r * ($s4 - $r *$s5));

		return $retval;
	}

	/**
	 * Lambert Function
	 * 
	 * Returns the positive branch of the lambert function
	 * 
	 * @param float $x Argument to the lambert funcction
	 * @param bool $principal True to use the principal branch, false to use the secondary
	 * @return float The result of the lambert function
	 * @static
	 */
	public static function lambert($x, $principal = true) {
		// http://www.whim.org/nebula/math/lambertw.html
		
		if ($principal) {
			if ($x > 10) $w = log($x) - log(log($x));
			elseif ($x > -1/M_E) $w = 0;
			else return NAN; //Undefined below -1/e
		}
		else { //Secondary
			if ($x >= -1/M_E && $x <= -0.1) $w = -2;
			elseif ($x > -0.1 && $x < 0) $w = log(-$x) - log(-log(-x));
			else return NAN; //Defined only for [-1/e, 0)
		}
		
		for ($k = 1; $k < 150; ++$k) {
			$old_w = $w;
			$w = ($x*exp(-$w) + pow($w, 2))/($w + 1);
			
			if (abs($w - $old_w) < 0.0000001) break;
		}
		
		return $w;
	}
	
	/**
	 * Incomplete (Lower) Gamma Function
	 * 
	 * Returns the lower gamma function of a number.
	 * 
	 * @param float $s Upper bound of integration
	 * @param float $x Argument to the lower gamma function.
	 * @return float The lower gamma of $x
	 * @static
	 */
	public static function lowerGamma($s, $x) {
		//Adapted from jStat
		$aln = self::gammaln($s);
		$afn = self::gamma($s);
		$ap = $s;
		$sum = 1 / $s;
		$del = $sum;

		$afix = ($s >= 1 )?$s:1 / $s;
		$ITMAX = floor(log($afix) * 8.5 + $s * 0.4 + 17);

		if ($x < 0 || $s <= 0 ) {
			return NAN;
		}
		elseif ($x < $s + 1 ) {
			for ($i = 1; $i <= $ITMAX; $i++) {
				$sum += $del *= $x / ++$ap;
			}

			$endval = $sum * exp(-$x + $s * log($x) - ($aln));
		}
		else {
			$b = $x + 1 - $s;
			$c = 1 / 1.0e-30;
			$d = 1 / $b;
			$h = $d;

			for ($i = 1; $i <= $ITMAX; $i++) {
				$an = -$i * ($i - $s);
				$b += 2;
				$d = $an * $d + $b;
				$c = $b + $an / $c;
				$d = 1 / $d;
				$h *= $d * $c;
			}

			$endval = 1 - $h * exp(-$x + $s * log($x) - ($aln));
		}

		return $endval * $afn;
	}
	
	/**
	 * Inverse Incomplete (Lower) Gamma Function
	 * 
	 * Returns the inverse of the lower gamma function of a number.
	 * 
	 * @param float $s Upper bound of integration
	 * @param float $x Result of the lower gamma function.
	 * @return float The argument to the lower gamma function that would return $x
	 * @static
	 */
	public static function ilowerGamma($s, $x) {
		$precision = 8;
		$guess = array(5, 20);
		$IT_MAX = 1000;
		$i = 1;

		while (round($guess[$i], $precision) != round($guess[$i - 1], $precision) && $i < $IT_MAX) {
			$f = self::lowerGamma($s, $guess[$i]);
			$f2 = self::lowerGamma($s, $guess[$i - 1]);
			$fp = ($f - $f2) / ($guess[$i] - $guess[$i - 1]);

			$guess[] = $guess[$i - 1] - $f / $fp;
			$i++;
		}

		return $guess[$i - 1];
	}
	
	/**
	 * Incomplete (Upper) Gamma Function
	 * 
	 * Returns the upper gamma function of a number.
	 * 
	 * @param float $s Lower bound of integration
	 * @param float $x Argument to the upper gamma function
	 * @return float The upper gamma of $x
	 * @static
	 */
	public static function upperGamma($s, $x) {
		return self::gamma($s) - self::lowerGamma($s, $x);
	}

	/**
	 * Beta Function
	 * 
	 * Returns the beta function of a pair of numbers.
	 * 
	 * @param float $a The alpha parameter
	 * @param float $b The beta parameter
	 * @return float The beta of $a and $b
	 * @static
	 */
	public static function beta($a, $b) {
		return self::gamma($a)*self::gamma($b) / self::gamma($a + $b);
	}
	
	/**
	 * Calculates the regularized incomplete beta function.
	 * 
	 * Implements the jStat method of calculating the incomplete beta.
	 * 
	 * @param float $a The alpha parameter
	 * @param float $b The beta parameter
	 * @param float $x Upper bound of integration
	 * @return float The incomplete beta of $a and $b, up to $x
	 * @static
	 */
	public static function regularizedIncompleteBeta($a, $b, $x) {
		//Again, thanks to jStat.
	
		// Factors in front of the continued fraction.
		if ($x < 0 || $x > 1) return false;
		if ($x == 0 || $x == 1) $bt = 0;
		else $bt = exp(self::gammaln($a + $b) - self::gammaln($a) - self::gammaln($b) + $a * log($x) + $b * log(1 - $x));

		if( $x < ( $a + 1 ) / ( $a + $b + 2 ) )
			// Use continued fraction directly.
			return $bt * self::betacf($x, $a, $b) / $a;
		else
			// else use continued fraction after making the symmetry transformation.
			return 1 - $bt * self::betacf(1 - $x, $b, $a) / $b;
	}

	// Evaluates the continued fraction for incomplete beta function by modified Lentz's method.
	// Is a factored-out portion of the implementation of the regularizedIncompleteBeta
	private static function betacf($x, $a, $b) {
		$fpmin = 1e-30;

		// These q's will be used in factors that occur in the coefficients
		$qab = $a + $b;
		$qap = $a + 1;
		$qam = $a - 1;
		$c = 1;
		$d = 1 - $qab * $x / $qap;
		if(abs($d) < $fpmin ) $d = $fpmin;
		$d = 1 / $d;
		$h = $d;
		for ($m = 1; $m <= 100; $m++) {
			$m2 = 2 * $m;
			$aa = $m * ($b - $m) * $x / (($qam + $m2) * ($a + $m2));

			// One step (the even one) of the recurrence
			$d = 1 + $aa * $d;
			if(abs($d) < $fpmin ) $d = $fpmin;
			$c = 1 + $aa / $c;
			if(abs($c) < $fpmin ) $c = $fpmin;
			$d = 1 / $d;
			$h *= $d * $c;
			$aa = -($a + $m) * ($qab + $m) * $x / (($a + $m2) * ($qap + $m2));

			// Next step of the recurrence (the odd one)
			$d = 1 + $aa * $d;
			if(abs($d) < $fpmin) $d = $fpmin;
			$c = 1 + $aa / $c;
			if(abs($c) < $fpmin) $c = $fpmin;
			$d = 1 / $d;
			$del = $d * $c;
			$h *= $del;

			if(abs($del - 1.0) < 3e-7 ) break;
		}
		return $h;
	}
	
	/**
	 * Inverse Regularized Incomplete Beta Function
	 *
	 * The inverse of the regularized incomplete beta function.  
	 *
	 * @param float $a The alpha parameter
	 * @param float $b The beta parameter
	 * @param float $x The incomplete beta of $a and $b, up to the upper bound of integration
	 * @return float Upper bound of integration
	 * @static
	 */
	public static function iregularizedIncompleteBeta($a, $b, $x) {
		//jStat is my hero.
		$EPS = 1e-8;
		$a1 = $a - 1;
		$b1 = $b - 1;

		$lna = $lnb = $pp = $t = $u = $err = $return = $al = $h = $w = $afac = 0;

		if( $x <= 0 ) return 0;
		if( $x >= 1 ) return 1;

		if( $a >= 1 && $b >= 1 ) {
			$pp = ($x < 0.5) ? $x : 1 - $x;
			$t = pow(-2 * log($pp), 0.5);
			$return = (2.30753 + $t * 0.27061) / (1 + $t * (0.99229 + $t * 0.04481)) - $t;
			
			if( $x < 0.5 ) $return = -$return;
			
			$al = ($return * $return - 3) / 6;
			$h = 2 / (1 / (2 * $a - 1) + 1 / (2 * $b - 1));
			$w = ($return * pow($al + $h, 0.5) / $h) - (1 / (2 * $b - 1) - 1 / (2 * $a - 1)) * ($al + 5 / 6 - 2 / (3 * $h));
			$return = $a / ($a + $b * exp(2 * $w));
		} 
		else {
			$lna = log($a / ($a + $b));
			$lnb = log($b / ($a + $b));
			$t = exp($a * $lna) / $a;
			$u = exp($b * $lnb) / $b;
			$w = $t + $u;
			if($x < $t / $w) $return = pow($a * $w * $x, 1 / $a);
			else $return = 1 - pow($b * $w * (1 - $x), 1 / $b);
		}

		$afac = -self::gammaln($a) - self::gammaln($b) + self::gammaln($a + $b);
		for($j = 0; $j < 10; $j++) {
			if($return === 0 || $return === 1) return $return;
			
			$err = self::regularizedIncompleteBeta($a, $b, $return) - $x;
			$t = exp($a1 * log($return) + $b1 * log(1 - $return) + $afac);
			$u = $err / $t;
			$return -= ($t = $u / (1 - 0.5 * min(1, $u * ($a1 / $return - $b1 / (1 - $return)))));
			
			if($return <= 0) $return = 0.5 * ($return + $t);
			if($return >= 1) $return = 0.5 * ($return + $t + 1);
			if(abs($t) < $EPS * $return && $j > 0) break;
		}
		return $return;
	}

	/**
	 * Permutation Function
	 * 
	 * Returns the number of ways of choosing $r objects from a collection
	 * of $n objects, where the order of selection matters.
	 * 
	 * @param int $n The size of the collection
	 * @param int $r The size of the selection
	 * @return int $n pick $r
	 * @static
	 */
	public static function permutations($n, $r) {
		return self::factorial($n)/self::factorial($n - $r);
	}

	/**
	 * Combination Function
	 * 
	 * Returns the number of ways of choosing $r objects from a collection
	 * of $n objects, where the order of selection does not matter.
	 * 
	 * @param int $n The size of the collection
	 * @param int $r The size of the selection
	 * @return int $n choose $r
	 * @static
	 */
	public static function combinations($n, $r) {
		return self::permutations($n, $r)/self::factorial($r);
	}
}
