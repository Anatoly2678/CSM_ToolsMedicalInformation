<?php
/*
 * Copyright Olegs Capligins, 2013
 *
 * This file is fork of BMPM (Beider-Morse Phonetic Matching System)
 * Copyright: Stephen P. Morse, 2005.
 * Website:   http://stevemorse.org/phoneticinfo.htm
 *
 * This is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * It is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public
 * License for more details.
 *
 * You should have received a copy of the GNU General Public License.
 * If not, see <http://www.gnu.org/licenses/>.
 */

// THE LINES BELOW WERE VALID FOR ASHKENAZIM
$this->approx[ $this->getLanguageIndexByName('french') ] = array(

    array("I", "", "$", "i"),
    array("I", "[aEIeiou]", "", "i"),
    array("I", "", "[^k]$", "i"),
    array("Ik", "[lr]", "$", "(ik|Qk)"),
    array("Ik", "", "$", "ik"),
    array("sIts", "", "$", "(sits|sQts)"),
    array("Its", "", "$", "its"),
    array("I", "", "", "(i|Q)"),

    array("au", "", "", "(D|a|u)"),
    array("ou", "", "", "(D|o|u)"),
    array("ai", "", "", "(D|a|i)"),
    array("oi", "", "", "(D|o|i)"),
    array("ui", "", "", "(D|u|i)"),

    array("a", "", "", "(a|o)"),
    array("e", "", "", "i"),

    array("E", "", "[fklmnprsStv]$", "i"),
    array("E", "", "ts$", "i"),
    array("E", "[aoiuQ]", "", "i"),
    array("E", "", "[aoQ]", "i"),
    array("E", "", "", "(Y|i)"),

    array("approxfrench")

);