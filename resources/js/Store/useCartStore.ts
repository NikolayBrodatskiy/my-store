import { defineStore } from "pinia";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

export const useCartStore = defineStore("Cart", () => {
    const page = usePage();

    const itemsCount = computed(() => page.props.misc?.cartItemsCount ?? 0);

    return { itemsCount };
});
