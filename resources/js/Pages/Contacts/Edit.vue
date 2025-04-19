<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import { Link } from "@inertiajs/vue3";

// Définir les props pour recevoir les données nécessaires
const props = defineProps<{
    contact: {
        id: number;
        name: string;
        email: string;
        phone?: string;
        company?: string;
        country: number;
    };
    countries: Array<{ id: number; name: string; flag?: string }>;
}>();

const toast = useToast();

// Initialiser le formulaire avec les données du contact
const form = useForm({
    name: props.contact.name,
    email: props.contact.email,
    phone: props.contact.phone || "",
    company: props.contact.company || "",
    country: props.contact.country,
    country_flag: "", // Ajouter le nom du pays
});

// Méthode pour soumettre le formulaire
const submit = () => {
    const selectedCountry = props.countries.find(
        (country) => country.name === form.country
    );
    console.log("Selected country:", selectedCountry); // Debugging line

    if (selectedCountry) {
        form.country_flag = selectedCountry.flag || ""; // Ajouter le nom du pays
    }
    console.log("Form data before submit:", form); // Debugging line

    form.put(route("contacts.update", props.contact.id), {
        onSuccess: () => {
            toast.success("Contact modifié avec succès !");
        },
        onError: (errors) => {
            toast.error("Veuillez corriger les erreurs dans le formulaire.");
        },
    });
};
</script>

<template>
    <Head title="Modifier le contact" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 animate-fadeIn"
                >
                    Modifier le contact
                </h2>
                <Link
                    :href="route('contacts.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 hover:scale-105"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 mr-2 transition-transform duration-300 group-hover:-translate-x-1"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>
                    Retour à la liste
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg animate-slideInUp"
                >
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <TransitionGroup name="form-field" tag="div" appear>
                                <!-- Nom -->
                                <div class="mb-6" key="name">
                                    <label
                                        for="name"
                                        class="block text-sm font-medium text-gray-700 transition-all duration-300 focus-within:text-blue-600"
                                        >Nom
                                        <span class="text-red-500"
                                            >*</span
                                        ></label
                                    >
                                    <input
                                        type="text"
                                        id="name"
                                        v-model="form.name"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-all duration-300"
                                        required
                                        @focus="highlightField('name')"
                                        @blur="resetHighlight"
                                        :class="{
                                            'border-blue-400 ring-2 ring-blue-200':
                                                activeField === 'name',
                                        }"
                                    />
                                    <div
                                        v-if="form.errors.name"
                                        class="mt-1 text-sm text-red-600 animate-shake"
                                    >
                                        {{ form.errors.name }}
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="mb-6" key="email">
                                    <label
                                        for="email"
                                        class="block text-sm font-medium text-gray-700 transition-all duration-300 focus-within:text-blue-600"
                                        >Email
                                        <span class="text-red-500"
                                            >*</span
                                        ></label
                                    >
                                    <input
                                        type="email"
                                        id="email"
                                        v-model="form.email"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-all duration-300"
                                        required
                                        @focus="highlightField('email')"
                                        @blur="resetHighlight"
                                        :class="{
                                            'border-blue-400 ring-2 ring-blue-200':
                                                activeField === 'email',
                                        }"
                                    />
                                    <div
                                        v-if="form.errors.email"
                                        class="mt-1 text-sm text-red-600 animate-shake"
                                    >
                                        {{ form.errors.email }}
                                    </div>
                                </div>

                                <!-- Téléphone -->
                                <div class="mb-6" key="phone">
                                    <label
                                        for="phone"
                                        class="block text-sm font-medium text-gray-700 transition-all duration-300 focus-within:text-blue-600"
                                        >Téléphone</label
                                    >
                                    <input
                                        type="tel"
                                        id="phone"
                                        v-model="form.phone"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-all duration-300"
                                        @focus="highlightField('phone')"
                                        @blur="resetHighlight"
                                        :class="{
                                            'border-blue-400 ring-2 ring-blue-200':
                                                activeField === 'phone',
                                        }"
                                    />
                                    <div
                                        v-if="form.errors.phone"
                                        class="mt-1 text-sm text-red-600 animate-shake"
                                    >
                                        {{ form.errors.phone }}
                                    </div>
                                </div>

                                <!-- Entreprise -->
                                <div class="mb-6" key="company">
                                    <label
                                        for="company"
                                        class="block text-sm font-medium text-gray-700 transition-all duration-300 focus-within:text-blue-600"
                                        >Entreprise</label
                                    >
                                    <input
                                        type="text"
                                        id="company"
                                        v-model="form.company"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-all duration-300"
                                        @focus="highlightField('company')"
                                        @blur="resetHighlight"
                                        :class="{
                                            'border-blue-400 ring-2 ring-blue-200':
                                                activeField === 'company',
                                        }"
                                    />
                                    <div
                                        v-if="form.errors.company"
                                        class="mt-1 text-sm text-red-600 animate-shake"
                                    >
                                        {{ form.errors.company }}
                                    </div>
                                </div>

                                <!-- Pays -->
                                <div class="mb-6" key="country">
                                    <label
                                        for="country"
                                        class="block text-sm font-medium text-gray-700 transition-all duration-300 focus-within:text-blue-600"
                                        >Pays
                                        <span class="text-red-500"
                                            >*</span
                                        ></label
                                    >
                                    <select
                                        id="country"
                                        v-model="form.country"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-all duration-300"
                                        required
                                        @focus="highlightField('country')"
                                        @blur="resetHighlight"
                                        :class="{
                                            'border-blue-400 ring-2 ring-blue-200':
                                                activeField === 'country',
                                        }"
                                    >
                                        <option value="" disabled>
                                            Sélectionnez un pays
                                        </option>
                                        <option
                                            v-for="country in props.countries"
                                            :key="country.id"
                                            :value="country.name"
                                        >
                                            {{ country.name }}
                                        </option>
                                    </select>
                                    <div
                                        v-if="form.errors.country"
                                        class="mt-1 text-sm text-red-600 animate-shake"
                                    >
                                        {{ form.errors.country }}
                                    </div>
                                </div>
                            </TransitionGroup>

                            <!-- Boutons de soumission -->
                            <div class="flex items-center justify-end mt-8">
                                <Link
                                    :href="route('contacts.index')"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-3 hover:scale-105"
                                >
                                    Annuler
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center cursor-pointer px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25 relative overflow-hidden group hover:scale-105"
                                >
                                    <span
                                        class="absolute right-full w-full h-full bg-blue-800 group-hover:animate-slide-right"
                                    ></span>
                                    <svg
                                        v-if="form.processing"
                                        class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            class="opacity-25"
                                            cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                        ></circle>
                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                        ></path>
                                    </svg>
                                    <span class="relative z-10"
                                        >Mettre à jour</span
                                    >
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
/* Animations pour les champs du formulaire */
.form-field-enter-active,
.form-field-leave-active {
    transition: all 0.5s ease;
}

.form-field-enter-from {
    opacity: 0;
    transform: translateY(30px);
}

.form-field-leave-to {
    opacity: 0;
    transform: translateY(-30px);
}

/* Staggered animation - delay pour chaque item */
.form-field-enter-active:nth-child(1) {
    transition-delay: 0ms;
}
.form-field-enter-active:nth-child(2) {
    transition-delay: 100ms;
}
.form-field-enter-active:nth-child(3) {
    transition-delay: 200ms;
}
.form-field-enter-active:nth-child(4) {
    transition-delay: 300ms;
}
.form-field-enter-active:nth-child(5) {
    transition-delay: 400ms;
}

/* Animation pour le bouton de soumission */
@keyframes slide-right {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(0);
    }
}

/* Animation de shake pour les erreurs */
@keyframes shake {
    0%,
    100% {
        transform: translateX(0);
    }
    10%,
    30%,
    50%,
    70%,
    90% {
        transform: translateX(-5px);
    }
    20%,
    40%,
    60%,
    80% {
        transform: translateX(5px);
    }
}

/* Animation d'entrée pour le conteneur du formulaire */
@keyframes slideInUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Utility classes */
.animate-shake {
    animation: shake 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
}

.animate-slideInUp {
    animation: slideInUp 0.6s ease-out forwards;
}

.animate-fadeIn {
    animation: fadeIn 0.8s ease-out forwards;
}

.animate-slide-right {
    animation: slide-right 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
}

/* Styles de focus améliorés */
input:focus,
select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
    transition: all 0.3s ease;
}
</style>
