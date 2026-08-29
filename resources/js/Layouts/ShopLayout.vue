<script setup>
import {ref} from "vue";
import {router} from '@inertiajs/vue3';
import {useI18n} from 'vue-i18n'

import ButtonLink from "@/Components/Buttons/ButtonLink.vue";
import DrawerButtonLink from "@/Components/Buttons/DrawerButtonLink.vue";
import LocaleSwitcher from "@/Components/LocaleSwitcher.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import ApplicationLogo from "@/Components/Buttons/ApplicationLogo.vue";
import LoginButtonLink from "@/Components/Buttons/LoginButtonLink.vue";
import ButtonPrimary from "@/Components/Buttons/ButtonDD.vue";
import ProductSearch from "@/Components/Filters/ProductSearch.vue";
import Drawer from 'primevue/drawer';
import OverlayBadge from 'primevue/overlaybadge';

const {t} = useI18n()

const navigation = ref([]);
const darkMode = ref(isDarkMode());
const menu = ref(null);
const currentSection = ref(null);
const navigationExpand = ref(false);
const CategoriesExpand = ref(false);

function getNavigation() {
    axios.get(`/catalog/navigation`).then((res) => {
        navigation.value = res.data;
    });
}

function navigationExpander() {
    if (navigation.value.length === 0) {
        getNavigation();
    }
    navigationExpand.value = true;
}

function CategoriesExpander(section) {
    currentSection.value = section
    CategoriesExpand.value = true;
}

const overlayMenuItems = ref([
    {
        label: () => t('Profile'),
        icon: 'pi pi-id-card',
        command: () => {
            router.visit(route('profile.edit'))
        },
    },
    {
        label: () => t('Orders'),
        icon: 'pi pi-shopping-bag',
        command: () => {
            router.visit(route('orders.index'))
        },
    },
    {
        separator: true
    },
    {
        label: () => t('Admin panel'),
        icon: 'pi pi-objects-column',
        command: () => {
            location.href = route('moonshine.index')
        },
    },
    {
        label: () => t('Log Out'),
        icon: 'pi pi-sign-out',
        command: () => {
            router.post(route('logout'))
        },
    },
]);


function toggleMenu(event) {
    menu.value.toggle(event);
}

function toggleDarkMode() {
    document.documentElement.classList.toggle('dark');
    darkMode.value = !darkMode.value;
}

function isDarkMode() {
    return document.documentElement.classList.contains('dark');
}

</script>
<template>
    <div class="min-h-screen">
        <header class="w-full sticky top-0 z-20 px-6 py-3 shadow-md bg-surface-200 dark:bg-surface-700">
            <div class="flex items-center justify-between space-x-4">
                <div class="flex space-x-4">
                    <div class="flex">
                        <ButtonPrimary
                            @click="navigationExpander()"
                            variant="text"
                        >
                            <i class="pi pi-bars" style="font-size: 1.25rem"></i>
                        </ButtonPrimary>

                    </div>
                    <div class="flex">
                        <ApplicationLogo/>
                    </div>
                </div>

                <!-- Search -->
                <div class="flex-1">
                    <ProductSearch
                        :target="route('catalog.index')"
                        placeholder="Search"
                        type="text"
                        class="min-w-32 sm:w-full"
                    />
                </div>
                <div class="flex items-center justify-end">
                    <!-- Icon Buttons  -->
                    <LocaleSwitcher/>
                    <div class="flex items-center space-x-4 ml-4">
                        <ButtonPrimary @click="toggleDarkMode()">
                            <i v-if=darkMode class="pi pi-moon" style="font-size: 1.25rem"></i>
                            <i v-else class="pi pi-sun" style="font-size: 1.25rem"></i>
                        </ButtonPrimary>
                        <ButtonLink :href="route('cart.index')">
                            <OverlayBadge :value="$page.props.misc.cartItemsCount" size="small" >
                                <i class="pi pi-shopping-cart" style="font-size: 1.25rem"></i>
                            </OverlayBadge>
                        </ButtonLink>
                        <ButtonLink :href="route('wishes.index')">
                            <i class="pi pi-heart" style="font-size: 1.25rem"></i>
                        </ButtonLink>
                    </div>
                    <!-- Auth -->
                    <div class="ml-3">
                        <div
                            v-if="!$page.props.auth.user"
                            class="flex items-center gap-x-2"
                        >
                            <LoginButtonLink
                                :href="route('login')"
                            >
                            </LoginButtonLink>
                        </div>
                        <div v-else>
                            <div class="sm:ml-1">
                                <div class="relative">
                                    <Menu ref="menu" :model="overlayMenuItems" :popup="true"/>
                                    <ButtonPrimary @click="toggleMenu">
                                        <i class="pi pi-user" style="font-size: 1.25rem"></i>
                                    </ButtonPrimary>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <Transition name="slide-fade">
            <FlashMessage v-if="$page.props.flash.message"></FlashMessage>
        </Transition>

        <!-- Page Heading -->
        <header class="" v-if="$slots.header">
            <div class="mx-auto mt-2 sm:mt-4 text-center text-2xl font-bold">
                <h2>
                    <slot name="header"/>
                </h2>
            </div>
        </header>

        <main>
            <div class="mx-auto p-1 sm:p-2 lg:p-4 min-w-[500px]">
                <slot/>
            </div>
        </main>

        <footer class="w-full sticky top-[100vh] px-6 py-3 bg-surface-200 dark:bg-surface-700">
            <div class="flex justify-end items-center">
                {{ new Date().getFullYear() }} — <strong>MyStore</strong>
            </div>
        </footer>
        <Drawer
            v-model:visible="navigationExpand"
            header="Categories"
            position="left"
            :modal="false"
            :showCloseIcon="false"
            :pt="{
                content: {class: 'p-0!'},
            }"
        >
            <div class="flex">
                <div class="flex flex-col w-1/2 ">
                    <DrawerButtonLink v-for="section in navigation"
                                      :key="section.id"
                                      :href="route('catalog.section.index', section.slug)"
                                      @mouseenter="CategoriesExpander(section)"
                    >
                        {{ section.title }}
                    </DrawerButtonLink>
                </div>

                <div v-if="CategoriesExpand" class="flex flex-col w-1/2">
                    <DrawerButtonLink v-for="category in currentSection.parentCategories"
                                      :key="category.id"
                                      :href="route('catalog.section.category.index', [currentSection.slug, category.slug])"
                    >
                        {{ category.title }}
                    </DrawerButtonLink>
                </div>
            </div>
        </Drawer>
    </div>
</template>

<style></style>
