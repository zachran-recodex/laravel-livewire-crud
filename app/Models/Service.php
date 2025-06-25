<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    protected $fillable = [
        'title',
        'description',
        'image',
        'features',
        'order',
        'is_active'
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function addFeature($feature)
    {
        $features = $this->features ?? [];
        $features[] = $feature;
        $this->features = $features;
        return $this;
    }

    public function removeFeature($feature)
    {
        $features = $this->features ?? [];
        $features = array_filter($features, fn($f) => $f !== $feature);
        $this->features = array_values($features);
        return $this;
    }

    public function hasFeature($feature)
    {
        return in_array($feature, $this->features ?? []);
    }
}
