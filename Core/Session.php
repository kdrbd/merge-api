<?php

namespace Core;

class Session
{
	public function __construct()
    {
		self::init();
	}

	public static function init()
	{
		if (version_compare(phpversion(), '5.4.0', '<')) {
			if (session_id() == '') {
				session_start();
			}
		} else {
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
		}
	}

	public static function set($key, $val)
	{
		$_SESSION[$key] = $val;
	}

	public static function get($key)
	{
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		} else {
			return false;
		}
	}

	public static function checkSession()
	{
		if (self::get("login") == false) {
			self::destroy();
			echo "<script>window.location.href = '/login.php';</script>";
		}
	}

	public static function checkLogin()
	{
		if (self::get("login") == true) {
			echo "<script>window.location.href = '/dashboard';</script>";
		}
	}

	public static function destroy()
	{
		session_destroy();
		session_unset();
		echo "<script>window.location.href = '/login.php';</script>";
	}
}
