<script setup lang="ts">
import { onMounted, ref, type HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Field,
    FieldDescription,
    FieldGroup,
    FieldLabel,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { EyeClosedIcon, EyeIcon } from 'lucide-vue-next';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();

import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
} from '@/components/ui/input-group';

const showPassword = ref(false);

const info = ref({
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
});

import { Toaster } from 'vue-sonner';
import 'vue-sonner/style.css';
import { Toggle } from '@/components/ui/toggle';
import { useUserStore } from '@/stores/UserStore';
import LoadingBar from '../LoadingBar.vue';

const userStore = useUserStore();
const loading = ref(false);

const register = async () => {
    loading.value = !loading.value;
    await userStore.register(
        info.value.username,
        info.value.email,
        info.value.password,
        info.value.password_confirmation,
    );
    loading.value = !loading.value;
};

</script>

<template>
    <div :class="cn('flex flex-col gap-6', props.class)">
        <Toaster position="bottom-center" />
        <LoadingBar :loading="loading" />
        <Card>
            <CardHeader>
                <CardTitle class="text-3xl">Registre-se</CardTitle>
            </CardHeader>
            <CardContent>
                <form>
                    <FieldGroup>
                        <Field>
                            <FieldLabel for="username"> Nome de usuário </FieldLabel>
                            <Input
                                id="username"
                                type="text"
                                placeholder=""
                                required
                                v-model="info.username"
                            />
                        </Field>
                        <Field>
                            <FieldLabel for="email"> Email </FieldLabel>
                            <Input
                                id="email"
                                type="email"
                                placeholder="email@gmail.com"
                                required
                                v-model="info.email"
                            />
                        </Field>
                        <Field>
                            <FieldLabel for="email"> Senha </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    id="password"
                                    v-model="info.password"
                                    :type="
                                        showPassword == false
                                            ? 'password'
                                            : 'text'
                                    "
                                />
                                <InputGroupAddon align="inline-end">
                                    <Toggle
                                        aria-label="Toggle bold"
                                        class="h-8 w-8 bg-transparent active:bg-transparent"
                                        v-model="showPassword"
                                        >
                                        <EyeIcon v-if="showPassword == false" />
                                        <EyeClosedIcon v-else />
                                    </Toggle>
                                </InputGroupAddon>
                            </InputGroup>
                        </Field>
                        <Field>
                            <div class="flex items-center">
                                <FieldLabel for="password_confirmation"> Confirmar Senha </FieldLabel>
                            </div>
                            <InputGroup>
                                <InputGroupInput
                                    id="password_confirmation"
                                    v-model="info.password_confirmation"
                                    type="password"
                                />
                            </InputGroup>
                        </Field>
                        <Field>
                            <Button type="button" @click="register">
                                Registrar
                            </Button>
                            <FieldDescription class="text-center">
                                Já tem uma conta?
                                <a href="/auth/login"> Faça login </a>
                            </FieldDescription>
                        </Field>
                    </FieldGroup>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
