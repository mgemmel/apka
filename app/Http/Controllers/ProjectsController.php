<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        if(Auth::check()) {
            $projects = Project::where('user_id', Auth::user()->id)->get();
            return view('projects.index', ['projects' => $projects]);
        }
        return view('auth.login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::check()){
            $project = Project::create([
                'name'=>$request->input('name'),
                'description'=>$request->input('description'),
                'user_id'=>Auth::user()->id
            ]);

            if($project){
                return redirect()->route('projects.show',['project.show'=>$project->id])
                    ->with('success','project created successfully');
            }
        }
        return back()->withInput()->with('errors','Error creating new project');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(project $project)
    {
        //prva možnost
        //$project=Project::where('id',$project->id)->first();

        //druha možnosť
        $project=Project::find($project->id);

        return view('projects.show',['project'=>$project]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(project $project)
    {
        $project=Project::find($project->id);

        return view('projects.edit',['project'=>$project]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, project $project)
    {
        $projectupdate=Project::where('id',$project->id)->update([
            'name'=>$request->input('name'),
            'description'=>$request->input('description')
        ]);
        if($projectupdate){
            return redirect()->route('projects.show',['project'=>$project->id])->with('success','project updated successfully');
        }
        return back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(project $project)
    {
        $deleteproject = Project::find($project->id);
        if($deleteproject->delete()){
            return redirect()->route('projects.index')->with('success','project deleted successfully');
        }else{
            return back()->withInput()->with('errors','project could not be deleted');
        }
    }
}