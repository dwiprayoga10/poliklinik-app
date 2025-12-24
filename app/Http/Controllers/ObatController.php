<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    /* ===============================
       CRUD OBAT
    ================================ */

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
            'nama_obat' => 'required|string|max:255',
            'kemasan'   => 'required|string|max:100',
            'harga'     => 'required|integer|min:0',
            'stok'      => 'nullable|integer|min:0',
        ]);

        Obat::create([
            'nama_obat' => $request->nama_obat,
            'kemasan'   => $request->kemasan,
            'harga'     => $request->harga,
            'stok'      => $request->stok ?? 0,
        ]);

        return redirect()->route('obat.index')
            ->with('message', 'Data obat berhasil ditambahkan')
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
            'nama_obat' => 'required|string|max:255',
            'kemasan'   => 'required|string|max:100',
            'harga'     => 'required|integer|min:0',
        ]);

        $obat = Obat::findOrFail($id);
        $obat->update([
            'nama_obat' => $request->nama_obat,
            'kemasan'   => $request->kemasan,
            'harga'     => $request->harga,
        ]);

        return redirect()->route('obat.index')
            ->with('message', 'Data obat berhasil diperbarui')
            ->with('type', 'success');
    }

    public function destroy(string $id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();

        return redirect()->route('obat.index')
            ->with('message', 'Data obat berhasil dihapus')
            ->with('type', 'success');
    }

    /* ===============================
       KELOLA STOK (FINAL)
    ================================ */

    public function updateStok(Request $request, string $id)
    {
        $request->validate([
            'tipe'   => 'required|in:tambah,kurang',
            'jumlah' => 'required|integer|min:1',
        ]);

        $obat = Obat::findOrFail($id);

        if ($request->tipe === 'kurang') {
            if ($obat->stok < $request->jumlah) {
                return redirect()->route('obat.index')
                    ->with('message', 'Stok tidak mencukupi')
                    ->with('type', 'danger');
            }

            $obat->decrement('stok', $request->jumlah);
        } else {
            $obat->increment('stok', $request->jumlah);
        }

        return redirect()->route('obat.index')
            ->with('message', 'Stok obat berhasil diperbarui')
            ->with('type', 'success');
    }
}
