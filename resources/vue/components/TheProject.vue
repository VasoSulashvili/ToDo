<script>

import { useToDoStore } from "../store.js";
import { mapState, mapActions} from "pinia";
import NewProject from "./NewProject.vue";
export default {
    components: {NewProject},
    emits: ['projectSelected'],
    data() {
        return {
            project: '',
        }
    },
    computed: {
        ...mapState(useToDoStore, {
            projects: "projects",
            tasks: "tasks"
        })
    },


    methods: {
        ...mapActions(useToDoStore, ["fetchProjects", "fetchTasks"])
    },
    watch: {
        project(val, oldVal) {
            this.fetchTasks(val);
        }
    },
    created() {
        this.fetchProjects();
    }
}

</script>

<template>

        <div class="w-full lg:w-3/4 mx-auto">
            <NewProject @project-created="fetchProjects"/>
            <div class="py-5 w-full flex justify-center">
                <select v-model="project" class="break-words max-w-full border border-slate-500 rounded-md px-3 lg:px-10 py-2">
                    <option value="" selected>Choose Project</option>
                    <option :value="project.id" v-for="project in projects" :key="project.id">
                        {{ project.name }}
                    </option>
                </select>
            </div>

        </div>

</template>

<style scoped>

</style>
