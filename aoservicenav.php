<?php
define('SERVICENAV', 131);
define('IS_REGISTERED', 'custom_774');
define('DIAGNOSIS', 'custom_773');
define('SERVICE_LEAD_MEMBER', 'custom_28');
define('ENEWS_GROUP', 16);

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

    // Second parent
    $form->add('text', 'second_parent_first_name', ts('Second Parent/Guardian First Name (if applicable)'));
    $form->add('text', 'second_parent_last_name', ts('Second Parent/Guardian Last Name (if applicable)'));
    
    // Enews
    $form->add('checkbox', 'is_enews', ts('Please add me to Autism Ontario’s news and event notification list'));
    $form->assign('isEnews', TRUE);

    $submittedValues = [];
    $fields = [
      'child_diagnosis' => ts("Child's Diagnosis"),
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
          $options = CRM_Core_OptionGroup::values('is_your_child_registered_with_th_20190320085954');
          $form->addSelect($name, array('label' => $fieldLabel, 'allowClear' => FALSE, 'options' => $options));
        }
        elseif ($fieldName == "child_diagnosis") {
          $childasd = CRM_Core_OptionGroup::values('child_s_diagnosis_20190320085837');
          $form->addCheckBox($name, $fieldLabel, array_flip($childasd));
        }
        else {
          $form->add('text', $name, $fieldLabel, NULL);
        }

        // Validations
        if ($rowNumber == 1 && $fieldName != "child_gender") {
          $form->addRule($name, ts('%1 is a required field.', [1 => $fieldLabel]), 'required');
        }
      }
    }
    $form->assign('childSubmitted', json_encode($submittedValues));
  }
}

function aoservicenav_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  if ($formName == "CRM_Case_Form_Case" && ($form->_action & CRM_Core_Action::ADD)) {
    $case = array_flip(CRM_Case_BAO_Case::buildOptions('case_type_id'));
    if ($fields['case_type_id'] == $case["Service Navigation"]) {
      $cid = $fields['client_id'];
      $leadMember = "";
      $parent = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', 'Parent of', 'id', 'name_b_a');
      $relationship = civicrm_api3('Relationship', 'get', [
        'sequential' => 1,
        'return' => ["contact_id_a"],
        'relationship_type_id' => $parent,
        'contact_id_b' => $cid,
      ])['values'];
      foreach ($relationship as $contact) {
        $custom = civicrm_api3('CustomValue', 'get', [
          'sequential' => 1,
          'return' => [SERVICE_LEAD_MEMBER],
          'entity_id' => $contact['contact_id_a'],
        ]);
        if ($custom['count']) {
          $leadMember = $custom['values'][0]['latest'];
        }
      }
      if (!$leadMember) {
        $errors['client_id'] = ts('This client does not have a lead family member. Please create one and try to save the Service Navigation Request.');
      }
    }
  }
}

/**
 * Implementation of hook_civicrm_postProcess
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postProcess
 */
function aoservicenav_civicrm_postProcess($formName, &$form) {
  if ($formName == "CRM_Event_Form_ManageEvent_EventInfo" && ($form->_action & CRM_Core_Action::ADD)) {
    if (CRM_Utils_Array::value('event_type_id', $form->_submitValues) == 7) {
      $result = civicrm_api3('Group', 'create', [
        'title' => $form->_submitValues['title'] . ' Registrants',
        'is_active' => TRUE,
        'group_type' => "Mailing List",
        'visibility' => ["User and User Admin Only"],
      ]);
    }
  }
  if ($formName == "CRM_Profile_Form_Edit" && $form->getVar('_gid') == SERVICENAV) {
    $params = $form->_submitValues;
    $contactID = $form->getVar('_id');

    if (!empty($params['postal_code-Primary'])) {
      list($chapter, $region, $service, $sub) = getServiceChapRegCodes($params['postal_code-Primary']);
      if ($chapter || $region || $service || $sub) {
        $cParams = [
          'chapter' => $chapter,
          'region' => $region,
          'service_region' => $service,
          'service_sub_region' => $sub,
          'contact_id' => $contactID,
        ];
        setServiceChapRegCodes($cParams);
      }
    }

    // Check if contact present in Enews group.
    $groupContact = civicrm_api3('GroupContact', 'get', array(
      'sequential' => 1,
      'group_id' => ENEWS_GROUP,
      'contact_id' => $contactID,
    ));
    if (!$groupContact['count']) {
      $groupContact = civicrm_api3('GroupContact', 'create', array(
        'group_id' => ENEWS_GROUP,
        'contact_id' => $contactID,
      ));
    }

    // Second Parent
    if (!empty($params['second_parent_first_name']) || !empty($params['second_parent_last_name'])) {
      $secondParent = civicrm_api3('Contact', 'create', ['contact_type' => 'Individual', 'first_name' => $params['second_parent_first_name'], 'last_name' => $params['second_parent_last_name']])['id'];
      $spouse = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', 'Spouse of', 'id', 'name_a_b');
      createServiceRelationship($contactID, $secondParent, $spouse);
      if (!empty($cParams)) {
        $cParams['contact_id'] = $spouse;
        setServiceChapRegCodes($cParams);
      }
      foreach ($address as $k => &$val) {
        unset($val['id']);
        $val['contact_id'] = $spouse;
        civicrm_api3('Address', 'create', $address[$k]);
      }
    }

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
    if (!empty($params['child_diagnosis'])) {
      foreach ($params['child_diagnosis'] as $key => $value) {
        if ($value) {
          $contactParams[$key][DIAGNOSIS] = $value;
        }
      }
    }
    $address = civicrm_api3('Address', 'get', ['contact_id' => $contactID])['values'];
    $childRel = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', 'Child of', 'id', 'name_a_b');
    $sibling = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', 'Sibling of', 'id', 'name_a_b');
    foreach ($contactParams as $key => $child) {
      $dedupeParams = CRM_Dedupe_Finder::formatParams($child, 'Individual');
      $dedupeParams['check_permission'] = FALSE;
      $rule = CRM_Core_DAO::singleValueQuery("SELECT max(id) FROM civicrm_dedupe_rule_group WHERE name = 'Child_Rule_10'");
      $dupes = CRM_Dedupe_Finder::dupesByParams($dedupeParams, 'Individual', NULL, array(), $rule);
      $cid = CRM_Utils_Array::value('0', $dupes, NULL);
      $child['contact_type'] = 'Individual';
      $child['contact_sub_type'] = 'Child';
      if ($key == 1) {
        $child[SERVICE_LEAD_MEMBER] = 1;
      }
      if ($cid) {
        $child['contact_id'] = $cid;
      }
      $childId = civicrm_api3('Contact', 'create', $child)['id'];

      $children[$key] = $childId;

      $isFilled = CRM_Core_DAO::executeQuery("SELECT
        entity_id FROM civicrm_value_newsletter_cu_3 WHERE entity_id IN (" . $childId . ") AND (first_contacted_358 IS NOT NULL OR first_contacted_358 != '')")->fetchAll();
      if (empty($isFilled)) {
        civicrm_api3('CustomValue', 'create', [
          'entity_id' => $childId,
          'custom_29' => date('Ymd'),
        ]);
      }
      // Add I am a person with ASD.
      civicrm_api3('CustomValue', 'create', [
        'entity_id' => $childId,
        'custom_7' => 'Une personne TSA',
      ]);

      // Add address for child.
      foreach ($address as $k => &$val) {
        unset($val['id']);
        $val['contact_id'] = $childId;
        $val['master_id'] = $k;
        civicrm_api3('Address', 'create', $address[$k]);
      }

      if (!empty($cParams)) {
        $cParams['contact_id'] = $childId;
        setServiceChapRegCodes($cParams);
      }

      createServiceRelationship($childId, $contactID, $childRel);

      civicrm_api3('Case', 'create', [
        'contact_id' => $contactID,
        'case_type_id' => "service_navigation",
        'details' => "Service navigation for " .  CRM_Contact_BAO_Contact::displayName($contactID),
        'subject' => "Service navigation",
        'start_date' => date('Ymd'),
        'status_id' => "Urgent",
        'creator_id' => CRM_Core_DAO::singleValueQuery("SELECT contact_id FROM civicrm_email WHERE email LIKE 'stefanie@autismontario.com'"),
      ]);
    }
    if (!empty($children[2])) {
      createServiceRelationship($children[1], $children[2], $sibling);
    }
    if (!empty($contact[3])) {
      createServiceRelationship($children[1], $children[3], $sibling);
      createServiceRelationship($children[2], $children[3], $sibling);
    }
    if (!empty($contact[4])) {
      createServiceRelationship($children[1], $children[4], $sibling);
      createServiceRelationship($children[2], $children[4], $sibling);
      createServiceRelationship($children[3], $children[4], $sibling);
    }
    if (!empty($contact[5])) {
      createServiceRelationship($children[1], $children[5], $sibling);
      createServiceRelationship($children[2], $children[5], $sibling);
      createServiceRelationship($children[3], $children[5], $sibling);
      createServiceRelationship($children[4], $children[5], $sibling);
    }
  }
}

function createServiceRelationship($cida, $cidb, $type) {
  $relationshipParams = array(
    "contact_id_a" => $cida,
    "contact_id_b" => $cidb,
    "relationship_type_id" => $type,
  );
  civicrm_api3("Relationship", "create", $relationshipParams);
}

function getServiceChapRegCodes($postalCode) {
  $chapterCode = strtoupper(substr($postalCode, 0, 3));
  $sql = "SELECT service_region, service_sub_region, region, chapter FROM chapters WHERE pcode = '{$chapterCode}'";
  $dao = CRM_Core_DAO::executeQuery($sql);
  while ($dao->fetch()) {
    $region = $dao->region;
    $chapter = $dao->chapter;
    $service = $dao->service_region;
    $sub = $dao->service_sub_region;
  }
  return [$chapter, $region, $service, $sub];
}

function getServiceChapRegIds() {
  $chapterId = civicrm_api3('CustomField', 'getvalue', array(
    'name' => 'Chapter',
    'return' => 'id',
    'custom_group_id' => "chapter_region",
  ));

  $regionId = civicrm_api3('CustomField', 'getvalue', array(
    'name' => 'Region',
    'return' => 'id',
    'custom_group_id' => "chapter_region",
  ));

  $serviceRegionId = civicrm_api3('CustomField', 'getvalue', array(
    'name' => 'Service_Region',
    'return' => 'id',
    'custom_group_id' => "chapter_region",
  ));

  $subRegionId = civicrm_api3('CustomField', 'getvalue', array(
    'name' => 'Service_Sub_Region',
    'return' => 'id',
    'custom_group_id' => "chapter_region",
  ));
  return [$chapterId, $regionId, $serviceRegionId, $subRegionId];
}

function setServiceChapRegCodes($params, $existingCodes = []) {
  list($chapterId, $regionId, $serviceRegionId, $subRegionId) = getServiceChapRegIds();

  if (!empty($params['chapter'])) {
    civicrm_api3('CustomValue', 'create', array(
      'entity_id' => $params['contact_id'],
      'custom_' . $chapterId => CRM_Core_DAO::VALUE_SEPARATOR . $params['chapter'] . CRM_Core_DAO::VALUE_SEPARATOR,
    ));
  }
  if (!empty($params['region'])) {
    civicrm_api3('CustomValue', 'create', array(
      'entity_id' => $params['contact_id'],
      'custom_' . $regionId => CRM_Core_DAO::VALUE_SEPARATOR . $params['region'] . CRM_Core_DAO::VALUE_SEPARATOR,
    ));
  }
  if (!empty($params['service_region'])) {
    civicrm_api3('CustomValue', 'create', array(
      'entity_id' => $params['contact_id'],
      'custom_' . $serviceRegionId => $params['service_region'],
    ));
  }
  if (!empty($params['service_sub_region'])) {
    civicrm_api3('CustomValue', 'create', array(
      'entity_id' => $params['contact_id'],
      'custom_' . $subRegionId => $params['service_sub_region'],
    ));
  }
}
