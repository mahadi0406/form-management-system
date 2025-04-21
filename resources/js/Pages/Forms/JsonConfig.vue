<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';
import { FormConfiguration, FormField } from "@/types/form";

// Sample default JSON configuration - updated to match validation rules
const defaultConfig = {
    title: "New Form",
    method: "POST",
    action: "/api/submit",
    configuration: {
        fields: [
            {
                type: "text",
                name: "full_name",
                label: "Full Name",
                placeholder: "Enter your full name",
                required: true
            }
        ]
    }
};

// Form state for submitting configuration
const form = useForm({
    title: "New JSON Form",
    method: "POST",
    action: "",
    configuration: {
        fields: defaultConfig.configuration.fields
    }
});

// Preview state
const showPreview = ref(false);
const previewData = reactive<FormConfiguration>(defaultConfig);

// Validation state
const jsonError = ref('');
const isValid = ref(true);

// Convert form data to JSON string for display
const formConfigAsString = ref(JSON.stringify({
    title: form.title,
    method: form.method,
    action: form.action,
    configuration: form.configuration
}, null, 2));

// Update preview when JSON changes
const updatePreview = () => {
    try {
        const parsed = JSON.parse(formConfigAsString.value);

        // Update form data
        form.title = parsed.title || 'Untitled Form';
        form.method = parsed.method || 'POST';
        form.action = parsed.action || '';
        form.configuration = parsed.configuration || { fields: [] };

        // Update preview data
        previewData.title = parsed.title || 'Untitled Form';
        previewData.method = parsed.method || 'POST';
        previewData.action = parsed.action || '';
        previewData.configuration = parsed.configuration || { fields: [] };

        jsonError.value = '';
        isValid.value = true;
        showPreview.value = true;
    } catch (error) {
        jsonError.value = (error as Error).message;
        isValid.value = false;
        showPreview.value = false;
    }
};

// Format JSON
const formatJson = () => {
    try {
        const parsed = JSON.parse(formConfigAsString.value);
        formConfigAsString.value = JSON.stringify(parsed, null, 2);
        jsonError.value = '';
        isValid.value = true;
    } catch (error) {
        jsonError.value = (error as Error).message;
        isValid.value = false;
    }
};

// Handle form submission
const submitForm = () => {
    if (!isValid.value) return;

    // Submit to the forms resource route
    form.post('/forms', {
        onSuccess: () => {
            showPreview.value = false;
        }
    });
};

// Add examples dropdown options - updated to match validation rules
const exampleTemplates = [
    {
        name: 'Contact Form',
        config: {
            title: "Contact Form",
            method: "POST",
            action: "/api/contact",
            configuration: {
                fields: [
                    {
                        type: "text",
                        name: "full_name",
                        label: "Full Name",
                        placeholder: "Enter your full name",
                        required: true
                    },
                    {
                        type: "email",
                        name: "email",
                        label: "Email Address",
                        placeholder: "your@email.com",
                        required: true
                    },
                    {
                        type: "textarea",
                        name: "message",
                        label: "Message",
                        placeholder: "Enter your message here",
                        required: true
                    },
                    {
                        type: "checkbox",
                        name: "subscribe",
                        label: "Subscribe to newsletter",
                        required: false
                    }
                ]
            }
        }
    },
    {
        name: 'Registration Form',
        config: {
            title: "User Registration",
            method: "POST",
            action: "/api/register",
            configuration: {
                fields: [
                    {
                        type: "text",
                        name: "username",
                        label: "Username",
                        placeholder: "Choose a username",
                        required: true
                    },
                    {
                        type: "email",
                        name: "email",
                        label: "Email Address",
                        placeholder: "your@email.com",
                        required: true
                    },
                    {
                        type: "password",
                        name: "password",
                        label: "Password",
                        placeholder: "Enter a secure password",
                        required: true
                    },
                    {
                        type: "password",
                        name: "password_confirmation",
                        label: "Confirm Password",
                        placeholder: "Confirm your password",
                        required: true
                    },
                    {
                        type: "select",
                        name: "role",
                        label: "Role",
                        required: true,
                        options: [
                            { label: "User", value: "user" },
                            { label: "Editor", value: "editor" },
                            { label: "Admin", value: "admin" }
                        ]
                    },
                    {
                        type: "checkbox",
                        name: "terms_agreement",
                        label: "I agree to the Terms and Conditions",
                        required: true
                    }
                ]
            }
        }
    }
];

const loadTemplate = (template: any) => {
    // Update both form data and JSON string
    form.title = template.config.title;
    form.method = template.config.method;
    form.action = template.config.action;
    form.configuration = template.config.configuration;

    formConfigAsString.value = JSON.stringify(template.config, null, 2);
    updatePreview();
};
</script>

<template>
    <Head title="JSON Form Configuration" />

    <AdminLayout>
        <div class="max-w-7xl mx-auto py-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-8">
                        <h1 class="text-3xl font-semibold text-gray-800">JSON Form Configuration</h1>
                        <div class="flex space-x-4">
                            <button
                                @click="formatJson"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition"
                            >
                                Format JSON
                            </button>
                            <div class="relative inline-block text-left">
                                <select
                                    @change="(e) => loadTemplate(exampleTemplates[(e.target as HTMLSelectElement).value])"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition"
                                >
                                    <option value="" disabled selected>Load Template</option>
                                    <option v-for="(template, index) in exampleTemplates" :key="index" :value="index">
                                        {{ template.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 p-6 rounded-lg mb-8">
                        <h2 class="text-xl font-medium text-blue-700 mb-2">Create Forms with JSON</h2>
                        <p class="text-blue-600 mb-4">
                            Design your form structure using JSON configuration. Define form fields, validation rules, and appearance.
                        </p>
                    </div>

                    <form @submit.prevent="submitForm" class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Form Title</label>
                            <input
                                type="text"
                                id="title"
                                v-model="form.title"
                                class="block w-full rounded-md bg-white px-3 py-2 text-gray-900 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                required
                                maxlength="255"
                            />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="method" class="block text-sm font-medium text-gray-700 mb-1">Form Method</label>
                                <select
                                    id="method"
                                    v-model="form.method"
                                    class="block w-full rounded-md bg-white px-3 py-2 text-gray-900 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                                    <option value="GET">GET</option>
                                    <option value="POST">POST</option>
                                </select>
                            </div>

                            <div>
                                <label for="action" class="block text-sm font-medium text-gray-700 mb-1">Form Action URL</label>
                                <input
                                    type="text"
                                    id="action"
                                    v-model="form.action"
                                    class="block w-full rounded-md bg-white px-3 py-2 text-gray-900 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                    maxlength="255"
                                    placeholder="/api/endpoint"
                                />
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label for="json-config" class="block text-sm font-medium text-gray-700">JSON Configuration</label>
                                <button
                                    type="button"
                                    @click="updatePreview"
                                    class="text-sm text-indigo-600 hover:text-indigo-800"
                                >
                                    Update Preview
                                </button>
                            </div>
                            <textarea
                                id="json-config"
                                v-model="formConfigAsString"
                                class="block w-full rounded-md bg-white px-3 py-2 text-gray-900 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm"
                                rows="20"
                                required
                            ></textarea>
                            <p v-if="jsonError" class="mt-2 text-sm text-red-600">{{ jsonError }}</p>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <button
                                type="button"
                                @click="updatePreview"
                                class="px-5 py-2 bg-indigo-100 text-indigo-700 rounded-md hover:bg-indigo-200 transition"
                            >
                                Preview Form
                            </button>
                            <button
                                type="submit"
                                :disabled="!isValid"
                                class="px-5 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition"
                                :class="{ 'opacity-50 cursor-not-allowed': !isValid }"
                            >
                                Create Form
                            </button>
                        </div>
                    </form>

                    <!-- Form Preview Section -->
                    <div v-if="showPreview" class="mt-12">
                        <h2 class="text-xl font-medium text-gray-900 mb-4">Form Preview</h2>
                        <div class="bg-white border border-gray-200 rounded-lg shadow-md overflow-hidden">
                            <!-- Preview Header -->
                            <div class="bg-indigo-700 px-6 py-4">
                                <h1 class="text-xl font-semibold text-white">{{ previewData.title }}</h1>
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

                            <!-- Preview Method and Action -->
                            <div class="bg-gray-50 px-6 py-3 border-b border-gray-100">
                                <div class="flex items-center text-sm text-gray-500">
                                    <span class="font-medium">Method:</span>
                                    <span class="ml-2 px-2 py-1 bg-gray-200 rounded">{{ previewData.method }}</span>
                                    <span class="ml-4 font-medium">Action:</span>
                                    <span class="ml-2">{{ previewData.action || 'Not specified' }}</span>
                                </div>
                            </div>

                            <!-- Form Fields Preview -->
                            <div class="p-6">
                                <form class="space-y-6">
                                    <div v-for="(field, index) in previewData.configuration.fields" :key="index" class="space-y-2">
                                        <label v-if="field.type !== 'checkbox'" :for="`preview-${field.name}`" class="block text-sm font-medium text-gray-700">
                                            {{ field.label }}
                                            <span v-if="field.required" class="text-red-500">*</span>
                                        </label>

                                        <div v-if="field.type === 'text'">
                                            <input
                                                type="text"
                                                :id="`preview-${field.name}`"
                                                :name="field.name"
                                                :placeholder="field.placeholder"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                            />
                                        </div>

                                        <div v-else-if="field.type === 'email'">
                                            <input
                                                type="email"
                                                :id="`preview-${field.name}`"
                                                :name="field.name"
                                                :placeholder="field.placeholder"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                            />
                                        </div>

                                        <div v-else-if="field.type === 'password'">
                                            <input
                                                type="password"
                                                :id="`preview-${field.name}`"
                                                :name="field.name"
                                                :placeholder="field.placeholder"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                            />
                                        </div>

                                        <div v-else-if="field.type === 'number'">
                                            <input
                                                type="number"
                                                :id="`preview-${field.name}`"
                                                :name="field.name"
                                                :placeholder="field.placeholder"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                            />
                                        </div>

                                        <div v-else-if="field.type === 'textarea'">
                                            <textarea
                                                :id="`preview-${field.name}`"
                                                :name="field.name"
                                                :placeholder="field.placeholder"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                                rows="4"
                                            ></textarea>
                                        </div>

                                        <div v-else-if="field.type === 'select'">
                                            <select
                                                :id="`preview-${field.name}`"
                                                :name="field.name"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                            >
                                                <option v-for="option in field.options" :key="option.value" :value="option.value">
                                                    {{ option.label }}
                                                </option>
                                            </select>
                                        </div>

                                        <div v-else-if="field.type === 'checkbox'" class="flex items-center">
                                            <input
                                                type="checkbox"
                                                :id="`preview-${field.name}`"
                                                :name="field.name"
                                                class="form-checkbox text-indigo-600"
                                            />
                                            <label :for="`preview-${field.name}`" class="ml-2 text-sm text-gray-700">
                                                {{ field.label }}
                                                <span v-if="field.required" class="text-red-500">*</span>
                                            </label>
                                        </div>

                                        <div v-else-if="field.type === 'radio'" class="space-y-2">
                                            <div v-for="option in field.options" :key="option.value" class="flex items-center">
                                                <input
                                                    type="radio"
                                                    :id="`preview-${field.name}-${option.value}`"
                                                    :name="field.name"
                                                    :value="option.value"
                                                    class="form-radio text-indigo-600"
                                                />
                                                <label :for="`preview-${field.name}-${option.value}`" class="ml-2 text-sm text-gray-700">
                                                    {{ option.label }}
                                                </label>
                                            </div>
                                        </div>

                                        <div v-else-if="field.type === 'date'">
                                            <input
                                                type="date"
                                                :id="`preview-${field.name}`"
                                                :name="field.name"
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm"
                                            />
                                        </div>

                                        <div v-else-if="field.type === 'file'">
                                            <input
                                                type="file"
                                                :id="`preview-${field.name}`"
                                                :name="field.name"
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
                                            type="button"
                                            class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-200"
                                        >
                                            Submit Form (Preview Only)
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- JSON Schema Guide -->
                    <div class="mt-12">
                        <h2 class="text-xl font-medium text-gray-900 mb-4">JSON Schema Guide</h2>
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h3 class="font-medium text-gray-800 mb-2">Form Configuration Structure</h3>
                            <pre class="bg-gray-800 text-gray-100 p-4 rounded-md overflow-x-auto text-sm">
{
  "title": "Form Title",
  "method": "POST|GET",
  "action": "/api/submit-endpoint",
  "configuration": {
    "fields": [
      {
        "type": "text|email|password|number|textarea|select|checkbox|radio|date|file|button",
        "name": "field_name",
        "label": "Field Label",
        "placeholder": "Placeholder text",
        "required": true|false,
        "options": [                 // For select and radio types
          {
            "label": "Option Label",
            "value": "option_value"
          }
        ]
      }
    ]
  }
}</pre>

                            <h3 class="font-medium text-gray-800 mt-6 mb-2">Validation Rules</h3>
                            <ul class="list-disc list-inside space-y-1 text-gray-700">
                                <li><span class="font-medium">title</span> - Required, max 255 characters</li>
                                <li><span class="font-medium">method</span> - Must be either GET or POST</li>
                                <li><span class="font-medium">action</span> - Optional, max 255 characters</li>
                                <li><span class="font-medium">fields</span> - Required, must have at least one field</li>
                                <li><span class="font-medium">field.name</span> - Required for each field</li>
                                <li><span class="font-medium">field.type</span> - Required for each field</li>
                                <li><span class="font-medium">options</span> - Required for select and radio fields</li>
                            </ul>

                            <h3 class="font-medium text-gray-800 mt-6 mb-2">Supported Field Types</h3>
                            <ul class="list-disc list-inside space-y-1 text-gray-700">
                                <li><span class="font-medium">text</span> - Single line text input</li>
                                <li><span class="font-medium">email</span> - Email input with validation</li>
                                <li><span class="font-medium">password</span> - Password input</li>
                                <li><span class="font-medium">number</span> - Numeric input</li>
                                <li><span class="font-medium">textarea</span> - Multi-line text input</li>
                                <li><span class="font-medium">select</span> - Dropdown selection</li>
                                <li><span class="font-medium">checkbox</span> - Single checkbox</li>
                                <li><span class="font-medium">radio</span> - Radio button group</li>
                                <li><span class="font-medium">date</span> - Date picker</li>
                                <li><span class="font-medium">file</span> - File upload</li>
                                <li><span class="font-medium">button</span> - Button element</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
