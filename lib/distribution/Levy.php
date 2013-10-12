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
 * Levy class
 * 
 * Represents the Levy distribution, one of the stable distributions.  Note
 * that as a stable distribution with a maxed skewness parameter, the support
 * of the distribution (acceptable values for x) is [mu, INF).
 * 
 * For more information, see: http://en.wikipedia.org/wiki/L%C3%A9vy_distribution
 */
class Levy extends ProbabilityDistribution {
	private $mu;
	private $c;
	
	/**
	 * Constructor function
	 * 
	 * @param float $mu The location parameter
	 * @param float $c The scale parameter
	 */
	public function __construct($mu = 0.0, $c = 1.0) {
		$this->mu = $mu;
		$this->c = $c;
	}
	
	/**
	 * Returns a random float between $mu and $mu plus $c
	 * 
	 * @return float The random variate.
	 */
	public function rvs() {
		return self::getRvs($this->mu, $this->c);
	}
	
	/**
	 * Returns the probability distribution function
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function pdf($x) {
		return self::getPdf($x, $this->mu, $this->c);
	}
	
	/**
	 * Returns the cumulative distribution function, the probability of getting the test value or something below it
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function cdf($x) {
		return self::getCdf($x, $this->mu, $this->c);
	}
	
	/**
	 * Returns the survival function, the probability of getting the test value or something above it
	 * 
	 * @param float $x The test value
	 * @return float The probability
	 */
	public function sf($x) {
		return self::getSf($x, $this->mu, $this->c);
	}
	
	/**
	 * Returns the percent-point function, the inverse of the cdf
	 * 
	 * @param float $x The test value
	 * @return float The value that gives a cdf of $x
	 */
	public function ppf($x) {
		return self::getPpf($x, $this->mu, $this->c);
	}
	
	/**
	 * Returns the inverse survival function, the inverse of the sf
	 * 
	 * @param float $x The test value
	 * @return float The value that gives an sf of $x
	 */
	public function isf($x) {
		return self::getIsf($x, $this->mu, $this->c);
	}
	
	/**
	 * Returns the moments of the distribution
	 * 
	 * @param string $moments Which moments to compute. m for mean, v for gamma, s for skew, k for kurtosis.  Default 'mv'
	 * @return type array A dictionary containing the first four moments of the distribution
	 */
	public function stats($moments = 'mv') {
		return self::getStats($moments, $this->mu, $this->c);
	}
	
	/**
	 * Returns a Cauchy-distributed random float
	 * 
	 * @param float $mu The location parameter
	 * @param float $c The scale parameter
	 * @return float The random variate.
	 * @static
	 * @todo Implement this
	 */
	public static function getRvs($mu = 0.0, $c = 1.0) {
		//Using the algorithm from: http://www.signallake.com/publications/StablePubs/SimulatingStableRandomVariables.pdf
		$u = self::randFloat();
		$v = self::randFloat();
		
		return $c * pow(sqrt(-2 * log($u)) * cos(2 * M_PI * $v), -2) + $mu;
	}
	
	/**
	 * Returns the probability distribution function
	 * 
	 * @param float $x The test value
	 * @param float $mu The location parameter
	 * @param float $c The scale parameter
	 * @return float The probability
	 * @static
	 */
	public static function getPdf($x, $mu = 0.0, $c = 1.0) {
		return sqrt($c / (2 * M_PI)) * exp(-0.5 * $c / ($x - $mu)) / pow($x - $mu, 3/2);
	}
	
	/**
	 * Returns the cumulative distribution function, the probability of getting the test value or something below it
	 * 
	 * @param float $x The test value
	 * @param float $mu The location parameter
	 * @param float $c The scale parameter
	 * @return float The probability
	 * @static
	 */
	public static function getCdf($x, $mu = 0.0, $c = 1.0) {
		return 1 - \mcordingley\phpstats\Stats::erf(sqrt($c / (2 * ($x - $mu))));
	}
	
	/**
	 * Returns the survival function, the probability of getting the test value or something above it
	 * 
	 * @param float $x The test value
	 * @param float $mu The location parameter
	 * @param float $c The scale parameter
	 * @return float The probability
	 * @static
	 */
	public static function getSf($x, $mu = 0.0, $c = 1.0) {
		return 1.0 - self::getCdf($x, $mu, $c);
	}
	
	/**
	 * Returns the percent-point function, the inverse of the cdf
	 * 
	 * @param float $x The test value
	 * @param float $mu The location parameter
	 * @param float $c The scale parameter
	 * @return float The value that gives a cdf of $x
	 * @static
	 */
	public static function getPpf($x, $mu = 0.0, $c = 1.0) {
		return $c / (2 * pow(\mcordingley\phpstats\Stats::ierf(1 - $x), 2)) + $mu;
	}
	
	/**
	 * Returns the inverse survival function, the inverse of the sf
	 * 
	 * @param float $x The test value
	 * @param float $mu The location parameter
	 * @param float $c The scale parameter
	 * @return float The value that gives an sf of $x
	 * @static
	 */
	public static function getIsf($x, $mu = 0.0, $c = 1.0) {
		return self::getPpf(1.0 - $x, $mu, $c);
	}
	
	/**
	 * Returns the moments of the distribution
	 * 
	 * @param string $moments Which moments to compute. m for mean, v for gamma, s for skew, k for kurtosis.  Default 'mv'
	 * @param float $mu The location parameter
	 * @param float $c The scale parameter
	 * @return type array A dictionary containing the first four moments of the distribution
	 * @static
	 */
	public static function getStats($moments = 'mv', $mu = 0.0, $c = 1.0) {
		$return = array();
		
		if (strpos($moments, 'm') !== FALSE) $return['mean'] = INF;
		if (strpos($moments, 'v') !== FALSE) $return['variance'] = INF;
		if (strpos($moments, 's') !== FALSE) $return['skew'] = NAN;
		if (strpos($moments, 'k') !== FALSE) $return['kurtosis'] = NAN;
		
		return $return;
	}
}
