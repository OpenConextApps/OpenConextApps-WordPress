<?php
/*
 * Copyright 2008 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * OpenSocial API class for Profile Comments requests
 *
 * @author Jesse Edwards
 */
class osapiProfileComments extends osapiService {
  /**
   * Gets a list of fields supported by this service
   *
   * @return osapiRequest the request
   */
  public function getSupportedFields() {
    throw new osapiException("@supportedFields for statusmood is not supported");
  }
  
  /**
   * Gets status and mood. Uses specific endpoint for this
   * Myspace specific
   * @return osapiRequest the request
   */
  public function get($params)
  {  
      if(!array_key_exists('userId', $params))
        $params['userId'] = '@me';
      if(!array_key_exists('groupId', $params))
        $params['groupId'] = '@self';
        
      return osapiRequest::createRequest('profilecomments.get', $params);
  }
  /**
   * Sets status. Uses specific endpoint for this
   * Myspace specific
   * @return osapiRequest the request
   */
  public function update($params)
  {
      throw new osapiException("Updating this endpoint is not supported");
  }

  public function delete($params)
  {
    throw new osapiException("Deleting statusmood is not supported");
  }

  public function create($params)
  {
    throw new osapiException("Creating statusmood is not supported");
  }

  /**
   * Converts a response into a native data type.
   *
   * @param array $array the raw data
   * @param boolean $strictMode whether to throw spec errors
   * @return osapiPerson
   */
  static public function convertArray($array, $strictMode = true) {
    $instance = new osapiStatusMoodModel();
    $defaults = get_class_vars('osapiStatusMoodModel');
    
    if ($strictMode && sizeof($defaults != sizeof($array))) {
      throw new osapiException("Unexpected fields in statusmood response". print_r($array, true));
    }
    foreach($array as $key=>$value){
        $instance->setField($key, $value);
    }
    return self::trimResponse($instance);
  }
}
