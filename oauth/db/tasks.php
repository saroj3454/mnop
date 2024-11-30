<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$tasks = array(
   
        array(
        'classname' => 'local_oauth\task\Districts',
        'blocking' => 0,
        'minute' => '*',
        'hour' => '1',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*',
    ),
     array(
        'classname' => 'local_oauth\task\CourseMapping',
        'blocking' => 0,
        'minute' => '*',
        'hour' => '1',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*',
    ),
     array(
        'classname' => 'local_oauth\task\UserEnrollments',
        'blocking' => 0,
        'minute' => '*',
        'hour' => '1',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*',
    ),

      array(
        'classname' => 'local_oauth\task\SchoolMapping',
        'blocking' => 0,
        'minute' => '*',
        'hour' => '1',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*',
    ),
    
    
);