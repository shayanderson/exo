<?php
/**
 * Exo // Next-Gen Eco Framework
 *
 * @package Exo
 * @copyright 2015-2021 Shay Anderson <https://www.shayanderson.com>
 * @license MIT License <https://github.com/shayanderson/exo/blob/master/LICENSE>
 * @link <https://github.com/shayanderson/exo>
 */
declare(strict_types=1);

namespace Exo;

/**
 * Timer
 *
 * @author Shay Anderson
 */
class Timer
{
	/**
	 * Start memory
	 *
	 * @var int
	 */
	private $memStart;

	/**
	 * Start peak memory
	 *
	 * @var int
	 */
	private $memStartPeak;

	/**
	 * Stop memory
	 *
	 * @var int
	 */
	private $memStop;

	/**
	 * Stop peak memory
	 *
	 * @var int
	 */
	private $memStopPeak;

	/**
	 * Start time
	 *
	 * @var float
	 */
	private $timeStart;

	/**
	 * Stop time
	 *
	 * @var float
	 */
	private $timeStop;

	/**
	 * Init
	 */
	public function __construct()
	{
		$this->timeStart = microtime(true);
		$this->memStart = memory_get_usage();
		$this->memStartPeak = memory_get_peak_usage();
	}

	/**
	 * Elapsed time getter
	 *
	 * @param int $precision
	 * @return float
	 */
	public function diff(int $precision = 4): float
	{
		return round(( $this->timeStop ?: microtime(true) ) - $this->timeStart, $precision);
	}

	/**
	 * Elapsed time as string getter
	 *
	 * @param int $precision
	 * @return string
	 */
	public function diffString(int $precision = 4): string
	{
		return number_format($this->diff($precision), $precision);
	}

	/**
	 * Memory difference since start getter
	 *
	 * @return int
	 */
	public function memoryDiff(): int
	{
		return $this->memoryUsage()['diff'];
	}

	/**
	 * Memory usage info getter
	 *
	 * @return array [start, current, peak, diff, diffPeak]
	 */
	public function memoryUsage(): array
	{
		$a = [
			'start' => $this->memStart,
			'current' => $this->memStop ?: memory_get_usage(),
			'peak' => $this->memStopPeak ?: memory_get_peak_usage()
		];

		$a['diff'] = $a['current'] - $a['start'];
		$a['diffPeak'] = $a['peak'] - $this->memStartPeak;

		return $a;
	}

	/**
	 * Stop timer
	 *
	 * @return float (elapsed time)
	 */
	public function stop(): float
	{
		$this->timeStop = microtime(true);
		$this->memStop = memory_get_usage();
		$this->memStopPeak = memory_get_peak_usage();
		return $this->diff();
	}
}