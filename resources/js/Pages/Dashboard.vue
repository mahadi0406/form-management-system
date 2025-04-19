<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Form {
    id: number;
    title: string;
    created_at: string;
}

interface Props {
    recentForms: Form[];
    totalForms: number;
}

const props = defineProps<Props>();
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
            <div class="p-8 bg-white border-b border-gray-200">
                <h1 class="text-3xl font-semibold text-gray-800 mb-8">Dashboard</h1>

                <section class="bg-blue-50 p-6 rounded-lg shadow-md mb-8">
                    <h2 class="text-2xl font-medium text-blue-700 mb-4">Form Management System</h2>
                    <p class="text-blue-600 mb-4">Welcome to the Form Management System. Create, manage, and track dynamic forms seamlessly.</p>
                    <div class="flex space-x-6">
                        <Link href="/forms/create" class="inline-flex items-center px-5 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                            Create New Form
                        </Link>
                        <Link href="/forms/json-config" class="inline-flex items-center px-5 py-3 bg-white border-2 border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 transition duration-200">
                            Configure JSON
                        </Link>
                    </div>
                </section>

                <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                        <h3 class="text-xl font-medium text-gray-800 mb-3">Total Forms</h3>
                        <p class="text-4xl font-bold text-blue-600">{{ totalForms }}</p>
                    </div>
                </section>

                <section class="mb-8">
                    <h2 class="text-xl font-medium text-gray-900 mb-4">Recent Forms</h2>
                    <div v-if="recentForms.length" class="bg-white shadow-sm overflow-hidden sm:rounded-lg">
                        <ul class="divide-y divide-gray-200">
                            <li v-for="form in recentForms" :key="form.id" class="px-6 py-4 hover:bg-gray-50 transition duration-150">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-blue-700 truncate">{{ form.title }}</p>
                                        <p class="text-sm text-gray-500">Created: {{ new Date(form.created_at).toLocaleDateString() }}</p>
                                    </div>
                                    <div class="flex space-x-3">
                                        <Link :href="`/forms/${form.id}/preview`" class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-md transition duration-200">
                                            Preview
                                        </Link>
                                        <Link :href="`/forms/${form.id}/edit`" class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-700 bg-green-100 hover:bg-green-200 rounded-md transition duration-200">
                                            Edit
                                        </Link>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div v-else class="text-center py-6 text-gray-500">
                        No forms created yet.
                        <Link href="/forms/create" class="text-blue-600 hover:underline">Create your first form</Link>.
                    </div>
                </section>
            </div>
        </div>
    </AdminLayout>
</template>
