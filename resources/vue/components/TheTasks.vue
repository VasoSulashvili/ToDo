<script>
import { useToDoStore } from "../store.js";
import { mapActions, mapWritableState} from "pinia";
import NewTask from "./NewTask.vue";
import draggable from 'vuedraggable';
import {baseUrl, csrf_token} from "../constants.js";

export default {
    data(){
        return {
            deleteTask: false,
            deleteTaskId: null,
            askEditTask: false,
            updateTaskId: null,
            name: '',
        }
    },
    components: {NewTask, draggable},
    computed: {
        ...mapWritableState(useToDoStore, {
            tasks: "tasks",
            selectedProject: "selectedProject",
            errors: "errors",
            showAlert: "showAlert",
            alertType: "alertType",
            alertMessage: "alertMessage",
        }),
        style() {
            return {
                'overline': this.type === 'success',
                'bg-red-700 dark:bg-red-700': this.type === 'error',
            }
        }
    },
    methods: {
        ...mapActions(useToDoStore, ["updateTasks", "fetchTasks", "triggerAlert"]),
        askDeleteTask(taskId) {
            this.deleteTask = true;
            this.deleteTaskId = taskId;
        },
        cancelDelete(){
            this.deleteTask = false;
            this.deleteTaskId = null;
        },
        async confirmDelete(){
            const that = this;
            const response = await fetch(baseUrl + 'tasks/' + this.deleteTaskId,
                {
                    method: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': csrf_token,
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                    }
                    // credentials: "same-origin",
                    // mode: 'no-cors'
                })
                .then(response => response.json())
                .then(data => {
                    that.fetchTasks(that.selectedProject)
                    that.triggerAlert(data.type, data.message)
                    that.errors = [];
                    this.deleteTask = false;
                    this.deleteTaskId = null;
                })
                .catch(error => console.error('Error:', error));


        },

        async editTask(taskId, taskName) {
            this.askEditTask = true;
            this.updateTaskId = taskId;
            this.name = taskName;
        },

        async updateTask()
        {
            const that = this;
            const data = {
                name: this.name,
            };
            const jsonString = JSON.stringify(data);
            const response = await fetch(baseUrl + 'tasks/' + this.updateTaskId,
                {
                    method: "PUT",
                    headers: {
                        'X-CSRF-TOKEN': csrf_token,
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                    },
                    // credentials: "same-origin",
                    body: jsonString,
                    // mode: 'cors'
                })
                .then(response => response.json(),)
                .then(data => {
                    if(data.type === 'validation') {
                        console.log(data.data)
                        that.errors = data.data
                    } else {
                        console.log(data.data)
                        that.fetchTasks(that.selectedProject)
                        that.triggerAlert(data.type, data.message)
                        that.errors = [];
                        this.reset();
                    }
                })
                .catch(error => console.error('Error:', error));

        },

        async toggleCompleted(taskId)
        {
            const that = this;
            const response = await fetch(baseUrl + 'tasks/' + taskId + '/toggle',
                {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': csrf_token,
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                    },
                    // credentials: "same-origin",
                    body: [],
                    // mode: 'cors'
                })
                .then(response => response.json(),)
                .then(data => {
                    that.fetchTasks(that.selectedProject)
                    that.triggerAlert(data.type, data.message)
                    that.errors = [];
                    this.reset();
                })
                .catch(error => console.error('Error:', error));

        },

        cancelUpdate(){
            this.reset();
        },
        reset() {
            this.deleteTaskId = null;
            this.deleteTask = null;
            this.askEditTask = false;
            this.updateTaskId = null;
            this.name = ''
        }
    },
    created() {
    }
}

</script>

<template>
    <main>
        <new-task v-if="selectedProject" />
        <div class="w-full mt-10 lg:w-3/4 mx-auto px-5" draggable="true">
            <draggable @end="updateTasks" v-model="tasks" tag="div" item-key="id">
                <template #item="{ element: task }">
                    <div class="mb-3 px-5 py-5 bg-slate-50 lg:flex justify-around rounded-md shadow-xl" >
                        <div class="flex justify-between">
                            <div class="pt-1 mr-10 mb-5 flex justify-between flex-none w-20 space-x-1.5 lg:mb-0">
                                <svg @click="askDeleteTask(task.id)" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash cursor-pointer" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                </svg>

                                <svg @click="editTask(task.id, task.name)" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil cursor-pointer" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                </svg>

                                <svg @click="toggleCompleted(task.id)" v-if="task.completed" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-square cursor-pointer" viewBox="0 0 16 16">
                                    <path d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5z"/>
                                    <path d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0"/>
                                </svg>

                                <svg @click="toggleCompleted(task.id)" v-if="!task.completed" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-square cursor-pointer" viewBox="0 0 16 16">
                                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                </svg>
                            </div>

                            <div class="flex-none lg:hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                                    <path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2m0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                                </svg>
                            </div>
                        </div>

                        <p :class="{'line-through': task.completed}" class="grow">
                            {{ task.name }}
                        </p>

                        <div class="flex-none hidden lg:block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                                <path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2m0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2m0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                            </svg>
                        </div>
                    </div>
                </template>
            </draggable>
        </div>

        <div v-if="deleteTask" class="bg-slate-800 bg-opacity-50 flex justify-center items-center absolute top-0 right-0 bottom-0 left-0">
            <div class="bg-white px-16 py-14 rounded-md text-center">
                <h1 class="text-xl mb-4 font-bold text-slate-500">Do you Want Delete</h1>
                <button @click="cancelDelete" class="bg-red-500 px-4 py-2 rounded-md text-md text-white">Cancel</button>
                <button @click="confirmDelete" class="bg-indigo-500 px-7 py-2 ml-2 rounded-md text-md text-white font-semibold">Ok</button>
            </div>
        </div>

        <!-- component -->
        <!-- This is an example component -->
        <div>

        </div>
        <div v-if="askEditTask" class="bg-slate-800 bg-opacity-50 flex justify-center items-center absolute top-0 right-0 bottom-0 left-0">
            <!-- Main modal -->
            <div class="h-modal w-full mt-10 lg:w-3/4 mx-auto md:h-auto bg-white px-16 py-14 rounded-md text-center ">
                <div class="relative w-full ">
                    <!-- Modal content -->
                    <div class="bg-white rounded-lg shadow relative dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-gray-900 text-xl lg:text-2xl font-semibold dark:text-white">
                                Update Task
                            </h3>

                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-6">
                            <textarea v-model="name" class="px-10 py-5 w-full h-20 border rounded-md"  type="text"></textarea>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex space-x-2 items-center p-6 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button @click="updateTask" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update</button>
                            <button @click="cancelUpdate" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">Decline</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</template>

<style scoped>

</style>
