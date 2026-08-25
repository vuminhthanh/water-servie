<?php
namespace App\Services;use Illuminate\Support\Str;
class OrderCodeGenerator{public function generate():string{return 'SO'.now()->format('YmdHis').Str::upper(Str::random(4));}}
