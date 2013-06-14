<?php

require_once 'JsArrayObject.php';


class JsArrayObjectTest extends PHPUnit_Framework_TestCase {

	public function testIndexOf() {
		$o = new JsArrayObject(array('a', 'b', 'c'));
		$this->assertEquals(0, $o->indexOf('a'));
		$this->assertEquals(2, $o->indexOf('c'));
		$this->assertEquals(-1, $o->indexOf('x'));
	}

	public function testMap() {
		$o = new JsArrayObject(array('a', 'b', 'c', 'd', 'e'));
		$mapped = $o->map(function ($n) {
			return $n."0";
		});
		$this->assertEquals(array('a0', 'b0', 'c0', 'd0', 'e0'), $mapped->getArrayCopy());
	}

	public function testReduce() {
		$o = new JsArrayObject(array(1, 2, 3, 4, 5));
		$this->assertSame(15, $o->reduce(function ($prev, $cur, $idx, $obj) {
			return $prev + $cur;
		}, 0));
		$this->assertSame(15, $o->reduce(function ($prev, $cur, $idx, $obj) {
			return $prev + $cur;
		}));
		$this->assertSame(10, $o->reduce(function ($prev, $cur, $idx, $obj) {
			return $prev + $idx;
		}, 0));
	}

	public function testReduceRight() {
		$o = new JsArrayObject(array(1, 2, 3, 4, 5));
		$this->assertSame(12, $o->reduceRight(function ($prev, $cur, $idx, $obj) {
			return $cur > 2 ? $prev + $cur : $prev;
		}, 0));
		$this->assertSame(12, $o->reduceRight(function ($prev, $cur, $idx, $obj) {
			return $cur > 2 ? $prev + $cur : $prev;
		}));
	}



	public function testFilter() {
		$o = new JsArrayObject(array('a', 'b', 'c', 'd', 'e'));
		$empty = $o->filter(function ($n, $i, $o) {
			return false;
		});
		$this->assertCount(0, $empty);

		$full = $o->filter(function ($n, $i, $o) {
			return true;
		});
		$this->assertEquals($full, $o);

		$filtered = $o->filter(function ($n, $i, $o) {
			return $i === 1;
		});
		$this->assertEquals($filtered->getArrayCopy(), array('b'));
	}

	public function testSome() {
		$o = new JsArrayObject(array(1, -2, 6, 11, 100));
		$this->assertTrue($o->some(function ($n) {
			return $n > 99;
		}));
		$this->assertTrue($o->some(function ($n) {
			return $n < -1;
		}));
		$this->assertTrue($o->some(function ($n) {
			return $n > 0;
		}));
		$this->assertFalse($o->some(function ($n) {
			return $n < -3;
		}));
	}

	public function testEvery() {
		$o = new JsArrayObject(array(1, -2, 6, 11, 100));
		$this->assertFalse($o->every(function ($n) {
			return $n > 99;
		}));
		$this->assertTrue($o->every(function ($n) {
			return $n > -4;
		}));
	}

	public function testEach() {
		$o = new JsArrayObject(array(1, -2, 6, 11, 100));
		$cnt = new stdClass();
		$cnt->cnt = 0;
		$arr = $o->getArrayCopy();
		assert_options(ASSERT_WARNING, true);
		$o->each(function ($val, $idx, $obj) use ($o, $cnt, &$arr) {
			assert($obj === $o);
			$cnt->cnt++;
			assert($val === array_shift($arr));
		});
		$this->assertEquals(count($o),  $cnt->cnt);
	}

	public function testLastIndexOf() {
		$o = new JsArrayObject(array('a', 'b', 'c', 'd', 'b'));
		$this->assertEquals(4, $o->lastIndexOf('b'));
		$this->assertEquals(0, $o->lastIndexOf('a'));
		$this->assertEquals(3, $o->lastIndexOf('d'));
	}
    
    public function testLastIndexOf_emptyArr() {
        $o = new JsArrayObject(array());
        $this->assertEquals(-1, $o->lastIndexOf('ae'));
    }
    
    public static function provideTestShift() {
        return array(
            array(array(), null, null, null),
            array(array(42, 'xxx'), 42, 'xxx', null),
            );
    }
    
    /**
     * @dataProvider provideTestShift 
     */
    public function testShift($arr, $res1, $res2, $res3) {
        $o = new JsArrayObject($arr);
        $this->assertSame($res1, $o->shift());
        $this->assertSame($res2, $o->shift());
        $this->assertSame($res3, $o->shift());
        $this->assertSame(array(), $o->getArrayCopy());
    }
    
    
    public static function provideTestPop() {
        return array(
            array(array(), null, null, null),
            array(array(42, 'xxx'), 'xxx', 42, null),
            );
    }
        
    /**
     * @dataProvider provideTestPop 
     */
    public function testPop($arr, $res1, $res2, $res3) {
        $o = new JsArrayObject($arr);
        $this->assertSame($res1, $o->pop());
        $this->assertSame($res2, $o->pop());
        $this->assertSame($res3, $o->pop());
        $this->assertSame(array(), $o->getArrayCopy());
    }
}
