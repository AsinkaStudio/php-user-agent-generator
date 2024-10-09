<?php

namespace Asinka;

use Exception;
use RuntimeException;

/**
 * Class UAGenerator
 * @package Asinka
 */
class UAGenerator
{
	/** @var string */
	public const string OS_WINDOWS = 'win';
	/** @var string */
	public const string OS_LINUX = 'lin';
	/** @var string */
	public const string OS_MAC = 'mac';
	/** @var string */
	public const string BROWSER_CHROME = 'chrome';
	/** @var string */
	public const string BROWSER_IEXPLORER = 'iexplorer';
	/** @var string */
	public const string BROWSER_FIREFOX = 'firefox';
	/** @var string */
	public const string BROWSER_SAFARI = 'safari';
	/** @var string */
	public const string BROWSER_OPERA = 'opera';

	/**
	 * @return string[]
	 * @throws Exception
	 */
	private function chooseRandomBrowserAndOS(): array
	{
		$frequencies = [
			34 => [
				89 => [self::BROWSER_CHROME, self::OS_WINDOWS],
				9  => [self::BROWSER_CHROME, self::OS_MAC],
				2  => [self::BROWSER_CHROME, self::OS_LINUX],
			],
			32 => [
				100 => [self::BROWSER_IEXPLORER, self::OS_WINDOWS],
			],
			25 => [
				83 => [self::BROWSER_FIREFOX, self::OS_WINDOWS],
				16 => [self::BROWSER_FIREFOX, self::OS_MAC],
				1  => [self::BROWSER_FIREFOX, self::OS_LINUX],
			],
			7  => [
				95 => [self::BROWSER_SAFARI, self::OS_MAC],
				4  => [self::BROWSER_SAFARI, self::OS_WINDOWS],
				1  => [self::BROWSER_SAFARI, self::OS_LINUX],
			],
			2  => [
				91 => [self::BROWSER_OPERA, self::OS_WINDOWS],
				6  => [self::BROWSER_OPERA, self::OS_LINUX],
				3  => [self::BROWSER_OPERA, self::OS_MAC],
			],
		];
		$rand        = rand(1, 100);
		$sum         = 0;
		foreach ($frequencies as $freq => $osFreqs) {
			$sum += $freq;
			if ($rand <= $sum) {
				$rand = rand(1, 100);
				$sum  = 0;
				foreach ($osFreqs as $freq2 => $choice) {
					$sum += $freq2;
					if ($rand <= $sum) {
						return $choice;
					}
				}
			}
		}
		throw new Exception("Frequencies don't sum to 100.");
	}

	/**
	 * @param array $array
	 * @return mixed
	 */
	private function array_random(array $array): mixed
	{
		return $array[array_rand($array, 1)];
	}

	/*** @return string */
	private function nt_version(): string
	{
		return rand(5, 6) . '.' . rand(0, 1);
	}

	/*** @return string */
	private function ie_version(): string
	{
		return rand(7, 9) . '.0';
	}

	/*** @return string */
	private function trident_version(): string
	{
		return rand(3, 5) . '.' . rand(0, 1);
	}

	/*** @return string */
	private function osx_version(): string
	{
		return "10_" . rand(5, 7) . '_' . rand(0, 9);
	}

	/*** @return string */
	private function chrome_version(): string
	{
		return rand(100, 140) . '.0.' . rand(800, 899) . '.0';
	}

	/*** @return string */
	private function presto_version(): string
	{
		return '2.9.' . rand(160, 190);
	}

	/*** @return string */
	private function presto_version2(): string
	{
		return rand(10, 12) . '.00';
	}

	/**
	 * @param string $arch
	 * @return string
	 */
	private function firefox(string $arch = self::OS_WINDOWS): string
	{
		$ver = $this->array_random([
			'Gecko/' . date('Ymd', rand(strtotime('2010-1-1'), time())) . ' Firefox/' . rand(120, 131) . '.0',
			'Gecko/' . date('Ymd', rand(strtotime('2010-1-1'), time())) . ' Firefox/' . rand(120, 131) . '.0.1',
		]);
		switch ($arch) {
			case self::OS_LINUX:
				return "(X11; Linux {proc}; rv:" . rand(5, 7) . ".0) $ver";
			case self::OS_MAC:
				$osx = $this->osx_version();
				return "(Macintosh; {proc} Mac OS X $osx rv:" . rand(2, 6) . ".0) $ver";
			case self::OS_WINDOWS:
			default:
				$nt = $this->nt_version();
				return "(Windows NT $nt; {lang}; rv:1.9." . rand(0, 2) . ".20) $ver";
		}
	}

	/**
	 * @param string $arch
	 * @return string
	 */
	private function safari(string $arch = self::OS_WINDOWS): string
	{
		$saf = rand(600, 605) . '.' . rand(1, 50) . '.' . rand(1, 7);
		if (rand(0, 1) == 0) {
			$ver = rand(15, 18) . '.' . rand(0, 1);
		} else {
			$ver = rand(15, 18) . '.0.' . rand(1, 5);
		}
		switch ($arch) {
			case self::OS_MAC:
				$osx = $this->osx_version();
				return "(Macintosh; U; {proc} Mac OS X $osx rv:" . rand(2, 6) . ".0; {lang}) AppleWebKit/$saf (KHTML, like Gecko) Version/$ver Safari/$saf";
			case self::OS_WINDOWS:
			default:
				$nt = $this->nt_version();
				return "(Windows; U; Windows NT $nt) AppleWebKit/$saf (KHTML, like Gecko) Version/$ver Safari/$saf";
		}
	}

	/*** @return string */
	private function iexplorer(): string
	{
		$nt      = $this->nt_version();
		$ie      = $this->ie_version();
		$trident = $this->trident_version();
		return "(compatible; MSIE $ie; Windows NT $nt; Trident/$trident)";
	}

	/**
	 * @param string $arch
	 * @return string
	 */
	private function opera(string $arch = self::OS_WINDOWS): string
	{
		$presto  = $this->presto_version();
		$version = $this->presto_version2();
		switch ($arch) {
			case self::OS_LINUX:
				return "(X11; Linux {proc}; U; {lang}) Presto/$presto Version/$version";
			case self::OS_WINDOWS:
			default:
				$nt = $this->nt_version();
				return "(Windows NT $nt; U; {lang}) Presto/$presto Version/$version";
		}
	}

	/**
	 * @param string $arch
	 * @return string
	 */
	private function chrome(string $arch = self::OS_WINDOWS): string
	{
		$saf    = rand(531, 536) . rand(0, 2);
		$chrome = $this->chrome_version();
		switch ($arch) {
			case self::OS_LINUX:
				return "(X11; Linux {proc}) AppleWebKit/$saf (KHTML, like Gecko) Chrome/$chrome Safari/$saf";
			case self::OS_MAC:
				$osx = $this->osx_version();
				return "(Macintosh; U; {proc} Mac OS X $osx) AppleWebKit/$saf (KHTML, like Gecko) Chrome/$chrome Safari/$saf";
			case self::OS_WINDOWS:
			default:
				$nt = $this->nt_version();
				return "(Windows NT $nt) AppleWebKit/$saf (KHTML, like Gecko) Chrome/$chrome Safari/$saf";
		}
	}

	/**
	 * Main function which will choose random browser
	 * @param string|NULL $browser
	 * @param string|NULL $os
	 * @param array       $lang languages to choose from
	 * @return string       user agent
	 * @throws Exception
	 */
	public function random_agent(?string $browser = NULL, ?string $os = NULL, array $lang = ['en-US']): string
	{
		[$genBrowser, $genOs] = $this->chooseRandomBrowserAndOs();
		$browser = $browser ?? $genBrowser;
		$os      = $os ?? $genOs;
		$proc    = [
			self::OS_LINUX   => ['i686', 'x86_64'],
			self::OS_MAC     => ['Intel', 'PPC', 'U; Intel', 'U; PPC'],
			self::OS_WINDOWS => ['foo'],
		];
		$ua      = match ($browser) {
			self::BROWSER_FIREFOX => "Mozilla/5.0 " . $this->firefox($os),
			self::BROWSER_SAFARI => "Mozilla/5.0 " . $this->safari($os),
			self::BROWSER_IEXPLORER => "Mozilla/5.0 " . $this->iexplorer($os),
			self::BROWSER_OPERA => "Opera/" . rand(8, 9) . '.' . rand(10, 99) . ' ' . $this->opera($os),
			self::BROWSER_CHROME => 'Mozilla/5.0 ' . $this->chrome($os),
			default => throw new RuntimeException('Unknown browser: ' . $browser),
		};
		return str_replace(['{proc}', '{lang}'], [$this->array_random($proc[$os]), $this->array_random($lang)], $ua);
	}
}