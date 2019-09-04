<?php

namespace foo;

class Example1 
{
    private $name;
        
    function __construct($name = 'name') 
    {
        $this->name = $name;
    }
    
    function getName() 
    {
        return 'foo.Example1.' . $this->name;
    }
}
