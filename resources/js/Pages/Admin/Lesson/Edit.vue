<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from '@/Components/Button.vue';

// --- Props ---
const props = defineProps({
    lesson: { type: Object, required: true },
    courses: { type: [Object, Array], required: true },
    page_info: { type: Object, required: true },
    button_label: { type: String, required: true },
});

// --- Helper Functions ---
const normalizeContent = (value) => {
    if (value === null || value === undefined) return '';
    if (typeof value === 'string') return value;
    if (Array.isArray(value)) {
        return value
            .map((item) => {
                if (typeof item === 'string') return item;
                if (typeof item === 'object') {
                    if (item?.value !== undefined && item?.value !== null) {
                        if (typeof item.value === 'string') return item.value;
                        if (typeof item.value === 'object') return item.value.html || item.value.content || '';
                        return String(item.value);
                    }
                    return item?.html || item?.content || '';
                }
                return String(item);
            })
            .join('');
    }
    if (typeof value === 'object') {
        if (value?.value !== undefined && value?.value !== null) {
            if (typeof value.value === 'string') return value.value;
            if (typeof value.value === 'object') return value.value.html || value.value.content || '';
            return String(value.value);
        }
        return value.html || value.content || '';
    }
    return String(value);
};

const syncEditorContent = () => {
    if (!window.editor) return;
    const editorContent = normalizeContent(window.editor.getData());
    if (editorContent !== form.content) {
        form.content = editorContent;
    }
};

// --- Form State ---
const form = useForm({
    title: props.lesson.title || '',
    description: props.lesson.description || '',
    status: props.lesson.status || 'Active',
    type: props.lesson.type || 'Default',
    course_id: props.lesson.course_id ?? null,
    content: normalizeContent(props.lesson.content || ''),
    _method: 'PUT',
});

// --- Editor State ---
const editor = ref(null);

onMounted(() => {
    const editorElement = document.querySelector('#content');
    if (editorElement && window.ClassicEditor) {
        if (editor.value) {
            editor.value.destroy();
        }
        window.ClassicEditor.create(editorElement, {
            toolbar: [
                'heading',
                '|',
                'undo',
                'redo',
                '|',
                'bold',
                'italic',
                'underline',
                'strikethrough',
                '|',
                'link',
                'code',
                'codeBlock',
                '|',
                'bulletedList',
                'numberedList',
                '|',
                'outdent',
                'indent',
                'alignment',
                '|',
                'insertTable',
                'blockQuote',
                'imageUpload',
                'mediaEmbed',
            ],
        })
            .then((newEditor) => {
                editor.value = newEditor;
                window.editor = newEditor;
                newEditor.setData(normalizeContent(form.content));
                newEditor.model.document.on('change:data', () => {
                    syncEditorContent();
                });
                newEditor.editing.view.change((writer) => {
                    writer.setStyle('min-height', '400px', newEditor.editing.view.document.getRoot());
                    writer.setStyle('height', '400px', newEditor.editing.view.document.getRoot());
                });
            })
            .catch((error) => {
                console.error('CKEditor error:', error);
            });
    }
});

// --- Form Submission ---
const submitForm = () => {
    if (window.editor) {
        form.content = normalizeContent(window.editor.getData());
    }
    syncEditorContent();
    form.put(route('lessons.update', props.lesson.id), {
        preserveScroll: true,
        onError: () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
    });
};

// UI text labels
const courseList = computed(() => (Array.isArray(props.courses) ? props.courses : props.courses?.data || []));
const title = props.page_info?.title || 'Edit Lesson';
const back_button = props.page_info?.back_button || 'Back To Lessons';
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-6 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Top Header Section Bar -->
            <header class="bg-white border-b border-gray-200 py-6 mb-6 rounded-lg p-4 shadow-sm">
                <div class="flex items-center justify-between w-full">
                    <div class="flex-1 flex justify-start">
                        <Link
                            :href="route('lessons.index')"
                            class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full hover:bg-gray-200 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ back_button }}
                        </Link>
                    </div>
                    <div class="flex-1 text-center">
                        <h1 class="text-2xl font-bold text-gray-800 whitespace-nowrap">
                            {{ title }}
                        </h1>
                    </div>
                    <div class="flex-1"></div>
                </div>
            </header>

            <!-- Main Form Card Container -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-200">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Lesson Title Input -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Lesson Title</label>
                            <input
                                type="text"
                                id="title"
                                v-model="form.title"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm text-gray-900 bg-white"
                                placeholder="Enter lesson title" />
                            <span v-if="form.errors.title" class="text-red-700 text-sm block mt-1 font-medium">
                                {{ form.errors.title }}
                            </span>
                        </div>

                        <!-- Course Assign Dropdown Input -->
                        <div>
                            <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Assign Course</label>
                            <select
                                id="course_id"
                                v-model="form.course_id"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm text-gray-900 bg-white">
                                <option :value="null">-- Select a Course --</option>
                                <option v-for="course in courseList" :key="course.id" :value="course.id">
                                    {{ course.title }}
                                </option>
                            </select>
                            <span v-if="form.errors.course_id" class="text-red-700 text-sm block mt-1 font-medium">
                                {{ form.errors.course_id }}
                            </span>
                        </div>

                        <!-- Description Input -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <input
                                type="text"
                                id="description"
                                v-model="form.description"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm text-gray-900 bg-white"
                                placeholder="Enter lesson description" />
                            <span v-if="form.errors.description" class="text-red-700 text-sm block mt-1 font-medium">
                                {{ form.errors.description }}
                            </span>
                        </div>

                        <!-- Status Selector Input -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select
                                id="status"
                                v-model="form.status"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm text-gray-900 bg-white">
                                <option value="Active">Active</option>
                                <option value="Disabled">Disabled</option>
                            </select>
                            <span v-if="form.errors.status" class="text-red-700 text-sm block mt-1 font-medium">
                                {{ form.errors.status }}
                            </span>
                        </div>

                        <!-- Lesson Type Input -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Lesson Type</label>
                            <select
                                id="type"
                                v-model="form.type"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 p-2 border text-sm text-gray-900 bg-white">
                                <option value="Default">Default</option>
                                <option value="Video">Video</option>
                                <option value="Quiz">Quiz</option>
                            </select>
                            <span v-if="form.errors.type" class="text-red-700 text-sm block mt-1 font-medium">
                                {{ form.errors.type }}
                            </span>
                        </div>

                        <!-- CKEditor Rich Content Block -->
                        <div class="md:col-span-2">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Lesson Content</label>
                            <div class="prose max-w-none">
                                <textarea id="content" name="content" class="hidden"></textarea>
                            </div>
                            <span v-if="form.errors.content" class="text-red-700 text-sm block mt-1 font-medium">
                                {{ form.errors.content }}
                            </span>
                        </div>
                    </div>

                    <!-- Form Action Buttons -->
                    <div class="flex flex-col gap-4 pt-8 border-t border-gray-200 mt-8 sm:flex-row sm:items-center sm:justify-end">
                        <Link
                            :href="route('lessons.index')"
                            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full hover:bg-gray-200 transition duration-200">
                            Cancel
                        </Link>
                        <Button type="submit" :button_label="button_label" :processing="form.processing" />
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
