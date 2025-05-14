import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {

  Alpine.data('rtnotifications', () => ({
   notifications: [],
   nextId:1,
    init() {
      const userId=window.userID;
      window.Echo.private(`user.${userId}`)
        .listen('.notifyEvent', ({message,type}) => {
          this.notifications.push({
            id: this.nextId++,
            message,
            type,
          });
          setTimeout(() => this.notifications.shift(), 5000);
        });
    }
  }));


});

Alpine.start();
