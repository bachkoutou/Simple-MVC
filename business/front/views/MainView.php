<?php
class MainView extends CoreView
{
    /**
     * index Action 
     *
     */
    public function alwaysAction()
    {
        $this->addJs('/scripts/jquery-1.4.4.min.js');
        $this->enableJQueryMobile();
    }

    /**
     * Adds the js and css for the jqueryMobile Framework
     *
     */
    private function enableJQueryMobile()
    {
        $this->addCss('/scripts/jquery.mobile-1.0a2/jquery.mobile-1.0a2.min.css');
        $this->addJs('/scripts/jquery.mobile-1.0a2/jquery.mobile-1.0a2.min.js');
    }
}    
