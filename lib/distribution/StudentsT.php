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
 
namespace mcordingley\phpstats\distribution;

/**
 * StudentsT class
 * 
 * Represents Student's T distribution, which estimates the mean of a normally
 * distributed population in situations where the sample size is small and
 * population standard deviation unknown.
 * 
 * For more information, see: http://en.wikipedia.org/wiki/Student's_t-distribution
 */
class StudentsT extends ProbabilityDistribution {
	private $df;
	
	/**
	 * Constructor function
	 * 
	 * @param int $df The degrees of freedom
	 */
	public function __construct($df = 1) {
		$this->df = $df;
	}
	
	/**
	 * Returns a random float between $minimum and $minimum plus $maximum
	 * 
	 * @return float The random variate.
	 * @todo Unimplemented
	 */
	public function rvs() {
		return self::getRvs($this->df);
	}
	
	/**
	 * Returns the probability distribution function
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function pdf($x) {
		return self::getPdf($x, $this->df);
	}
	
	/**
	 * Returns the cumulative distribution function, the probability of getting the test value or something below it
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function cdf($x) {
		return self::getCdf($x, $this->df);
	}
	
	/**
	 * Returns the survival function, the probability of getting the test value or something above it
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function sf($x) {
		return self::getSf($x, $this->df);
	}
	
	/**
	 * Returns the percent-point function, the inverse of the cdf
	 * 
	 * @param float $x The test value
	 * @return float The value that gives a cdf of $x
	 */
	public function ppf($x) {
		return self::getPpf($x, $this->df);
	}
	
	/**
	 * Returns the inverse survival function, the inverse of the sf
	 * 
	 * @param float $x The test value
	 * @return float The value that gives an sf of $x
	 */
	public function isf($x) {
		return self::getIsf($x, $this->df);
	}
	
	/**
	 * Returns the moments of the distribution
	 * 
	 * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
	 * @return type array A dictionary containing the first four moments of the distribution
	 */
	public function stats($moments = 'mv') {
		return self::getStats($moments, $this->df);
	}
	
	/**
	 * Returns a random float between $minimum and $minimum plus $maximum
	 * 
	 * @param float $df The degrees of freedeom.  Default 1
	 * @return float The random variate.
	 * @static
	 */
	public static function getRvs($df = 1) {
		$Z = \mcordingley\phpstats\distribution\Normal::getRvs(0, 1);
		$V = \mcordingley\phpstats\distribution\ChiSquare::getRvs($df);
		return $Z / sqrt($V/$df);
	}
	
	/**
	 * Returns the probability distribution function
	 * 
	 * @param float $x The test value
	 * @param float $df The degrees of freedeom.  Default 1
	 * @return float The probability
	 * @static
	 */
	public static function getPdf($x, $df = 1) {
		return \mcordingley\phpstats\Stats::gamma(($df + 1)/ 2) * pow(1 + pow($x, 2)/$df, -($df + 1)/2)/ (sqrt($df * M_PI)*\mcordingley\phpstats\Stats::gamma($df/2));
	}
	
	/**
	 * Returns the cumulative distribution function, the probability of getting the test value or something below it
	 * 
	 * @param float $x The test value
	 * @param float $df The degrees of freedeom.  Default 1
	 * @return float The probability
	 * @static
	 */
	public static function getCdf($x, $df = 1) {
		$return = 1 - .5*\mcordingley\phpstats\Stats::regularizedIncompleteBeta($df/2, 0.5, $df/(pow($x, 2) + $df)); //Valid only for $x > 0
		
		if ($x < 0) return 1 - $return; //...but we can infer < 0 by way of symmetry.
		elseif ($x == 0) return .5; //Can't mirror it for zero, but the mean is here so the CDF is 0.5 at this point.
		else return $return;
	}
	
	/**
	 * Returns the survival function, the probability of getting the test value or something above it
	 * 
	 * @param float $x The test value
	 * @param float $df The degrees of freedeom.  Default 1
	 * @return float The probability
	 * @static
	 */
	public static function getSf($x, $df = 1) {
		return 1.0 - self::getCdf($x, $df);
	}
	
	/**
	 * Returns the percent-point function, the inverse of the cdf
	 * 
	 * @param float $x The test value
	 * @param float $df The degrees of freedeom.  Default 1
	 * @return float The value that gives a cdf of $x
	 * @static
	 */
	public static function getPpf($x, $df = 1) {
		return pow($df/(\mcordingley\phpstats\Stats::iregularizedIncompleteBeta($df/2, 0.5, 2* (1 - $x))) - $df, 0.5);
	}
	
	/**
	 * Returns the inverse survival function, the inverse of the sf
	 * 
	 * @param float $x The test value
	 * @param float $df The degrees of freedeom.  Default 1
	 * @return float The value that gives an sf of $x
	 * @static
	 */
	public static function getIsf($x, $df = 1) {
		return self::getPpf(1.0 - $x, $df);
	}
	
	/**
	 * Returns the moments of the distribution
	 * 
	 * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
	 * @param float $df The degrees of freedeom.  Default 1
	 * @return type array A dictionary containing the first four moments of the distribution
	 * @static
	 */
	public static function getStats($moments = 'mv', $df = 1) {
		$return = array();
		
		if (strpos($moments, 'm') !== FALSE) {
			if ($df > 1) $return['mean'] = 0;
			else $return['mean'] = NAN;
		}
		if (strpos($moments, 'v') !== FALSE) {
			if ($df > 2) $return['variance'] = $df / ($df - 2);
			elseif ($df > 1 && $df <= 2) $return['variance'] = INF;
			else $return['variance'] = NAN;
		}
		if (strpos($moments, 's') !== FALSE) {
			if ($df > 3) $return['skew'] = 0;
			else $return['skew'] = NAN;
		}
		if (strpos($moments, 'k') !== FALSE) {
			if ($df > 4) $return['kurtosis'] = 6/($df - 4);
			else $return['kurtosis'] = NAN;
		}
		
		return $return;
	}
}
