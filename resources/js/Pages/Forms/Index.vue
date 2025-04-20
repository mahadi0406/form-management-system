<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { FormConfiguration } from '@/types/form';
import { ref } from 'vue';

interface Props {
    forms: FormConfiguration[];
}

const props = defineProps<Props>();

// Format the created_at date
const formatDate = (date: string) => {
    const options: Intl.DateTimeFormatOptions = {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
    };
    return new Date(date).toLocaleDateString('en-US', options);
};

// Parse fields from configuration
const getFieldsFromConfig = (form: FormConfiguration) => {
    try {
        if (typeof form.configuration === 'string') {
            return JSON.parse(form.configuration).fields || [];
        } else if (form.configuration?.fields) {
            return form.configuration.fields;
        }
        return [];
    } catch (error) {
        console.error('Error parsing form configuration:', error);
        return [];
    }
};

// Delete form using Inertia router
const deleteForm = (id: number) => {
    if (confirm('Are you sure you want to delete this form?')) {
        router.delete(`/forms/${id}`);
    }
};
</script>

<template>
    <Head title="Forms" />

    <AdminLayout>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold">Forms</h1>
                    <Link
                        href="/forms/create"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700"
                    >
                        Create New Form
                    </Link>
                </div>

                <div class="overflow-x-auto">
                    <table v-if="forms.length" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fields</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="form in forms" :key="form.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ form.title }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500">
                                    <div
                                        v-for="field in getFieldsFromConfig(form)"
                                        :key="field.name"
                                        class="mb-2"
                                    >
                                        <p class="font-semibold">{{ field.label }}:</p>
                                        <p class="text-gray-500">{{ field.placeholder || 'No placeholder' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    {{ formatDate(form.created_at) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <Link
                                    :href="`/forms/${form.id}/edit`"
                                    class="text-green-600 hover:text-green-900"
                                >
                                    Edit
                                </Link>

                                <button
                                    @click="deleteForm(form.id)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <div v-else class="text-center py-10">
                        <p class="text-gray-500 mb-4">No forms have been created yet.</p>
                        <Link
                            href="/forms/create"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700"
                        >
                            Create Your First Form
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
