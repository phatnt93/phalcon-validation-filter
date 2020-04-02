<?php 


/** 
* Detect SSL Domain Expired
* 
* Made by Phatnt
* 02/04/2020
* 
* @license MIT License
* @author phatnt <thanhphat.uit@gmail.com>
* @github https://github.com/phatnt93/python_detect_domain_ssl
* @version 1.0.0
* 
*/


namespace PhalconValidation;

use \Phalcon\Validation;
use \Phalcon\Filter;

/**
 * 
 */
class PValidation{
    public $error = false;
    public $msg = '';
    private $validation = null;
    private $filter = null;
    private $lang = null;
    private $listMsg = [];
    private $basePath = __DIR__;
    private $dataInput = [];

    function __construct($options = []){
        $this->validation = new Validation();
        $this->filter = new Filter();
        // Define lang
        if(isset($options['lang'])){
            $this->lang = $options['lang'];
        }else{
            $this->lang = 'en';
        }
        // Load msg
        $langPath = $this->basePath . '/langs/' . $this->lang . '.php';
        if(file_exists($langPath)){
            $this->listMsg = require $langPath;
        }
    }

    private function getMessageValidator($attr = ''){
        if(array_key_exists($attr, $this->listMsg)){
            return $this->listMsg[$attr];
        }
        return 'Empty';
    }

    private function createValidator($attr, $field = ''){
        $res = null;
        switch ($attr) {
            case 'required':{
                $res = new Validation\Validator\PresenceOf([
                    'message' => str_replace('{field}', $field, $this->getMessageValidator($attr))
                ]);
                break;
            }
            case 'alpha':{
                $res = new Validation\Validator\Alpha([
                    'message' => str_replace('{field}', $field, $this->getMessageValidator($attr))
                ]);
                break;
            }
            case 'alpha_numeric':{
                $res = new Validation\Validator\Alnum([
                    'message' => str_replace('{field}', $field, $this->getMessageValidator($attr))
                ]);
                break;
            }
            case 'numeric':{
                $res = new Validation\Validator\Digit([
                    'message' => str_replace('{field}', $field, $this->getMessageValidator($attr))
                ]);
                break;
            }
            case 'numericality':{
                $res = new Validation\Validator\Numericality([
                    'message' => str_replace('{field}', $field, $this->getMessageValidator($attr))
                ]);
                break;
            }
            case 'email':{
                $res = new Validation\Validator\Email([
                    'message' => str_replace('{field}', $field, $this->getMessageValidator($attr))
                ]);
                break;
            }
            case (preg_match('/^date/', $attr) ? true : false):{ // For date
                // date|Y-m-d
                // date|format
                $arr = array_filter(explode("|", $attr));
                if(!isset($arr[1])){
                    $arr[1] = '';
                }
                $res = new Validation\Validator\Date([
                    'format' => $arr[1],
                    'message' => str_replace('{field}', $field, $this->getMessageValidator($arr[0]))
                ]);
                break;
            }
            case (preg_match('/^exclusion_in/', $attr) ? true : false):{
                // exclusion_in|A,B,C
                // exclusion_in|domain
                $arr = array_filter(explode("|", $attr));
                if(!isset($arr[1])){
                    $arr[1] = '';
                }
                $domain = array_filter(explode(",", $arr[1]));
                $domainMsg = implode(" or ", $domain);
                $res = new Validation\Validator\ExclusionIn([
                    'domain' => $domain,
                    'message' => str_replace(['{field}', '{param}'], [$field, $domainMsg], $this->getMessageValidator($arr[0]))
                ]);
                break;
            }
            case (preg_match('/^inclusion_in/', $attr) ? true : false):{
                // inclusion_in|A,B,C
                // inclusion_in|domain
                $arr = array_filter(explode("|", $attr));
                if(!isset($arr[1])){
                    $arr[1] = '';
                }
                $domain = array_filter(explode(",", $arr[1]));
                $domainMsg = implode(" or ", $domain);
                $res = new Validation\Validator\InclusionIn([
                    'domain' => $domain,
                    'message' => str_replace(['{field}', '{param}'], [$field, $domainMsg], $this->getMessageValidator($arr[0]))
                ]);
                break;
            }
            case (preg_match('/^regex/', $attr) ? true : false):{
                // regex|/^[a-z]$/
                // regex|pattern
                $arr = array_filter(explode("|", $attr));
                if(!isset($arr[1])){
                    $arr[1] = '';
                }
                $res = new Validation\Validator\Regex([
                    'pattern' => $arr[1],
                    'message' => str_replace('{field}', $field, $this->getMessageValidator($arr[0]))
                ]);
                break;
            }
            case (preg_match('/^identical/', $attr) ? true : false):{
                // identical|value
                $arr = array_filter(explode("|", $attr));
                if(!isset($arr[1])){
                    $arr[1] = '';
                }
                $res = new Validation\Validator\Identical([
                    'value' => $arr[1],
                    'message' => str_replace(['{field}', '{param}'], [$field, $arr[1]], $this->getMessageValidator($arr[0]))
                ]);
                break;
            }
            case (preg_match('/^string_length/', $attr) ? true : false):{
                // string_length|max_len,number|min_len,number
                $resMax = preg_match('/max_len,([0-9]+)/', $attr, $outputMax);
                $resMin = preg_match('/min_len,([0-9]+)/', $attr, $outputMin);
                $paramsValid = [];
                if($resMax){
                    $paramsValid['max'] = $outputMax[1];
                    $paramsValid['messageMaximum'] = str_replace(['{field}', '{param}'], [$field, $outputMax[1]], $this->getMessageValidator('max_len'));
                }
                if($resMin){
                    $paramsValid['min'] = $outputMin[1];
                    $paramsValid['messageMinimum'] = str_replace(['{field}', '{param}'], [$field, $outputMin[1]], $this->getMessageValidator('min_len'));
                }
                $res = new Validation\Validator\StringLength($paramsValid);
                break;
            }
            case (preg_match('/^between/', $attr) ? true : false):{
                // between|max,number|min,number
                $resMax = preg_match('/max,([0-9]+)/', $attr, $outputMax);
                $resMin = preg_match('/min,([0-9]+)/', $attr, $outputMin);
                $paramsValid = [];
                if($resMax){
                    $paramsValid['maximum'] = $outputMax[1];
                }
                if($resMin){
                    $paramsValid['minimum'] = $outputMin[1];
                }
                $paramsValid['message'] = str_replace(['{field}', '{min}', '{max}'], [$field, $outputMin[1], $outputMax[1]], $this->getMessageValidator('between'));
                $res = new Validation\Validator\Between($paramsValid);
                break;
            }
            case 'url':{
                $res = new Validation\Validator\Url([
                    'message' => str_replace('{field}', $field, $this->getMessageValidator($attr))
                ]);
                break;
            }
            case 'credit_card':{
                $res = new Validation\Validator\CreditCard([
                    'message' => str_replace('{field}', $field, $this->getMessageValidator($attr))
                ]);
                break;
            }
        }
        return $res;
    }

    private function createItemValidation($params){
        foreach ($params as $field => $items) {
            // Check field validation contained Field Input
            if(!array_key_exists($field, $this->dataInput)){
                throw new \Exception("Field validation '{$field}' was not found");
            }
            foreach ($items as $key => $attr) {
                $attrAdd = $this->createValidator($attr, $field);
                if($attrAdd === null){
                    throw new \Exception("Create validator '{$field} - {$attr}' failed");
                }
                $this->validation->add($field, $attrAdd);
            }
        }
    }

    private function runFilter($field = '', $attr = ''){
        $res = '';
        switch ($attr) {
            case 'absint':{
                $res = $this->filter->sanitize($this->dataInput[$field], $attr);
                break;
            }
            case 'alphanum':{
                $res = $this->filter->sanitize($this->dataInput[$field], $attr);
                break;
            }
            case 'email':{
                $res = $this->filter->sanitize($this->dataInput[$field], $attr);
                break;
            }
            case 'float':{
                $res = $this->filter->sanitize($this->dataInput[$field], $attr);
                break;
            }
            case 'float!':{
                $res = $this->filter->sanitize($this->dataInput[$field], $attr);
                break;
            }
            case 'int':{
                $res = $this->filter->sanitize($this->dataInput[$field], $attr);
                break;
            }
            case 'int!':{
                $res = $this->filter->sanitize($this->dataInput[$field], $attr);
                break;
            }
            case 'lower':{
                $res = $this->filter->sanitize($this->dataInput[$field], $attr);
                break;
            }
            case 'string':{
                $res = $this->filter->sanitize($this->dataInput[$field], $attr);
                break;
            }
            case 'striptags':{
                $res = $this->filter->sanitize($this->dataInput[$field], $attr);
                break;
            }
            case 'trim':{
                $res = $this->filter->sanitize($this->dataInput[$field], $attr);
                break;
            }
            case 'upper':{
                $res = $this->filter->sanitize($this->dataInput[$field], $attr);
                break;
            }
            default:{
                break;
            }
        }
        return $res;
    }

    // Filter
    public function filter($params = [], $filterParams = []){
        try {
            if(empty($params)){
                throw new \Exception("Params was not found");
            }
            $this->dataInput = $params;
            foreach ($filterParams as $fieldFilter => $attrs) {
                if(array_key_exists($fieldFilter, $params)){
                    foreach ($attrs as $attr) {
                        $this->dataInput[$fieldFilter] = $this->runFilter($fieldFilter, $attr);
                    }
                }
            }
        } catch (\Exception $e) {
            // error
        }
        return $this->dataInput;
    }

    // Run validate
    public function run($params = [], $validateParams = [], $filterParams = []){
        try {
            if(empty($params)){
                throw new \Exception("Params was not found");
            }
            $this->dataInput = $params;
            if(empty($this->listMsg)){
                throw new \Exception("The lang was not found");
            }
            if (count($validateParams) > 0) {
                // Set validate
                $this->createItemValidation($validateParams);
                // Execute validation
                $messages = $this->validation->validate($params);
                if (count($messages)) {
                    foreach ($messages as $message) {
                        throw new \Exception($message);
                    }
                }
            }
            $dataFilter = $params;
            if (count($filterParams) > 0) {
                $dataFilter = $this->filter($dataFilter, $filterParams);
            }
            return $dataFilter;
        } catch (\Exception $e) {
            $this->error = true;
            $this->msg = $e->getMessage();
        }
    }
}