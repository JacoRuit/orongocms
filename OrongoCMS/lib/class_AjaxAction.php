<?php
/**
 * AjaxACtion Class
 *
 * @author Jaco Ruit
 */
abstract class AjaxAction implements IJSConvertable {
    
    /**
     * Starts the Ajax Action, add it to the Display 
     */
    final public function start(){
        $this->doImports();
        getDisplay()->addJS($this->toJS(), "document.ready");
    }
    
    abstract function doImports();
    
    public function __toString(){
        return "AjaxAction";
    }
}
?>
