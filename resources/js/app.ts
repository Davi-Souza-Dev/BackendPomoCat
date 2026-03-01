import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import '../css/app.css';
import { initializeTheme } from './composables/useAppearance';
// FIREBASE

// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyDJg0vtT1GR4P4uQ0Ig_gBoTCWxfniEVOs",
  authDomain: "pomodoro-37468.firebaseapp.com",
  projectId: "pomodoro-37468",
  storageBucket: "pomodoro-37468.firebasestorage.app",
  messagingSenderId: "26634546197",
  appId: "1:26634546197:web:39166e903a88ed5057f21c",
  measurementId: "G-5S0RZS0ZFL"
};

 
initializeApp(firebaseConfig);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
import { createPinia } from 'pinia'
const pinia = createPinia()

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
