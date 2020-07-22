<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolLabel extends BxDolFactory implements iBxDolSingleton
{
    protected $_oDb;

    protected $_sForm;
    protected $_sFormDisplaySelect;

    protected function __construct()
    {
        parent::__construct();

        $this->_oDb = new BxDolLabelQuery();

        $this->_sForm= 'sys_labels';
        $this->_sFormDisplaySelect = 'sys_labels_select';
    }

    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    public static function getInstance($oTemplate = false)
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxTemplLabel($oTemplate);

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public function actionSelectLabels()
    {
        return echoJson($this->selectLabels(array(
            'list' => bx_get('value')
        )));
    }

    public function actionLabelsList()
    {
        $sTerm = bx_get('term');

        $aLabels = $this->getLabels(array('type' => 'term', 'term' => $sTerm));

        $aResult = array();
        foreach($aLabels as $aLabel)
            $aResult[] = array (
            	'label' => $aLabel['value'], 
                'value' => $aLabel['value'], 
            );

        echoJson($aResult);
    }

    public function getElementLabels($aInput = array())
    {
        $oForm = BxDolForm::getObjectInstance($this->_sForm, $this->_sFormDisplaySelect);
        if(!$oForm)
            return '';

        $aInput['attrs']['id'] = $this->_aHtmlIds['labels_element'];

        return $oForm->getElementLabels($aInput);
    }

    public function getLabels($aParams = array())
    {
        return $this->_oDb->getLabels($aParams);
    }

    public function onAdd($iId)
    {
        $aLabel = $this->_oDb->getLabels(array('type' => 'id', 'id' => $iId));
        if(empty($aLabel) || !is_array($aLabel))
            return;

        bx_alert('label', 'added', $iId, false, array('label' => $aLabel));
    }

    public function onEdit($iId)
    {
        $aLabel = $this->_oDb->getLabels(array('type' => 'id', 'id' => $iId));
        if(empty($aLabel) || !is_array($aLabel))
            return;

        bx_alert('label', 'edited', $iId, false, array('label' => $aLabel));
    }

    public function onDelete($iId)
    {
        $aLabel = $this->_oDb->getLabels(array('type' => 'id', 'id' => $iId));
        if(empty($aLabel) || !is_array($aLabel))
            return;

        bx_alert('label', 'deleted', $iId, false, array('label' => $aLabel));
    }
}

/** @} */
