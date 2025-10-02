<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
USE App\Models\Dashboard;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Assign;

class AssignmentController extends Controller
{
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($locale, Dashboard $dashboard, Assignment $assignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($locale, Dashboard $dashboard, Assignment $assignment)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assignment $assignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($locale, Dashboard $dashboard,Assignment $assignment)
    {
        //
    }
}
