<?php

namespace Larfeus\Lazy;

/**
 * Lazy setter
 *
 * Usage:
 *
 * 		class Street {
 * 				use Mutator;
 *
 * 				protected function __set_city($value) {
 *
 * 					$this->city_id = $value->id;
 *
 * 					return $value;
 * 				}
 * 		}
 *
 * @author larfeus
 */
trait Mutator {

	/**
	 * Magic setter
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value) {

		$method = "__set_{$name}";

		if (method_exists($this, $method)) {
			$value = $this->{$method}($value);
		}

		$this->{$name} = $value;
	}
}