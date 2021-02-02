<template>
    <v-app id="inspire">
        <v-app-bar
            app
            clipped-left
        >
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>Application</v-toolbar-title>
        </v-app-bar>

        <v-main>
            <v-container>
             <TodoComponents />
            </v-container>
        </v-main>

        <v-footer app>
            <span>© {{ new Date().getFullYear() }}</span>
        </v-footer>
    </v-app>
</template>

<script>
import TodoComponents from './Todos.vue'
export default {
    components: { TodoComponents },
    props: {
        source: String,
    },
    data: () => ({
        drawer: null,
    }),
    created () {
        this.$vuetify.theme.dark = true
    },
}
</script>
