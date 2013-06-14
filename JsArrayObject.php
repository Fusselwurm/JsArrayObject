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

	public function reduceRight(Closure $callback, $initialValue = null) {
		$o = new static(array_reverse($this->getArrayCopy(), false));
		return $o->reduce($callback, $initialValue);
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

	/**
	 * @note cannot use forEach, as it's a keyword
	 * @param Closure $callback
	 */
	public function each(Closure $callback) {
		foreach ($this as $key => $member)  {
			$callback($member, $key, $this);
		}
	}

	public function lastIndexOf($member) {
		// note: DO NOT preserve keys when reversing - else, the order is not really reversed in numerically indexed ArrayObjects. strange? yes.
		$o = new static(array_reverse($this->getArrayCopy(), false));
		$idx = $o->indexOf($member);
		if ($idx === -1) {
			return $idx;
		}
		return count($this) - 1 - $idx;

	}
    
    public function shift() {
        $arr = $this->getArrayCopy();
        $res = array_shift($arr);
        $this->exchangeArray($arr);
        return $res;
    }
    
    public function pop() {
        $idx = $this->count() - 1;
        if ($idx === -1) {
            $res = null; 
        } else {
            $res = $this->offsetGet($idx);
            $this->offsetUnset($idx);
        }
        return $res;
    }
    
}
