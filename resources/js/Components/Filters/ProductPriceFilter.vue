<script setup>
import {ref, watch} from "vue";
import {useFilterStore} from "@/Store/useFilterStore";

import InputNumber from 'primevue/inputnumber';

const filterStore = useFilterStore();

const props = defineProps({
    price: Object,
});

const price = ref(props.price);

watch(price, (price) => {
    filterStore.price = price;
});
</script>

<template>
    <section>
        <p class="font-medium text-xl mb-4">Price</p>
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div class="space-x-2 flex">
                    <InputNumber
                        v-model="filterStore.price[0]"
                        :useGrouping="false"
                        inputClass="w-20"
                    />
                </div>

                <div>-</div>

                <div class="space-x-2 flex">
                    <InputNumber
                        v-model="filterStore.price[1]"
                        :useGrouping="false"
                        inputClass="w-20"
                    />
                </div>
            </div>
            <div>
                <Slider
                    v-model="filterStore.price"
                    :min="0"
                    :max="5000"
                    range
                />
            </div>
        </div>
    </section>
</template>
