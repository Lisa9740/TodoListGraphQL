<template>
    <div>
            <v-dialog
                v-model="dialog"
                width="500"
            >

                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        color="red lighten-2"
                        dark
                        v-bind="attrs"
                        v-on="on"
                    >
                        Voir
                    </v-btn>
                </template>

                <v-card>
                    <v-card-title class="headline">
                        {{ todos.title }} - {{ todos.date_execution | moment("dddd Do MMMM YYYY") }}
                    </v-card-title>

                    <v-card-text v-if="isEdited" class="d-flex">
                        <v-row>
                            <v-text-field label="Nom de la tâche" v-model="todos.title"></v-text-field>
                            <v-menu
                                ref="menu"
                                v-model="menu"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="todos.date_execution"
                                        label="Choisir une date d'execution"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="todos.date_execution"
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
                                v-model="todos.description"
                                hint="Hint text"
                            ></v-textarea>
                            <v-spacer></v-spacer>
                            <v-radio-group v-model="radioGroup">
                                <v-radio
                                    v-for="n in priorities"
                                    :key="n"
                                    :label="`Priorité ${n}`"
                                    :value="n"
                                    color="red"
                                ></v-radio>
                            </v-radio-group>
                            <v-switch
                                v-model="todos.is_executed"

                                color="red"
                            ></v-switch>
                        </v-row>
                        <v-spacer></v-spacer>
                        <v-btn @click="save(todos.is_executed)">Modifier</v-btn>
                    </v-card-text>


                    <v-card-text v-if="isShow" class="d-flex">
                        <div>
                            <v-row class="mt-4">
                                <p> Priorité : {{ todos.priority }}</p>
                                <v-spacer></v-spacer>

                                    <p v-if="todos.is_executed">
                                        Statut : Terminé
                                    </p>
                                    <p v-if="!todos.is_executed">
                                        Statut : En cours
                                    </p>

                            </v-row>
                            <v-row>
                                <p> Description :  {{ todos.description }} </p>
                            </v-row>
                        </div>



                    </v-card-text>

                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-btn
                            color="red lighten-2"
                            dark
                            @click="editTodo"
                        >
                           Editer
                        </v-btn>
                        <v-btn
                            color="red lighten-2"
                            dark
                            @click="showTodo"
                        >
                           Voir
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
    </div>
</template>
<script>
import axios from "axios";

export default{
    props: {
        todos: {
            required: true
        },
    },
    data() {
        return {
            dialog: false,
            isEdited: false,
            n: 0,
            isShow: true,
            priorities : ['Haute', 'Moyenne', 'Basse'],
            title:'',
            description:'',
            message:'Ma TODO list',
            radioGroup: 1,
            date: '',
            menu: false,
        }
    },
    methods : {
        editTodo (){
            this.isEdited = true
            this.isShow = false
        },
        showTodo (){
            this.isEdited = false
            this.isShow = true
        },
        save  : function (isExecuted) {
            let executed = false
            if (isExecuted){
                executed = true
            }
            axios({
                url: '/graphql',
                method: 'POST',
                data: {
                    query:
                        `mutation{
                                updateTodo( id:"${ this.todos.id }",title:"${ this.todos.title }",date_execution:"${ this.todos.date_execution }",priority:"${ this.radioGroup }",
                                           description:"${ this.todos.description }",is_executed:${ executed })
                                {
                                    id
                                    title
                                    date_execution
                                    priority
                                    description
                                    is_executed
                                }
                            }`
                }
            }).then((result) => {
                console.log(result.data)
                this.$emit('message', 'La tache à été modifié avec succès !')
            });
        },

    }
}
</script>
