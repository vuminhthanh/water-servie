<?php
namespace App\Enums; final class TechnicianAssignmentStatus extends StringEnumCast{const ASSIGNED='assigned',ACCEPTED='accepted',REJECTED='rejected',IN_PROGRESS='in_progress',COMPLETED='completed',CANCELLED='cancelled';public static function values():array{return[self::ASSIGNED,self::ACCEPTED,self::REJECTED,self::IN_PROGRESS,self::COMPLETED,self::CANCELLED];}}
