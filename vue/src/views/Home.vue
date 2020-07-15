<template>
  <div>
      <router-link to="/login">Login</router-link> |
      <router-link to="/register">Register</router-link> |
      <router-link to="/contacts">Contacts</router-link>
  </div>
</template>

<script>
export default {
  methods: {
    checkToken() {
      return this.$http({
          url: this.api_url + 'auth/checktoken',
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'Authorization': 'Bearer ' + localStorage.getItem('user_token')
          },
      })
      .then(response => {
          console.log(response);
          this.$router.push('/contacts');

      })
      .catch(error => {
          console.log(error);
          localStorage.removeItem('user_token');
          localStorage.removeItem('expires_at');
          this.$router.push('/login');
      });
    }
  },
  created() {
    this.checkToken();
  }
}
</script>

<style>

</style>