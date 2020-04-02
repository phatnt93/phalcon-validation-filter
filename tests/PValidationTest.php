<?php 
namespace Tests;

use \PHPUnit\Framework\TestCase;
use \PhalconValidation\PValidation;

/**
 * 
 */
class PValidationTest extends TestCase{

    /**
     * Test validate
     */


    /**
     * Test filter
     */
    // This test figure out this function not remove character. It only convert string to int
    public function testFilterAbsintRemoveCharater(){
        $originString = 'asdasd-12';
        $expectString = 12;
        $paramsFilter = [
            'name' => ['absint']
        ];
        $validate = new PValidation();
        $data = $validate->run(['name' => $originString], [], $paramsFilter);
        $this->assertSame($expectString, $data['name']);
    }

    public function testFilterAbsint(){
        $originString = '-12';
        $expectString = 12;
        $paramsFilter = [
            'name' => ['absint']
        ];
        $validate = new PValidation();
        $data = $validate->run(['name' => $originString], [], $paramsFilter);
        $this->assertSame($expectString, $data['name']);
    }

    public function testFilterAlphanum(){
        $originString = '#%$%^^Phalcon Validation!#!#!@';
        $expectString = 'PhalconValidation';
        $paramsFilter = [
            'name' => ['alphanum']
        ];
        $validate = new PValidation();
        $data = $validate->run(['name' => $originString], [], $paramsFilter);
        $this->assertSame($expectString, $data['name']);
    }

    public function testFilterEmail(){
        $originString = '<>phalcon@example.com>>>>>';
        $expectString = 'phalcon@example.com';
        $paramsFilter = [
            'name' => ['email']
        ];
        $validate = new PValidation();
        $data = $validate->run(['name' => $originString], [], $paramsFilter);
        $this->assertSame($expectString, $data['name']);
    }
    
    // This test figure out this function not remove character. It only convert string to int
    public function testFilterNotFloatRemoveCharater(){
        $originString = 'phalcon validation+2020.15';
        $expectString = 2020.15;
        $paramsFilter = [
            'name' => ['float!']
        ];
        $validate = new PValidation();
        $data = $validate->run(['name' => $originString], [], $paramsFilter);
        $this->assertSame($expectString, $data['name']);
    }

    public function testFilterNotFloat(){
        $originString = '+2020.15';
        $expectString = 2020.15;
        $paramsFilter = [
            'name' => ['float!']
        ];
        $validate = new PValidation();
        $data = $validate->run(['name' => $originString], [], $paramsFilter);
        $this->assertSame($expectString, $data['name']);
    }

    public function testFilterFloat(){
        $originString = 'phalcon validation2020.15';
        $expectString = '2020.15';
        $paramsFilter = [
            'name' => ['float']
        ];
        $validate = new PValidation();
        $data = $validate->run(['name' => $originString], [], $paramsFilter);
        $this->assertSame($expectString, $data['name']);
    }

    // This test figure out this function not remove character. It only convert string to int
    public function testFilterNotIntRemoveCharater(){
        $originString = 'phalcon validation+2020';
        $expectString = 2020;
        $paramsFilter = [
            'name' => ['int!']
        ];
        $validate = new PValidation();
        $data = $validate->run(['name' => $originString], [], $paramsFilter);
        $this->assertSame($expectString, $data['name']);
    }

    public function testFilterNotInt(){
        $originString = '+2020';
        $expectString = 2020;
        $paramsFilter = [
            'name' => ['int!']
        ];
        $validate = new PValidation();
        $data = $validate->run(['name' => $originString], [], $paramsFilter);
        $this->assertSame($expectString, $data['name']);
    }

    public function testFilterInt(){
        $originString = 'phalcon validation2020';
        $expectString = '2020';
        $paramsFilter = [
            'name' => ['int']
        ];
        $validate = new PValidation();
        $data = $validate->run(['name' => $originString], [], $paramsFilter);
        $this->assertSame($expectString, $data['name']);
    }

    public function testFilterLower(){
        $originString = 'phalcon validation';
        $expectString = 'phalcon validation';
        $paramsFilter = [
            'name' => ['lower']
        ];
        $validate = new PValidation();
        $data = $validate->run(['name' => $originString], [], $paramsFilter);
        $this->assertSame($expectString, $data['name']);
    }

    public function testFilterString(){
        $originString = '<a>Phalcon Validation</a>';
        $expectString = 'Phalcon Validation';
        $paramsFilter = [
            'name' => ['string']
        ];
        $validate = new PValidation();
        $data = $validate->run(['name' => $originString], [], $paramsFilter);
        $this->assertSame($expectString, $data['name']);
    }

    public function testFilterStriptags(){
        $originString = '<?php echo "123"; ?><a>Phalcon Validation</a>';
        $expectString = 'Phalcon Validation';
        $paramsFilter = [
            'name' => ['string']
        ];
        $validate = new PValidation();
        $data = $validate->run(['name' => $originString], [], $paramsFilter);
        $this->assertSame($expectString, $data['name']);
    }

    public function testFilterTrim(){
        $originString = ' Phalcon Validation ';
        $expectString = 'Phalcon Validation';
        $paramsFilter = [
            'name' => ['trim']
        ];
        $validate = new PValidation();
        $data = $validate->run(['name' => $originString], [], $paramsFilter);
        $this->assertSame($expectString, $data['name']);
    }

    public function testFilterUpper(){
        $originString = 'Phalcon Validation';
        $expectString = 'PHALCON VALIDATION';
        $paramsFilter = [
            'name' => ['upper']
        ];
        $validate = new PValidation();
        $data = $validate->run(['name' => $originString], [], $paramsFilter);
        $this->assertSame($expectString, $data['name']);
    }

}