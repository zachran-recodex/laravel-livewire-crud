<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    protected $fillable = [
        'title',
        'category',
        'description',
        'image',
        'passengers',
        'range',
        'features',
        'order',
        'is_active',
    ];

    protected $casts = [
        'passengers' => 'integer',
        'range' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    public function getFormattedRangeAttribute()
    {
        return number_format($this->range) . ' nm';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
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
