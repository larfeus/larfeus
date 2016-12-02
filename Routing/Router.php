<?php

namespace Larfeus\Routing;

/**
 * Simple url router
 *
 * @author larfeus
 */
class Router {

	/**
	 * @var array
	 */
	public $routes = array();

	/**
	 * @var array
	 */
	public $handlers = array();

	/**
	 * Constructor
	 *
	 * @param array $map
	 */
	public function __construct(array $map = array()) {

		$this->registerRoutemap($map);
	}

	/**
	 * Create route on GET method
	 *
	 * @param string $pattern
	 * @param \Closure|array|string $action
	 */
	public function get($pattern, $action) {

		$this->registerRoute(['GET'], $pattern, $action);
	}

	/**
	 * Create route on POST method
	 *
	 * @param string $pattern
	 * @param \Closure|array|string $action
	 */
	public function post($pattern, $action) {

		$this->registerRoute(['POST'], $pattern, $action);
	}

	/**
	 * Create route on any methods
	 *
	 * @param string $pattern
	 * @param \Closure|array|string $action
	 */
	public function request($pattern, $action) {

		$this->registerRoute(['GET','POST'], $pattern, $action);
	}

	/**
	 * Register route map
	 *
	 * @param array $map
	 */
	public function registerRoutemap(array $map) {

		foreach ($map as $pattern => $action) {
			$this->request($pattern, $action);
		}
	}

	/**
	 * Register new route
	 *
	 * @param array $methods
	 * @param string $pattern
	 * @param \Closure|array|string $action
	 */
	public function registerRoute($methods, $pattern, $action) {

		$this->routes[] = [$methods, $pattern, $action];
	}

	/**
	 * Register new action handler
	 *
	 * @param string $typeof
	 * @param callable $callback
	 */
	public function registerHandler($typeof, $callback) {

		$this->handlers[] = [$typeof, $callback];
	}

	/**
	 * Run routes for specified url
	 *
	 * @param string $url
	 */
	public function run($url) {

		$requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

		foreach ($this->routes as $route) {

			list($methods, $pattern, $action) = $route;

			if (!preg_match('/^'.str_replace('/', '\/', $pattern).'/i', $url, $matches)) {
				continue;
			}

			foreach ($matches as $key => $value) {
				if (is_string($key)) {
					$_GET[$key] = $value;
				}
			}

			foreach ($methods as $method) {

				if (strcasecmp($method, $requestMethod) !== 0) {
					continue;
				}

				$type = gettype($action);

				foreach ($this->handlers as $handler) {

					list($typeof, $callback) = $handler;

					if ($typeof == $type) {
						if ($callback() === false) {
							break;
						}
					}
				}
			}
		}
	}
}