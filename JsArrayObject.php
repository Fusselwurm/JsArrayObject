<?php
/**
 * @brief array class containing all those wonderful methods ECMAscript5 provides
 */
class JsArrayObject extends ArrayObject {

	public function indexOf($member) {
		$idx = array_search($member, $this->getArrayCopy(), true);
		if ($idx === false) {
			return -1;
		}
		return $idx;
	}

	public function map(Closure $callback) {
		return new static(array_map($callback, $this->getArrayCopy()));
	}

	public function filter(Closure $callback) {
		$result = new static();
		foreach ($this as $key => $member) {
			if ($callback($member, $key, $this)) {
				$result[] = $member;
			}
		}
		return $result;
	}

	public function reduce(Closure $callback, $initialValue = null) {
		$result = $initialValue;
		$first = true;
		foreach ($this as $key => $member) {
			if ($first) {
				$first = false;
				if ($result === null) {
					$result = $member;
					continue;
				}
			}
			$result = $callback($result, $member, $key, $this);
		}
		return $result;
	}

	public function some(Closure $callback) {
		foreach ($this as $key => $member) {
			if ($callback($member, $key, $this)) {
				return true;
			}
		}
		return false;
	}

	public function every(Closure $callback) {
		foreach ($this as $key => $member) {
			if (!$callback($member, $key, $this)) {
				return false;
			}
		}
		return true;
	}

}
