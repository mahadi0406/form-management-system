<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { FormConfiguration, FormField } from "@/types/form";

interface Props {
    form: FormConfiguration;
    savedConfigs?: FormConfiguration[];
}

const props = defineProps<Props>();

const fieldTypes = [
    { value: 'text', label: 'Text Input' },
    { value: 'email', label: 'Email Input' },
    { value: 'number', label: 'Number Input' },
    { value: 'password', label: 'Password Input' },
    { value: 'textarea', label: 'Text Area' },
    { value: 'select', label: 'Select Dropdown' },
    { value: 'checkbox', label: 'Checkbox' },
    { value: 'radio', label: 'Radio Button' },
    { value: 'date', label: 'Date Picker' },
    { value: 'file', label: 'File Upload' },
    { value: 'button', label: 'Button' },
];

const formConfig = useForm<FormConfiguration>({
    id: props.form.id,
    title: props.form.title,
    method: props.form.method,
    action: props.form.action,
    configuration: {
        fields: props.form.configuration.fields || []
    }
});

const formFields = ref<FormField[]>([]);
const selectedFieldIndex = ref<number | null>(null);
const showNewFieldForm = ref(false);
const showEditFieldForm = ref(false);

const newField = ref<FormField>({
    type: 'text',
    name: '',
    label: '',
    placeholder: '',
    required: false,
});

const selectedField = computed(() => {
    if (selectedFieldIndex.value === null) return null;
    return formFields.value[selectedFieldIndex.value];
});

onMounted(() => {
    // Initialize form fields from the provided form configuration
    formFields.value = [...(props.form.configuration.fields || [])];
    updateFields();
});

const updateFields = () => {
    formConfig.configuration.fields = [...formFields.value];
};

const addNewField = () => {
    formFields.value.push({ ...newField.value });
    newField.value = {
        type: 'text',
        name: '',
        label: '',
        placeholder: '',
        required: false,
    };
    showNewFieldForm.value = false;
    updateFields();
};

const removeField = (index: number) => {
    formFields.value.splice(index, 1);
    if (selectedFieldIndex.value === index) {
        selectedFieldIndex.value = null;
    } else if (selectedFieldIndex.value && selectedFieldIndex.value > index) {
        selectedFieldIndex.value--;
    }
    updateFields();
};

const editField = (index: number) => {
    selectedFieldIndex.value = index;
    showEditFieldForm.value = true;
};

const saveFieldChanges = () => {
    updateFields();
    showEditFieldForm.value = false;
};

const submitForm = () => {
    formConfig.configuration.fields = [...formFields.value];

    formConfig.patch(`/forms/${props.form.id}`, {
        onSuccess: () => {
            window.location.href = '/forms';
        },
        onError: (errors) => {
            console.error('Form update errors:', errors);
        }
    });
};

const loadSavedConfig = (config: FormConfiguration) => {
    formConfig.title = config.title;
    formConfig.method = config.method;
    formConfig.action = config.action;
    formFields.value = [...config.configuration.fields];
    updateFields();
};
</script>

<template>
    <AdminLayout>
        <Head title="Edit Form" />

        <div class="max-w-4xl mx-auto mt-8 p-6 bg-white rounded shadow">
            <h2 class="text-2xl font-bold mb-4">Edit Form</h2>

            <div class="space-y-4">
                <div>
                    <label for="formTitle" class="block text-sm font-medium text-gray-900">Form Title</label>
                    <div class="mt-2">
                        <input v-model="formConfig.title" type="text" id="formTitle" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm" />
                    </div>
                </div>

                <div>
                    <label for="method" class="block text-sm font-medium text-gray-900">Method</label>
                    <div class="mt-2">
                        <select v-model="formConfig.method" id="method" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm">
                            <option value="GET">GET</option>
                            <option value="POST">POST</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="action" class="block text-sm font-medium text-gray-900">Action</label>
                    <div class="mt-2">
                        <input v-model="formConfig.action" type="text" id="action" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm" />
                    </div>
                </div>
            </div>

            <hr class="my-6" />

            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold">Fields</h3>
                <button class="bg-blue-500 text-white px-4 py-1 rounded" @click="showNewFieldForm = true">Add Field</button>
            </div>

            <div v-if="formFields.length === 0" class="text-gray-500 mt-4">No fields added yet.</div>

            <ul class="mt-4 space-y-4">
                <li v-for="(field, index) in formFields" :key="index" class="p-4 border rounded flex justify-between">
                    <div>
                        <strong>{{ field.label || field.name }}</strong> ({{ field.type }})
                        <p class="text-sm text-gray-600">{{ field.placeholder }}</p>
                    </div>
                    <div class="space-x-2">
                        <button @click="editField(index)" class="text-blue-600">Edit</button>
                        <button @click="removeField(index)" class="text-red-600">Remove</button>
                    </div>
                </li>
            </ul>

            <!-- Add New Field Form -->
            <div v-if="showNewFieldForm" class="mt-6 bg-gray-50 p-4 rounded shadow">
                <h4 class="font-semibold mb-4">Add New Field</h4>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="fieldType" class="block text-sm font-medium text-gray-900">Type</label>
                        <div class="mt-2">
                            <select v-model="newField.type" id="fieldType" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm">
                                <option v-for="type in fieldTypes" :key="type.value" :value="type.value">{{ type.label }}</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="fieldName" class="block text-sm font-medium text-gray-900">Name</label>
                        <div class="mt-2">
                            <input v-model="newField.name" type="text" id="fieldName" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm" />
                        </div>
                    </div>

                    <div>
                        <label for="fieldLabel" class="block text-sm font-medium text-gray-900">Label</label>
                        <div class="mt-2">
                            <input v-model="newField.label" type="text" id="fieldLabel" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm" />
                        </div>
                    </div>

                    <div>
                        <label for="fieldPlaceholder" class="block text-sm font-medium text-gray-900">Placeholder</label>
                        <div class="mt-2">
                            <input v-model="newField.placeholder" type="text" id="fieldPlaceholder" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm" />
                        </div>
                    </div>

                    <div class="col-span-2">
                        <label class="inline-flex items-center">
                            <input v-model="newField.required" type="checkbox" class="form-checkbox text-indigo-600" />
                            <span class="ml-2">Required</span>
                        </label>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <button @click="addNewField" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                    <button @click="showNewFieldForm = false" class="ml-2 text-gray-500">Cancel</button>
                </div>
            </div>

            <!-- Edit Field Form -->
            <div v-if="showEditFieldForm && selectedField" class="mt-6 bg-gray-50 p-4 rounded shadow">
                <h4 class="font-semibold mb-4">Edit Field: {{ selectedField.label }}</h4>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="editFieldType" class="block text-sm font-medium text-gray-900">Type</label>
                        <div class="mt-2">
                            <select v-model="selectedField.type" id="editFieldType" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm">
                                <option v-for="type in fieldTypes" :key="type.value" :value="type.value">{{ type.label }}</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="editFieldName" class="block text-sm font-medium text-gray-900">Name</label>
                        <div class="mt-2">
                            <input v-model="selectedField.name" type="text" id="editFieldName" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm" />
                        </div>
                    </div>

                    <div>
                        <label for="editFieldLabel" class="block text-sm font-medium text-gray-900">Label</label>
                        <div class="mt-2">
                            <input v-model="selectedField.label" type="text" id="editFieldLabel" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm" />
                        </div>
                    </div>

                    <div>
                        <label for="editFieldPlaceholder" class="block text-sm font-medium text-gray-900">Placeholder</label>
                        <div class="mt-2">
                            <input v-model="selectedField.placeholder" type="text" id="editFieldPlaceholder" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:outline-indigo-600 sm:text-sm" />
                        </div>
                    </div>

                    <div class="col-span-2">
                        <label class="inline-flex items-center">
                            <input v-model="selectedField.required" type="checkbox" class="form-checkbox text-indigo-600" />
                            <span class="ml-2">Required</span>
                        </label>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <button @click="saveFieldChanges" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                    <button @click="showEditFieldForm = false" class="ml-2 text-gray-500">Cancel</button>
                </div>
            </div>

            <hr class="my-6" />

            <div class="flex justify-between space-x-4">
                <a href="/forms" class="px-4 py-2 text-gray-600 border border-gray-300 rounded">Cancel</a>
                <button @click="submitForm" class="bg-blue-500 text-white px-4 py-2 rounded">Update Form</button>
            </div>
        </div>
    </AdminLayout>
</template>
