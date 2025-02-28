<?php

namespace App\Http\Controllers;

use App\Models\Manufacture;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class ManufactureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $manufactures = Manufacture::whereNull('deletedBy')->get();
        if($request->ajax()){
            return DataTables::of($manufactures)
            ->addIndexColumn()
            ->addColumn('action', function($data) {
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$data->id.'" class="btn btn-primary btn-sm editManufacture"><i class="far fa-edit"></i></a>';
                // $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip" id="'.$data->id.'" name="'.$data->manufactureName.'" " url="'.$data->url.'" " email="'.$data->supportEmail.'" " phone="'.$data->supportPhone.'"
                // data-original-title="Delete" class="btn btn-danger btn-sm deleteManufacture"><i class="far fa-trash-alt"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('admin.manufacture.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'manufactureName' => 'required',
            'url' => 'required',
            'supportEmail' => 'required',
            'supportPhone' => 'required|string|min:10|max:12|regex:/[0-9]{9}/',
            'Image'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if($validator->fails())
        {
            return response()->json(['status'=>400, 'errors'=>$validator->errors()]);
        }
        else
        {
            $createdBy = Auth::user()->level;
            $createdUtc = Carbon::now();

            $data = new Manufacture();
            $data -> manufactureName = $request->input('manufactureName');
            $data -> url = $request->input('url');
            $data -> supportEmail = $request->input('supportEmail');
            $data -> supportPhone = $request->input('supportPhone');
            if($request->hasFile('Image')){
                $file = $request->file('Image');
                $extension = $file->getClientOriginalName();
                $filename = date('d-m-Y').' - '.$extension;
                $file->move('uploads/Image/', $filename);
                $data->Image = $filename;
            }
            $data -> createdBy = $createdBy;
            $data -> createdUtc = $createdUtc;
            $data -> save();

             return response()->json(['status'=>200, 'messages'=>'Data berhasil ditambahkan.']);
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Manufacture  $manufacture
     * @return \Illuminate\Http\Response
     */
    public function show(Manufacture $manufacture)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Manufacture  $manufacture
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $manufactures = Manufacture::find($id);
        if($manufactures)
        {

            return response()->json(['status' => 200,'manufactures'=>$manufactures]);
        }
        else
        {
            return response()->json(['status' => 404, 'messages' => 'Tidak ada data ditemukan.']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Manufacture  $manufacture
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'manufactureName' => 'required',
            'url' => 'required',
            'supportEmail' => 'required',
            'supportPhone' => 'required|string|min:10|max:12|regex:/[0-9]{9}/',
            // 'Image' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json(['status' => 400, 'errors' => $validator->errors()]);
        }
        else
        {
            $manufactures = Manufacture::find($id);
            if($manufactures)
            {
                $getBy  = Auth::user()->name;
                $getUtc = Carbon::now();

                $manufactures -> manufactureName = $request->input('manufactureName');
                $manufactures -> url = $request->input('url');
                $manufactures -> supportEmail = $request->input('supportEmail');
                $manufactures -> supportPhone = $request->input('supportPhone');
                // $manufactures -> Image = $request->input('Image');
                if($request->has('Image')) {
                    $Image = $request->file('Image');
                    $filename = $Image->getClientOriginalName();
                    $Image->move(public_path('Image/manufactures'), $filename);
                    $manufactures->Image = $request->file('Image')->getClientOriginalName();
                }
                $manufactures -> updatedBy = $getBy;
                $manufactures -> updatedUtc = $getUtc;
                $manufactures -> update();
                return response()->json(['status' => 200, 'messages' => 'Data berhasil diperbaharui']);
            }
            else
            {
                return response()->json([
                    'status' => 404,
                    'messages' => 'Tidak ada data ditemukan',
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Manufacture  $manufacture
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $manufactures = Manufacture::find($id);
        $assets = Asset::with('manufactures')->where('manufacture_id', $manufactures->id)->exists();
        if($manufactures)
        {
            if($assets)
            {
                return response()->json(['status'=>400, 'messages'=>'Data Manufaktur masih digunakan.']);
            }
            else
            {
                $manufactures->deletedBy = Auth::user()->name;
                $manufactures->deletedUtc = Carbon::now();
                $manufactures->update();
                return response()->json([
                    'status' => 200,
                    'messages' => 'Data berhasil dihapus.'
                ]);
            }
        }
        else
        {
            return response()->json([
                'status' => 404,
                'messages' => 'Data tidak ditemukan.'
            ]);
        }
    }
}
