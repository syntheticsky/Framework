<?php

/* 
* Class: Statistics
* 
* Helps to keep statistics of the application.
*/

class Statistics
{
	private static $totalTime = 0;
	private static $sqlTime = 0;
	private static $sqlQueryAmount = 0;
	
	private static $sqlTimer = 0;
	private static $totalTimer = 0;
	
	public static function totalTimerStart()
	{
		self::$totalTimer = self::getTime();
	}

	public static function totalTimerEnd()
	{
		self::$totalTime = number_format((self::getTime() - self::$totalTimer), 5);	
	}
	
	public static function sqlStart()
	{
		self::$sqlTimer = self::getTime();
	}
	
	public static function sqlEnd()
	{
		self::$sqlTime += number_format((self::getTime() - self::$sqlTimer), 5);
		self::$sqlQueryAmount += 1;
	}

	public static function getSqlQueryAmount()
	{
		return self::$sqlQueryAmount;
	}	

	public static function getSqlTime()
	{
		return self::$sqlTime;
	}	
	
	public static function getTotalTime()
	{
		return self::$totalTime;
	}	

	public static function getPhpPercents()
	{
		$phpTime = self::$totalTime - self::$sqlTime;
		
		return number_format((($phpTime * 100) / self::$totalTime), 0);
	}
	
	public static function getSqlPercents()
	{
		return number_format(((self::$sqlTime * 100) / self::$totalTime), 0);		
	}	
	
	private static function getTime()
	{
		list($usec, $sec) = explode(' ', microtime());
		
		return (float)$sec + (float)$usec;
	}
}