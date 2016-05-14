<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

//validate functions

//////////////// validation functions  ///////////////////

function convertToString($str) {
    return htmlspecialchars($str);
}

function testPhoneNumber($phoneNumber) {
    $phone = preg_replace('/[\(\) -]/', "", $phoneNumber);
    return preg_match('/^\d{10}$/', $phone);
}

function testEmail($email) {
    return preg_match('/^[\w\.-]+@[\w\.-]+\.\w+$/i', $email);
}

function testName($name) {
    return preg_match('/^[a-zA-Z\. ]{2,100}$/', $name);
}

function testCompanyName($companyName) {
    if ($companyName == "") {
        return true;
    }
    return preg_match('/^.{3,50}$/', $companyName);
}

function testMessage($message) {
    return preg_match('/^.{3,250}$/m', $message);
}

function testTopic($topic) {
    return preg_match('/^.{3,50}$/', $topic);
}

///////////////////////// end of function validation. ////////////////////


function responseAsJSON(&$array) {
    echo json_encode($array);
}

//This function is used by catch the statements to convert the exception object into a proper json message.
//At the moment this function only supports BadRequestException.
function exceptionResponseAsJSON(Exception $e) {
    $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');  // use the protocol format sent by the client, or use the HTTP/1.0 protocol.
    header($protocol . ' ' . 400 . ' ' . "Bad Request");
    $message = array(
        "message" => $e->getMessage()
    );
    //echo json_encode($message);
    responseAsJSON($message);
}



/**
 * Class BadRequestException
 * this is a extended class of PHP's Exception class.
 */
class BadRequestException extends Exception {
    public function __construct($message) {
        parent::__construct($message, 400);
    }
}

class ValidationEngine {

    private $methodType;
    protected $bodyMessage;
    private $requiredFields;
    private $validateFunctions;

    //this is constructor of ContactEngine.
    public function __construct() {
        //gets the type of HTTP request. [GET, POST, PUT or DELETE].
        //in this class we only care about POST.
        $this->methodType = $_SERVER['REQUEST_METHOD'];
        $this->validateFunctions = array();
    }

    private function checkMessage() {
        //if the method is not POST we will throw a bad exception
        if ($this->methodType != "POST") {
            throw new BadRequestException("Only POST is permitted.");
        }

        //this line gets HTTP body that user sends to us and converts json request to php stdObject.
        $this->bodyMessage = json_decode(file_get_contents("php://input"));

        //if the conversion is not valid, body message will be NULL, so we are returning an exception.
        if ($this->bodyMessage == null) {
            throw new BadRequestException("Content needs to be sent as JSON.");
        }
    }

    //this method is private and it will be called by parse public method.
    //it checks whether required fields exist and populated.
    //if not, it will throw an exception
    private function checkRequiredFields() {
        $result = true;
        foreach ($this->requiredFields as $requiredField) {
            if (!isset($this->bodyMessage->{$requiredField})) {
                $result = "Field " . $requiredField . " is missing.";
                break;
            } else if ($this->bodyMessage->{$requiredField} === "") {
                $result = "Field " . $requiredField . " is empty.";
                break;
            }
        }

        if ($result !== true) {
            throw new BadRequestException($result);
        }
    }

    //this private method is called from parse public method.
    //the purpose of this method is checking whether each field has a custom validation.
    //if it does, then it will call that validation function and if that validation function returns false,
    //we will throw an exception.
    private function checkValidateFunctions() {
        foreach($this->bodyMessage as $field => &$value) {

            if (isset($this->validateFunctions[$field])) {
                $functionName = $this->validateFunctions[$field];
                $result = call_user_func($functionName, $value);
                if (!$result) {
                    throw new BadRequestException("Field " . $field . " is not valid.");
                }
            }
        }
    }

    private function preParseAll() {
        foreach($this->bodyMessage as $field => &$value) {
            $this->bodyMessage->{$field} = $this->preParse($value);
        }
    }

    //this method is accepting and array of field's name which you want to be required.
    public function setRequiredFields($fields) {
        $this->requiredFields = $fields;
    }

    //assigning custom validation function for each individual field.
    public function validateField($fieldName, $validateFunction) {
        $this->validateFunctions[$fieldName] = $validateFunction;
    }

    //this is the main method which needs to be called and wrapped around try and catch.
    public function parse() {
        $this->checkMessage();
        $this->preParseAll();
        $this->checkRequiredFields();
        $this->checkValidateFunctions();
    }

    //this method needs to be overridden if extended class requires to do extra logic before
    //any tests apply.
    public function preParse(&$value) {
        return $value;
    }
}


/**
 * Class ContactEngine
 * we will override the preParse method because we can't trust public communication to our servers.
 *
 */
class ContactEngine extends ValidationEngine {
    private $emailArray;

    public function __construct() {
        parent::__construct();
        //these are the recipients of the email.
       $this->emailArray = array( "kramerdesign@outlook.com");

    }

    //we are override the parse method from parent class to add extra logic
    //for example, once the parent parse is done, we would like to send an email to
    //recipient.
    public function parse() {
        //calls the logic of parent class first
        parent::parse();

        //add the new logic to parse function
        $message = "Full Name: " . $this->bodyMessage->fullName . "\n\n";
        $message = $message . "Phone: " . $this->bodyMessage->phone . "\n\n";
        $message = $message . $this->bodyMessage->shortMessage . "\n";

        //send an email to recipients which defines in constructor.
        $this->sendEmails($this->bodyMessage->email, $this->bodyMessage->topic, $message);

        //constructing a responseMessage
        $responseMessage = array(
            "message" => "Thank you for your email. We will get back to you shortly."
            );
        //sending that response message to user
        responseAsJSON($responseMessage);
    }

    //this is an overridden method of parent class. this method is called once preParseAll is called.
    public function preParse(&$value) {
        //it converts all field's values to string
        return htmlspecialchars($value);
    }

    //this method will send the email to the recipients.
    private function sendEmails($from, $subject, $message) {
        $headers = "From: $from\n";
        $headers .= "Reply-To: $from";
        foreach($this->emailArray as $email) {
            mail($email, $subject, $message, $headers);
        }
    }
}

//create an object from ContactEngine
$contactEngine = new ContactEngine();

//Assigning required fields. this is only checking whether the fields is missing from
//json message or field is empty.
$contactEngine->setRequiredFields(array(
   "email", "fullName", "phone", "shortMessage", "topic"
));

//if you need to check extra logic for field's value, you have to assign a function
//the first parameter is name of the field, the second parameter is function that you want to call.
//all of these functions are defined at the top of this page.
$contactEngine->validateField("email", "testEmail");
$contactEngine->validateField("fullName", "testName");
$contactEngine->validateField("phone", "testPhoneNumber");
$contactEngine->validateField("shortMessage", "testMessage");
$contactEngine->validateField("topic", "testTopic");

//since we are using exception we are going to call the parse method from
//contactEngine object. this method is checking all the criteria that you defined.
//if an error is found, an exception will be thrown which will be caught by catch statement and
//converted into proper json message to be displayed to user.
try {
    $contactEngine->parse();
} catch (Exception $e) {
    exceptionResponseAsJSON($e);
}
