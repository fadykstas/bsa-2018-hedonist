<?php

namespace Hedonist\Exceptions\Auth;

class InvalidUserDataException extends \Exception
{
   public static function create(){
       return new self("Sorry, looks like your data is corrupted. Please, contact our customer support");
   }
}