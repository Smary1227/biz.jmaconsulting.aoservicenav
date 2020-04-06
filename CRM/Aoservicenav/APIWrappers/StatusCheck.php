<?php

use CRM_Aoservicenav_ExtensionUtil as E;

class CRM_Aoservicenav_APIWrappers_StatusCheck implements API_Wrapper {

  /**
   * Conditionally changes contact_type parameter for the API request.
   */
  public function fromApiInput($apiRequest) {
    return $apiRequest;
  }

  public function toApiOutput($apiRequest, $result) {
    if ($apiRequest['entity'] == 'Activity' && $apiRequest['action'] == 'create' && !empty($apiRequest['params']['id'])) {
      if (!in_array($apiRequest['params']['activity_type_id'], [REQUEST, PROVISION])) {
        return;
      }
      $actId = $apiRequest['params']['id'];
      foreach ($apiRequest['fields'] as $field => $fieldVal) {
        if (!empty($fieldVal['is_required'])) {
          $requiredFields[] = $field;
        }
      }
      $activity = civicrm_api3('Activity', 'get', ['return' => $requiredFields, 'id' => $actId])['values'][$actId];
      foreach ($requiredFields as $customField) {
        if (empty($activity[$customField])) {
          throw new API_Exception(E::ts("Please fill in the required fields before you change the activity status."));
        }
      }
    }
    return $result;
  }
}