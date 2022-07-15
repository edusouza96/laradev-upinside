<?php

namespace LaraDev;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use LaraDev\Support\Cropper;

class PropertyImage extends Model
{
    protected $fillable = [
        'property',
        'path',
        'cober',
    ];

    public function getUrlCroppedAttribute()
    {
        return Storage::url(Cropper::thumb($this->path, 1366, 768));
    }
}
