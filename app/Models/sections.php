<?php

namespace App\Models;

use App\Http\Enum\SectionVisibilityEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sections extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','name','visibility'];

    protected $casts = [
      'visibility' => SectionVisibilityEnum::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(attributes::class,attributes_sections::class,'section_id','attribute_id');
    }
}
