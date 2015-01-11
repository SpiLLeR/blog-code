<?php
/*
 * Класс RequiredNumberFromListValidator
 *
 * @author Konstantin Perminov <konstantin.perminov@gmail.com>
 * @link http://devkp.ru/
 * @copyright 2010
 */

/**
 * RequiredNumberFromListValidator валидатор.
 * Расширяет стандартный CRequiredValidator и позволяет из списка атрибутов
 * указать кол-во атрибутов необходимх для обязательного заполения.
 * Пример:
 * У компании есть поля: мобильный телефон, станционарный телефон.
 * Необходимо чтобы один из телефонов был указан.
 * array('mobile_phone, landline_phone', 'RequiredNumberFromListValidator'),
 */
class RequiredNumberFromListValidator extends CRequiredValidator {

    protected $errors = array();
    protected $requiredFieldsNumber = 1;

    /**
     * Validates the specified object.
     * @param CModel the data object being validated
     * @param array the list of attributes to be validated. Defaults to null,
     * meaning every attribute listed in {@link attributes} will be validated.
     */
    public function validate($object,$attributes=null) {
        $this->errors = array();
        parent::validate($object, $attributes);
        $errorsCount = count($this->errors);
        $attributesCount = count($this->attributes);

        if($errorsCount >= $attributesCount || (($attributesCount - $errorsCount) < $this->requiredFieldsNumber)) {
            foreach($this->errors as $error)
                $this->addError($error['object'], $error['attribute'], $error['message']);
        }
    }

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel the object being validated
     * @param string the attribute being validated
     */
    protected function validateAttribute($object,$attribute)
    {
            $value=$object->$attribute;
            if($this->requiredValue!==null)
            {
                    if(!$this->strict && $value!=$this->requiredValue || $this->strict && $value!==$this->requiredValue)
                    {
                            $message=$this->message!==null?$this->message:Yii::t('yii','{attribute} must be {value}.',
                                    array('{value}'=>$this->requiredValue));
                            $this->errors[] = array('object'=>$object, 'attribute'=>$attribute, 'message'=>$message);
                    }
            }
            else if($this->isEmpty($value,true))
            {
                    $message=$this->message!==null?$this->message:Yii::t('yii','{attribute} cannot be blank.');
                    $this->errors[] = array('object'=>$object, 'attribute'=>$attribute, 'message'=>$message);
            }
    }
}

