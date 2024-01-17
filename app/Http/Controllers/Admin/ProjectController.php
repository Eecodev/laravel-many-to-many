<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Project;
use App\Models\Category;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.projects.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $formData = $request->validated();
        $slug = Str::slug($formData['title'], '-');
        $formData['slug'] = $slug;
        // $userId = auth()->id();

        if($request->hasFile('image')){
            $img_path = Storage::put('images', $request->image);
            $formData['image'] = $img_path;
        }
        $project = Project::create($formData);
        return redirect()->route('admin.projects.show', $project->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $categories = Category::all();
        return view('admin.projects.edit', compact('project', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $formData = $request->validated();
        $formData['slug'] = $project->slug;

        if ($project->title !== $formData['title']){
            //create slug
            $slug = Str::slug($formData['title'], '-');
            //add slug to form data
            $formData['slug'] = $slug;
        }

        // $userId = auth()->id();
        if($request->hasFile('image')){
            if ($project->image) {
                Storage::delete($project->image);
            }

            $img_path = Storage::put('image', $formData['image']);
            $formData['image'] = $img_path;
        }
        $project->update($formData);
        return redirect()->route('admin.projects.show', $project->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if ($project->image){
            Storage::delete($project->image);
        }

        $project-delete();
        return to_route('admin.projects.index')->with('message', "$project->title eliminato con successo");
    }
}
