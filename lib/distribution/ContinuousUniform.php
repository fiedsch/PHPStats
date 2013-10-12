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
 
namespace PHPStats\ProbabilityDistribution;

/**
 * ContinuousUniform class
 * 
 * Represents the continuous uniform distribution, a distribution that 
 * represents equiprobable outcomes on a continuous space.
 *
 * For more information, see: http://en.wikipedia.org/wiki/Uniform_distribution_%28continuous%29
 */
class ContinuousUniform extends ProbabilityDistribution {
	private $minimum;
	private $maximum;
	
	/**
	 * Constructor function
	 *
	 * @param float $minimum The minimum value that the distribution can take on
	 * @param float $maximum The maximum value that the distribution can take on
	 */
	public function __construct($minimum = 0.0, $maximum = 1.0) {
		$this->minimum = $minimum;
		$this->maximum = $maximum;
	}
	
	/**
	 * Returns a random float between $minimum and $minimum plus $maximum
	 * 
	 * @return float The random variate.
	 */
	public function rvs() {
		return self::getRvs($this->minimum, $this->maximum);
	}
	
	/**
	 * Returns the probability distribution function
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function pdf($x) {
		return self::getPdf($x, $this->minimum, $this->maximum);
	}
	
	/**
	 * Returns the cumulative distribution function, the probability of getting the test value or something below it
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function cdf($x) {
		return self::getCdf($x, $this->minimum, $this->maximum);
	}
	
	/**
	 * Returns the survival function, the probability of getting the test value or something above it
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function sf($x) {
		return self::getSf($x, $this->minimum, $this->maximum);
	}
	
	/**
	 * Returns the percent-point function, the inverse of the cdf
	 * 
	 * @param float $x The test value
	 * @return float The value that gives a cdf of $x
	 */
	public function ppf($x) {
		return self::getPpf($x, $this->minimum, $this->maximum);
	}
	
	/**
	 * Returns the inverse survival function, the inverse of the sf
	 * 
	 * @param float $x The test value
	 * @return float The value that gives an sf of $x
	 */
	public function isf($x) {
		return self::getIsf($x, $this->minimum, $this->maximum);
	}
	
	/**
	 * Returns the moments of the distribution
	 * 
	 * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
	 * @return type array A dictionary containing the first four moments of the distribution
	 */
	public function stats($moments = 'mv') {
		return self::getStats($moments, $this->minimum, $this->maximum);
	}
	
	/**
	 * Returns a random float between $minimum and $minimum plus $maximum
	 * 
	 * @param float $minimum The minimum parameter. Default 0.0
	 * @param float $maximum The maximum parameter. Default 1.0
	 * @return float The random variate.
	 * @static
	 */
	public static function getRvs($minimum = 0.0, $maximum = 1.0) {
		return self::randFloat()*($maximum-$minimum) + $minimum;
	}
	
	/**
	 * Returns the probability distribution function
	 * 
	 * @param float $x The test value
	 * @param float $minimum The minimum parameter. Default 0.0
	 * @param float $maximum The maximum parameter. Default 1.0
	 * @return float The probability
	 * @static
	 */
	public static function getPdf($x, $minimum = 0.0, $maximum = 1.0) {
		if ($x >= $minimum && $x <= $maximum) return 1.0/($maximum - $minimum);
		else return 0.0;
	}
	
	/**
	 * Returns the cumulative distribution function, the probability of getting the test value or something below it
	 * 
	 * @param float $x The test value
	 * @param float $minimum The minimum parameter. Default 0.0
	 * @param float $maximum The maximum parameter. Default 1.0
	 * @return float The probability
	 * @static
	 */
	public static function getCdf($x, $minimum = 0.0, $maximum = 1.0) {
		if ($x >= $minimum && $x <= $maximum) return ($x - $minimum) / ($maximum - $minimum);
		elseif ($x > $maximum) return 1.0;
		else return 0.0;
	}
	
	/**
	 * Returns the survival function, the probability of getting the test value or something above it
	 * 
	 * @param float $x The test value
	 * @param float $minimum The minimum parameter. Default 0.0
	 * @param float $maximum The maximum parameter. Default 1.0
	 * @return float The probability
	 * @static
	 */
	public static function getSf($x, $minimum = 0.0, $maximum = 1.0) {
		return 1.0 - self::getCdf($x, $minimum, $maximum);
	}
	
	/**
	 * Returns the percent-point function, the inverse of the cdf
	 * 
	 * @param float $x The test value
	 * @param float $minimum The minimum parameter. Default 0.0
	 * @param float $maximum The maximum parameter. Default 1.0
	 * @return float The value that gives a cdf of $x
	 * @static
	 */
	public static function getPpf($x, $minimum = 0.0, $maximum = 1.0) {
		return $minimum + $x*($maximum - $minimum);
	}
	
	/**
	 * Returns the inverse survival function, the inverse of the sf
	 * 
	 * @param float $x The test value
	 * @param float $minimum The minimum parameter. Default 0.0
	 * @param float $maximum The maximum parameter. Default 1.0
	 * @return float The value that gives an sf of $x
	 * @static
	 */
	public static function getIsf($x, $minimum = 0.0, $maximum = 1.0) {
		return self::getPpf(1.0 - $x, $minimum, $maximum);
	}
	
	/**
	 * Returns the moments of the distribution
	 * 
	 * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
	 * @param float $minimum The minimum parameter. Default 0.0
	 * @param float $maximum The maximum parameter. Default 1.0
	 * @return type array A dictionary containing the first four moments of the distribution
	 * @static
	 */
	public static function getStats($moments = 'mv', $minimum = 0.0, $maximum = 1.0) {
		$return = array();
		
		if (strpos($moments, 'm') !== FALSE) $return['mean'] = 0.5*($maximum + $minimum);
		if (strpos($moments, 'v') !== FALSE) $return['variance'] = (1.0/12)*pow(($maximum - $minimum), 2);
		if (strpos($moments, 's') !== FALSE) $return['skew'] = 0;
		if (strpos($moments, 'k') !== FALSE) $return['kurtosis'] = -1.2;
		
		return $return;
	}
}
