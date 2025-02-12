<template>
  <div class="max-w-2xl mx-auto p-6">
    <!-- Loading State -->
    <div v-if="isLoading" class="text-center py-8">
      <p class="text-gray-600">Loading article...</p>
    </div>

    <!-- Error Message -->
    <div v-if="errorMessage" class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
      {{ errorMessage }}
    </div>

    <!-- Success Message -->
    <div v-if="successMessage" class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
      {{ successMessage }}
    </div>

    <form v-if="!isLoading" @submit.prevent="updateArticle" class="bg-white shadow-md rounded-lg p-8">
      <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">Update Article</h2>

      <!-- Title Input -->
      <div class="mb-6">
        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
          Title
        </label>
        <input
          id="title"
          v-model="form.title"
          type="text"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
          placeholder="Enter article title"
          required
          :disabled="isSaving"
        >
      </div>

      <!-- Content Input -->
      <div class="mb-6">
        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
          Content
        </label>
        <textarea
          id="content"
          v-model="form.content"
          rows="6"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors resize-none"
          placeholder="Write your article content here..."
          required
          :disabled="isSaving"
        ></textarea>
      </div>

      <!-- Buttons -->
      <div class="flex justify-between space-x-4">
        <button
          type="button"
          @click="navigateToList"
          class="w-64 bg-gray-100 text-gray-700 px-8 py-3 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-colors"
        >
          Back to List
        </button>

        <button
          type="submit"
          class="w-64 bg-[#07bf9b] text-white px-8 py-3 rounded-full hover:bg-[#06ab8b] focus:outline-none focus:ring-2 focus:ring-[#07bf9b] focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          :disabled="isSaving"
        >
          <span v-if="isSaving">Updating...</span>
          <span v-else>Update Article</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const form = ref({
  title: '',
  content: ''
})

const isLoading = ref(true)
const isSaving = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

// Get article ID from URL
const articleId = window.location.pathname.split('/').slice(-2)[0] // Get the ID from /articles/{id}/edit

const fetchArticle = async () => {
  isLoading.value = true
  errorMessage.value = ''

  try {
    const response = await axios.get(`/api/articles/${articleId}`)
    form.value = {
      title: response.data.title,
      content: response.data.content
    }
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'An error occurred while fetching the article'
  } finally {
    isLoading.value = false
  }
}

const updateArticle = async () => {
  isSaving.value = true
  errorMessage.value = ''
  successMessage.value = ''

  try {
    await axios.patch(`/api/articles/${articleId}`, form.value)
    successMessage.value = 'Article updated successfully!'
    // Redirect to articles list after a short delay
    setTimeout(() => {
      window.location.href = '/articles'
    }, 1500)
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'An error occurred while updating the article'
  } finally {
    isSaving.value = false
  }
}

const navigateToList = () => {
  window.location.href = '/articles'
}

onMounted(() => {
  fetchArticle()
})
</script>
