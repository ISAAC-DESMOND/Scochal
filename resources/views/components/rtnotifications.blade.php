<div
    x-data="{
        notifications: [],
        add(message, type = 'info') {
            this.notifications.push({ id: Date.now(), message, type });
            setTimeout(() => {
                this.notifications.shift();
            }, 5000);
        }
    }"
    x-init="
        window.Echo.private('user.{{ Auth::id() }}')
            .listen('.notifyEvent', (e) => {
                add(e.message, e.type);
            });
    "
    class="fixed top-5 left-1/2 transform -translate-x-1/2 space-y-2 z-50 w-full max-w-sm"
>
    <template x-for="notification in notifications" :key="notification.id">
        <div
            x-transition
            x-show="true"
            class="text-white px-4 py-3 rounded shadow"
            :class="{
                'bg-blue-500': notification.type === 'info',
                'bg-green-500': notification.type === 'success',
            }"
        >
            <span x-text="notification.message"></span>
        </div>
    </template>
</div>
