<?php

/*
 * Copyright (C) PowerOn Sistemas
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace PowerOn\Utility;

/**
 * Hash son utilidades para operar un array
 * @author Lucas Sosa
 * @version 0.1
 * @copyright (c) 2016, Lucas Sosa
 */
class Hash {
    /**
     * Devuelve un array asociativo en base a un texto
     * @param string $path
     * @return array
     */
    private static function _read($path, $value = NULL) {
        $n = explode('.', $path);
        $result = array();
        for ($i = count($n) - 1; $i >= 0; --$i) {
            $result[$n[$i]] = $value;
            $value = $result;
            if ($i > 0) {
                unset($result[$n[$i]]);
            }
        }
        
        return $result;
    }
    
    /**
     * Busca la interseccion con otro array y la devuelve
     * @param array $array1
     * @param array $array2
     * @return array 
     */
    private static function _intersect(array $array1, array $array2) {
        $array = array_intersect_key($array1, $array2);
        if (empty($array)) {
            return NULL;
        }
        foreach ($array as $key => &$value) {
            if ( is_array($value) && is_array($array2[$key]) ) {
                $value = self::_intersect($value, $array2[$key]);
            }
        }
        return reset($array);
    }
    
    /**
     * Devuelve la diferencia entre dos arrays multidimensionales
     * @param array $array1
     * @param array $array2
     * @return array
     */
    private static function _diff(array $array1, array $array2){
        $result = array();
        foreach ($array1 as $key => $value) {
            if ( key_exists($key, $array2) ) {
               if ( is_array($value) && is_array($array2[$key]) ){
                   $result[$key] = self::_diff($value, $array2[$key]);
               }
            } else {
               $result[$key] = $value;
            }
        }

        return $result;
    }
    /**
     * Devuelve el último elemento de un array
     * @param array $array
     * @return mix
     */
    private static function _last(array $array) {
        foreach ($array as $a) {
            if ( !is_array($a) || count($a) > 1 ) {
                return $a;
            } else {
                return self::_last($a);
            }
        }
        return NULL;
    }
    
    /**
     * Devuelve el valor especificado de un array
     * @param array $array
     * @param string $path
     * @return mix
     */
    public static function get(array $array, $path) {
        $read = self::_read($path);
        $intersection = self::_intersect($array, $read);
        return $intersection;
    }
    
    /**
     * Agrega un elemento a un array
     * @param array $array
     * @param string $path
     * @param mix $value
     * @return array el array con el nuevo elemento
     */
    public static function write(array $array, $path, $value) {
        $read = self::_read($path, $value);
        
        return array_merge($array, $read);
    }
    
    /**
     * Agrega un elemento a un array
     * @param array $array
     * @param string $path
     * @param mix $value
     * @return array el array con el nuevo elemento
     */
    public static function insert(array $array, $path, $value) {
        $read = self::_read($path, $value);
        
        return array_merge_recursive($array, $read);
    }
    
    /**
     * Elimina un elemento del array
     * @param array $array
     * @param string $path
     * @return array El array sin el elemento borrado
     */
    public static function remove(array $array, $path) {
        $read = self::_read($path);
        $result = self::_diff($array, $read);
        return $result;
    }
    
    /**
     * Verifica si el contenido existe en un array
     * @param array $array
     * @param string $path
     * @return boolean
     */
    public static function check(array $array, $path = NULL) {
        return self::get($array, $path) !== NULL;
    }
    
}
