import { defineStore } from 'pinia'
import {baseUrl, csrf_token} from "./constants.js";
import { isProxy, toRaw } from 'vue';

export const useToDoStore = defineStore(
    'todo',
    {
        state() {
            return {
                errors: [],
                showAlert: false,
                alertType: null,
                alertMessage: null,
                projects: [],
                tasks: [],
                selectedProject: null
            }
        },
        getters: {

        },
        actions: {
            async fetchProjects()
            {
                fetch(baseUrl + 'projects')
                    .then(response => response.json())
                    .then(data => this.projects = data.data)
                    .catch(error => console.error('Error:', error));
            },
            async fetchTasks(projectId)
            {
                this.selectedProject = projectId;
                if(projectId) {
                    fetch(baseUrl + 'projects/' + projectId + '/tasks')
                        .then(response => response.json())
                        .then(data => this.tasks = data.data)
                        .catch(error => console.error('Error:', error));
                } else {
                    this.tasks = [];
                }

            },
            async updateTasks()
            {
                // const data = JSON.parse(JSON.stringify(this.tasks));
                const body = new FormData();
                body.set("data", JSON.stringify(this.tasks));
                const response = await fetch(baseUrl + 'tasks/upsert',
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
                    .then(data => this.triggerAlert(data.type, data.message))
                    .catch(error => console.error('Error:', error));
            },

            triggerAlert(type, message) {
                this.alertType = type;
                this.alertMessage = message;
                this.showAlert = true;
                setTimeout(() => this.showAlert = false, 2000)
            }
        }
})
