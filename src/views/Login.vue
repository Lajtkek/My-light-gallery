<template>
  <div class="login-wrapper">
    <div>
      <h1>Login</h1>
      <v-form ref="form" v-model="valid" lazy-validation>
        <v-text-field
          v-model="username"
          label="Username"
          :rules="usernameRules"
          required
          @keydown.enter="login"
        ></v-text-field>
        <v-text-field
          type="password"
          v-model="password"
          label="Password"
          :rules="passwordRules"
          required
          @keydown.enter="login"
        ></v-text-field>
        <v-col class="text-right">
          <v-btn color="blue" @click="login" right>
          Login
          </v-btn>
        </v-col>
      </v-form>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
  name: "Login",
  data: function () {
    return {
      valid: true,
      username: "",
      password: "",
      //validation
      usernameRules: [
        (v) => !!v || 'Name is required',
      ],
      passwordRules: [
        (v) => !!v || 'Password is required',
      ],
    };
  },
  components: {},
  methods: {
    async login () {
      if(this.$refs.form.validate()){
        let result = await Vue.prototype.post("login", { username: this.username, password: this.password }, {});

        if(result.error){
          this.showErrorTooltip("Wrong username or password.");
          //console.log("invalid username or password")
        }else{
          try{
            let parsed = this.parseJwt(result.data.token)
            if(parsed != null){
              localStorage.token = result.data.token;
              location.reload();
            }
          }catch(e){
            console.error(e);
          }
          
        }
      }
    },
    reset () {
      this.$refs.form.reset()
    },
    resetValidation () {
      this.$refs.form.resetValidation();
    }
  },
});
</script>

<style lang="less" scoped>
  .login-wrapper{
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;

    >div{
      margin: 80px auto;
      width: 30%;
      min-width: 300px;
    }
  }
</style>
