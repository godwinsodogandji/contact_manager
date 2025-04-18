<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import { ref } from "vue";

// Définir les props pour recevoir les contacts et le message de succès
const props = defineProps<{
    contacts: {
        data: Array<{
            id: number;
            name: string;
            email: string;
            phone?: string;
            company?: string;
            country: string;
            country_flag?: string;
        }>;
        links: Array<{ url: string | null; label: string; active: boolean }>;
    };
}>();

// Initialiser vue-toastification
const toast = useToast();

// État du modal
const showModal = ref(false);
const contactIdToDelete = ref<number | null>(null);

// Méthode pour supprimer un contact
const deleteContact = (id: number) => {
    contactIdToDelete.value = id; // Stocker l'ID pour confirmation
    showModal.value = true; // Afficher le modal
};

const confirmDeletion = () => {
    if (contactIdToDelete.value !== null) {
        useForm({}).delete(route("contacts.destroy", contactIdToDelete.value), {
            onSuccess: () => {
                toast.success("Contact supprimé avec succès !");
                showModal.value = false; // Fermer le modal
            },
            onError: () => {
                toast.error("Erreur lors de la suppression du contact.");
                showModal.value = false; // Fermer le modal
            },
        });
    }
};

const cancelDeletion = () => {
    showModal.value = false; // Fermer le modal
};
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Gestion des Contacts
                </h2>
                <Link
                    :href="route('contacts.create')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
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
                            d="M12 4v16m8-8H4"
                        />
                    </svg>
                    Ajouter un contact
                </Link>
            </div>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    v-if="props.contacts.data.length === 0"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center opacity-0 animate-fade-in"
                >
                    <p class="text-gray-500">
                        Aucun contact trouvé. Commencez par en ajouter un
                        nouveau.
                    </p>
                </div>
                <div
                    v-else
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        v-for="contact in props.contacts.data"
                        :key="contact.id"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1"
                    >
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500"
                                    >
                                        {{ contact.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">
                                            {{ contact.name }}
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            {{ contact.email }}
                                        </p>
                                    </div>
                                </div>
                                <img
                                    v-if="contact.country_flag"
                                    :src="contact.country_flag"
                                    alt="Flag"
                                    class="w-6 h-4"
                                />
                            </div>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 flex items-center">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"
                                        />
                                    </svg>
                                    {{ contact.country }}
                                </p>
                                <p
                                    v-if="contact.phone"
                                    class="text-sm text-gray-500 flex items-center mt-1"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                        />
                                    </svg>
                                    {{ contact.phone }}
                                </p>
                                <p
                                    v-if="contact.company"
                                    class="text-sm text-gray-500 flex items-center mt-1"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                        />
                                    </svg>
                                    {{ contact.company }}
                                </p>
                            </div>
                            <div
                                class="mt-4 pt-4 border-t border-gray-100 flex justify-end space-x-3"
                            >
                                <Link
                                    :href="route('contacts.edit', contact.id)"
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                        />
                                    </svg>
                                    Modifier
                                </Link>
                                <button
                                    @click="deleteContact(contact.id)"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        />
                                    </svg>
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pagination améliorée -->
                <div v-if="props.contacts.links.length > 3" class="mt-8">
                    <div class="flex justify-center">
                        <nav
                            class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                            aria-label="Pagination"
                        >
                            <template
                                v-for="(link, i) in props.contacts.links"
                                :key="i"
                            >
                                <!-- Lien précédent -->
                                <Link
                                    v-if="i === 0 && link.url"
                                    :href="link.url"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors duration-200"
                                >
                                    <span class="sr-only">Précédent</span>
                                    <svg
                                        class="h-5 w-5"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        aria-hidden="true"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </Link>
                                <span
                                    v-else-if="i === 0"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400"
                                >
                                    <span class="sr-only">Précédent</span>
                                    <svg
                                        class="h-5 w-5"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        aria-hidden="true"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </span>
                                <!-- Pages numérotées -->
                                <Link
                                    v-else-if="
                                        i !== props.contacts.links.length - 1 &&
                                        link.url
                                    "
                                    :href="link.url"
                                    :class="[
                                        link.active
                                            ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                        'relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors duration-200',
                                    ]"
                                >
                                    {{ link.label }}
                                </Link>
                                <span
                                    v-else-if="
                                        i !== props.contacts.links.length - 1
                                    "
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                                >
                                    {{ link.label }}
                                </span>
                                <!-- Lien suivant -->
                                <Link
                                    v-else-if="link.url"
                                    :href="link.url"
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors duration-200"
                                >
                                    <span class="sr-only">Suivant</span>
                                    <svg
                                        class="h-5 w-5"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        aria-hidden="true"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </Link>
                                <span
                                    v-else
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400"
                                >
                                    <span class="sr-only">Suivant</span>
                                    <svg
                                        class="h-5 w-5"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        aria-hidden="true"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </span>
                            </template>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- Modal de confirmation -->
            <div
                v-if="showModal"
                class="fixed inset-0 flex items-center justify-center bg-gray-700 bg-opacity-50 z-50 transition-opacity duration-300 ease-in-out"
                :class="{ 'opacity-0 pointer-events-none': !showModal }"
            >
                <div
                    class="bg-white p-6 rounded shadow-lg max-w-md w-full transform transition-transform duration-300 ease-in-out"
                    :class="{ 'scale-90 opacity-0': !showModal }"
                >
                    <div class="flex items-center mb-4">
                        <div class="mr-3 bg-red-100 rounded-full p-2">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-red-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">
                            Confirmation de suppression
                        </h3>
                    </div>
                    <p class="mb-4 text-gray-600">
                        Êtes-vous sûr de vouloir supprimer ce contact ? Cette
                        action est irréversible.
                    </p>
                    <div class="mt-5 flex justify-end space-x-3">
                        <button
                            @click="cancelDeletion"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 mr-1"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                            Annuler
                        </button>
                        <button
                            @click="confirmDeletion"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 mr-1"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                />
                            </svg>
                            Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>