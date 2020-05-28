<template>
  <b-navbar type="dark" variant="dark" class="mb-5">
    <template v-if="authenticated">
      <b-navbar-brand class="col-6">Hello {{user.name}}!</b-navbar-brand>
      <b-navbar-nav class="col-6 justify-content-end">
        <b-nav-item @click.prevent="signOut" right>Sign out</b-nav-item>
      </b-navbar-nav>
    </template>
  </b-navbar>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  computed: {
    ...mapGetters({
      authenticated: "auth/authenticated",
      user: "auth/user"
    })
  },
  methods: {
    ...mapActions({
      signOutAction: "auth/signOut"
    }),
    signOut() {
      this.signOutAction().then(() => {
        this.$router.replace({
          name: "SignIn"
        });
      });
    }
  }
};
</script>
