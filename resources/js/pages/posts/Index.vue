<script setup lang="ts">
import PublicLayout from '@/layouts/app/AppHeaderLayout.vue';
import { type BreadcrumbItem, Post, LaravelPaginator  } from '@/types';
import { Head, Link } from '@inertiajs/vue3';


const props = defineProps<{
    posts: LaravelPaginator<Post>
}>();

// Función simple para crear un extracto (opcional)
const createExcerpt = (content: string, length = 100): string => {
  if (!content) return '';
  if (content.length <= length) {
    return content;
  }
  return content.substring(0, length) + '...';
};

</script>

<template>
    <Head title="Posts" />
    <PublicLayout>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-4 md:p-6">
            <!-- Iteramos sobre los posts -->
            <div
                v-for="post in posts.data"
                :key="post.id"
                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 ease-in-out flex flex-col"
            >
                <!-- Imagen del Post (Opcional) -->
                <!-- <img v-if="post.image_url" :src="post.image_url" alt="Post image" class="w-full h-48 object-cover"> -->
                <!-- Placeholder si no hay imagen -->
                <div v-if="!post.image_path" class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div v-else>
                    <img :src="post.image_path" alt="Post image" class="w-full h-48 object-cover">
                </div>

                <!-- Contenido de la tarjeta -->
                <div class="p-4 flex flex-col flex-grow">
                    <!-- Título del Post -->
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">
                        {{ post.title }}
                    </h2>

                    <!-- Extracto/Contenido del Post -->
                    <p class="text-gray-600 text-sm mb-4 flex-grow">
                        <!-- Usa la función de extracto o muestra el contenido directamente si es corto -->
                        {{ createExcerpt(post.extract, 120) }}
                        <!-- O si tienes un campo 'excerpt' en tu modelo Post: -->
                        <!-- {{ post.excerpt }} -->
                    </p>

                    <!-- Enlace "Leer más" (usando Inertia Link) -->
                    <!-- Asegúrate de tener una ruta definida llamada 'posts.show' que acepte el slug o id del post -->
                    <Link
                        :href="route('posts.show', { slug: post.slug})"
                        class="mt-auto inline-block text-indigo-600 hover:text-indigo-800 hover:underline font-medium text-sm cursor-pointer"
                    >
                        Leer más &rarr;
                    </Link>

                    <!-- Si prefieres un enlace normal (recarga de página completa) -->
                    <!-- <a :href="`/posts/${post.slug || post.id}`" class="mt-auto inline-block text-indigo-600 hover:text-indigo-800 hover:underline font-medium text-sm">
                        Leer más &rarr;
                    </a> -->
                </div>
            </div>
        </div>

        <!-- Sección de Paginación -->
        <div v-if="posts.links && posts.links.length > posts.per_page" class="mt-6 mb-4 flex justify-center items-center space-x-1 px-4">
            <!-- Iteramos sobre los links de paginación -->
            <template v-for="(link, index) in posts.links" :key="index">
                <!-- Link Deshabilitado (sin URL, ej. '...' o Prev/Next en extremos) -->
                <span
                    v-if="link.url === null"
                    class="px-3 py-2 text-sm text-gray-400 border rounded cursor-default"
                    v-html="link.label"
                />
                <!-- Link Activo (página actual) -->
                <Link
                    v-else-if="link.active"
                    :href="link.url"
                    class="px-3 py-2 text-sm font-bold text-white bg-indigo-600 border border-indigo-600 rounded hover:bg-indigo-700"
                    preserve-scroll
                    v-html="link.label"
                />
                <!-- Link Normal -->
                <Link
                    v-else
                    :href="link.url"
                    class="px-3 py-2 text-sm text-indigo-600 border rounded hover:bg-gray-100 hover:text-indigo-800"
                    preserve-scroll
                    v-html="link.label"
                />
            </template>
        </div>
        <!-- Fin Sección de Paginación -->
    </PublicLayout>
</template>