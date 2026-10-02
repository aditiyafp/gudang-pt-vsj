<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - PT GUDANG KEBERSIHAN VSJ</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gradient-to-br from-indigo-900 via-slate-900 to-blue-950 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 border border-slate-100 space-y-6">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <div class="w-16 h-16 bg-indigo-600 text-white rounded-2xl mx-auto flex items-center justify-center text-2xl font-black shadow-lg shadow-indigo-600/30">
                VSJ
            </div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">PT GUDANG KEBERSIHAN VSJ</h1>
            <p class="text-xs text-slate-500 font-medium">Sistem Informasi Manajemen Gudang & Stok</p>
        </div>

        {{ $slot }}

        <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-400">
            <p>&copy; {{ date('Y') }} PT Gudang Kebersihan VSJ.</p>
            <p class="mt-1 text-[11px] font-mono text-indigo-600">Default Admin: admin@vsj.com / password123</p>
        </div>

    </div>

</body>
</html>
