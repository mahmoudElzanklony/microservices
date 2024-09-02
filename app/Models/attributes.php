<?php

namespace App\Models;

use App\Http\Enum\SectionVisibilityEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attributes extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','name','label','placeholder','type','icon','visibility'];

    protected $casts = [
      'visibility'=>SectionVisibilityEnum::class
    ];

    public function options()
    {
        return $this->hasMany(attribute_options::class,'attribute_id');
    }


}
