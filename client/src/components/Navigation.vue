<template>
  <b-navbar type="dark" variant="dark" class="mb-5">
    <b-navbar-nav>
      <template v-if="authenticated">
        <b-navbar-brand>Hello {{user.name}}!</b-navbar-brand>
        <b-nav-item @click.prevent="signOut" right>Sign out</b-nav-item>
      </template>
    </b-navbar-nav>
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
