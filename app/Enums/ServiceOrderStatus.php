<?php
namespace App\Enums; final class ServiceOrderStatus extends StringEnumCast{const NEW='new',CONFIRMED='confirmed',ASSIGNED='assigned',ON_THE_WAY='on_the_way',IN_PROGRESS='in_progress',COMPLETED='completed',CANCELLED='cancelled';public static function values():array{return[self::NEW,self::CONFIRMED,self::ASSIGNED,self::ON_THE_WAY,self::IN_PROGRESS,self::COMPLETED,self::CANCELLED];}}
