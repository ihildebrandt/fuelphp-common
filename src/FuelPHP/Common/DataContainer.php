<?php
/**
 * Part of the FuelPHP framework.
 *
 * @package    FuelPHP\Foundation
 * @version    2.0
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 */

namespace FuelPHP\Common;

use ArrayAccess;

/**
 * Generic data container
 *
 * @package  FuelPHP\Common
 *
 * @since  2.0.0
 */
class DataContainer implements ArrayAccess
{
	/**
	 * @var  array
	 *
	 * @since  2.0.0
	 */
	protected $data;

	/**
	 * @var  bool
	 *
	 * @since  2.0.0
	 */
	protected $readOnly = false;

	/**
	 * Constructor
	 *
	 * @param  array  $data
	 *
	 * @since  2.0.0
	 */
	public function __construct(array $data = null, $readOnly = false)
	{
		$this->data = $data ?: array();
		$this->readOnly = $readOnly;
	}


	/**
	 * Replace the container's data.
	 *
	 * @param   array  $data  new data
	 * @return  $this
	 */
	public function setContents(array $data)
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * Get the container's data
	 *
	 * @return  array  container's data
	 */
	public function getContents()
	{
		return $this->data;
	}

	/**
	 * Set wether the container is read-only.
	 *
	 * @param   boolean  $readOnly  wether it's a read-only container
	 * @return  $this
	 */
	public function setReadOnly($readOnly = true)
	{
		$this->readOnly = (bool) $readOnly;

		return $this;
	}

	/**
	 * Check wether the container is read-only.
	 *
	 * @return  boolean  $readOnly  wether it's a read-only container
	 */
	public function isReadOnly()
	{
		return $this->readOnly;
	}

	/**
	 * Check if a key was set upon this bag's data
	 *
	 * @param   string  $key
	 * @return  bool
	 *
	 * @since  2.0.0
	 */
	public function has($key)
	{
		return array_has($this->data, $key);
	}

	/**
	 * Get a key's value from this bag's data
	 *
	 * @param   string  $key
	 * @param   mixed   $default
	 * @return  mixed
	 *
	 * @since  2.0.0
	 */
	public function get($key, $default = null)
	{
		return array_get($this->data, $key, $default);
	}

	/**
	 * Set a config value
	 *
	 * @param   string  $key
	 * @param   mixed   $value
	 * @throws  \RuntimeException
	 *
	 * @since  2.0.0
	 */
	public function set($key, $value)
	{
		if ($this->readOnly)
		{
			throw new \RuntimeException('Changing values on this Data Container is not allowed.');
		}

		array_set($this->data, $key, $value);

		return $this;
	}

	public function delete($key)
	{
		if ($this->readOnly)
		{
			throw new \RuntimeException('Changing values on this Data Container is not allowed.');
		}

		return array_delete($this->data, $key);
	}

	/**
	 * Get this bag's entire data
	 *
	 * @return  array
	 *
	 * @since  2.0.0
	 */
	public function all()
	{
		return $this->data;
	}

	/**
	 * Allow usage of isset() on the param bag as an array
	 *
	 * @param   string  $key
	 * @return  bool
	 *
	 * @since  2.0.0
	 */
	public function offsetExists($key)
	{
		return $this->has($key);
	}

	/**
	 * Allow fetching values as an array
	 *
	 * @param   string  $key
	 * @return  mixed
	 * @throws  OutOfBoundsException
	 * @since  2.0.0
	 */
	public function offsetGet($key)
	{
		return $this->get($key, function() use ($key)
		{
			throw new \OutOfBoundsException('Access to undefined index: '.$key);
		});
	}

	/**
	 * Disallow setting values like an array
	 *
	 * @param   string  $key
	 * @param   mixed   $value
	 *
	 * @since  2.0.0
	 */
	public function offsetSet($key, $value)
	{
		$this->set($key, $value);
	}

	/**
	 * Disallow unsetting values like an array
	 *
	 * @param   string  $key
	 * @throws  \RuntimeException
	 *
	 * @since  2.0.0
	 */
	public function offsetUnset($key)
	{
		return $this->delete($key);
	}
}