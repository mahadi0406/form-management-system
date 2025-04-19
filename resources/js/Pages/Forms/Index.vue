// resources/js/Pages/Forms/Index.vue
<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import {FormConfiguration} from '@/types/form';

interface Props {
  forms: FormConfiguration[];
}

const props = defineProps<Props>();
</script>

<template>
  <Head title="Forms" />

  <AdminLayout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-semibold">Forms</h1>
          <Link href="/forms/create"
              class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700"
          >
            Create New Form
          </Link>
        </div>

        <div class="overflow-x-auto">
          <table v-if="forms.length" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Title
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Fields
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Created
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="form in forms" :key="form.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ form.title }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-500">{{ form.fields.length }} fields</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-500">Data</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
<!--                <Link :href="route('forms.preview', form.id)" class="text-blue-600 hover:text-blue-900">Preview</Link>-->
<!--                <Link :href="route('forms.edit', form.id)" class="text-green-600 hover:text-green-900">Edit</Link>-->
<!--                <Link :href="route('forms.delete', form.id)" method="delete" as="button" type="button" class="text-red-600 hover:text-red-900" preserve-scroll>-->
<!--                  Delete-->
<!--                </Link>-->
              </td>
            </tr>
            </tbody>
          </table>
          <div v-else class="text-center py-10">
            <p class="text-gray-500 mb-4">No forms have been created yet.</p>
            <Link href="/forms/create"
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
