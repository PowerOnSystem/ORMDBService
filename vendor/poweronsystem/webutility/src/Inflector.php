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
 * Inflector
 * @author Lucas Sosa
 * @version 0.1
 */
class Inflector {
    /**
     * Singulariza una palabra
     * @param string $text
     * @return string
     */
    public static function singularize($text) {
        $length = strlen($text);
        $last_word = substr($text, $length - 3);
        $return = $text;
        switch ($last_word) {
            case 'ies'  : $return = substr($text, 0, $length - 3) . 'y'; break;
            default     : $return = substr($text, 0, $length - 1); break;
        }
        return $return;
    }
    
    /**
     * Pluraliza una palabra
     * @param string $text
     * @return string
     */
    public static function pluralize($text) {
        $last_letter = strtolower($text[strlen($text)-1]);
        switch ($last_letter) {
            case 'y'    : $return = substr($text, 0, -1) . 'ies'; break;
            case 's'    : $return = $text . 'es'; break;
            default     : $return = $text . 's'; break;
        }
        return $return;
    }
    
    /**
     * Convierte una palabra en formato de clase
     * @param string $text
     * @return string
     */
    public static function classify($text) {
        return preg_replace('/ /', '', ucwords(preg_replace('/_/', ' ', $text)));
    }
}
