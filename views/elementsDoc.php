<?php
include_once 'htmlDoc.php';
abstract class ElementsDoc extends HtmlDoc{
    /**
     * Determine whether a class parameter should be shown.
     * 
     * @param string $class the class name, which can be empty
     * @return string empty string when class is empty, otherwise string contains the classname
     */
    protected function classValue($class){
        return empty($class) ? '': ' class="'. $class . '"';
    }
    /**
     * Show a html element.
     * 
     * @param string $tagName the name of the tag
     * @param string $content the content of the html element
     * @param string $cssClass the class of the html element
     */
    protected function htmlElement($tagName, $content, $cssClass=''){
        $this-> htmlElementStart($tagName, $cssClass);
        echo $content;
        $this-> htmlElementEnd($tagName);
    }
    /**
     * Show the start of a html element
     * 
     * @param string $tagName the name of the tag
     * @param string $cssClass the class of the html element
     */
    protected function htmlElementStart($tagName, $cssClass){
        echo '<'.$tagName.$this->classValue($cssClass).'>';
    }
    /**
     * Show the end of a html element
     * @param string $tagName the name of the tag
     */
    protected function htmlElementEnd($tagName){
        echo '</'.$tagName.'>';
    }
    /**
     * Show a HTML list.
     *
     * @param string $type  The type of list (ul, ol, dl).
     * @param array $content An array with the values for the list
     * @param string $class (optional) An optional class that can be given to the list.
     *                 if the class isn't set, class will not be part of the tag.
     * @param string $listItemClass (optional) a class for a list item within a HTML list
     */
    protected function list($type, $content, $class='', $listItemClass=''){
        $this->htmlElementStart($type, $class);
        foreach ($content as $name => $value){
            $this->listItem($name, $value, $listItemClass);
        }
        $this->htmlElementEnd($type);
    }
    /**
     * Show a list item in a HTML list.
     *
     *@param string  $name  A name that can be shown in front of the value.
     *               The name is the key of the array.
     *@param string $value  the value for in the list item.
     *@param string $class the class name for a list item within a HTML list
     */
    private function listItem($name, $value, $class=''){
        $value = empty($value) ? "-" : $value;
        if (!is_string($name)){
            $this->htmlElement('li', $value, $class);
        }
        else {
            $this->htmlElement('li', $name.': '.$value, $class);
        }
    }
}

?>