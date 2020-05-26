<template>
  <b-container>
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
      }
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
        .catch(e => {
          console.log("failed login", e);
        });
    }
  }
};
</script>
