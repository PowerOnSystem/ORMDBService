<?php

/*
 * Copyright (C) Makuc Julian & Makuc Diego S.H.
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
 * Arr
 * @author Lucas Sosa
 * @version 0.1
 * @copyright (c) 2016, Lucas Sosa
 */
class Arr {

    /**
     * Busca un valor especifico dentro de un array multidimensional
     * @param array $array El array donde buscar
     * @param string $key La columna donde buscar
     * @param mix $search El parametro a buscar
     * @return boolean
     */
    public static function srchkey(array $array, $key, $search) {
        foreach($array as $index => $value) {
            if (!is_array($value) || !key_exists($key, $value)) {
                return FALSE;
            }
            if ( $value[$key] == $search ) {
                return $index;
            }
        }
        return FALSE;
    }
    /**
     * Busca un valor especifico dentro de un array multidimensional
     * @param array $array El array donde buscar
     * @param mix $search El parametro a buscar
     * @return boolean
     */
    public static function srchfull(array $array, $search) {
        $r = FALSE;
        foreach ($array as $value) {
            if ( is_array($value) ) {
                if ( self::srchfull($value, $search) ) {
                    return TRUE;
                }
            } else if ($r = $value == $search) {
                return TRUE;
            }
        }
        return $r;
    }

    /**
     * Obtiene un valor específico de un array y lo elimina
     * @param array $array El array a obtener el valor
     * @param mix $key El valor a obtener
     * @return array Devuelve el valor solicitado
     */
    public static function trim(array &$array, $key) {
        if ( !key_exists($key, $array) ) {
            return NULL;
        }
        $value = $array[$key];
        unset ( $array[$key] );
        return $value;
    }
    
    /**
     * Obtiene varias columnas especificas de un array
     * @param array $array El array
     * @param array $columns_keys Array con los nombres de las columnas
     * @return array
     */
    function multicolumn(array $array, array $columns_keys) {
        $new = [];

        foreach ($array as $k => $c) {
            foreach ($columns_keys as $col) {
                if ( (is_array($c) && array_key_exists($col, $c)) || (is_object($c) && property_exists($c, $col))) {
                    $new[$k][$col] = is_object($c) ? $c->{ $col } : $c[$col];
                }
            }
        }
        return $new;
    }
}
