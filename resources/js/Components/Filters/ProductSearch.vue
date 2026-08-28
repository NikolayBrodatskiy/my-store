<script setup>
import InputText from 'primevue/inputtext';

import {useFilterStore} from "@/Store/useFilterStore";
import {usePage} from "@inertiajs/vue3";

const filterStore = useFilterStore();
const currentLocation = usePage().props.ziggy.location;
const props = defineProps({
    target: {
        type: String,
        default: null,
    },
});

function applyFilters() {
    filterStore.applyFilters(props.target ?? currentLocation);
}
</script>

<template>
        <InputText
            v-bind="$attrs"
            v-model="filterStore.search"
            type="text"
            placeholder="Search"
            v-on:keyup.enter="applyFilters"
        />
</template>
