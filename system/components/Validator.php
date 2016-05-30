<?php

namespace system\components;


class Validator
{
    public $errors = [];
    public $rules = [];
    public $method = 'POST';

    private function unique($field, $rule)
    {
        /**
         * @var $fieldName
         * @var $message
         */
        extract($rule);

        $fieldName = isset($fieldName) && !empty($fieldName) ? $fieldName : $field;

        $db = App::$pdo;
        $stmt = $db->prepare("SELECT id FROM users WHERE `{$fieldName}` = :value");
        $stmt->execute([
            'value' => $this->getValue($field),
        ]);

        $id = $stmt->fetchColumn();

        if ($id) {
            $this->addError($field, $message);
        }

    }

    private function login($field, $rule)
    {
        /**
         * @var $min
         * @var $max
         * @var $message
         */
        extract($rule);

        $login = $this->getValue($field);
        $loginLength = strlen($login);

        if ($loginLength < $min || $loginLength > $max || preg_match('/^[A-Za-z][A-Za-z0-9_]$/', $login)) {
            $this->addError($field, $message);
        }

    }

    private function file($field, $rule)
    {
        /**
         * @var $ext array
         * @var $message
         */
        extract($rule);

        $file = $this->getValue($field, true);

        // if file not selected
        if ($file['error'] == 4) return;

        $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);

        if (in_array($fileExt, $ext) === false) {
            $this->addError($field, $message);
            return;
        }

        if (exif_imagetype($file['tmp_name']) === false) {
            $this->addError($field, $message);
        }
    }

    private function email($field, $rule)
    {
        /**
         * @var $message
         */
        extract($rule);

        if (!filter_var($this->getValue($field), FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, $message);
        }
    }

    /**
     * @param $field
     * @param $rule
     */
    private function string($field, $rule)
    {
        /**
         * @var $min
         * @var $max
         * @var $message
         */
        extract($rule);
        $stringLength = strlen($this->getValue($field));

        if ($stringLength < $min || (is_int($max) && $stringLength > $max)) {
            $this->addError($field, $message);
        }
    }

    private function getValue($field, $file = null)
    {
        $method = $file === null ? $this->method : 'FILES';

        return Request::getValue($field, null, $method);
    }

    public function addRule($field, $rule)
    {
        if (!isset($this->rules[$field])) {
            $this->rules[$field] = [];
        }

        $this->rules[$field][] = $rule;
    }

    public function addError($field, $message)
    {
        if (!isset($this->errors[$field])) {
            // if it first error -- create field's error array
            $this->errors[$field] = [];
        }

        $this->errors[$field][] = $message;
    }

    public function validate()
    {
        foreach ($this->rules as $field => $rules) {
            foreach ($rules as $rule) {
                // Call validation method (rule name = method name)
                $this->{$rule['name']}($field, $rule);
            }
        }

        return count($this->errors) ? false : true;
    }
}