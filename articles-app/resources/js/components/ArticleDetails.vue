<template>
  <div class="max-w-4xl mx-auto p-6">
    <!-- Loading State -->
    <div v-if="isLoading" class="text-center py-8">
      <p class="text-gray-600">Loading article...</p>
    </div>

    <!-- Error Message -->
    <div v-if="errorMessage" class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
      {{ errorMessage }}
    </div>

    <!-- Article Details -->
    <div v-if="!isLoading && article" class="bg-white shadow-md rounded-lg p-8">
      <h2 class="text-3xl font-bold mb-6 text-gray-800">{{ article.title }}</h2>
      <div class="prose max-w-none">
        <p class="text-gray-600 text-lg leading-relaxed">{{ article.content }}</p>
      </div>

      <!-- Buttons -->
      <div class="mt-8 flex justify-end space-x-4">
        <button
          @click="navigateToList"
          class="w-48 bg-[#07bf9b] text-white px-8 py-3 rounded-full hover:bg-[#06ab8b] focus:outline-none focus:ring-2 focus:ring-[#07bf9b] focus:ring-offset-2 transition-colors"
        >
          Back to List
        </button>
        <button
          @click="navigateToEdit"
          class="w-48 bg-gray-500 text-white px-8 py-3 rounded-full hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
        >
          Edit Article
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const article = ref(null)
const isLoading = ref(true)
const errorMessage = ref('')

// Get article ID from URL
const articleId = window.location.pathname.split('/').pop()

const fetchArticle = async () => {
  isLoading.value = true
  errorMessage.value = ''

  try {
    const response = await axios.get(`/api/articles/${articleId}`)
    article.value = response.data
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'An error occurred while fetching the article'
  } finally {
    isLoading.value = false
  }
}

const navigateToList = () => {
  window.location.href = '/articles'
}

const navigateToEdit = () => {
  window.location.href = `/articles/${articleId}/edit`
}

onMounted(() => {
  fetchArticle()
})
</script>
