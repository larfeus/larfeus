<?php

namespace Larfeus;

/**
 * Singleton
 *
 * @author larfeus
 */
trait Singleton {

	/**
	 * This instance
	 *
	 * @return static
	 */
	public static function get_instance() {

		static $instance;

		if (null == $instance) {
			$instance = new static();
		}

		return $instance;
	}

	/**
	 * This instance
	 *
	 * @see self::get_instance()
	 *
	 * @return static
	 */
	public static function getInstance (){
		return static::get_instance();
	}

	/**
	 * Constructor
	 */
	protected function __construct() {

	}

	/**
	 * Wakeup magic method
	 */
	protected function __wakeup() {

	}

	/**
	 * Clone magic method
	 */
	protected function __clone() {

	}

	/**
	 * Set state magic method
	 */
	protected function __set_state() {

	}
}