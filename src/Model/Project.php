<?php

namespace App\Model;

/**
 * @TODO для классов Project и Task стоит реализовать родительский абстрактный
 *       класс, который будет унаследован от JsonSerializable и вынести туда
 *       методы getId, jsonSerialize
 *
 * @TODO добавить типизацию методам и аргументам методов
 */
class Project
{
    /**
     * @var array
     */
    public $_data;
    
    public function __construct($data)
    {
        $this->_data = $data;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int) $this->_data['id'];
    }

    /**
     * @TODO при наследовании от абстрактного класса (см. коммент выше) метод не потребуется
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->_data);
    }
}
