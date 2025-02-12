<template>
  <div class="max-w-4xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">Articles List</h2>

    <!-- Loading State -->
    <div v-if="isLoading" class="text-center py-8">
      <p class="text-gray-600">Loading articles...</p>
    </div>

    <!-- Articles List -->
    <div v-if="!isLoading && articles.length > 0" class="space-y-4">
      <div v-for="article in articles" :key="article.id" class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ article.title }}</h3>
        <p class="text-gray-600 mb-4">{{ truncateContent(article.content) }}</p>
        <div class="flex justify-end space-x-3">
          <button
            @click="confirmDelete(article.id)"
            class="bg-red-500 text-white px-6 py-2 rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors text-sm"
          >
            Delete
          </button>
          <button
            @click="handleEdit(article)"
            class="bg-gray-500 text-white px-6 py-2 rounded-full hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors text-sm"
          >
            Update
          </button>
          <button
            @click="handleViewDetails(article)"
            class="bg-[#07bf9b] text-white px-6 py-2 rounded-full hover:bg-[#06ab8b] focus:outline-none focus:ring-2 focus:ring-[#07bf9b] focus:ring-offset-2 transition-colors text-sm"
          >
            View Details
          </button>
        </div>
      </div>
    </div>

    <!-- No Articles State -->
    <div v-if="!isLoading && articles.length === 0" class="text-center py-8">
      <p class="text-gray-600">No articles found</p>
    </div>

    <!-- Create Article Button -->
    <div class="mt-8 flex justify-center">
      <button
        @click="showCreateForm = true"
        class="w-64 bg-[#07bf9b] text-white px-8 py-3 rounded-full hover:bg-[#06ab8b] focus:outline-none focus:ring-2 focus:ring-[#07bf9b] focus:ring-offset-2 transition-colors"
      >
        Create New Article
      </button>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg p-6 max-w-sm w-full">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirm Delete</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to delete this article? This action cannot be undone.</p>
        <div class="flex justify-end space-x-3">
          <button
            @click="showDeleteModal = false"
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-colors"
          >
            Cancel
          </button>
          <button
            @click="deleteArticle"
            class="px-4 py-2 bg-red-500 text-white rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
            :disabled="isDeleting"
          >
            <span v-if="isDeleting">Deleting...</span>
            <span v-else>Delete</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showCreateForm || editingArticle" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg p-6 max-w-2xl w-full">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">{{ editingArticle ? 'Update Article' : 'Create New Article' }}</h3>
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
            <input
              id="title"
              v-model="form.title"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
              required
            >
          </div>
          <div>
            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
            <textarea
              id="content"
              v-model="form.content"
              rows="6"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors resize-none"
              required
            ></textarea>
          </div>
          <div class="flex justify-end space-x-3 mt-6">
            <button
              type="button"
              @click="closeForm"
              class="px-6 py-2 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-6 py-2 bg-[#07bf9b] text-white rounded-full hover:bg-[#06ab8b] focus:outline-none focus:ring-2 focus:ring-[#07bf9b] focus:ring-offset-2 transition-colors"
              :disabled="isSubmitting"
            >
              <span v-if="isSubmitting">{{ editingArticle ? 'Updating...' : 'Creating...' }}</span>
              <span v-else>{{ editingArticle ? 'Update' : 'Create' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- View Details Modal -->
    <div v-if="viewingArticle" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg p-6 max-w-2xl w-full">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ viewingArticle.title }}</h3>
        <div class="prose max-w-none mb-6">
          <p class="text-gray-600 text-lg leading-relaxed">{{ viewingArticle.content }}</p>
        </div>
        <div class="flex justify-end">
          <button
            @click="viewingArticle = null"
            class="px-6 py-2 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-colors"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useToast } from 'vue-toastification'

const toast = useToast()
const articles = ref([])
const isLoading = ref(true)
const showDeleteModal = ref(false)
const isDeleting = ref(false)
const articleToDelete = ref(null)
const showCreateForm = ref(false)
const editingArticle = ref(null)
const viewingArticle = ref(null)
const isSubmitting = ref(false)

const form = ref({
  title: '',
  content: ''
})

const fetchArticles = async () => {
  isLoading.value = true

  try {
    const response = await axios.get('/api/articles/list')
    articles.value = response.data
  } catch (error) {
    toast.error(error.response?.data?.message || 'An error occurred while fetching articles')
  } finally {
    isLoading.value = false
  }
}

const handleSubmit = async () => {
  isSubmitting.value = true
  try {
    if (editingArticle.value) {
      await axios.patch(`/api/articles/${editingArticle.value.id}`, form.value)
      toast.success('Article updated successfully!')
    } else {
      await axios.post('/api/articles/create', form.value)
      toast.success('Article created successfully!')
    }
    await fetchArticles()
    closeForm()
  } catch (error) {
    toast.error(error.response?.data?.message || `An error occurred while ${editingArticle.value ? 'updating' : 'creating'} the article`)
  } finally {
    isSubmitting.value = false
  }
}

const handleEdit = (article) => {
  editingArticle.value = article
  form.value = {
    title: article.title,
    content: article.content
  }
}

const handleViewDetails = (article) => {
  viewingArticle.value = article
}

const closeForm = () => {
  showCreateForm.value = false
  editingArticle.value = null
  form.value = {
    title: '',
    content: ''
  }
}

const confirmDelete = (articleId) => {
  articleToDelete.value = articleId
  showDeleteModal.value = true
}

const deleteArticle = async () => {
  if (!articleToDelete.value) return

  isDeleting.value = true

  try {
    await axios.delete(`/api/articles/${articleToDelete.value}`)
    toast.success('Article deleted successfully!')
    showDeleteModal.value = false
    await fetchArticles()
  } catch (error) {
    toast.error(error.response?.data?.message || 'An error occurred while deleting the article')
    showDeleteModal.value = false
  } finally {
    isDeleting.value = false
    articleToDelete.value = null
  }
}

const truncateContent = (content) => {
  return content.length > 200 ? content.substring(0, 200) + '...' : content
}

onMounted(() => {
  fetchArticles()
})
</script>
