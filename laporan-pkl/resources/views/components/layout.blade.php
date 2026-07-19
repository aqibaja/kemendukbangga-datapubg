<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/png" href="{{ asset('public/image/logoBKKBN.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('public/image/logoBKKBN.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .group:focus .group-focus\:flex {
            display: flex;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1e293b;
        }

        ::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        /* Smooth transitions */
        * {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="h-full">
    <div class="min-h-screen flex flex-col bg-slate-50 text-slate-900 ">

        <!-- NAVBAR (ICON ADA DI SINI) -->
        <x-navbar>{{ $title }}</x-navbar>

        <!-- CONTENT -->
        <main class="flex-1 overflow-auto p-4 sm:p-8 bg-gradient-to-br">
            {{ $slot }}
        </main>

    </div>
</body>

</html>
