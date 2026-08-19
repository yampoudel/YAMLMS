<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Button from '@/Components/Button.vue'

const syncEditorContent = () => {
  if (!window.editor) return

  const editorContent = normalizeContent(window.editor.getData())
  if (editorContent !== form.content) {
    form.content = editorContent
  }
}

// Ingest ALL properties from controller, including dynamic button label
const props = defineProps({
  lesson: { type: Object, required: false, default: () => ({}) },
  courses: { type: [Object, Array], required: true },
  page_info: { type: Object, required: true },
  button_label: { type: String, required: true },
})

const normalizeContent = (value) => {
  if (value === null || value === undefined) {
    return ''
  }

  if (typeof value === 'string') {
    return value
  }

  if (Array.isArray(value)) {
    return value
      .map((item) => {
        if (typeof item === 'string') return item
        if (typeof item === 'object') {
          // support structures like { type, value } where value may contain html or content
          if (item?.value !== undefined && item?.value !== null) {
            if (typeof item.value === 'string') return item.value
            if (typeof item.value === 'object') return item.value.html || item.value.content || ''
            return String(item.value)
          }
          return item?.html || item?.content || ''
        }
        return String(item)
      })
      .join('')
  }

  if (typeof value === 'object') {
    // support object shapes with value/html/content
    if (value?.value !== undefined && value?.value !== null) {
      if (typeof value.value === 'string') return value.value
      if (typeof value.value === 'object') return value.value.html || value.value.content || ''
      return String(value.value)
    }
    return value.html || value.content || ''
  }

  return String(value)
}

const form = useForm({
  title: props.lesson?.title || '',
  description: props.lesson?.description || '',
  status: props.lesson?.status || 'Active',
  type: props.lesson?.type || 'Default',
  course_id: props.lesson?.course_id ?? null,
  content: normalizeContent(props.lesson?.content || ''),
})

const courseList = computed(() => (Array.isArray(props.courses) ? props.courses : props.courses?.data || []))

const editor = ref(null)
const title = props.page_info?.title || 'Edit Lesson'
const back_button = props.page_info?.back_button || 'Back To Lessons'

onMounted(() => {
  const editorElement = document.querySelector('#content')
  if (editorElement && window.ClassicEditor) {
    if (editor.value) {
      editor.value.destroy()
    }

    window.ClassicEditor
      .create(editorElement, {
        toolbar: [
          'heading', '|',
          'undo', 'redo', '|',
          'bold', 'italic', 'underline', 'strikethrough', '|',
          'link', 'code', 'codeBlock', '|',
          'bulletedList', 'numberedList', '|',
          'outdent', 'indent', 'alignment', '|',
          'insertTable', 'blockQuote', 'imageUpload', 'mediaEmbed',
        ],
      })
      .then((newEditor) => {
        editor.value = newEditor
        window.editor = newEditor
        newEditor.setData(normalizeContent(form.content))

        newEditor.model.document.on('change:data', () => {
          syncEditorContent()
        })

        newEditor.editing.view.change((writer) => {
          writer.setStyle('min-height', '400px', newEditor.editing.view.document.getRoot())
          writer.setStyle('height', '400px', newEditor.editing.view.document.getRoot())
        })
      })
      .catch((error) => {
        console.error('CKEditor error:', error)
      })
  }
})

const submitForm = () => {
  // Scrape data out of the custom iframe editor layer back into Inertia before shipping over HTTP
  if (window.editor) {
    form.content = normalizeContent(window.editor.getData())
  }

  syncEditorContent()

  form.post(route('lessons.store'), {
    preserveScroll: true,
    onError: () => window.scrollTo({ top: 0, behavior: 'smooth' }),
  })
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-6 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <header class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center justify-start">
                        <Link
                            :href="route('lessons.index')"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 transition-colors duration-200 hover:bg-slate-200 focus:outline-none focus:ring-4 focus:ring-slate-200"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>{{ back_button }}</span>
                        </Link>
                    </div>

                    <div class="flex-1 text-center sm:px-4">
                        <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">
                            {{ title }}
                        </h1>
                    </div>

                    <div class="hidden sm:block sm:w-32" aria-hidden="true"></div>
                </div>
            </header>

            <!-- Main Section -->
            <div class="bg-white shadow-sm sm:rounded-lg p-8 border border-gray-200">
                <form @submit.prevent="submitForm" class="space-y-6">
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <!-- Title Field Input -->
                        <div>
                            <label
                                for='title'
                                class="block text-base font-semibold text-gray-900 mb-3"
                            >
                                Lesson Title
                            </label>
                            <input
                                type='text'
                                name='title'
                                id='title'
                                v-model="form.title"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                :aria-invalid="Boolean(form.errors.title)"
                                placeholder="Enter lesson title"
                            />
                            <span
                                v-if="form.errors.title"
                                class="text-red-700 text-sm block mt-2"
                            >
                                {{ form.errors.title }}
                            </span>
                        </div>

                        <!-- Course Assign Dropdown Input -->
                        <div>
                            <label
                                for="course_id"
                                class="block text-base font-semibold text-gray-900 mb-3"
                            >
                                Course Assign
                            </label>
                            <select
                                id="course_id"
                                v-model="form.course_id"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                :aria-invalid="Boolean(form.errors.course_id)"
                            >
                                <option :value="null">
                                    -- Select a Course --
                                </option>
                                <option
                                    v-for="course in courseList"
                                    :key="course.id"
                                    :value="course.id"
                                >
                                    {{ course.title }}
                                </option>
                            </select>
                            <span
                                v-if="form.errors.course_id"
                                class="text-red-700 text-sm block mt-2"
                            >
                                {{ form.errors.course_id }}
                            </span>
                        </div>

                        <!-- Lesson Status Selection Dropdown -->
                        <div>
                            <label
                                for="status"
                                class="block text-base font-semibold text-gray-900 mb-3"
                            >
                                Status
                            </label>
                            <select
                                id="status"
                                v-model="form.status"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                :aria-invalid="Boolean(form.errors.status)"
                            >
                                <option value="Active">
                                    Active
                                </option>
                                <option value="Disabled">
                                    Disabled
                                </option>
                            </select>
                            <span
                                v-if="form.errors.status"
                                class="text-red-700 text-sm block mt-2"
                            >
                                {{ form.errors.status }}
                            </span>
                        </div>

                        <!-- Lesson Core Type Input -->
                        <div>
                            <label
                                for="type"
                                class="block text-base font-semibold text-gray-900 mb-3"
                            >
                                Type
                            </label>
                            <select
                                id="type"
                                name="type"
                                v-model="form.type"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                :aria-invalid="Boolean(form.errors.type)"
                            >
                                <option value="Default">
                                    Default
                                </option>
                                <option value="Survey">
                                    Survey
                                </option>
                                <option value="Quiz">
                                    Quiz
                                </option>
                            </select>
                            <span
                                v-if="form.errors.type"
                                class="text-red-700 text-sm block mt-2"
                            >
                                {{ form.errors.type }}
                            </span>
                        </div>

                        <!-- Description Box (Full Width Spans) -->
                        <div class="md:col-span-2">
                            <label
                                for="description"
                                class="block text-base font-semibold text-gray-900 mb-3"
                            >
                                Description
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                maxlength="500"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent h-20 resize-none"
                                :aria-invalid="Boolean(form.errors.description)"
                                placeholder="Enter lesson description"
                            ></textarea>
                            <span
                                v-if="form.errors.description"
                                class="text-red-700 text-sm block mt-2"
                            >
                                {{ form.errors.description }}
                            </span>
                        </div>

                        <!-- Content Textarea Input Target Block Frame -->
                        <div class="md:col-span-2">
                            <label
                                for="content"
                                class="block text-base font-semibold text-gray-900 mb-3"
                            >
                                Lesson Content
                            </label>
                            <textarea
                                id="content"
                                v-model="form.content"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent min-h-[400px] h-[400px] resize-none"
                                :aria-invalid="Boolean(form.errors.content)"
                                placeholder="Enter lesson content"
                            ></textarea>
                            <span
                                v-if="form.errors.content"
                                class="text-red-700 text-sm block mt-2 font-medium"
                            >
                                {{ form.errors.content }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 pt-8 border-t border-gray-200 mt-8 sm:flex-row sm:items-center sm:justify-end">
                        <Link
                            :href="route('lessons.index')"
                            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 rounded-full hover:bg-gray-200 transition duration-200"
                        >
                            Cancel
                        </Link>
                        <Button
                            type="submit"
                            :button_label="button_label"
                            :disabled="form.processing"
                        />
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
