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
    public function index(Request $request)
    {
        $search = $request->get('search') ? $request->get('search') :  null;
        $searchBy = $request->get('searchBy') ? $request->get('searchBy') : 'nama';

        if ($search !== null || $request->get('isCheckin') !== null) {
            $guests = Guest::where($searchBy, 'LIKE', "%{$search}%");
            
            if($request->get('isCheckin') === "true") {
                $guests = $guests->where('checkin', 1);
            } else if($request->get('isCheckin') === "false"){
            
                $guests = $guests->where('checkin', 0);
            }
            $guests = $guests->paginate(5);
        } else {
            $guests = Guest::paginate(5);
        }

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
    public function getCheckedIn(Request $request)
    {
        $search = $request->get('search') ? $request->get('search') :  null;
        $searchBy = $request->get('searchBy') ? $request->get('searchBy') : 'nama';

        if ($search !== null) {
            $guests = Guest::where($searchBy, 'LIKE', "%{$search}%")->where('checkin', 1)->paginate(5);
        } else {
            $guests = Guest::where('checkin', 1)->paginate(5);
        }

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
    public function show($request)
    {
        $txt = file_get_contents('https://www.eenvito.com/backend/public/api/'.$request->type);
        return response($txt, 200)->headers('Content-Type', 'text/html');
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
        $guests = Guest::where('nama', $request->nama)->where('alamat', $request->alamat)->first();

        if ($guests) {
            if ($guests->checkin == 1) {
                return response(['status' => false, 'message' => 'Tamu atas nama <b>'. $guests->nama .'</b> sudah check-in'], 400);    
            }
            $guests->update($request->all());
            return response(['status' => true, 'message' => 'Check-in tamu berhasil'], 200);
        } else {
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
