<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data_book = Book::all();

        return response()->json([
            'status' => true,
            'message' => 'Data Ditemukan',
            'data' => $data_book,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data_book = new Book();

        $rules = [
            'title' => 'required',
            'pengarang' => 'required',
            'tahun_terbit' => 'required',
            'jenis_buku' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'required',
            'stok' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memasukkan data',
                'data' => $validator->errors()
            ]);
        }
        $photoPath = $request->file('photo')->store('uploads', 'public');
        $data_book->title = $request->title;
        $data_book->pengarang = $request->pengarang;
        $data_book->tahun_terbit = $request->tahun_terbit;
        $data_book->jenis_buku = $request->jenis_buku;
        $data_book->photo = $photoPath;
        $data_book->deskripsi = $request->deskripsi;
        $data_book->stok = $request->stok;

        $post = $data_book->save();

        return response()->json([
            'status' => true,
            'message' => 'Sukses memasukkan data'
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        $data_book = Book::find($id);
        if ($data_book) {
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $data_book
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'title' => 'required',
            'pengarang' => 'required',
            'tahun_terbit' => 'required',
            'jenis_buku' => 'required',
            'deskripsi' => 'required',
            'stok' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal melakukan update data',
                'data' => $validator->errors()
            ]);
        }

        $data_book = Book::findOrFail($id);

        // Periksa apakah file foto diunggah
        // if ($request->hasFile('photo')) {
        //     $photoPath = $request->file('photo')->store('uploads', 'public');
        //     $data_book->photo = $photoPath;
        // }

        $data_book->title = $request->title;
        $data_book->pengarang = $request->pengarang;
        $data_book->tahun_terbit = $request->tahun_terbit;
        $data_book->jenis_buku = $request->jenis_buku;
        $data_book->deskripsi = $request->deskripsi;
        $data_book->stok = $request->stok;

        $data_book->save();

        return response()->json([
            'status' => true,
            'message' => 'Sukses melakukan update data'
        ]);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $data_book = Book::find($id);
        if (empty($data_book)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $post = $data_book->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses melakukan hapus data'
        ]);
    }
}
