<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-200 px-4 py-2.5">
            <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
                <Link href="/" class="flex items-center">
                    <span class="self-center text-xl font-semibold whitespace-nowrap">Form Management System</span>
                </Link>
                <div class="flex items-center">
                    <ul class="flex flex-row space-x-8 mt-0">
                        <li>
                            <Link href="/" class="block py-2 pr-4 pl-3 text-gray-700 hover:text-blue-600">Dashboard</Link>
                        </li>
                        <li>
                            <Link href="/forms" class="block py-2 pr-4 pl-3 text-gray-700 hover:text-blue-600">Forms</Link>
                        </li>
                        <li>
                            <Link href="/forms/json-config" class="block py-2 pr-4 pl-3 text-gray-700 hover:text-blue-600">JSON Config</Link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <slot></slot>
            </div>
        </main>
    </div>
</template>
