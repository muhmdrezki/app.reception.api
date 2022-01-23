<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;
use Illuminate\Support\Facades\DB;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $guests = Guest::paginate(5);

        return response()->json([
            'status' => true,
            'data' => $guests
        ], 200); 
    }

    /**
     * get checked in guest.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */
    public function getCheckedIn()
    {
        $guests = Guest::where('checkin', 1)->paginate(5);

        return response()->json([
            'status' => true,
            'data' => $guests
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::BeginTransaction();
        $guests = Guest::create($request->all());
        DB::commit();

        $response = [
            "status" => true,
            "data" => $guests,
            'message' => 'Tamu Berhasil Ditambahkan',
        ];
        return response($response, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::BeginTransaction();
        $guests = Guest::where('id', $request->id)->first();

        if ($guests) {
            $guests->update($request->all());
            DB::commit();
            return response(['status' => true, 'message' => 'Update data tamu berhasil'], 200);
        } else {
            DB::commit();
            return response(['status' => false, 'message' => 'Data tidak ditemukan'], 400);
        }
    }

    /**
     * Checkin tamu
     * 
     *  @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkin(Request $request) {
        DB::BeginTransaction();
        $guests = Guest::where('nama', $request->nama)->first();

        if ($guests) {
            $guests->update($request->all());
            DB::commit();
            return response(['status' => true, 'message' => 'Check-in tamu berhasil'], 200);
        } else {
            DB::commit();
            return response(['status' => false, 'message' => 'Data Tamu tidak ditemukan'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::BeginTransaction();
        $to_be_deleted = Guests::where('id', $request->id);
        if ($to_be_deleted->first()) {
            $to_be_deleted->delete();
            DB::commit();
            return response(['status' => true, 'message' => 'Data tamu berhasil dihapus'], 200);
        } else {
            DB::commit();
            return response(['status' => false, 'message' => 'Data tidak ditemukan'], 400);
        }
    }
}
