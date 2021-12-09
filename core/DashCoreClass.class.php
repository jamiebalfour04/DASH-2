<?php

/*
* Basic extendable class that all other classes should inherit
*/
class DashCoreClass
{
    //Basic get method
    public function __get($property)
    {
        if (property_exists($this, $property))
            return $this->$property;
    }

    //Basic set method
    public function __set($property, $value)
    {
        if (property_exists($this, $property))
            $this->$property = $value;
    }
}
