<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { FormConfiguration, FormField } from "@/types/form";

interface Props {
    form: FormConfiguration;
}

const props = defineProps<Props>();

const formFields = computed(() => props.form.configuration.fields || []);

// Define the form data with an index signature to allow dynamic field names
const formData = ref<Record<string, any>>({});

// Initialize form data with default values
const initFormData = () => {
    formFields.value.forEach(field => {
        if (field.name) {  // Ensure field.name exists before using it as an index
            if (field.type === 'checkbox') {
                formData.value[field.name] = false;
            } else if (field.type === 'select' || field.type === 'radio') {
                // formData.value[field.name] = field.options ? field.options[0]?.value : '';
            } else {
                formData.value[field.name] = '';
            }
        }
    });
};

initFormData();

const handleSubmit = (e: Event) => {
    e.preventDefault();
    alert('This is a preview. Form submission is disabled.');
};

const getFieldClass = (field: FormField): string => {
    const baseClasses = "block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm";

    if (field.type === 'checkbox' || field.type === 'radio') {
        return "form-checkbox text-indigo-600";
    }

    return baseClasses;
};
</script>

<template>
    <AdminLayout>
        <Head :title="`Preview: ${form.title}`" />

        <div class="max-w-4xl mx-auto mt-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-indigo-700 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <h1 class="text-xl font-semibold text-white">{{ form.title }}</h1>
                        <div class="flex space-x-3">
                            <Link :href="`/forms/${form.id}/edit`" class="inline-flex items-center px-3 py-1 bg-white text-indigo-700 rounded text-sm font-medium hover:bg-indigo-50">
                                Edit Form
                            </Link>
                            <Link href="/forms" class="inline-flex items-center px-3 py-1 bg-transparent border border-white text-white rounded text-sm font-medium hover:bg-indigo-600">
                                Back to Forms
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Preview Notice -->
                <div class="bg-yellow-50 px-6 py-3 border-b border-yellow-100">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <p class="ml-2 text-sm text-yellow-700">This is a preview. Form submissions in preview mode are disabled.</p>
                    </div>
                </div>

                <!-- Form Preview -->
                <div class="p-6">
                    <form @submit="handleSubmit" class="space-y-6">
                        <div v-for="(field, index) in formFields" :key="index" class="space-y-2">
                            <label v-if="field.type !== 'checkbox'" :for="field.name" class="block text-sm font-medium text-gray-700">
                                {{ field.label }}
                                <span v-if="field.required" class="text-red-500">*</span>
                            </label>

                            <div v-if="field.type === 'text'">
                                <input
                                    type="text"
                                    :id="field.name"
                                    :name="field.name"
                                    v-model="formData[field.name as string]"
                                    :placeholder="field.placeholder"
                                    :required="field.required"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                />
                            </div>

                            <div v-else-if="field.type === 'email'">
                                <input
                                    type="email"
                                    :id="field.name"
                                    :name="field.name"
                                    v-model="formData[field.name as string]"
                                    :placeholder="field.placeholder"
                                    :required="field.required"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                />
                            </div>

                            <div v-else-if="field.type === 'password'">
                                <input
                                    type="password"
                                    :id="field.name"
                                    :name="field.name"
                                    v-model="formData[field.name as string]"
                                    :placeholder="field.placeholder"
                                    :required="field.required"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                />
                            </div>

                            <div v-else-if="field.type === 'number'">
                                <input
                                    type="number"
                                    :id="field.name"
                                    :name="field.name"
                                    v-model="formData[field.name as string]"
                                    :placeholder="field.placeholder"
                                    :required="field.required"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                />
                            </div>

                            <div v-else-if="field.type === 'textarea'">
                                <textarea
                                    :id="field.name"
                                    :name="field.name"
                                    v-model="formData[field.name as string]"
                                    :placeholder="field.placeholder"
                                    :required="field.required"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                    rows="4"
                                ></textarea>
                            </div>

                            <div v-else-if="field.type === 'select'">
                                <select
                                    :id="field.name"
                                    :name="field.name"
                                    v-model="formData[field.name as string]"
                                    :required="field.required"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                >
<!--                                    <option v-for="option in field.options" :key="option.value" :value="option.value">-->
<!--                                        {{ option.label }}-->
<!--                                    </option>-->
                                </select>
                            </div>

                            <div v-else-if="field.type === 'checkbox'" class="flex items-center">
                                <input
                                    type="checkbox"
                                    :id="field.name"
                                    :name="field.name"
                                    v-model="formData[field.name as string]"
                                    :required="field.required"
                                    class="form-checkbox text-indigo-600"
                                />
                                <label :for="field.name" class="ml-2 text-sm text-gray-700">
                                    {{ field.label }}
                                    <span v-if="field.required" class="text-red-500">*</span>
                                </label>
                            </div>

                            <div v-else-if="field.type === 'radio'" class="space-y-2">
<!--                                <div v-for="option in field.options" :key="option.value" class="flex items-center">-->
<!--                                    <input-->
<!--                                        type="radio"-->
<!--                                        :id="`${field.name}-${option.value}`"-->
<!--                                        :name="field.name"-->
<!--                                        :value="option.value"-->
<!--                                        v-model="formData[field.name as string]"-->
<!--                                        :required="field.required"-->
<!--                                        class="form-radio text-indigo-600"-->
<!--                                    />-->
<!--                                    <label :for="`${field.name}-${option.value}`" class="ml-2 text-sm text-gray-700">{{ option.label }}</label>-->
<!--                                </div>-->
                            </div>

                            <div v-else-if="field.type === 'date'">
                                <input
                                    type="date"
                                    :id="field.name"
                                    :name="field.name"
                                    v-model="formData[field.name as string]"
                                    :required="field.required"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                />
                            </div>

                            <div v-else-if="field.type === 'file'">
                                <input
                                    type="file"
                                    :id="field.name"
                                    :name="field.name"
                                    :required="field.required"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                />
                            </div>

                            <div v-else-if="field.type === 'button'">
                                <button
                                    type="button"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-200"
                                >
                                    {{ field.label || 'Button' }}
                                </button>
                            </div>
                        </div>

                        <div class="pt-4 border-t">
                            <button
                                type="submit"
                                class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-200"
                            >
                                Submit Form (Preview Only)
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Form Details -->
            <div class="mt-6 bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h2 class="text-lg font-medium text-gray-900">Form Details</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Form Title</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ form.title }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Form Method</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ form.method }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Form Action</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ form.action }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Number of Fields</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ formFields.length }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
