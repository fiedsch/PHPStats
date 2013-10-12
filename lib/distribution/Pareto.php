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
 * Pareto class
 * 
 * Represents the Pareto distribution, also known as the Bradford distribution,
 * which is a power-law distribution.  It is frequently used to model the
 * distribution of wealth, popularity, population, etc.  It is the 80-20 rule.
 * 
 * For more information, see: http://en.wikipedia.org/wiki/Pareto_distribution
 */
class Pareto extends ProbabilityDistribution {
	private $minimum;
	private $alpha;
	
	/**
	 * Constructor function
	 * 
	 * @param float $minimum The minimum value that the distribution can take on
	 * @param float $alpha The shape parameter
	 */
	public function __construct($minimum = 1.0, $alpha = 1.0) {
		$this->minimum = $minimum;
		$this->alpha = $alpha;
	}
	
	/**
	 * Returns a random float between $minimum and $minimum plus $alpha
	 * 
	 * @return float The random variate.
	 */
	public function rvs() {
		return self::getRvs($this->minimum, $this->alpha);
	}
	
	/**
	 * Returns the probability distribution function
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function pdf($x) {
		return self::getPdf($x, $this->minimum, $this->alpha);
	}
	
	/**
	 * Returns the cumulative distribution function, the probability of getting the test value or something below it
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function cdf($x) {
		return self::getCdf($x, $this->minimum, $this->alpha);
	}
	
	/**
	 * Returns the survival function, the probability of getting the test value or something above it
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function sf($x) {
		return self::getSf($x, $this->minimum, $this->alpha);
	}
	
	/**
	 * Returns the percent-point function, the inverse of the cdf
	 * 
	 * @param float $x The test value
	 * @return float The value that gives a cdf of $x
	 */
	public function ppf($x) {
		return self::getPpf($x, $this->minimum, $this->alpha);
	}
	
	/**
	 * Returns the inverse survival function, the inverse of the sf
	 * 
	 * @param float $x The test value
	 * @return float The value that gives an sf of $x
	 */
	public function isf($x) {
		return self::getIsf($x, $this->minimum, $this->alpha);
	}
	
	/**
	 * Returns the moments of the distribution
	 * 
	 * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
	 * @return type array A dictionary containing the first four moments of the distribution
	 */
	public function stats($moments = 'mv') {
		return self::getStats($moments, $this->minimum, $this->alpha);
	}
	
	/**
	 * Returns a random float between $minimum and $minimum plus $alpha
	 * 
	 * @param float $minimum The scale parameter. Default 0.0
	 * @param float $alpha The shape parameter. Default 1.0
	 * @return float The random variate.
	 * @static
	 */
	public static function getRvs($minimum = 1.0, $alpha = 1.0) {
		return $minimum/pow(self::randFloat(), 1/$alpha);
	}
	
	/**
	 * Returns the probability distribution function
	 * 
	 * @param float $x The test value
	 * @param float $minimum The scale parameter. Default 0.0
	 * @param float $alpha The shape parameter. Default 1.0
	 * @return float The probability
	 * @static
	 */
	public static function getPdf($x, $minimum = 1.0, $alpha = 1.0) {
		if ($x >= $minimum) return $alpha * pow($minimum, $alpha)/ pow($x, $alpha + 1);
		else return 0.0;
	}
	
	/**
	 * Returns the cumulative distribution function, the probability of getting the test value or something below it
	 * 
	 * @param float $x The test value
	 * @param float $minimum The scale parameter. Default 0.0
	 * @param float $alpha The shape parameter. Default 1.0
	 * @return float The probability
	 * @static
	 */
	public static function getCdf($x, $minimum = 1.0, $alpha = 1.0) {
		if ($x >= $minimum) return 1 - pow($minimum/$x, $alpha);
		else return 0.0;
	}
	
	/**
	 * Returns the survival function, the probability of getting the test value or something above it
	 * 
	 * @param float $x The test value
	 * @param float $minimum The scale parameter. Default 0.0
	 * @param float $alpha The shape parameter. Default 1.0
	 * @return float The probability
	 * @static
	 */
	public static function getSf($x, $minimum = 1.0, $alpha = 1.0) {
		return 1.0 - self::getCdf($x, $minimum, $alpha);
	}
	
	/**
	 * Returns the percent-point function, the inverse of the cdf
	 * 
	 * @param float $x The test value
	 * @param float $minimum The scale parameter. Default 0.0
	 * @param float $alpha The shape parameter. Default 1.0
	 * @return float The value that gives a cdf of $x
	 * @static
	 */
	public static function getPpf($x, $minimum = 1.0, $alpha = 1.0) {
		return $minimum / pow(1 - $x, 1 / $alpha);
	}
	
	/**
	 * Returns the inverse survival function, the inverse of the sf
	 * 
	 * @param float $x The test value
	 * @param float $minimum The scale parameter. Default 0.0
	 * @param float $alpha The shape parameter. Default 1.0
	 * @return float The value that gives an sf of $x
	 * @static
	 */
	public static function getIsf($x, $minimum = 1.0, $alpha = 1.0) {
		return self::getPpf(1.0 - $x, $minimum, $alpha);
	}
	
	/**
	 * Returns the moments of the distribution
	 * 
	 * @param string $moments Which moments to compute. m for mean, v for variance, s for skew, k for kurtosis.  Default 'mv'
	 * @param float $minimum The scale parameter. Default 0.0
	 * @param float $alpha The shape parameter. Default 1.0
	 * @return type array A dictionary containing the first four moments of the distribution
	 * @static
	 */
	public static function getStats($moments = 'mv', $minimum = 1.0, $alpha = 1.0) {
		$return = array();
		
		if (strpos($moments, 'm') !== FALSE) {
			if ($alpha > 1) $return['mean'] = ($alpha*$minimum)/($alpha - 1);
			else $return['mean'] = NAN;
		}
		if (strpos($moments, 'v') !== FALSE) {
			if ($alpha > 2) $return['variance'] = (pow($minimum, 2)*$alpha)/(pow($alpha - 1, 2)*($alpha - 2));
			else $return['variance'] = NAN;
		}
		if (strpos($moments, 's') !== FALSE) {
			if ($alpha > 3) $return['skew'] = ((2 + 2*$alpha)/($alpha - 3))*sqrt(($alpha - 2)/$alpha);
			else $return['skew'] = NAN;
		}
		if (strpos($moments, 'k') !== FALSE) {
			if ($alpha > 4) $return['kurtosis'] = (6*(pow($alpha, 3) + pow($alpha, 2) - 6*$alpha - 2))/($alpha*($alpha - 3)*($alpha - 4));
			else $return['kurtosis'] = NAN;
		}
		
		return $return;
	}
}
