<?php
namespace App\Enums; final class PaymentStatus extends StringEnumCast{const UNPAID='unpaid',PARTIAL='partial',PAID='paid',REFUNDED='refunded';public static function values():array{return[self::UNPAID,self::PARTIAL,self::PAID,self::REFUNDED];}}
