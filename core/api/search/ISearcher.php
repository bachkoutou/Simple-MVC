<?php 
/**
 * Note : Code is released under the GNU LGPL
 *
 * Please do not change the header of this file 
 *
 * This library is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * Lesser General Public License as published by the Free Software Foundation; either version 2 of 
 * the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 *
 * See the GNU Lesser General Public License for more details.
 */

/**
 * File:        ISearcher.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * ISearcher Interface
 * Fixes the contract for all search plugins
 * 
 */
interface ISearcher
{
    /**
     * does the search
     * should be implemented in the ISearcher 
     * 
     * @param  string  $query   The search query
     * @param  int     $offset  The offset
     * @param  int     $limit   The limit
     * @return mixed   The search results   
     */
     public function search($query,$offset,$limit);
}    
