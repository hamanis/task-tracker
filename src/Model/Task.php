<?php

namespace App\Model;

/**
 * @TODO для классов Project и Task стоит реализовать родительский абстрактный
 *       класс, который будет унаследован от JsonSerializable и вынести туда
 *       методы getId, jsonSerialize
 *
 * @TODO добавить типизацию методам и аргументам методов
 */
class Task implements \JsonSerializable
{
    /**
     * @var array
     */
    private $_data;
    
    public function __construct($data)
    {
        $this->_data = $data;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        /**
         * @TODO необходимо преобразовать в json
         */
        return $this->_data;
    }
}
