<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import { Link } from "@inertiajs/vue3";

// Définir les props pour recevoir les données nécessaires
const props = defineProps<{
    countries: Array<{ id: number; name: string; flag?: string }>;
}>();

const toast = useToast();

// Initialiser le formulaire
const form = useForm({
    name: "",
    email: "",
    phone: "",
    company: "",
    country: "",
    country_flag: "",
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
    

    form.post(route("contacts.store"), {
        data: form, // Envoyer les données transformées
        onSuccess: () => {
            toast.success("Contact ajouté avec succès !");
            form.reset();
        },
        onError: (errors) => {
            toast.error("Veuillez corriger les erreurs dans le formulaire.");
        },
    });
};
</script>

<template>
    <Head title="Ajouter un contact" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Ajouter un nouveau contact
                </h2>
                <Link
                    :href="route('dashboard')"
                    class="inline-flex items-center px-4 cursor-pointer py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 mr-2"
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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <!-- Nom -->
                            <div class="mb-6">
                                <label
                                    for="name"
                                    class="block text-sm font-medium text-gray-700"
                                    >Nom
                                    <span class="text-red-500">*</span></label
                                >
                                <input
                                    type="text"
                                    id="name"
                                    v-model="form.name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                <div
                                    v-if="form.errors.name"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.name }}
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="mb-6">
                                <label
                                    for="email"
                                    class="block text-sm font-medium text-gray-700"
                                    >Email
                                    <span class="text-red-500">*</span></label
                                >
                                <input
                                    type="email"
                                    id="email"
                                    v-model="form.email"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                <div
                                    v-if="form.errors.email"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <!-- Téléphone -->
                            <div class="mb-6">
                                <label
                                    for="phone"
                                    class="block text-sm font-medium text-gray-700"
                                    >Téléphone</label
                                >
                                <input
                                    type="tel"
                                    id="phone"
                                    v-model="form.phone"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                <div
                                    v-if="form.errors.phone"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.phone }}
                                </div>
                            </div>

                            <!-- Entreprise -->
                            <div class="mb-6">
                                <label
                                    for="company"
                                    class="block text-sm font-medium text-gray-700"
                                    >Entreprise</label
                                >
                                <input
                                    type="text"
                                    id="company"
                                    v-model="form.company"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                                <div
                                    v-if="form.errors.company"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.company }}
                                </div>
                            </div>

                            <!-- Pays -->
                            <div class="mb-6">
                                <label
                                    for="country"
                                    class="block text-sm font-medium text-gray-700"
                                    >Pays
                                    <span class="text-red-500">*</span></label
                                >
                                <select
                                    id="country"
                                    v-model="form.country"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
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
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.country }}
                                </div>
                            </div>

                            <!-- Boutons de soumission -->
                            <div class="flex items-center justify-end">
                                <button
                                    type="button"
                                    @click="form.reset()"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-3"
                                >
                                    Réinitialiser
                                </button>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25"
                                >
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
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
