<template>
  <div class="container">
    <div class="row mt-4 justify-content-center">
      <div class="col-sm-6 col-md-4 col-md-offset-4">
          <h1 class="text-center login-title">Sign in to continue</h1>
          <div class="account-wall">
              <img class="profile-img" src="../assets/no-profile-photo.png"
                  alt="">
              <form class="form-signin">

                <input v-model="email" type="text" class="form-control" placeholder="Email" required autofocus>
                <input v-model="password" type="password" class="form-control" placeholder="Password" required>

                <button class="btn btn-lg btn-primary btn-block" type="button" @click="doLogin()" >
                    Sign in
                    <font-awesome-icon v-if="loading" icon="spinner" spin />
                </button>

                

                <p class="text-center" v-if="error"> {{ error_msg }} </p>

              </form>
          </div>
          <router-link router-link to="/register" class="text-center new-account">Don't have an account? Register</router-link>

      </div>
    </div>
</div>
</template>

<script>
export default {
  name: 'login',
  components: {
  },
  data() {
    return {
        loading: false,
        error: false,
        error_msg: "none",
        email: '',
        password: ''
    }
  },
  methods: {
    doLogin() {
        if(!this.loading) {
            this.loading = true;

            this.$http({
                url: this.api_url + 'auth/login',
                method: 'POST',
                data: {
                    email: this.email,
                    password: this.password
                },
                headers: {'Content-Type': 'application/json'},
            })
            .then(response => {

                localStorage.setItem('user_token', response.data.bearer_token);
                localStorage.setItem('expires_at', response.data.expires_at);
                this.$router.push('/contacts');

            })
            .catch(error => {
                console.log(error.message);
                this.error = true;
                this.error_msg = error.message;
            })
            .finally(() =>{
                this.loading = false;
            });
        }
    }
  }
}
</script>
<style lang="scss" scoped>
.form-signin
{
    max-width: 330px;
    padding: 15px;
    margin: 0 auto;
}
.form-signin .form-signin-heading, .form-signin .checkbox
{
    margin-bottom: 10px;
}
.form-signin .checkbox
{
    font-weight: normal;
}
.form-signin .form-control
{
    position: relative;
    font-size: 16px;
    height: auto;
    padding: 10px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.form-signin .form-control:focus
{
    z-index: 2;
}
.form-signin input[type="text"]
{
    margin-bottom: -1px;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}
.form-signin input[type="password"]
{
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
.account-wall
{
    margin-top: 20px;
    padding: 40px 0px 20px 0px;
    background-color: #f7f7f7;
    -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
}
.login-title
{
    color: #555;
    font-size: 18px;
    font-weight: 400;
    display: block;
}
.profile-img
{
    width: 96px;
    height: 96px;
    margin: 0 auto 10px;
    display: block;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
}
.need-help
{
    margin-top: 10px;
}
.new-account
{
    display: block;
    margin-top: 10px;
}
</style>