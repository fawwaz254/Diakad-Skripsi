class{
    public function handleImportPesertaEkskul(Request $request, $id_semester, $id_ekskul)
    {
        $request->validate([
            'file-excel' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new PesertaEkskulImport($id_semester, $id_ekskul), $request->file('file-excel'));
            return redirect()->back()->with('success', 'Data berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }
}