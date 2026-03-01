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
    email: '',
    password: '',
});
import { Toaster, toast } from 'vue-sonner';
import 'vue-sonner/style.css';
import { Toggle } from '@/components/ui/toggle';
import { useUserStore } from '@/stores/UserStore';

const userStore = useUserStore();
const loading = ref(false);
const login = async () => {
    loading.value = !loading.value;
    const response = await userStore.loginForm(
        info.value.email,
        info.value.password,
    );
    toast.error('Email ou senha inválidos');
};


// LOGIN COM O GOOGLE
import { getAuth, signInWithPopup, GoogleAuthProvider } from 'firebase/auth';
import Loading from '../pomodoro/Loading.vue';
const auth = getAuth();
const provider = new GoogleAuthProvider();

const googleLogin = () => {
    loading.value = !loading.value;

    signInWithPopup(auth, provider)
        .then((result) => {
            const credential = GoogleAuthProvider.credentialFromResult(result);
            const token = credential?.accessToken;
            const user = result.user;
            userStore.loginGoogle(
                user.email,
                user.uid,
                user.displayName,
                user.photoURL,
            );
        })
        .catch((error) => {
            // Handle Errors here.
            const errorCode = error.code;
            const errorMessage = error.message;
            // The email of the user's account used.
            const email = error.customData.email;
            // The AuthCredential type that was used.
            const credential = GoogleAuthProvider.credentialFromError(error);

            toast.error('Algo deu errado...!');
        });
};
</script>

<template>
    <div :class="cn('flex flex-col gap-6', props.class)">
        <Toaster position="bottom-center" />
        <Loading :is-loading="loading" />
        <Card>
            <CardHeader>
                <CardTitle class="text-3xl">Login</CardTitle>
            </CardHeader>
            <CardContent>
                <form>
                    <FieldGroup>
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
                            <div class="flex items-center">
                                <FieldLabel for="password"> Senha </FieldLabel>
                                <a
                                    href="#"
                                    class="ml-auto inline-block text-sm underline-offset-4 hover:underline"
                                >
                                    Esqueceu a senha?
                                </a>
                            </div>

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
                                        class="h-8 w-8"
                                        v-model="showPassword"
                                    >
                                        <EyeIcon v-if="showPassword == false" />
                                        <EyeClosedIcon v-else />
                                    </Toggle>
                                </InputGroupAddon>
                            </InputGroup>
                        </Field>
                        <Field>
                            <Button type="button" @click="login">
                                Login
                            </Button>
                            <Button
                                variant="outline"
                                type="button"
                                class="bg-foreground text-background"
                                @click="googleLogin"
                            >
                                Login com Google
                            </Button>
                            <FieldDescription class="text-center">
                                Não tem uma conta?
                                <a href="/auth/register"> Crie uma </a>
                            </FieldDescription>
                        </Field>
                    </FieldGroup>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
