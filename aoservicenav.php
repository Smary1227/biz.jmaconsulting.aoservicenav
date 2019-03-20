<?php
define('SERVICENAV', 15);
define('IS_REGISTERED', 'custom_11');

require_once 'aoservicenav.civix.php';

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function aoservicenav_civicrm_config(&$config) {
  _aoservicenav_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function aoservicenav_civicrm_xmlMenu(&$files) {
  _aoservicenav_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function aoservicenav_civicrm_install() {
  _aoservicenav_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function aoservicenav_civicrm_uninstall() {
  _aoservicenav_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function aoservicenav_civicrm_enable() {
  _aoservicenav_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function aoservicenav_civicrm_disable() {
  _aoservicenav_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function aoservicenav_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _aoservicenav_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function aoservicenav_civicrm_managed(&$entities) {
  _aoservicenav_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function aoservicenav_civicrm_caseTypes(&$caseTypes) {
  _aoservicenav_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function aoservicenav_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _aoservicenav_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implementation of hook_civicrm_buildForm
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function aoservicenav_civicrm_buildForm($formName, &$form) {
  if ($formName == "CRM_Profile_Form_Edit" && $form->getVar('_gid') == SERVICENAV) {
    CRM_Core_Region::instance('page-body')->add(array(
      'template' => 'CRM/ServiceNav.tpl',
    ));

    $submittedValues = [];
    $fields = [
      'child_first_name' => ts("Child's First Name"),
      'child_last_name' => ts("Child's Last Name"),
      'child_birth_date' => ts("Child's Birth Date"),
      'child_gender' => ts("Gender of Child with ASD?"),
      'child_is_registered' => ts("Is your child registered with the Ontario Autism Program?"),
    ];
    for ($rowNumber = 1; $rowNumber <= 6; $rowNumber++) {
      if (!empty($_POST['child_first_name']) && !empty($_POST['child_first_name'][$rowNumber])) {
        $submittedValues[] = $rowNumber;
      }
      foreach ($fields as $fieldName => $fieldLabel) {
        $name = sprintf("%s[%d]", $fieldName, $rowNumber);
        if ($fieldName == "child_birth_date") {
          $form->add('datepicker', $name, $fieldLabel, array(), FALSE, array('time' => FALSE, 'yearRange' => "-100:+0"));
        }
        elseif ($fieldName == "child_gender") {
          $gender = CRM_Core_OptionGroup::values('gender');
          $form->addSelect($name, array('label' => $fieldLabel, 'allowClear' => TRUE, 'options' => $gender));
        }
        elseif ($fieldName == "child_is_registered") {
          $form->addYesNo($name, $fieldLabel, TRUE);
        }
        else {
          $form->add('text', $name, $fieldLabel, NULL);
        }
      }
    }
    $form->assign('childSubmitted', json_encode($submittedValues));
  }
}

/**
 * Implementation of hook_civicrm_postProcess
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postProcess
 */
function aoservicenav_civicrm_postProcess($formName, &$form) {
  if ($formName == "CRM_Profile_Form_Edit" && $form->getVar('_gid') == SERVICENAV) {
    $params = $form->_submitValues;
    if (!empty($params['child_first_name'])) {
      foreach ($params['child_first_name'] as $key => $value) {
        if ($value) {
          $contactParams[$key]['first_name'] = $value;
        }
      }
    }
    if (!empty($params['child_last_name'])) {
      foreach ($params['child_last_name'] as $key => $value) {
        if ($value) {
          $contactParams[$key]['last_name'] = $value;
        }
      }
    }
    if (!empty($params['child_birth_date'])) {
      foreach ($params['child_birth_date'] as $key => $value) {
        if ($value) {
          $contactParams[$key]['birth_date'] = $value;
        }
      }
    }
    if (!empty($params['child_gender'])) {
      foreach ($params['child_gender'] as $key => $value) {
        if ($value) {
          $contactParams[$key]['gender'] = $value;
        }
      }
    }
    if (!empty($params['child_is_registered'])) {
      foreach ($params['child_is_registered'] as $key => $value) {
        if ($value) {
          $contactParams[$key][IS_REGISTERED] = $value;
        }
      }
    }
    $childRel = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', 'Child of', 'id', 'name_a_b');
    foreach ($contactParams as $child) {
      $dedupeParams = CRM_Dedupe_Finder::formatParams($child, 'Individual');
      $dedupeParams['check_permission'] = FALSE;
      $rule = CRM_Core_DAO::singleValueQuery("SELECT max(id) FROM civicrm_dedupe_rule_group WHERE name = 'Child_Rule_8'");
      $dupes = CRM_Dedupe_Finder::dupesByParams($dedupeParams, 'Individual', NULL, array(), $rule);
      $cid = CRM_Utils_Array::value('0', $dupes, NULL);
      $child['contact_type'] = 'Individual';
      if ($cid) {
        $child['contact_id'] = $cid;
      }
      $childId = civicrm_api3('Contact', 'create', $child)['id'];
      createRelationship($childId, $form->getVar('_id'), $childRel);
    }
  }
}

function createRelationship($cida, $cidb, $type) {
  $relationshipParams = array(
    "contact_id_a" => $cida,
    "contact_id_b" => $cidb,
    "relationship_type_id" => $type,
  );
  civicrm_api3("Relationship", "create", $relationshipParams);
}
