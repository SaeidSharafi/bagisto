<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\CMS\Models\CmsPage;

class CmsCategories extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'slug'
    ];

    public function posts()
    {
        return $this->hasMany(CmsPage::class);
    }
}
