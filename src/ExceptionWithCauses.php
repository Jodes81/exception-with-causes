<?php

namespace Jodes\ExceptionWithCauses;

class ExceptionWithCauses extends \Exception {
    
    private $causes = array();

    public function __construct($message, $code = 0, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
    
    public function addCause(\Exception $ex){
        $this->causes[] = $ex;
        $this->message .= "\n".$this->getCauseString($ex);
    }
    
    public function hasCause($className){
        foreach ($this->causes as $cause){
            if ($this->getClassName($cause) == $className){
                return true;
            }
        }
        foreach ($this->causes as $cause){
            if (
                $cause instanceof ExceptionWithCauses &&
                $cause->hasCause($className)
            ){
                return true;
            }
        }
        return false;
    }
    
    public function hasCauses(){
        return 0 < count($this->causes);
    }
    
    private function getCauseString(\Exception $ex){
        return $this->getClassName($ex) . ": " . $ex->getMessage();
    }
    
    private function getClassName(\Exception $ex){
        return (new \ReflectionClass($ex))->getShortName();
    }
    
}
