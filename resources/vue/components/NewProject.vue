<script >
import { baseUrl, csrf_token } from "../constants.js";
import {mapWritableState, mapActions, mapState} from 'pinia'
import { useToDoStore } from "../store.js";

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
    emits: ['projectCreated'],
    methods: {
        ...mapActions(useToDoStore, {
            fetchTasks: 'fetchTasks',
            triggerAlert: 'triggerAlert'}),
        reset() {
            this.name = '';
        },
        async addProject()
        {
            const that = this;
            const body = new FormData();
            body.set("name", this.name);
            const response = await fetch(baseUrl + 'projects',
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
                .then(response => response.json())
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

        }
    }
}

</script>

<template>
    <div class="py-5 w-full lg:flex">
        <input v-model="name" class="w-full mb-3 border border-slate-500 rounded-md px-10 py-2 lg:mb-0 lg:grow lg:mr-5" type="text">
        <button @click="addProject" class="w-full bg-slate-600 rounded-md px-10 py-2 text-white block lg:w-44 whitespace-nowrap">Add Project</button>
    </div>
</template>

<style scoped>

</style>
