<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { FormConfiguration, FormField } from '@/types/form';

interface Props {
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
    title: '',
    method: 'POST',
    action: '/submit',
    fields: []
});

const formFields = ref<FormField[]>([]);
const selectedFieldIndex = ref<number | null>(null);
const showNewFieldForm = ref(false);

const newField = ref<FormField>({
    type: 'text',
    name: '',
    label: '',
    placeholder: '',
    required: false,
    validation: {},
    options: [],
    defaultValue: ''
});

const selectedField = computed(() => {
    if (selectedFieldIndex.value === null) return null;
    return formFields.value[selectedFieldIndex.value];
});

const updateFields = () => {
    formConfig.fields = [...formFields.value];
};

const addNewField = () => {
    formFields.value.push({ ...newField.value });
    newField.value = {
        type: 'text',
        name: '',
        label: '',
        placeholder: '',
        required: false,
        validation: {},
        options: [],
        defaultValue: ''
    };
    showNewFieldForm.value = false;
    updateFields();
};

const addOption = () => {
    if (!selectedField.value) return;
    if (!selectedField.value.options) {
        selectedField.value.options = [];
    }
    selectedField.value.options.push({ label: '', value: '' });
};

const removeOption = (index: number) => {
    if (!selectedField.value?.options) return;
    selectedField.value.options.splice(index, 1);
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

const submitForm = () => {
    formConfig.fields = [...formFields.value];
    formConfig.post('/forms', {
        onSuccess: () => {
            formFields.value = [];
            selectedFieldIndex.value = null;
        },
    });
};

const loadSavedConfig = (config: FormConfiguration) => {
    formConfig.title = config.title;
    formConfig.method = config.method;
    formConfig.action = config.action;
    formFields.value = [...config.fields];
    updateFields();
};
</script>

<template>
    <AdminLayout>
        <Head title="Create Form" />

        <div class="max-w-4xl mx-auto mt-8 p-6 bg-white rounded shadow">
            <h2 class="text-2xl font-bold mb-4">Create New Form</h2>

            <div class="space-y-4">
                <div>
                    <label class="block font-medium">Form Title</label>
                    <input v-model="formConfig.title" type="text" class="form-input w-full mt-1" />
                </div>

                <div>
                    <label class="block font-medium">Method</label>
                    <select v-model="formConfig.method" class="form-select w-full mt-1">
                        <option value="GET">GET</option>
                        <option value="POST">POST</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium">Action</label>
                    <input v-model="formConfig.action" type="text" class="form-input w-full mt-1" />
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
                        <button @click="selectedFieldIndex = index" class="text-blue-600">Edit</button>
                        <button @click="removeField(index)" class="text-red-600">Remove</button>
                    </div>
                </li>
            </ul>

            <div v-if="showNewFieldForm" class="mt-6 bg-gray-50 p-4 rounded shadow">
                <h4 class="font-semibold mb-4">Add New Field</h4>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block">Type</label>
                        <select v-model="newField.type" class="form-select w-full mt-1">
                            <option v-for="type in fieldTypes" :key="type.value" :value="type.value">{{ type.label }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block">Name</label>
                        <input v-model="newField.name" type="text" class="form-input w-full mt-1" />
                    </div>
                    <div>
                        <label class="block">Label</label>
                        <input v-model="newField.label" type="text" class="form-input w-full mt-1" />
                    </div>
                    <div>
                        <label class="block">Placeholder</label>
                        <input v-model="newField.placeholder" type="text" class="form-input w-full mt-1" />
                    </div>
                    <div class="col-span-2">
                        <label class="inline-flex items-center">
                            <input v-model="newField.required" type="checkbox" class="form-checkbox" />
                            <span class="ml-2">Required</span>
                        </label>
                    </div>
                </div>

                <div v-if="['select', 'checkbox', 'radio'].includes(newField.type)" class="mt-4">
                    <h5 class="font-semibold mb-2">Options</h5>
                    <div v-if="newField.options" v-for="(option, i) in newField.options" :key="i" class="flex items-center space-x-2 mb-2">
                        <input v-model="option.label" placeholder="Label" class="form-input w-1/2" />
                        <input v-model="option.value" placeholder="Value" class="form-input w-1/2" />
                        <button @click="newField.options.splice(i, 1)" class="text-red-600 text-sm">Remove</button>
                    </div>
                    <button v-if="newField.options" @click="newField.options.push({ label: '', value: '' })" class="text-blue-600 text-sm mt-2">Add Option</button>

                </div>

                <div class="mt-4 flex justify-end space-x-2">
                    <button @click="showNewFieldForm = false" class="text-gray-600">Cancel</button>
                    <button @click="addNewField" class="bg-green-600 text-white px-4 py-2 rounded">Add</button>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button @click="submitForm" class="bg-blue-600 text-white px-6 py-2 rounded">Save Form</button>
            </div>
        </div>
    </AdminLayout>
</template>
