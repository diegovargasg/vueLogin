<template>
  <b-container>
    <template v-if="showAlert">
      <b-alert show variant="danger">Invalid username or password</b-alert>
    </template>
    <b-form @submit.prevent="submit">
      <b-form-group id="input-group-1" label="Email:" label-for="email">
        <b-form-input id="email" name="email" type="email" v-model="form.email" required></b-form-input>
      </b-form-group>
      <b-form-group id="input-group-2" label="Password:" label-for="password">
        <b-form-input
          id="password"
          name="password"
          type="password"
          required
          v-model="form.password"
        ></b-form-input>
      </b-form-group>
      <b-button type="submit" variant="primary">Sign in</b-button>
    </b-form>
  </b-container>
</template>

<script>
import { mapActions } from "vuex";

export default {
  name: "SignIn",
  components: {},
  data() {
    return {
      form: {
        email: "",
        password: ""
      },
      showAlert: false
    };
  },
  methods: {
    ...mapActions({
      signIn: "auth/signIn"
    }),
    submit() {
      this.signIn(this.form)
        .then(() => {
          this.$router.replace({
            name: "Dashboard"
          });
        })
        .catch(() => {
          this.showAlert = true;
        });
    }
  }
};
</script>
