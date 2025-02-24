<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="/storage/galerizzicon.png" type="image/png" sizes="16x16">
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ $query ? 'Hasil Pencarian: ' . $query : 'Home' }}</title>
</head>

<body class="font-monsterrat pt-16">

    @include('components.navbar')

    {{-- Photos --}}
    <div class="max-w-screen-xl mx-auto p-4">
        @if ($query)
            <h2 class="text-xl font-bold mb-4">
                Hasil Pencarian untuk "{{ $query }}"
                <a href="{{ route('user.index') }}" class="ml-2 text-sm text-blue-500 hover:underline">
                    Hapus Filter
                </a>
            </h2>
        @endif

        <div class="columns-5 md:columns-3 lg:columns-4 gap-4 space-y-4 masonry-container" id="indexPhotoContainer">
            @if ($photos->isNotEmpty())
                @include('partials.photo-grid', ['photos' => $photos])
            @endif
        </div>

        @if ($photos->isEmpty())
            <div class="flex items-center justify-center">
                @include('partials.empty-state', ['type' => $query ? 'search' : 'index'])
            </div>
        @endif
    </div>

    <style>
        img {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            pointer-events: none;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                // Prevent right-click save options
                img.addEventListener('contextmenu', (e) => {
                    const selection = window.getSelection();
                    selection.removeAllRanges();
                });

                // Remove event listeners to ensure image can't be interacted with
                img.oncontextmenu = (e) => {
                    e.preventDefault();
                    return false;
                };

                // Prevent copying
                img.oncopy = (e) => {
                    e.preventDefault();
                    return false;
                };
            });

            // Prevent keyboard copy
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && (e.key === 'c' || e.key === 'C')) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
</body>

</html>
