<script setup>
import {Link, router} from "@inertiajs/vue3";
import ButtonPrimary from "@/Components/Buttons/ButtonPrimary.vue";
import {ref} from "vue";

const props = defineProps(["product"]);

const processingAddToWish = ref(false);
const processingAddToCart = ref(false);

function addToCart(product) {
    router.post(route('cart.add', {product: product}), {}, {
        preserveScroll: true,
        async: true,
        onStart: () => {
            processingAddToCart.value = true;
        },
        onFinish: () => {
            processingAddToCart.value = false;
        },
    })
}

function toggleWish(product) {
    router.post(route('wishes.toggle', {product: product}), {}, {
        preserveScroll: true,
        async: true,
        onStart: () => {
            processingAddToWish.value = true;
        },
        onFinish: () => {
            processingAddToWish.value = false;
        },
    });
}

console.log(props);
</script>
<template>
    <Link :href="route('product.show', product) ">
        <div
            class="max-w-72 min-w-52 cursor-pointer rounded-lg bg-surface-100 dark:bg-surface-800 p-2 shadow duration-150 hover:scale-105 hover:shadow-md">
            <div class="relative">
                <div v-if="!product.image" class="w-full h-72 bg-gray-300 rounded"></div>
                <img v-else class="w-full h-72 rounded-lg object-cover object-center" :src="product.image"
                     :alt="product.title">
                <p v-if="product.sticker" class="absolute top-2 right-2 text-black">
                    {{ product.sticker.title.toUpperCase() }}
                </p>
            </div>
            <div class="flex items-center justify-between py-2">
                <p class="text-l font-semibold text-surface-800 dark:text-surface-200">{{ product.price }}₽</p>
                <p class="text-center min-w-0 flex-1 truncate px-2 text-lg font-semibold text-surface-800 dark:text-surface-200">
                    {{ product.title }}
                </p>
                <Button severity="secondary" text v-if="$page.props.auth.user" @click.prevent="toggleWish(product)"
                        :class="{ 'opacity-50': processingAddToWish }">
                    <i v-if="product.wished" class="pi pi-heart-fill" style="color: red"></i>
                    <i v-else class="pi pi-heart" style="color: red"></i>
                </Button>
            </div>
            <ButtonPrimary
                class="w-full"
                :class="{ 'opacity-50 ': processingAddToCart } "
                @click.prevent="addToCart(product)"
            >
                {{ $t("Add to cart") }}
            </ButtonPrimary>
        </div>
    </Link>
</template>
<style scoped></style>
