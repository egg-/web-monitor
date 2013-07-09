<?php
/*
 * manage cache
 * #ref
 * https://bitbucket.org/imegg/web-poling
 */
class Cache {

	private $__path_cache = null;
	private $__uselock = true;

	public function Cache($path = null, $uselock = true)
	{
		$this->__path_cache = $path !== null ? $path : '';
		$this->__uselock = $uselock;
	}

	/**
	 * return cache data
	 *
	 * if lifetime == 0 then load unconditionally
	 */
	public function load($filename, $lifetime = 0, $serialize = true, $lock = null)
	{
		return $this->__load($this->__path_cache.$filename, $lifetime, $serialize, $lock);
	}

	/**
	 * save cache data
	 */
	public function save($filename, $data, $serialize = true)
	{
		return $this->__save($this->__path_cache.$filename, $data, $serialize);
	}

	/**
	 * delete cache file
	 */
	public function delete($filename)
	{
		$path = $this->__path_cache.$filename;
		return file_exists($path) and @unlink($path);
	}

	/**
	 * return cache path
	 *
	 * if lifetime == 0 then load unconditionally
	 */
	public function path($filename = null, $lifetime = 0)
	{
		if (null === $filename) {
			return $this->__path_cache;
		}

		$path = $this->__path_cache.$filename;

		return (file_exists($path) && (0 == $lifetime || (time() - $lifetime) < filemtime($path))) ? 
				$path : 
				null;
	}

	/**
	 * return file mtime
	 */
	public function mtime($filename)
	{
		$path = $this->path($filename);
		return filemtime($path);
	}

	/**
	 * make directory
	 */
	private function __mkdir($pathname, $mode)
	{
		is_dir(dirname($pathname)) || $this->__mkdir(dirname($pathname), $mode);
		return is_dir($pathname) || (@mkdir($pathname, $mode) && @chmod($pathname, $mode));
	}

	/**
	 * save file
	 */
	private function __save($path, $data, $serialize = true, $lock = null)
	{
		$dirpath = dirname($path);
		is_dir($dirpath) or $this->__mkdir($dirpath, 0777);

		if ($serialize === true || is_string($data) === false) {
			$data = serialize($data);
		}

		if ( ! $fp = @fopen($path, 'wb')) {
			return null;
		}

		$lock === null and $lock = $this->__uselock;
		$lock and flock($fp, LOCK_EX);
		fwrite($fp, $data);
		$lock and flock($fp, LOCK_UN);
		fclose($fp);
		@chmod($path, 0777);

		return $path;
	}

	/**
	 * load data from file
	 */
	private function __load($path, $lifetime, $serialize = true, $lock = null)
	{
		if (file_exists($path) == false || (0 != $lifetime && (time() - $lifetime) > filemtime($path))) {
			return null;
		}

		if ( ! $fp = @fopen($path, 'rb')) {
			return null;
		}

		$data = '';

		$lock === null and $lock = $this->__uselock;
		$lock and flock($fp, LOCK_SH);
		$size = filesize($path);
		if ($size > 0) {
			$data = fread($fp, $size);
		}
		$lock and flock($fp, LOCK_UN);
		fclose($fp);

		return $serialize ? unserialize($data) : $data;
	}

}
