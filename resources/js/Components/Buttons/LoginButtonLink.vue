<script setup>
import {Link} from "@inertiajs/vue3";
</script>

<template>
    <Link
        v-bind="$attrs"
        class="rounded-md bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 hover:from-pink-500 hover:to-indigo-500 text-white border-none px-4 py-2 font-bold cursor-pointer transition-all"
    >
        LOG IN
    </Link>
</template>
