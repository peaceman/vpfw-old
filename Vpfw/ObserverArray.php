<?php
class Vpfw_ObserverArray implements ArrayAccess, Iterator, Countable, Vpfw_Interface_Observer {
    private $storage = array();

    // Methoden aus dem ArrayAccess Interface
    public function offsetExists($offset) {
        return isset($this->storage[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->storage[$offset]) ? $this->storage[$offset] : null;
    }

    public function offsetSet($offset, $value) {
        if (false == Vpfw_Interface_Observable instanceof $value) {
            throw new Vpfw_Exception_Logical('Falscher Objekttyp');
        }
        $this->storage[$offset] = $value;
        $value->attachObserver($this);
    }

    public function offsetUnset($offset) {
        $this->container[$offset]->detachObserver($this);
        unset($this->container[$offset]);
    }

    // Methoden aus dem Iterator Interface
    public function rewind() {
        return reset($this->storage);
    }

    public function current() {
        return current($this->storage);
    }

    public function key() {
        return key($this->storage);
    }

    public function next() {
        return next($this->storage);
    }

    public function valid() {
        return key($this->storage) !== null;
    }

    // Methode für das Countable Interface
    public function count() {
        return count($this->storage);
    }

    // Methode für das Vpfw_Interface_Observer Interface
    public function observableUpdate(Vpfw_Interface_Observable $observable) {
        $key = array_search($observable, $this->storage, true);
        if (false !== $key) {
            unset($this->storage[key]);
        }
    }
}
