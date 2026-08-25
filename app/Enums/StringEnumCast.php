<?php
namespace App\Enums;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;use InvalidArgumentException;
abstract class StringEnumCast implements CastsAttributes{public $value;public function __construct($value=null){$value=$value??static::defaultValue();if(!in_array($value,static::values(),true))throw new InvalidArgumentException('Invalid '.static::class.' value: '.$value);$this->value=$value;}abstract public static function values():array;public static function defaultValue(){return static::values()[0];}public function get($m,string $k,$v,array $a){return $v===null?null:new static($v);}public function set($m,string $k,$v,array $a){return $v instanceof static?$v->value:(new static($v))->value;}public function __toString():string{return $this->value;}}
