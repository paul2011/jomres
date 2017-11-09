<?php


// ################################################################
defined('_JOMRES_INITCHECK') or die('');
// ################################################################

class j16000deleteregistry
{
    public function __construct()
    {
        // Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return
        $MiniComponents = jomres_singleton_abstract::getInstance('mcHandler');
        if ($MiniComponents->template_touch) {
            $this->template_touchable = false;

            return;
        }

        $registry = jomres_singleton_abstract::getInstance('delete_minicomponent_registry');
        $registry->regenerate_registry();

        if (!using_bootstrap()) {
            if ($registry->error_detected) {
                echo jr_gettext('JOMRES_DELETEREGISTRYREBUILD_FAILURE', 'JOMRES_DELETEREGISTRYREBUILD_FAILURE', false);
            } else {
                echo jr_gettext('JOMRES_DELETEREGISTRYREBUILD_SUCCESS', 'JOMRES_DELETEREGISTRYREBUILD_SUCCESS', false);
            }
            echo '<br />';
            echo jr_gettext('JOMRES_DELETEREGISTRYREBUILD_NOTES', 'JOMRES_DELETEREGISTRYREBUILD_NOTES', false);
        } else {
            if ($registry->error_detected) {
                echo '
				<div class="alert alert-block alert-error">
					<h4 class="alert-heading">' .jr_gettext('JOMRES_DELETEREGISTRYREBUILD_FAILURE', 'JOMRES_DELETEREGISTRYREBUILD_FAILURE', false).'</h4>
					<p>' .jr_gettext('JOMRES_DELETEREGISTRYREBUILD_NOTES', 'JOMRES_DELETEREGISTRYREBUILD_NOTES', false).'</p>
				</div>
				';
            } else {
                echo '
				<div class="alert alert-block alert-success">
					<h4 class="alert-heading">' .jr_gettext('JOMRES_DELETEREGISTRYREBUILD_SUCCESS', 'JOMRES_DELETEREGISTRYREBUILD_SUCCESS', false).'</h4>
					<p>' .jr_gettext('JOMRES_DELETEREGISTRYREBUILD_NOTES', 'JOMRES_DELETEREGISTRYREBUILD_NOTES', false).'</p>
				</div>
				';
            }
        }
    }

    // This must be included in every Event/Mini-component
    public function getRetVals()
    {
        return null;
    }
}
