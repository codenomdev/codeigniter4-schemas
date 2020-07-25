<?php

namespace Codenom\Schemas\Structures;

class Mergeable implements \Countable, \IteratorAggregate
{
	/**
	 * Merge two structures together.
	 *
	 * @return $this
	 */
	public function merge(?Mergeable $object): Mergeable
	{
		if (is_null($object)) {
			return $this;
		}

		foreach ($object as $key => $item) {
			if (!isset($this->$key)) {
				$this->$key = $item;
			} elseif ($item instanceof Mergeable && $this->$key instanceof Mergeable) {
				$this->$key->merge($item);
			} elseif (is_array($item) && is_array($this->$key)) {
				$this->$key = array_merge($this->$key, $item);
			} elseif (is_iterable($item) && is_iterable($this->$key)) {
				foreach ($item as $mykey => $value) {
					if ($item instanceof Mergeable && $this->$key instanceof Mergeable) {
						$this->$key->merge($item);
					} else {
						$this->$key->$mykey = $value;
					}
				}
			} else {
				$this->$key = $item;
			}
		}

		return $this;
	}

	/**
	 * Magic getter to prevent exceptions on missing property checks.
	 *
	 * @return mixed
	 */
	public function __get($name)
	{
		if (property_exists($this, $name)) {
			return $this->$name;
		}

		return null;
	}

	/**
	 * Magic checker to match the getter.
	 *
	 * @return bool
	 */
	public function __isset($name): bool
	{
		return property_exists($this, $name);
	}

	/**
	 * Specify count of public properties to satisfy Countable.
	 *
	 * @return int  Number of public properties
	 */
	public function count(): int
	{
		return count(get_object_vars($this));
	}

	/**
	 * Use simple properties array to satisfy Iterable.
	 *
	 * @return ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this);
	}
}

/** End of src/Structures/Mergeable.php */
