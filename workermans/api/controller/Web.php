<?php

/**
 * Description of Web
 *
 * @author Administrator
 */
class WebController {
    
    public function index($arg) {
       $arg['time'] = time();
       return $arg;
    }
    
}
