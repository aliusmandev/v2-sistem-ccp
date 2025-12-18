<?php

namespace App\Http\Controllers;

use App\Models\FeasibilityStudy;
use Illuminate\Http\Request;

class FeasibilityStudyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($idPengajuan, $idPengajuanItem)
    {
        $idPengajuan = decrypt($idPengajuan);
        $idPengajuanItem = decrypt($idPengajuanItem);

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
    public function show(FeasibilityStudy $feasibilityStudy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeasibilityStudy $feasibilityStudy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeasibilityStudy $feasibilityStudy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeasibilityStudy $feasibilityStudy)
    {
        //
    }
}
