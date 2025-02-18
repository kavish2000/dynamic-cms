<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    protected $fillable = [
        'parent_id',
        'slug',
        'title',
        'content'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id')->with('children');
    }

    public function getAvailableParentsAttribute()
    {
        $descendants = $this->getDescendantIds();
        return Page::whereNotIn('id', array_merge($descendants, [$this->id]))->get();
    }

    public function getDescendantIds()
    {
        $ids = [];
        foreach ($this->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->getDescendantIds());
        }
        return $ids;
    }

    public function getFullPathAttribute()
    {
        $path = $this->slug;
        $ancestors = $this->ancestors();
        
        foreach ($ancestors as $ancestor) {
            $path = $ancestor->slug . '/' . $path;
        }
        
        return '/' . $path;
    }

    // Get all ancestors of a page
    public function ancestors()
    {
        $ancestors = collect([]);
        $page = $this;
        
        while ($page->parent) {
            $ancestors->push($page->parent);
            $page = $page->parent;
        }
        
        return $ancestors;
    }

    public function getParents(): string
    {
        $parent = collect([]);
        $page = $this;
    
        while ($page->parent) {
            $parent->prepend($page->parent->title);
            $page = $page->parent;
        }
    
        return $parent->isEmpty() ? $this->title : $parent->join(' -> ');
    }
    
}
