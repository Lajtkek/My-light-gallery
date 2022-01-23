<template>
  <div>
    <h1>Login</h1>
    <div class="login-wrapper">
    <v-form ref="form" v-model="valid" lazy-validation>
      <v-text-field
        v-model="name"
        :counter="10"
        label="Username"
        required
      ></v-text-field>
      <v-text-field
        type="password"
        v-model="password"
        label="Password"
        required
      ></v-text-field>
      <v-btn color="success" class="mr-4" @click="validate">
        Validate
      </v-btn>
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
      username: "",
      password: "",
    };
  },
  components: {},
  methods: {
    validate () {
      // eslint-disable-next-line @typescript-eslint/ban-ts-comment
      // @ts-ignore
      this.$refs.form.validate()
    },
    reset () {
      // eslint-disable-next-line @typescript-eslint/ban-ts-comment
      // @ts-ignore
      this.$refs.form.reset()
    },
    resetValidation () {
      // eslint-disable-next-line @typescript-eslint/ban-ts-comment
      // @ts-ignore
      this.$refs.form.resetValidation()
    }
  },
});
</script>

<style lang="less" scoped>
  .login-wrapper{
    margin: auto;
    width: 60%;
  }
</style>
