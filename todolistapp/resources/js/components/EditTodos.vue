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
                        {{ todos.title }}
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
                                        :value="date"
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
                            <br>
                            <v-btn @click="save">Modifier</v-btn>
                        </v-row>

                    </v-card-text>

                    <v-card-text v-if="isShow" class="d-flex">
                        {{ todos.priority }}<br>
                        {{ todos.description }}<br>
                        {{ todos.date_execution }}
                        <div v-if="todos.is_executed">
                            Statut : Terminé
                        </div>
                        <div v-if="!todos.is_executed">
                            Statut : En cours
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
        save  : function () {
        this.todos = []
        axios({
            url: '/graphql',
            method: 'POST',
            data: {
                query:
                    `mutation{
                            updateTodo(id: "${ this.todos.id}", title:"${ this.title }", date_execution:"${ this.date }", priority:"${ this.radioGroup }",
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
            console.log(result)
        });
},

    }
}
</script>
