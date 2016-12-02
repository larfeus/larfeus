<?php

namespace Larfeus\SharedMemory;

/**
 * Shared memory block
 *
 * Usage:
 *
 * 	1) 	$block = new Block(666);
 * 		$block->write('test string');
 *
 * 	2)	$block = new Block('/tmp/sm.sock');
 * 		$block->read();
 *
 * @author larfeus
 */
class Block {

	/**
	 * @var string
	 */
	protected $file = __FILE__;

	/**
	 * @var int
	 */
	public $id;

	/**
	 * Constructor
	 *
	 * @param string|int $param
	 */
	public function __construct($param = null) {

		if (is_int($param)) {
			$this->id = $param;
		} else {
			$this->id = ftok(is_string($param) ? $param : $this->file, 'c');
		}
	}

	/**
	 * Write data to shared memory block
	 *
	 * @param mixed $data
	 */
	public function write($data) {

		$data = serialize($data);
		$size = strlen($data);

		if ($shmid = shmop_open($this->id, 'c', 0644, $size)) {

			shmop_write($shmid, $data, 0);
			shmop_close($shmid);
		}
	}

	/**
	 * Read data from shared memory block
	 *
	 * @return mixed
	 */
	public function read() {

		$data = null;

		if ($shmid = shmop_open($this->id, 'a', 0, 0)) {

			$size = shmop_size($shmid);
			$data = shmop_read($shmid, 0, $size);
			shmop_close($shmid);

			$data = unserialize($data);
		}

		return $data;
	}

	/**
	 * Mark shared memory block for delete
	 */
	public function delete() {

		if ($shmid = shmop_open($this->id, 'w', 0, 0)) {

			shmop_delete($shmid);
		}
	}
}