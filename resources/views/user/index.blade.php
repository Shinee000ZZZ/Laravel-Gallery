<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Home</title>
</head>

<body class="font-monsterrat pt-16">

    @include('components.navbar')

    {{-- Photos --}}
    <div class="max-w-screen-xl mx-auto p-4">
        <div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4" id="indexPhotoContainer">
            @include('partials.photo-grid', ['photos' => $photos])
        </div>
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
