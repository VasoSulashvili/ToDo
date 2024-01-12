<script>
import TheProject from "./components/TheProject.vue";
import TheTasks from "./components/TheTasks.vue";
import TheToast from "./components/TheToast.vue";
import { mapWritableState } from 'pinia'
import { useToDoStore } from "./store.js";
import ValidationError from "./components/ValidationError.vue";
import ConfirmAction from "./components/ConfirmAction.vue";

export default {
    components: {ConfirmAction, ValidationError, TheToast, TheTasks, TheProject },
    data(){
        return {
        }
    },

    computed:{
        ...mapWritableState(useToDoStore, {
            errors: "errors",
            showAlert: "showAlert",
            alertType: "alertType",
            alertMessage: "alertMessage",
        })
    },
    created() {
    }
}
</script>

<template>
        <transition>
            <TheToast v-if="showAlert" :message="alertMessage" :type="alertType" />
        </transition>

        <header>
            <main class="w-full bg-slate-200 pt-10 px-5">
                <div class="h-20">
                    <ValidationError v-for="(val, key) in errors" :message="val[0]" />
                </div>
                <TheProject />
            </main>
        </header>

        <main class="w-full">

            <TheTasks />

        </main>

</template>

<style scoped>
    .v-enter-active,
    .v-leave-active {
        transition: opacity 1.0s ease;
    }

    .v-enter-from,
    .v-leave-to {
        opacity: 0;
    }
</style>
