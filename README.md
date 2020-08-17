# Getting started
This library help validate or filter params quickly.

## Required
- PHP >= 5.6
- Phalcon Framework = 3.*

## Example for validations

```
use \PhalconValidation\PValidation;

$params = [
    'name' => 'phalcon<span>123</span>'
];
$paramsValidate = [
    'name' => ['required', 'alpha_numeric']
];
$validate = new PValidation();
$data = $validate->run($params, $paramsValidate);
if($validate->error){
    echo "Error: " . $validate->msg;
}else{
    // Get value
    echo $data['name'];
}
```

## Example for Filters

```
use \PhalconValidation\PValidation;

$params = [
    'name' => 'phalcon<span>123</span>'
];
$paramsFilter = [
    'name' => ['int']
];
$validate = new PValidation();
$data = $validate->run($params, [], $paramsFilter);
OR
$data = $validate->filter($params, $paramsFilter);
echo $data['name'];
```

## Example for validations and filters

```
use \PhalconValidation\PValidation;

$params = [
    'name' => 'phalcon<span>123</span>'
];
$paramsValidate = [
    'name' => ['required', 'alpha_numeric']
];
$paramsFilter = [
    'name' => ['int']
];
$validate = new PValidation();
$data = $validate->run($params, $paramsValidate, $paramsFilter);
if($validate->error){
    echo "Error: " . $validate->msg;
}else{
    // Get value
    echo $data['name'];
}
```

## Rule for validations and filters
Please, visit website:
- [Validations](https://docs.phalcon.io/3.4/en/validation#validators)
- [Filters](https://docs.phalcon.io/3.4/en/filter#types-of-built-in-filters)
