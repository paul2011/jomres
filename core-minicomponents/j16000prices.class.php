<?php

// ################################################################
defined('_JOMRES_INITCHECK') or die('');
// ################################################################

class j16000prices
{
    public function __construct($componentArgs)
    {
        // Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
        $MiniComponents = jomres_singleton_abstract::getInstance('mcHandler');
        if ($MiniComponents->template_touch) {
            $this->template_touchable = false;

            return;
        }
		
		return;
    }


    // This must be included in every Event/Mini-component
    public function getRetVals()
    {
        return null;
    }
}
