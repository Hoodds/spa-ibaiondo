<?php
class Validator {
  /**
   * Errores de validación
   * @var array
   */
  private $errors = [];
  
  /**
   * Datos a validar
   * @var array
   */
  private $data = [];
  
  /**
   * Reglas de validación
   * @var array
   */
  private $rules = [];
  
  /**
   * Mensajes de error personalizados
   * @var array
   */
  private $messages = [];
  
  /**
   * Constructor
   * @param array $data Datos a validar
   * @param array $rules Reglas de validación
   * @param array $messages Mensajes de error personalizados
   */
  public function __construct($data = [], $rules = [], $messages = []) {
      $this->data = $data;
      $this->rules = $rules;
      $this->messages = $messages;
  }
  
  /**
   * Establece los datos a validar
   * @param array $data Datos a validar
   * @return Validator
   */
  public function setData($data) {
      $this->data = $data;
      return $this;
  }
  
  /**
   * Establece las reglas de validación
   * @param array $rules Reglas de validación
   * @return Validator
   */
  public function setRules($rules) {
      $this->rules = $rules;
      return $this;
  }
  
  /**
   * Establece los mensajes de error personalizados
   * @param array $messages Mensajes de error personalizados
   * @return Validator
   */
  public function setMessages($messages) {
      $this->messages = $messages;
      return $this;
  }
  
  /**
   * Valida los datos según las reglas establecidas
   * @return bool
   */
  public function validate() {
      $this->errors = [];
      
      foreach ($this->rules as $field => $ruleString) {
          $rules = explode('|', $ruleString);
          
          foreach ($rules as $rule) {
              // Separar la regla y sus parámetros
              $parameters = [];
              if (strpos($rule, ':') !== false) {
                  list($rule, $parameterString) = explode(':', $rule, 2);
                  $parameters = explode(',', $parameterString);
              }
              
              // Obtener el valor del campo
              $value = $this->getValue($field);
              
              // Validar según la regla
              $method = 'validate' . ucfirst($rule);
              if (method_exists($this, $method)) {
                  $result = $this->$method($field, $value, $parameters);
                  if (!$result) {
                      break; // Si falla una regla, no seguir validando este campo
                  }
              }
          }
      }
      
      return empty($this->errors);
  }
  
  /**
   * Obtiene el valor de un campo
   * @param string $field Nombre del campo
   * @return mixed
   */
  private function getValue($field) {
      return $this->data[$field] ?? null;
  }
  
  /**
   * Añade un error de validación
   * @param string $field Nombre del campo
   * @param string $rule Regla que falló
   * @param array $parameters Parámetros de la regla
   */
  private function addError($field, $rule, $parameters = []) {
      $message = $this->messages[$field . '.' . $rule] ?? $this->getDefaultMessage($field, $rule, $parameters);
      $this->errors[$field][] = $message;
  }
  
  /**
   * Obtiene el mensaje de error por defecto
   * @param string $field Nombre del campo
   * @param string $rule Regla que falló
   * @param array $parameters Parámetros de la regla
   * @return string
   */
  private function getDefaultMessage($field, $rule, $parameters) {
      $messages = [
          'required' => 'El campo :field es obligatorio.',
          'email' => 'El campo :field debe ser una dirección de correo válida.',
          'min' => 'El campo :field debe tener al menos :min caracteres.',
          'max' => 'El campo :field no puede tener más de :max caracteres.',
          'numeric' => 'El campo :field debe ser un número.',
          'alpha' => 'El campo :field solo puede contener letras.',
          'alpha_num' => 'El campo :field solo puede contener letras y números.',
          'alpha_dash' => 'El campo :field solo puede contener letras, números, guiones y guiones bajos.',
          'date' => 'El campo :field debe ser una fecha válida.',
          'confirmed' => 'La confirmación del campo :field no coincide.',
          'same' => 'Los campos :field y :other deben coincidir.',
          'different' => 'Los campos :field y :other deben ser diferentes.',
          'in' => 'El campo :field seleccionado no es válido.',
          'not_in' => 'El campo :field seleccionado no es válido.',
          'unique' => 'El valor del campo :field ya está en uso.',
          'exists' => 'El campo :field seleccionado no existe.',
          'regex' => 'El formato del campo :field no es válido.',
      ];
      
      $message = $messages[$rule] ?? 'El campo :field no es válido.';
      
      // Reemplazar placeholders
      $message = str_replace(':field', $field, $message);
      
      if ($rule === 'min') {
          $message = str_replace(':min', $parameters[0], $message);
      } elseif ($rule === 'max') {
          $message = str_replace(':max', $parameters[0], $message);
      } elseif ($rule === 'same' || $rule === 'different') {
          $message = str_replace(':other', $parameters[0], $message);
      }
      
      return $message;
  }
  
  /**
   * Obtiene todos los errores de validación
   * @return array
   */
  public function getErrors() {
      return $this->errors;
  }
  
  /**
   * Obtiene los errores de un campo específico
   * @param string $field Nombre del campo
   * @return array
   */
  public function getFieldErrors($field) {
      return $this->errors[$field] ?? [];
  }
  
  /**
   * Obtiene el primer error de un campo específico
   * @param string $field Nombre del campo
   * @return string|null
   */
  public function getFirstFieldError($field) {
      return $this->errors[$field][0] ?? null;
  }
  
  /**
   * Valida que un campo sea obligatorio
   * @param string $field Nombre del campo
   * @param mixed $value Valor del campo
   * @param array $parameters Parámetros de la regla
   * @return bool
   */
  private function validateRequired($field, $value, $parameters) {
      $valid = false;
      
      if (is_null($value)) {
          $valid = false;
      } elseif (is_string($value) && trim($value) === '') {
          $valid = false;
      } elseif (is_array($value) && count($value) < 1) {
          $valid = false;
      } else {
          $valid = true;
      }
      
      if (!$valid) {
          $this->addError($field, 'required');
      }
      
      return $valid;
  }
  
  /**
   * Valida que un campo sea un email válido
   * @param string $field Nombre del campo
   * @param mixed $value Valor del campo
   * @param array $parameters Parámetros de la regla
   * @return bool
   */
  private function validateEmail($field, $value, $parameters) {
      if (empty($value)) {
          return true;
      }
      
      $valid = filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
      
      if (!$valid) {
          $this->addError($field, 'email');
      }
      
      return $valid;
  }
  
  /**
   * Valida que un campo tenga una longitud mínima
   * @param string $field Nombre del campo
   * @param mixed $value Valor del campo
   * @param array $parameters Parámetros de la regla
   * @return bool
   */
  private function validateMin($field, $value, $parameters) {
      if (empty($value)) {
          return true;
      }
      
      $min = (int) $parameters[0];
      $valid = mb_strlen($value) >= $min;
      
      if (!$valid) {
          $this->addError($field, 'min', $parameters);
      }
      
      return $valid;
  }
  
  /**
   * Valida que un campo tenga una longitud máxima
   * @param string $field Nombre del campo
   * @param mixed $value Valor del campo
   * @param array $parameters Parámetros de la regla
   * @return bool
   */
  private function validateMax($field, $value, $parameters) {
      if (empty($value)) {
          return true;
      }
      
      $max = (int) $parameters[0];
      $valid = mb_strlen($value) <= $max;
      
      if (!$valid) {
          $this->addError($field, 'max', $parameters);
      }
      
      return $valid;
  }
  
  /**
   * Valida que un campo sea numérico
   * @param string $field Nombre del campo
   * @param mixed $value Valor del campo
   * @param array $parameters Parámetros de la regla
   * @return bool
   */
  private function validateNumeric($field, $value, $parameters) {
      if (empty($value)) {
          return true;
      }
      
      $valid = is_numeric($value);
      
      if (!$valid) {
          $this->addError($field, 'numeric');
      }
      
      return $valid;
  }
  
  /**
   * Valida que un campo coincida con otro
   * @param string $field Nombre del campo
   * @param mixed $value Valor del campo
   * @param array $parameters Parámetros de la regla
   * @return bool
   */
  private function validateSame($field, $value, $parameters) {
      $otherField = $parameters[0];
      $otherValue = $this->data[$otherField] ?? null;
      
      $valid = $value === $otherValue;
      
      if (!$valid) {
          $this->addError($field, 'same', $parameters);
      }
      
      return $valid;
  }
  
  /**
   * Valida que un campo sea diferente a otro
   * @param string $field Nombre del campo
   * @param mixed $value Valor del campo
   * @param array $parameters Parámetros de la regla
   * @return bool
   */
  private function validateDifferent($field, $value, $parameters) {
      $otherField = $parameters[0];
      $otherValue = $this->data[$otherField] ?? null;
      
      $valid = $value !== $otherValue;
      
      if (!$valid) {
          $this->addError($field, 'different', $parameters);
      }
      
      return $valid;
  }
  
  /**
   * Valida que un campo esté en una lista de valores
   * @param string $field Nombre del campo
   * @param mixed $value Valor del campo
   * @param array $parameters Parámetros de la regla
   * @return bool
   */
  private function validateIn($field, $value, $parameters) {
      if (empty($value)) {
          return true;
      }
      
      $valid = in_array($value, $parameters);
      
      if (!$valid) {
          $this->addError($field, 'in');
      }
      
      return $valid;
  }
  
  /**
   * Valida que un campo no esté en una lista de valores
   * @param string $field Nombre del campo
   * @param mixed $value Valor del campo
   * @param array $parameters Parámetros de la regla
   * @return bool
   */
  private function validateNotIn($field, $value, $parameters) {
      if (empty($value)) {
          return true;
      }
      
      $valid = !in_array($value, $parameters);
      
      if (!$valid) {
          $this->addError($field, 'not_in');
      }
      
      return $valid;
  }
  
  /**
   * Valida que un campo sea una fecha válida
   * @param string $field Nombre del campo
   * @param mixed $value Valor del campo
   * @param array $parameters Parámetros de la regla
   * @return bool
   */
  private function validateDate($field, $value, $parameters) {
      if (empty($value)) {
          return true;
      }
      
      $format = $parameters[0] ?? 'Y-m-d';
      $date = \DateTime::createFromFormat($format, $value);
      $valid = $date && $date->format($format) === $value;
      
      if (!$valid) {
          $this->addError($field, 'date');
      }
      
      return $valid;
  }
}

