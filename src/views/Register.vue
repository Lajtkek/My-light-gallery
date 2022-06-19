<template>
	<div class="login-wrapper">
		<div>
			<h1>Register</h1>
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
					class="password"
					label="Password"
					:rules="passwordRules"
					required
					@keydown.enter="login"
				></v-text-field>
				<v-text-field
					type="password"
					v-model="passwordRepeat"
					label="Password again :-)"
					:rules="passwordRepeatRules"
					required
					@keydown.enter="login"
				></v-text-field>
				<v-text-field
					type="code"
					v-model="code"
					class="code"
					label="code"
					:rules="codeRules"
					required
					@keydown.enter="login"
				></v-text-field>
				<v-col class="text-right">
					<v-btn color="blue" @click="register" right> Register </v-btn>
				</v-col>
			</v-form>
		</div>
	</div>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
	name: "Register",
	data: function () {
		return {
			valid: true,
			username: "",
			password: "",
			passwordRepeat: "",
			//validation
			usernameRules: [(v) => !!v || "Name is required"],
			passwordRules: [(v) => !!v || "Password is required"],
			//todo proper fix
			passwordRepeatRules: [(v) => {
				const value = document.querySelector(".password input").value
				return value == v || "Passwords must match";
			}],
			
			codeRules: [(v) => !!v || "Name is required"],
		};
	},
	components: {},
	methods: {
		async register() {
			if (this.$refs.form.validate()) {
				let result = await Vue.prototype.post(
					"register",
					{ username: this.username, password: this.password, code: this.code }
				);

				if (result.error) {
					this.showErrorTooltip(result.error);
				} else {
					this.showMainTooltip(result.data);
				}
			}
		},
		reset() {
			this.$refs.form.reset();
		},
		resetValidation() {
			this.$refs.form.resetValidation();
		},
	},
});
</script>

<style lang="less" scoped>
.login-wrapper {
	height: 100%;
	display: flex;
	align-items: center;
	justify-content: center;

	> div {
		margin: 80px auto;
		width: 30%;
		min-width: 300px;
	}
}
</style>
