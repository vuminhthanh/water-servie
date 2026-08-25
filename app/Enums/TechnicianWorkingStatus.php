<?php
namespace App\Enums; final class TechnicianWorkingStatus extends StringEnumCast{const AVAILABLE='available',BUSY='busy',OFF='off';public static function values():array{return[self::AVAILABLE,self::BUSY,self::OFF];}}
