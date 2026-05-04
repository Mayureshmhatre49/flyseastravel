<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'title', 'slug', 'location', 'country', 'category', 'badge',
        'days', 'nights', 'tier', 'price_per_person', 'rating', 'review_count',
        'description', 'overview', 'highlights', 'inclusions', 'exclusions',
        'includes_icons', 'hero_image', 'gallery_images', 'is_featured',
        'is_active', 'sort_order', 'seats_left',
    ];

    protected $casts = [
        'highlights'     => 'array',
        'inclusions'     => 'array',
        'exclusions'     => 'array',
        'includes_icons' => 'array',
        'gallery_images' => 'array',
        'is_featured'    => 'boolean',
        'is_active'      => 'boolean',
        'rating'         => 'decimal:1',
    ];

    public function packageDays(): HasMany
    {
        return $this->hasMany(PackageDay::class)->orderBy('day_number');
    }

    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return '₹' . number_format($this->price_per_person);
    }

    public function getDurationLabelAttribute(): string
    {
        return "{$this->days}D / {$this->nights}N";
    }

    /** SEO-ready page title (under 60 chars where possible) */
    public function getMetaTitleAttribute(): string
    {
        return "{$this->title} — {$this->days}D/{$this->nights}N from " . $this->formatted_price . ' | FlySeas Travels';
    }

    /** SEO-ready meta description (under 160 chars) */
    public function getMetaDescriptionAttribute(): string
    {
        $duration = "{$this->days} days {$this->nights} nights";
        $price    = '₹' . number_format($this->price_per_person);
        $base     = "{$this->title} — {$duration} {$this->location} {$this->category} package starting from {$price} per person.";

        if ($this->description) {
            $extra = ' ' . trim($this->description);
            if (strlen($base . $extra) <= 160) {
                $base .= $extra;
            }
        }

        return mb_substr($base, 0, 157) . (mb_strlen($base) > 157 ? '…' : '');
    }

    /** Comma-separated keywords for meta tag */
    public function getMetaKeywordsAttribute(): string
    {
        return implode(', ', array_filter([
            $this->title,
            $this->location,
            $this->country,
            ucfirst($this->category) . ' package',
            "{$this->location} tour package",
            'FlySeas Travels',
            'Nagpur travel agency',
        ]));
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
