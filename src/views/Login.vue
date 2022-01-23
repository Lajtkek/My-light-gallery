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
        ></v-text-field>
        <v-text-field
          type="password"
          v-model="password"
          label="Password"
          :rules="passwordRules"
          required
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
        (v: string) => !!v || 'Name is required',
      ],
      passwordRules: [
        (v: string) => !!v || 'Password is required',
      ],
    };
  },
  components: {},
  methods: {
    async login () {
      // eslint-disable-next-line @typescript-eslint/ban-ts-comment
      // @ts-ignore
      if(this.$refs.form.validate()){
        let result = await Vue.prototype.post("api/login", { username: this.username, password: this.password }, {});
        if(result.data == "USERNAME_PARAM_REQUIRED" || result.data == "IVALID_PASSWORD"){
          //todo add toaster
          console.log("invalid username or password")
        }else{
          Vue.prototype.saveToken(result.data.token);
        }
      }
    },
    reset () {
      // eslint-disable-next-line @typescript-eslint/ban-ts-comment
      // @ts-ignore
      this.$refs.form.reset()
    },
    resetValidation () {
      // eslint-disable-next-line @typescript-eslint/ban-ts-comment
      // @ts-ignore
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
