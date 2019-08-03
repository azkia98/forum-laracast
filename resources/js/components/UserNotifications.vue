<template>
  <li class="nav-item dropdown" v-if="hasNotifications">
    <a class="nav-link" href="#" id="notificationDropdown" data-toggle="dropdown">
      <i class="fa fa-bell"></i>
    </a>
    <div class="dropdown-menu">
      <li v-for="notification in notifications" class="dropdown-item p-0">
        <a
          @click="markAsRead(notification)"
          :href="notification.data.link"
          class="nav-link text-truncate"
          v-text="notification.data.message.substring(0,40)"
        ></a>
      </li>
    </div>
  </li>
</template>


<script>
export default {
  data() {
    return {
      notifications: []
    };
  },
  created() {
    axios.get(`/profiles/${window.App.user.name}/notifications`).then(res => {
      this.notifications = res.data;
    });
  },
  methods: {
    markAsRead(notification) {
      axios.delete(`/profiles/${window.App.user.name}/notifications/${notification.id}`);
    }
  },
  computed: {
    hasNotifications() {
      return !!this.notifications.length;
    }
  }
};
</script>

