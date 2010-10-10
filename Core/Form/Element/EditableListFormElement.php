<?php
/**
 * Note : Code is released under the GNU LGPL
 *
 * Please do not change the header of this file 
 *
 * This library is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * Lesser General Public License as published by the Free Software Foundation; either version 2 of 
 * the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 *
 * See the GNU Lesser General Public License for more details.
 */

/**
 * File:        EditableListFormElement.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Represents a EditableList form element
 * 
 */
namespace Core\Form\Element;
class EditableListFormElement extends FormElement
{
    /**
     * button name
     * 
     * @var string  Defaults to null. 
     */
    private $buttonName = null;

    /**
     * Button name setter
     *
     * @param  string  $buttonName the button name
     */
    public function setButtonName($buttonName)
    {
        $this->buttonName = $buttonName;
    }

    /**
     * Button name getter
     * 
     * @return string the button name 
     */
    public function getButtonName()
    {
        return $this->buttonName;
    }

    /**
     * button value
     * 
     * @var string  Defaults to null. 
     */
    private $buttonValue = null;

    /**
     * Button value setter
     *
     * @param  string  $buttonValue the button value
     */
    public function setButtonValue($buttonValue)
    {
        $this->buttonValue = $buttonValue;
    }

    /**
     * Button value getter
     * 
     * @return string the button value 
     */
    public function getButtonValue()
    {
        return $this->buttonValue;
    }

    /**
     * list name
     * 
     * @var string  Defaults to null. 
     */
    private $listName = null;

    /**
     * list name setter
     *
     * @param  string  $listName the list name
     */
    public function setListName($listName)
    {
        $this->listName = $listName;
    }

    /**
     * list name getter
     * 
     * @return string the list name 
     */
    public function getListName()
    {
        return $this->listName;
    }
    
    /**
     * Button attributes
     * 
     * @var array  Defaults to array(). 
     */
    private $buttonAttributes = array();
 
    /**
     * the text name
     * 
     * @var string  Defaults to null. 
     */
    private $textName = null;

    /**
     * Text name setter
     * 
     * @param  string  $textName the text name
     */
    public function setTextName($textName)
    {
        $this->textName = $textName;
    }

    /**
     * text name Getter
     *
     * @return string the text name
     */
    public function getTextName()
    {
        return $this->textName;
    }    
    /**
     * Text attributes
     * 
     * @var array  Defaults to array(). 
     */
    private $textAttributes = array();


    /**
     * Button attributes string setter
     * 
     * @param  string $buttonAttributes the button attributes string
     */
    public function setButtonAttributes($buttonAttributes)
    {
        $this->buttonAttributes = $buttonAttributes;
    }

    /**
     * Button attributes string getter
     *
     * @return string the button attributes string
     */
    public function getbuttonAttributes()
    {
        return $this->buttonAttributes;
    }    

    /**
     * Returns a string representation of the button attributes
     * 
     * @return string 
     */
    public function getButtonAttributesString()
    {
        return $this->getAttributesString($this->buttonAttributes);
    }
    
    /**
     * Returns a string representation of the text attributes
     * 
     * @return string 
     */
    public function getTextAttributesString()
    {
        return $this->getAttributesString($this->textAttributes);
    }
    

    /**
     * List items
     * 
     * @var array  Defaults to array(). 
     */
    private $listItems = array();
    
    /**
     * List Items Setter
     * 
     * @param  array  $listItems The list items
     */
    public function setListItems(array $listItems)
    {
        $this->listItems = $listItems;
    }    
    
    /**
     * List Items Getter
     * 
     * @return array the list items
     */
    public function getListItems()
    {
        return $this->listItems;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getListItemsString()
    {
        $string = '';
        foreach ($this->listItems as $item)
        {
            $string .= '<li><a onclick="$(this).parent().remove(); return false;" href="#">&nbsp;</a> <em>' . $item . '</em></li>';
        }    
        return $string;
    }    

    /**
     * Echoes a EditableList form element
     * 
     * @return string
     */
    public function render()
    {
        echo '<input id="' . $this->getTextName() .'" name="' . $this->getTextName() . '" type="text" ' . $this->getTextAttributesString() . '/>';
        echo '<input type="button" id="' . $this->getButtonName() .'" name="' . $this->getButtonName() . '" value="' . $this->buttonValue . '" ' . $this->getbuttonAttributesString(). '>';
        echo '<input type="hidden"  id="' . $this->getName() .'" name="' . $this->getName() . '">';
        echo '<ul id="' . $this->listName . '" class="editableList">' . $this->getListItemsString() . '</ul>';
        echo '
        <script>
        $(document).ready(function(){
            $(\'#' . $this->getButtonName() . '\').click(function(){
                $(\'#'  . $this->listName. '\').append("<li><a onclick=\"$(this).parent().remove(); return false;\" href=\"#\">&nbsp;</a> <em>"+$(\'#' . $this->getTextName() . '\').val()+"</em></li>");     
            });
            $(\'#' . $this->formName . '\').submit(function(){
                 $(\'#'  . $this->listName. ' li\').each(function(index){
                    $(\'#' . $this->getName() . '\').val($(\'#' . $this->getName() . '\').val() + \'|\' + $(this).text().trim());
                 });
            });
        });

        </script>
        ';
    }    
}    
