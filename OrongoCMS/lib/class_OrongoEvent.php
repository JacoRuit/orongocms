<?php


/**
 * OrongoEvent Class
 *
 * @author Ruit
 */
class OrongoEvent {
    
    private $invoker;
    private $signatureParamCount;
    private $functions;
    
    /**
     * Init the event 
     * @param Closure $paramFunctionSignature the function signature
     */
    public function __construct($paramFunctionSignature){
        if(($paramFunctionSignature instanceof Closure) == false)
            throw new IllegalArgumentException("Invalid argument, Closure (anonymous function) expected!");
        $backtrace = debug_backtrace();
        if(!isset($backtrace[1]['class']))
            throw new Exception("Can't init an event outside a class!");
        $this->invoker = $backtrace[1]['class'];
        $rf = new ReflectionFunction($paramFunctionSignature); 
        $this->signatureParamCount = count($rf->getParameters());
    }
    
    /**
     * Adds the anonymous function to the event
     * @param Closure $paramFunction the anonymous function to add
     */
    public function add($paramFunction){
        if(($paramFunction instanceof Closure) == false)
            throw new IllegalArgumentException("Invalid argument, Closure (anonymous function) expected!");
        $rf = new ReflectionFunction($paramFunction); 
        if(count($rf->getParameters()) != $this->signatureParamCount)
            throw new IllegalArgumentException("The parameter count of the function you tried to add doesn't match the signature param count.");
        $this->functions[count($this->functions)] = $paramFunction;
    }
    
    /**
     * Invokes all the functions which were added to the event
     * @param array $paramArgs the args for the function (optional)
     */
    public function __invoke($paramArgs = null){
        $this->invoke($paramArgs);
    }
    
    /**
     * Invokes all the functions which were added to the event
     * @param array $paramArgs the args for the function (optional)
     */
    public function invoke($paramArgs = null){
        if($paramArgs == null && $this->signatureParamCount > 0)
            throw new IllegalArgumentException("Can't invoke the functions, arguments missing.");
        $backtrace = debug_backtrace();
        if(!isset($backtrace[1]['class']))
            throw new Exception("Can't invoke an event outside a class!");
        if($this->invoker != $backtrace[1]['class'])
            throw new Exception("Can't invoke the event from a different class than the class where it was initted.");
        if($paramArgs != null){
            $fixedArgs = array();
            $c = 0;
            foreach($paramArgs as $arg){
                if($c >= $this->signatureParamCount) break;
                $fixedArgs[$c] = $arg;
                $c++;
            }
        }
        foreach($this->functions as $function){
            if(($function instanceof Closure) == false) continue;
            call_user_func_array($function, $fixedArgs);
        }
    }
    
}

?>
