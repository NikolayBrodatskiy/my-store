<script setup>
import {ref} from "vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";

import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import InputError from "@/Components/InputError.vue";
import ButtonPrimary from "@/Components/Buttons/ButtonPrimary.vue";

const props = defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const visible = ref(false);

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <AuthLayout>
        <Head title="Log in"/>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div class="flex flex-col gap-3">
                <div>
                    <IconField iconPosition="left">
                        <InputIcon>
                            <i class="pi pi-at -mt-1"></i>
                        </InputIcon>

                        <InputText
                            v-model="form.email"
                            id="email"
                            type="email"
                            placeholder="Email"
                            class="w-full"
                        />
                    </IconField>
                    <InputError class="mt-1" :message="form.errors.email"/>
                </div>
                <div>
                    <IconField iconPosition="left">
                        <InputIcon>
                            <i class="pi pi-unlock -mt-1"></i>
                        </InputIcon>
                        <InputText
                            v-model="form.password"
                            type="password"
                            placeholder="Password"
                            class="pl-10 w-full"
                        />
                    </IconField>
                    <InputError class="mt-1" :message="form.errors.password"/>
                </div>
                <div class="flex items-center">
                    <Checkbox
                        v-model="form.remember"
                        inputId="remember"
                        :binary="true"
                        value="Cheese"
                    />
                    <label for="remember" class="ml-2"> Remember me</label>
                </div>

                <ButtonPrimary
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log In
                </ButtonPrimary>

                <Link v-if="canResetPassword" :href="route('password.request')"
                      class="text-primary-600 dark:text-primary-300 hover:text-primary-500 dark:hover:text-primary-400">
                    <p>Forgot your password?</p>
                </Link>

                <Link :href="route('register')"
                      class="text-primary-600 dark:text-primary-300 hover:text-primary-500 dark:hover:text-primary-400">
                    <p>Don't have an account? Register</p>
                </Link>
            </div>
        </form>
    </AuthLayout>
</template>

<style scoped></style>
