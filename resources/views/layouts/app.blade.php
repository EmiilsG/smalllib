<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bibliotēka')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow mb-8">
        <div class="max-w-7xl mx-auto px-4 py-3 flex gap-6">
            <a href="{{ route('books.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">Grāmatas</a>
            <a href="{{ route('readers.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">Lasītāji</a>
            <a href="{{ route('borrowings.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">Aizņēmumi</a>
            <a href="{{ route('zurnals.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">Žurnāls</a>
            <a href="{{ route('kavetie.index') }}" class="text-red-600 hover:text-red-800 font-medium">Kavētie</a>
            <a href="{{ route('sodi.index') }}" class="text-purple-600 hover:text-purple-800 font-medium">Sodi</a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
