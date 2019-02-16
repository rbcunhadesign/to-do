<template>
  <div>
    <top-nav
      :user="user"
    />
  </div>
</template>

<script>
    import TopNav from "./Navigation/TopNav";
    export default {
      name: 'App',
      components: {TopNav},

      data() {
        return {
          axios: window.axios,
          user: {},
        }
      },

      created() {
        this.fetchUser();
      },

      methods: {
        fetchUser() {
          this.axios('/app/user')
            .then(response => {
              this.user = response.data;
            })
            .catch(() => {
              console.warn('Cannot fetch user..')
            });
        }
      },
    }
</script>