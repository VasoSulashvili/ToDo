<script>
import {mapActions, mapState, mapWritableState} from "pinia";
import {useToDoStore} from "../store.js";
import {baseUrl, csrf_token} from "../constants.js";

export default {
    data() {
        return {
            name: ''
        }
    },
    computed: {
        ...mapState(useToDoStore, ['selectedProject']),
        ...mapWritableState(useToDoStore, ["errors"])
    },
    methods: {
        ...mapActions(useToDoStore, ['triggerAlert', "fetchTasks"]),
        reset() {
            this.name = '';
        },
        async addTask()
        {
            const that = this;
            const body = new FormData();
            body.set("project_id", this.selectedProject);
            body.set("name", this.name);
            const response = await fetch(baseUrl + 'tasks',
                {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': csrf_token,
                        "Content-Type": "application/json",
                        "Accept": "application/json, text-plain, */*",
                    },
                    // credentials: "same-origin",
                    body: body,
                    mode: 'no-cors'
                })
                .then(response => response.json(),)
                .then(data => {
                    if(data.type === 'validation'){
                        that.errors = data.data
                    } else {
                        that.fetchTasks(that.selectedProject)
                        that.triggerAlert(data.type, data.message)
                        that.errors = [];
                        this.reset();
                    }
                })
                .catch(error => console.error('Error:', error));

            this.$emit('taskCreated');

        }
    }
}

</script>

<template>
    <main class="w-full bg-slate-100 px-5">
        <div class="py-5 w-full justify-center lg:w-3/4 mx-auto lg:flex items-stretch">
            <input v-model="name" class="w-full mb-3 border border-slate-500 rounded-md px-10 py-2 lg:mb-0 lg:grow lg:mr-5" type="text">
            <button @click="addTask" class="w-full bg-slate-400 rounded-md px-10 py-2 text-white lg:w-44 whitespace-nowrap">Add Task</button>
        </div>
    </main>

</template>

<style scoped>

</style>
