<template>
    <div>
        <div v-if="message.length !== 0">
            {{ message}}
        </div>
        <div class="d-flex ">
            <v-spacer></v-spacer>
            <v-dialog
                v-model="dialog"
                width="500"
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        color="red lighten-1"
                        dark
                        v-bind="attrs"
                        v-on="on"
                    >
                        <v-icon>mbi-add</v-icon>
                        Ajouter
                    </v-btn>
                </template>

                <v-card>
                    <v-card-title class="headline">
                        Nouvelle tâche
                    </v-card-title>

                    <v-card-text>
                        <v-text-field label="Nom de la tâche" v-model="title"></v-text-field>
                        <v-menu
                            ref="menu"
                            v-model="menu"
                            transition="scale-transition"
                            offset-y
                            min-width="auto"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="date"
                                    label="Choisir une date d'execution"
                                    prepend-icon="mdi-calendar"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker
                                v-model="date"
                                no-title
                                scrollable
                            >
                                <v-spacer></v-spacer>
                                <v-btn
                                    text
                                    color="primary"
                                    @click="menu = false"
                                >
                                    Cancel
                                </v-btn>
                                <v-btn
                                    text
                                    color="primary"
                                    @click="$refs.menu.save(date)"
                                >
                                    OK
                                </v-btn>
                            </v-date-picker>
                        </v-menu>
                        <v-textarea
                            name="input-7-1"
                            label="Description"
                            v-model="description"
                            value=""
                            hint="Hint text"
                        ></v-textarea>

                        <v-radio-group v-model="radioGroup">
                            <v-radio
                                v-for="n in priorities"
                                :key="n"
                                :label="`Priorité ${n}`"
                                :value="n"
                               color="red"
                            ></v-radio>
                        </v-radio-group>
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="red darken"
                            class="mb-5"
                            text
                            @click="createTodo"
                        >
                            Ajouter
                        </v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </div>
        <v-spacer></v-spacer>
        <v-divider class="mt-5"></v-divider>
        <p class="mt-5">Filtrer par priorité :</p>
        <div class="d-flex-inline">
            <v-radio-group v-model="radioGroup" row>
            <v-radio
                label="Tout"
                color="red"
                value="Tout"
                @click="retrieveTodos()"
            ></v-radio>

            <v-radio
                label="Haute"
                color="red"
                value="Haute"
                @click="FilterPriorities('Haute')"
            ></v-radio>
                <v-radio
                    label="Moyenne"
                    color="red"
                    value="Moyenne"
                    @click="FilterPriorities('Moyenne')"
                ></v-radio>
                <v-radio
                    label="Basse"
                    color="red"
                    value="Basse"
                    @click="FilterPriorities('Basse')"
                ></v-radio>
            </v-radio-group>
        </div>

        <v-divider></v-divider>
        <v-card
            elevation="2"
            class="mt-7 d-flex"
            v-if="todos.length !== 0"
            v-for="todo in todos.todos" v-bind:key="todo.id"
        >
            <v-card-text class="d-flex">
                {{ todo.title }}
                <v-spacer></v-spacer>
                    <v-icon
                        large
                        color="green darken-2"
                        v-if="!todo.is_executed" disabled
                    >
                        mdi-check
                    </v-icon>
                    <v-icon
                        large
                        color="green darken-2"
                        v-if="todo.is_executed"
                    >
                        mdi-check
                    </v-icon>

            </v-card-text>
            <v-card-actions>
                <EditTodos :todos="todo" @message="messageSent"/>
            </v-card-actions>
        </v-card>
    </div>
</template>
<script>
import axios from 'axios';
import EditTodos from '../components/EditTodos.vue';

export default {
    computed: {},
    data() {
        return {
            todos: [],
            switch1 : false,
            priorities : ['Haute', 'Moyenne', 'Basse'],
            title:'',
            description:'',
            message:'',
            radioGroup: 1,
            date: '',
            menu: false,
            dialog: false,
        }
    },
  components : { EditTodos },
    methods : {
        createTodo : function () {
            this.todos = []
            axios({
                url: '/graphql',
                method: 'POST',
                data: {
                    query:
                        `mutation{
                        createTodo(title:"${ this.title }", date_execution:"${ this.date }", priority:"${ this.radioGroup }",
                                   description:"${ this.description }", is_executed:false)
                        {
                            id
                            title
                            date_execution
                            priority
                            description
                        }
                    }`
                }
            }).then((result) => {
                console.log(result.data)
                this.todos.push(result.data.data)
                this.dialog = false
                this.retrieveTodos()
            });
        },
        retrieveTodos : function () {
            this.todos = []
            axios({
                url: '/graphql',
                method: 'POST',
                data: {
                    query: `{
                            todos{
                              id
                              title
                              date_execution
                              description
                              priority
                              is_executed
                              }}`
                }
            }).then((result) => {
                console.log("ttttest" + JSON.stringify(result.data.data))
                this.todos = result.data.data
             });
        },
        FilterPriorities : function (priority) {
            this.todos = []
            axios({
                url: '/graphql',
                method: 'POST',
                data: {
                    query: `{
                    todos(priority:"${priority}"){
                                title
                                priority
                                date_execution
                                description
                                is_executed
                        }}`
                }
            }).then((result) => {
                console.log("hi priority" + JSON.stringify(result.data))
                this.todos = result.data.data
            });
        },
        messageSent: function (text) {
            this.message = text
        },

    },
    created() {
        this.retrieveTodos();
    },
};
</script>

