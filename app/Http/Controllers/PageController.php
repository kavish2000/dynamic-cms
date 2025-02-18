<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Page;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $pages = Page::with('children')->whereNull('parent_id')->get();
            return Inertia::render('Pages/Index', [
                'pages' => $pages
            ]);
        } catch (Exception $e) {
            return Inertia::render('Pages/Index', [
                'pages' => [],
                'error' => 'Failed to load pages. ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {

            $pages = Page::with(['children', 'parent'])->get()->map(function ($page) {
                return [
                    'id' => $page->id,
                    'title' => $page->title,
                    'parent_id' => $page->parent_id,
                    'parent_title' => $page->getParents(),
                    'children' => $page->children->map(fn($child) => [
                        'id' => $child->id,
                        'title' => $child->title,
                    ])
                ];
            });

            return Inertia::render('Pages/Create', [
                'pages' => $pages
            ]);
        } catch (Exception $e) {
            return Inertia::render('Pages/Create', [
                'pages' => [],
                'error' => 'Failed to load page creation form. ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:pages,id',
            'slug' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);
        try {
            DB::beginTransaction();

            Page::create($validated);

            DB::commit();

            return redirect()->route('pages.index')->with('success', 'Page created successfully');
        } catch (Exception $e) {
            DB::rollBack();
             return redirect()->route('pages.create')->with('error', 'Failed to create page. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        try {
            $path = $request->path();
            $slugs = explode('/', $path);
            
            $page = null;
            $parent_id = null;
            $currentPath = '';
            
            foreach ($slugs as $slug) {
                if (empty($slug)) continue;
                
                $page = Page::where('slug', $slug)
                            ->where('parent_id', $parent_id)
                            ->firstOrFail();
                            
                $parent_id = $page->id;                
                $currentPath = $currentPath ? "$currentPath/$slug" : $slug;
            }
            
            // we have the page with all children
            $page->load(['children' => function ($query) {
                $query->with('children');
            }]);
            
            // Function to recursively set full path on children
            $buildPaths = function ($pages, $basePath) use (&$buildPaths) {
                foreach ($pages as $childPage) {
                    $childPage->full_path = "$basePath/{$childPage->slug}";
                    
                    if ($childPage->children && $childPage->children->isNotEmpty()) {
                        $buildPaths($childPage->children, $childPage->full_path);
                    }
                }
            };
            
            // Start building paths from the current page
            $buildPaths($page->children, $currentPath);
            
            // Create with full paths
            $breadcrumbs = $page->ancestors();
            $breadcrumbPath = '';
            
            foreach ($breadcrumbs as $crumb) {
                $breadcrumbPath = $breadcrumbPath ? "$breadcrumbPath/{$crumb->slug}" : $crumb->slug;
                $crumb->full_path = "/$breadcrumbPath";
            }
            
            // Add current page to breadcrumbs
            $page->full_path = "/$currentPath";
            $breadcrumbs->push($page);
            
            return Inertia::render('Pages/Show', [
                'page' => $page,
                'breadcrumbs' => $breadcrumbs,
                'currentPath' => $currentPath
            ]);
            
        } catch (Exception $e) {
            return Inertia::render('Pages/Show', [
                'error' => 'Page not found. ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        try {
            return Inertia::render('Pages/Edit', [
                'page' => $page,
                'pages' => Page::all(),
                'availableParents' => $page->availableParents
            ]);
        } catch (Exception $e) {
            return Inertia::render('Pages/Edit', [
                'error' => 'Failed to load edit form. ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:pages,id',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $page->update($validated);

            DB::commit();

            return Inertia::render('Pages/Index', [
                'pages' => Page::with('children')->whereNull('parent_id')->get(),
                'success' => 'Page updated successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return Inertia::render('Pages/Edit', [
                'page' => $page,
                'error' => 'Failed to update page. ' . $e->getMessage()
            ]);
        }
    
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        try {
            DB::beginTransaction();

            $page->delete();

            DB::commit();
            return redirect()->route('pages.index')->with('success', 'Page deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('pages.index')->with('error', 'Failed to delete page. ' . $e->getMessage());
        }
    }
}
