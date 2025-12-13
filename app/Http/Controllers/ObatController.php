<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index()
    {
        $obats = Obat::all();
        return view('admin.obat.index', compact('obats'));
    }

    public function create()
    {
        return view('admin.obat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string',
            'kemasan'   => 'required|string',
            'harga'     => 'required|integer',
            'stok'      => 'nullable|integer|min:0'
        ]);

        Obat::create([
            'nama_obat' => $request->nama_obat,
            'kemasan'   => $request->kemasan,
            'harga'     => $request->harga,
            'stok'      => $request->stok ?? 0
        ]);

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat berhasil dibuat')
            ->with('type', 'success');
    }

    public function edit(string $id)
    {
        $obat = Obat::findOrFail($id);
        return view('admin.obat.edit', compact('obat'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_obat' => 'required|string',
            'kemasan'   => 'nullable|string',
            'harga'     => 'required|integer',
        ]);

        $obat = Obat::findOrFail($id);
        $obat->update([
            'nama_obat' => $request->nama_obat,
            'kemasan'   => $request->kemasan,
            'harga'     => $request->harga
        ]);

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat berhasil di edit')
            ->with('type', 'success');
    }

    public function destroy(string $id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat berhasil dihapus')
            ->with('type', 'success');
    }

    /* ===============================
       STOK (CARA LAMA - REDIRECT)
    ================================ */

    public function addStock(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $obat = Obat::findOrFail($id);
        $obat->increment('stok', $request->jumlah);

        return redirect()->route('obat.index')
            ->with('message', 'Stok obat berhasil ditambahkan')
            ->with('type', 'success');
    }

    public function reduceStock(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $obat = Obat::findOrFail($id);

        if ($obat->stok < $request->jumlah) {
            return redirect()->route('obat.index')
                ->with('message', 'Stok tidak cukup')
                ->with('type', 'danger');
        }

        $obat->decrement('stok', $request->jumlah);

        return redirect()->route('obat.index')
            ->with('message', 'Stok obat berhasil dikurangi')
            ->with('type', 'success');
    }

    /* ===============================
       STOK (AJAX - TANPA REFRESH)
    ================================ */

    public function updateStokAjax(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:add,reduce'
        ]);

        $obat = Obat::findOrFail($id);

        if ($request->action === 'reduce' && $obat->stok <= 0) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Stok sudah habis'
            ], 400);
        }

        if ($request->action === 'add') {
            $obat->increment('stok');
        } else {
            $obat->decrement('stok');
        }

        return response()->json([
            'status' => 'success',
            'stok'   => $obat->stok
        ]);
    }
}
