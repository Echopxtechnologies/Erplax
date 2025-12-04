<x-layouts.app>
    <div style="padding: 20px;">
        <div style="margin-bottom: 30px;">
            <h1 style="font-size: 28px; font-weight: 700; color: #2C3E50; margin: 0;">Todo Management</h1>
            <p style="color: #7F8C8D; margin: 8px 0 0;">Manage your tasks efficiently</p>
        </div>

        <!-- Livewire TodoList Component -->
        @livewire('todo::todo-list')
    </div>

    @push('scripts')
    <script>
        Livewire.on('notify', (data) => {
            const type = data.type;
            const message = data.message;
            
            // Create notification element
            const notif = document.createElement('div');
            notif.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 16px 20px;
                border-radius: 8px;
                color: white;
                font-weight: 500;
                z-index: 10000;
                animation: slideIn 0.3s ease;
                background: ${type === 'success' ? '#27AE60' : '#E74C3C'};
            `;
            notif.textContent = message;
            document.body.appendChild(notif);
            
            setTimeout(() => {
                notif.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notif.remove(), 300);
            }, 3000);
        });
    </script>
    <style>
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    </style>
    @endpush
</x-layouts.app>
