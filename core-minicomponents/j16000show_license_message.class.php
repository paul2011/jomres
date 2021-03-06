<?php

// ################################################################
defined('_JOMRES_INITCHECK') or die('');
// ################################################################

class j16000show_license_message
{
    public function __construct($componentArgs)
    {
        jr_import('minicomponent_registry');
        $MiniComponents = jomres_singleton_abstract::getInstance('mcHandler');
        $this->retVals = '';
        if ($MiniComponents->template_touch) {
            $this->template_touchable = false;

            return;
        }

        if (isset($componentArgs[ 'output_now' ])) {
            $output_now = $componentArgs[ 'output_now' ];
        } else {
            $output_now = true;
        }
        if (defined('LICENSE_EXPIRED_MESSAGE')) {
            $this->retVals = LICENSE_EXPIRED_MESSAGE;
        } else {
            $this->retVals = '';
        }
    }

    // This must be included in every Event/Mini-component
    public function getRetVals()
    {
        return $this->retVals;
    }
}
