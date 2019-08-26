<?php

namespace foo\example;

class Example1 
{
    private $name;
        
    function __construct($name = 'name') 
    {
        $this->name = $name;
    }
    
    function getName()
    {
        return 'foo.example.Example1.' . $this->name;
    }
}
