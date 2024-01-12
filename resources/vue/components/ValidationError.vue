<script>
export default {
    props: {
        message: String
    }
}

</script>

<template>
    <main class="w-full lg:w-3/4 mx-auto">
        <div class="font-regular relative block w-full rounded-lg bg-pink-500 p-4 text-base leading-5 text-white opacity-100">
            {{ message }}
        </div>
    </main>
</template>

<style>

</style>
