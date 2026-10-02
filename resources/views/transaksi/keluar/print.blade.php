<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan No. {{ $barangKeluar->no_surat_jalan }} - PT GUDANG KEBERSIHAN VSJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        body {
            font-family: 'Courier New', Courier, monospace, sans-serif;
            color: #000;
            background: #fff;
        }
        .table-surat-jalan th, .table-surat-jalan td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 11px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body class="p-6 max-w-4xl mx-auto">

    <!-- Action Bar (No Print) -->
    <div class="no-print mb-6 p-4 bg-slate-800 text-white rounded-xl flex items-center justify-between shadow-lg">
        <div>
            <h3 class="font-bold text-sm">Cetak Dokumen Surat Jalan</h3>
            <p class="text-xs text-slate-300">Gunakan tombol di sebelah kanan untuk mencetak atau menyimpan sebagai PDF.</p>
        </div>
        <div class="flex space-x-2">
            <button onclick="window.print()" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-xs shadow flex items-center space-x-1">
                <i class="fa-solid fa-print"></i>
                <span>Cetak / Save PDF</span>
            </button>
            <button onclick="window.close()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg font-bold text-xs">
                Tutup
            </button>
        </div>
    </div>

    <!-- SURAT JALAN PAPER -->
    <div class="border border-black p-6 space-y-4">

        <!-- HEADER SECTION -->
        <div class="flex justify-between items-start border-b-2 border-black pb-3">
            <div>
                <h1 class="text-xl font-extrabold tracking-wide uppercase">PT GUDANG KEBERSIHAN VSJ</h1>
                <p class="text-xs font-semibold">Layanan & Supply Alat Kebersihan Profesional</p>
                <p class="text-[10px]">Jl. Raya VSJ No. 12, Bekasi - Telp: 021-7778899</p>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-black tracking-widest uppercase border-b border-black pb-1">SURAT JALAN</h2>
            </div>
        </div>

        <!-- METADATA GRID (Exact match of Image 3) -->
        <div class="grid grid-cols-12 gap-4 text-xs font-mono py-2">
            <!-- Left: Kirim Ke -->
            <div class="col-span-7 space-y-1">
                <div class="flex">
                    <span class="w-24 font-bold uppercase">Kirim Ke</span>
                    <span>: <strong class="font-sans text-sm">{{ $barangKeluar->kirim_ke }}</strong></span>
                </div>
                @if($barangKeluar->catatan)
                    <div class="flex">
                        <span class="w-24 font-bold">Catatan</span>
                        <span>: {{ $barangKeluar->catatan }}</span>
                    </div>
                @endif
            </div>

            <!-- Right: Tanggal, Nomor, Halaman, Ref.# -->
            <div class="col-span-5 space-y-1 text-right">
                <div class="flex justify-end">
                    <span class="w-24 font-bold">Tanggal</span>
                    <span class="w-32 text-left">: {{ $barangKeluar->tanggal->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-end">
                    <span class="w-24 font-bold">Nomor</span>
                    <span class="w-32 text-left">: <strong>{{ $barangKeluar->no_surat_jalan }}</strong></span>
                </div>
                <div class="flex justify-end">
                    <span class="w-24 font-bold">Halaman</span>
                    <span class="w-32 text-left">: {{ $barangKeluar->halaman }}</span>
                </div>
                <div class="flex justify-end">
                    <span class="w-24 font-bold">Ref.#</span>
                    <span class="w-32 text-left">: {{ $barangKeluar->ref_no ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- ITEMS TABLE (Exact columns from Image 3 photo!) -->
        <table class="w-full text-left table-surat-jalan border-collapse">
            <thead>
                <tr class="bg-slate-100 font-bold text-center">
                    <th class="w-12">Nom</th>
                    <th>Detail Barang</th>
                    <th class="w-16">Qty</th>
                    <th class="w-32">Bekas Jumlah / Tukar</th>
                    <th class="w-28">Inventaris (+ / -)</th>
                    <th>Keterangan Kondisi Barang Yang Dikembalikan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangKeluar->details as $index => $detail)
                    <tr>
                        <td class="text-center font-bold">{{ $detail->barang->kode_barang ?? ($index + 1) }}</td>
                        <td class="font-sans font-medium">{{ $detail->barang->nama_barang ?? '-' }}</td>
                        <td class="text-center font-bold">{{ number_format($detail->qty, 2) }}</td>
                        <td class="text-center">{{ $detail->bekas_tukar ?? '-' }}</td>
                        <td class="text-center font-bold">{{ $detail->inventaris_status ?? '-' }}</td>
                        <td>{{ $detail->keterangan_kondisi ?? '-' }}</td>
                    </tr>
                @endforeach

                <!-- Empty Filler Rows if needed for paper aesthetics -->
                @for($i = count($barangKeluar->details); $i < 8; $i++)
                    <tr>
                        <td class="py-3">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <!-- SIGNATURE FOOTER (Exact 3 Signature Columns from photo) -->
        <div class="pt-6 grid grid-cols-3 gap-4 text-center text-xs font-mono">
            
            <div class="space-y-12">
                <p class="font-bold uppercase">Yang Menerima,</p>
                <div class="border-b border-black w-3/4 mx-auto"></div>
                <p class="font-sans font-semibold">({{ $barangKeluar->penerima ?? '........................' }})</p>
            </div>

            <div class="space-y-12">
                <p class="font-bold uppercase">Sopir / Pengirim,</p>
                <div class="border-b border-black w-3/4 mx-auto"></div>
                <p class="font-sans font-semibold">({{ $barangKeluar->sopir ?? '........................' }})</p>
            </div>

            <div class="space-y-12">
                <p class="font-bold uppercase">Gudang / Petugas,</p>
                <div class="border-b border-black w-3/4 mx-auto"></div>
                <p class="font-sans font-semibold">({{ $barangKeluar->petugas_gudang ?? Auth::user()->name ?? 'Admin VSJ' }})</p>
            </div>

        </div>

        <div class="pt-4 text-[9px] text-slate-500 italic text-center border-t border-slate-300">
            * Surat Jalan ini merupakan bukti sah penyerahan barang dari PT GUDANG KEBERSIHAN VSJ.
        </div>

    </div>

</body>
</html>
