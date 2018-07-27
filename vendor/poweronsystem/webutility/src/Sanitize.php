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
 * Sanitize procesa todo valor enviado por un formulario
 * @author Lucas Sosa
 * @version 0.1
 */
class Sanitize {
    
    /** Deja sin cambios pero previene espacios y demas de un tring */
    const SANITIZE_STRING = 0;
    /** Convierte el valor en un integer */
    const SANITIZE_NUMBER = 1;
    /** Convierte el valor en decimal según base de datos */
    const SANITIZE_DECIMAL = 2;
    /** Todo mayúscula */
    const SANITIZE_UPPER = 3;
    /** Todo minúscula */
    const SANITIZE_LOWER = 4;
    /** Mayúscula la primer letra de una cadena */
    const SANITIZE_TITLE = 5;
    /** Mayúscula la primer letra de cada palabra */
    const SANITIZE_UPPER_FIRST = 6;
    /** Codifica una url */
    const SANITIZE_URL = 7;
    /** Comprueba que sea una fecha válida */
    const SANITIZE_DATE = 8;
    /** Comprueba que sea una fecha y hora válida */
    const SANITIZE_DATE_TIME = 9;
    /** Comprueba que sea una hora válida */
    const SANITIZE_TIME = 10;
    /** Comprueba que sea un email válido */
    const SANITIZE_EMAIL = 11;
    /** Comprueba que sea una json codificado válido */
    const SANITIZE_JSON = 12;
    
    /**
     * Sanitiza sin ningún cambio
     * @param string $value
     * @return string
     */
    public static function clearNatural($value) {
        return addslashes(stripslashes(trim(filter_var($value, FILTER_SANITIZE_STRING))));
    }
    
    /**
     * Todo en mayúsculas
     * @param string $value
     * @return string
     */
    public static function clearUpper($value) {
        return addslashes(stripslashes(mb_convert_case(filter_var($value, FILTER_SANITIZE_STRING), MB_CASE_UPPER, 'UTF-8')));
    }
    
    /**
     * Todo en minúsculas
     * @param string $value
     * @return string
     */
    public static function clearLower($value) {
        return addslashes(stripslashes(mb_convert_case(filter_var($value, FILTER_SANITIZE_STRING), MB_CASE_LOWER, 'UTF-8')));
    }
    
    /**
     * Mayúscula la primer letra de cada palabra
     * @param string $value
     * @return string
     */
    public static function clearUpperFirst($value) {
        return addslashes(stripslashes(mb_convert_case(filter_var($value, FILTER_SANITIZE_STRING), MB_CASE_TITLE, 'UTF-8')));
    }
    
    /**
     * Mayúscula la primer letra de la oración
     * @param string $value
     * @return string
     */
    public static function clearTitle($value) {
        return addslashes(stripslashes(\PowerOn\Core\PowerOnUCFirst(filter_var($value, FILTER_SANITIZE_STRING))));
    }
    /**
     * 
     * Convierte en integer
     * @param string $value
     * @return string
     */
    public static function clearInteger($value) {
        return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }
    
    /**
     * Formatea en modo decimal
     * @param string $value
     * @return string
     */
    public static function clearDecimal($value) {
        return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
    }
    
    /**
     * Sanitiza una URL
     * @param string $value
     * @return string
     */
    public static function clearUrl($value) {
        return addslashes(stripslashes(filter_var($value, FILTER_SANITIZE_URL)));
    }
    
    /**
     * Sanitiza un Email
     * @param string $value
     * @return string
     */
    public static function clearEmail($value) {
        return addslashes(stripslashes(filter_var($value, FILTER_SANITIZE_EMAIL)));
    }
    
    /**
     * Filtra una cadena de tipo json
     * @param string $value
     * @return string
     */
    public static function clearJSON($value) {
        return filter_var($value, FILTER_DEFAULT);
    }
    
    /**
     * Comprueba una fecha y hora válidas
     * @param string $value
     * @return string
     */
    public static function clearDateTime($value) {
        $date_time = \DateTime::createFromFormat(CNC_DATE_TIME_FORMAT, $value);
        return $date_time ? $date_time->format(CNC_DB_DATE_TIME_FORMAT) : NULL;
    }
    
    /**
     * Comprueba una fecha válida
     * @param string $value
     * @return string
     */
    public static function clearDate($value) {
        $date_time = \DateTime::createFromFormat(CNC_DATE_FORMAT, $value);
        return $date_time ? $date_time->format(CNC_DB_DATE_FORMAT) : FALSE;
    }
    
    /**
     * Comprueba una hora válida
     * @param string $value
     * @return string
     */
    public static function clearTime($value) {
        $date_time = \DateTime::createFromFormat(CNC_TIME_FORMAT, $value);
        return $date_time ? $date_time->format(CNC_DB_TIME_FORMAT) : FALSE;
    }
    
    /**
     * Procesa un listado de variables a sanitizar
     * @param string $value
     * @param integer $mode El modo de sanazión
     * @return string
     */
    public static function process($value, $mode = self::SANITIZE_STRING) {
        $r = NULL;
        if ( is_array($value) ) {
            $data = $value;
            foreach ($value as $k => $s) {
                $data[$k] = self::process($s, is_array($mode) ? $mode[$k] : $mode); 
            }
            return $data;
        }
        
        if ( is_object($mode) ) {
            $r = $mode();
        } else {
            switch ($mode) {
                case self::SANITIZE_JSON        : $r = self::clearJSON($value); break;
                case self::SANITIZE_UPPER       : $r = self::clearUpper($value); break;
                case self::SANITIZE_LOWER       : $r = self::clearLower($value); break;
                case self::SANITIZE_UPPER_FIRST : $r = self::clearUpperFirst($value); break;
                case self::SANITIZE_TITLE       : $r = self::clearTitle($value); break;
                case self::SANITIZE_NUMBER      : $r = self::clearInteger($value); break;
                case self::SANITIZE_DECIMAL     : $r = self::clearDecimal($value); break;
                case self::SANITIZE_EMAIL       : $r = self::clearEmail($value); break;
                case self::SANITIZE_DATE_TIME   : $r = self::clearDateTime($value); break;
                case self::SANITIZE_DATE        : $r = self::clearDate($value); break;
                case self::SANITIZE_TIME        : $r = self::clearTime($value); break;
                case self::SANITIZE_URL         : $r = self::clearUrl($value); break;
                case self::SANITIZE_STRING      : $r = self::clearNatural($value); break;
                default : $r = NULL;                    break;
            }
        }
        
        return $r;
    }
}
