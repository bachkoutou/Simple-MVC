<?php
class MainView extends CoreView
{
    /**
     * index Action 
     *
     */
    public function alwaysAction()
    {
        $this->addJs('/scripts/jquery-1.4.3.min.js');
        $this->enableJQueryMobile();
    }

    /**
     * Adds the js and css for the jqueryMobile Framework
     *
     */
    private function enableJQueryMobile()
    {
        $this->addCss('/styles/jquerymobile.min.css');
        $this->addJs('/scripts/jquerymobile.min.js');
    }
}    
