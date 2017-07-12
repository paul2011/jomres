<?php
/**
 * Core file.
 *
 * @author Vince Wooll <sales@jomres.net>
 *
 * @version Jomres 9.9.6
 *
 * @copyright	2005-2017 Vince Wooll
 * Jomres (tm) PHP, CSS & Javascript files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly
 **/

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
		$loaders_available = false;
		if (function_exists('ioncube_loader_version')) {
			$loaders_available = true;
		}
		
		$output = array();
		
		$output['IONCUBE_WARNING'] = '';
		if (!$loaders_available) { // show the subscriptions available
			$output['IONCUBE_WARNING'] = '<p class="center alert alert-info">Ioncube loaders are not installed on this server, &#9785; you will need to install the Ioncube Loaders first</p>';
		}
		
		$pageoutput = array();
		$tmpl = new patTemplate();
		$tmpl->setRoot(JOMRES_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->readTemplatesFromInput('plugin_manager_licenses_subscriptions.html');
		$output['SUBSCRIPTION_LICENSES'] = $tmpl->getParsedTemplate();
		
		$pageoutput = array();
		$tmpl = new patTemplate();
		$tmpl->setRoot(JOMRES_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->readTemplatesFromInput('plugin_manager_licenses_full.html');
		$output['FULL_LICENSES'] = $tmpl->getParsedTemplate();
		
		$pageoutput = array();
		$pageoutput[ ] = $output;
		$tmpl = new patTemplate();
		$tmpl->setRoot(JOMRES_TEMPLATEPATH_ADMINISTRATOR);
		$tmpl->addRows('pageoutput', $pageoutput);
		$tmpl->readTemplatesFromInput('prices.html');
		$tmpl->displayParsedTemplate();
		return;
    }


    // This must be included in every Event/Mini-component
    public function getRetVals()
    {
        return null;
    }
}