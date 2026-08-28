<script setup>
import {ref} from "vue";
import {Head, usePage} from '@inertiajs/vue3'

import ShopLayout from "@/Layouts/ShopLayout.vue";
import ProductCard from "@/Components/ProductCard.vue";
import Pagination from "@/Components/Pagination.vue";
import ProductPriceFilter from "@/Components/Filters/ProductPriceFilter.vue";
import ProductSearch from "@/Components/Filters/ProductSearch.vue";
import SortProducts from "@/Components/Filters/SortProducts.vue";

import Drawer from 'primevue/drawer';

import {useFilterStore} from "@/Store/useFilterStore.js";
import ButtonPrimary from "@/Components/Buttons/ButtonPrimary.vue";

const filterStore = useFilterStore();

const currentLocation = usePage().props.ziggy.location;

const props = defineProps({
    products: Object,
    categories: Array,
    tags: Array,
    search: String,
    minPrice: Number,
    maxPrice: Number,
    sort: String,
});

filterStore.setStore(props);

const filters = ref({
    search: filterStore.search,
    minPrice: filterStore.minPrice,
    maxPrice:  filterStore.maxPrice,
    sort: filterStore.sort,
});

function applyFilters() {
    filterStore.applyFilters(currentLocation);
}

const filterExpand = ref(false);
</script>
<template>
    <Head title="Home"/>

    <ShopLayout>
        <div class="flex justify-center">
            <div class="flex space-x-8">
                <Button
                    @click="filterExpand = !filterExpand"
                    variant="text"
                >
                    <i class="pi pi-filter"></i>
                    <span>{{ $t("Filters") }}</span>
                </Button>
                <SortProducts
                    :sort="props.sort"
                    @change="applyFilters"
                ></SortProducts>
            </div>
        </div>

        <div class="flex flex-col">
            <div
                class="mx-auto grid max-w-6xl grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-4"
            >
                <ProductCard
                    v-for="product in products.data"
                    :key="product.id"
                    :product="product"
                ></ProductCard>
            </div>
            <div class="mt-2">
                <Pagination
                    v-if="products.links.next || products.links.prev"
                    :links="products.meta.links"
                    :meta="products.links"
                ></Pagination>
            </div>
        </div>

        <div>
            <Drawer
                v-model:visible="filterExpand"
                :header='$t("Filters")'
                position="right"
            >
                <div class="space-y-8 py-4">
                    <div>
                        <ProductSearch/>
                    </div>
                    <div>
                        <ProductPriceFilter/>
                    </div>
                    <div class="flex justify-center">
                        <ButtonPrimary @click="applyFilters" class="w-full">
                            {{ $t("Apply") }}
                        </ButtonPrimary>
                    </div>
                </div>
            </Drawer>
        </div>
    </ShopLayout>
</template>
