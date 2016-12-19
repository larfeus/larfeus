<?php

namespace Larfeus\Lazy;

/**
 * Lazy loading
 *
 * Usage:
 *
 * 		class City {
 * 			use Accessor;
 *
 * 			public function __get_streets() {
 *
 * 				return $mysql->get_list('SELECT `id`, `name` FROM `streets`');
 * 			}
 * 		}
 *
 * 		$city = new City();
 * 		$city->streets;
 *
 * @author larfeus
 */
trait Accessor {

	/**
	 * Magic getter
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {

		if (property_exists($this, $name)) {
			return $this->{$name};
		}

		$methodCache = true;
		$methodName = "__get_{$name}";

		if (!method_exists($this, $methodName)) {
			$methodCache = false;
			$methodName = "___get_{$name}";
		}

		if (method_exists($this, $methodName)) {

			$methodResult = $this->{$methodName}();

			if ($methodCache) {
				$this->{$name} = $methodResult;
			}

			return $methodResult;
		}

		return null;
	}
}