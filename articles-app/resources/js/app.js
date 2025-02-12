import './bootstrap';
import { createApp } from 'vue';
import CreateArticle from './components/CreateArticle.vue';
import ListArticles from './components/ListArticles.vue';
import ArticleDetails from './components/ArticleDetails.vue';
import UpdateArticle from './components/UpdateArticle.vue';

const app = createApp({});

app.component('create-article', CreateArticle);
app.component('list-articles', ListArticles);
app.component('article-details', ArticleDetails);
app.component('update-article', UpdateArticle);

app.mount('#app');
