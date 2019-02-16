<template>
  <div>
    <top-nav
      :user="user"
    />

    <div class="container">
      <todo />
    </div>
  </div>
</template>

<script>
  /*global axios*/
    import TopNav from "./Navigation/TopNav";
  import Todo from "./Todo/Todo";
    export default {
      name: 'App',
      components: {Todo, TopNav},

      data() {
        return {
          user: {},
        }
      },

      created() {
        this.fetchUser();
      },

      methods: {
        fetchUser() {
          axios('/app/user')
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