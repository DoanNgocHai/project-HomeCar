<?php

namespace App\Http\Controllers\User\Report;

use App\Http\Controllers\Controller;
use App\Models\Reports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    private $report;
    function __construct(Reports $report){
        $this->report = $report;
    }    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = $this->report->with('user', 'car')->orderBy("created_at","desc")->get();
        return response()->json($reports, 201);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $params['user_id'] = Auth::id();
        $params['car_id'] = $request->car_id;
        $infoReport = $this->report->create($params);
        return response()->json($infoReport, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
