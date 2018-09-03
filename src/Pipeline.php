<?php

declare(strict_types = 1);
namespace Xutengx\Pipeline;

use Closure;
use Xutengx\Pipeline\Traits\SetPipes;

/**
 * 管道模式
 */
class Pipeline {

	use SetPipes;

	/**
	 * 流程 类名以@分割构造参数
	 * @var array
	 */
	protected $pipes = [];
	/**
	 * 默认闭包
	 * @var null
	 */
	protected $defaultClosure = null;
	/**
	 * 管道执行的方法
	 * @var string
	 */
	protected $func = 'implement';

	/**
	 * 置匿名回调函数
	 * @param Closure $callback
	 * @return void
	 */
	public function setDefaultClosure(Closure $callback): void {
		$this->defaultClosure = $callback;
	}

	/**
	 * 执行
	 * @return void
	 */
	public function then(): void {
		call_user_func(array_reduce(array_reverse($this->pipes), $this->getSlice(), $this->defaultClosure()));
	}

	/**
	 * 管道堆
	 * @return Closure
	 */
	protected function getSlice(): Closure {
		return function($stack, $pipe) {
			return function() use ($stack, $pipe) {
				return $this->getObj($pipe)->{$this->func}($stack);
			};
		};
	}

	/**
	 * new 一个对象(中间件)
	 * eg App\Min\tt@1@44  ---> new App\Min\tt (1, 44)
	 * @param string $class
	 * @return mixed
	 */
	protected function getObj(string $class) {
		$arr           = explode('@', $class);
		$middlewareObj = array_shift($arr);
		return new $middlewareObj(...$arr);
	}

	/**
	 * 匿名回调函数 ( 控制器执行 )
	 * @return Closure
	 */
	protected function defaultClosure(): Closure {
		return function() {
			return call_user_func($this->defaultClosure);
		};
	}

}
