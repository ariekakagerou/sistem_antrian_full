<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Rumah Sakit Sehat Selalu' }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @livewireStyles
    
    <style>
        [x-cloak] { display: none !important; }
        
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }
        
        .animate-pulse-slow {
            animation: pulse-slow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="bg-gray-50">
    {{ $slot }}
    
    @livewireScripts
    
    <script>
        // Helper untuk notifikasi
        window.showNotification = function(message, type = 'success') {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };
            
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg z-50 transform transition-all duration-300`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        };
        
        // Listen untuk event Livewire
        document.addEventListener('livewire:init', () => {
            Livewire.on('notification', (event) => {
                showNotification(event.message, event.type || 'success');
            });
        });
    </script>
</body>
</html>
