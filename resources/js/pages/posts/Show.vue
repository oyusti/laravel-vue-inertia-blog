<script setup lang="ts">
import PublicLayout from '@/layouts/app/AppHeaderLayout.vue';
// Asegúrate de que tu tipo Post incluya las relaciones o los datos necesarios
// (author con name, category con name, published_at o created_at, content)
import { type Post } from '@/types';
import { Head } from '@inertiajs/vue3';

// Definimos las props que esperamos recibir del controlador
const props = defineProps<{
    post: Post; // Esperamos un único objeto Post
}>();

{{ console.log(props.post) }}

// Función auxiliar para formatear la fecha (opcional pero útil)
const formatDate = (dateString: string | Date | null): string => {
    if (!dateString) return 'Fecha desconocida';
    // Puedes personalizar las opciones de formato
    const options: Intl.DateTimeFormatOptions = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options); // Usa el locale del navegador
};

</script>

<template>
    <!-- Establece el título de la página dinámicamente -->
    <Head :title="post.title" />

    <PublicLayout>
        <!-- Contenedor principal con padding y centrado -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <article>
                <!-- 1. Título del Post -->
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 mb-4 leading-tight">
                    {{ post.title }}
                </h1>

                <div v-if="post.category" class="mb-2"> <!-- Margen inferior para separar de la imagen -->
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 capitalize">
                        {{ post.category.name }}
                    </span>
                </div>

                <!-- 2. Imagen del Post -->
                <div v-if="post.image_path" class="mb-6 rounded-lg overflow-hidden shadow-lg">
                    <img
                        :src="post.image_path"
                        :alt="`Imagen para ${post.title}`"
                        class="w-full h-auto max-h-[60vh] object-cover"
                    >
                </div>
                <!-- Placeholder si no hay imagen -->
                <div v-else class="my-6 w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>

                <!-- 3. Información Meta (Autor, Fecha, Categoría) -->
                <div class="flex flex-wrap items-center space-x-3 text-sm text-gray-500 mb-8">
                    <!-- Autor -->
                    <span v-if="post.user" class="flex items-center space-x-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="font-medium text-gray-700">{{ post.user.name }}</span>
                    </span>

                    <!-- Separador -->
                    <span v-if="post.user && (post.published_at || post.created_at)" aria-hidden="true">&middot;</span>

                    <!-- Fecha de Publicación -->
                    <span v-if="post.published_at || post.created_at" class="flex items-center space-x-1">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                         </svg>
                        <time :datetime="post.published_at || post.created_at">
                            {{ formatDate(post.published_at || post.created_at) }}
                        </time>
                    </span>

                    <!-- Separador -->
                     <span v-if="(post.author || post.published_at || post.created_at) && post.category" aria-hidden="true">&middot;</span>

                    
                </div>

                <!-- 4. Contenido del Post -->
                <!-- Usamos v-html porque el contenido puede venir con formato HTML (párrafos, negritas, etc.) -->
                <!-- IMPORTANTE: Asegúrate de que el contenido HTML que guardas en la BD esté sanitizado para prevenir ataques XSS -->
                <!-- Considera usar Tailwind Typography plugin (@tailwindcss/typography) para estilizar automáticamente el contenido HTML -->
                <div
                    class="prose prose-lg lg:prose-xl max-w-none text-gray-700 leading-relaxed text-justify"
                    v-html="post.content"
                >

                </div>

            </article>
        </div>
    </PublicLayout>
</template>

<style>
/* Opcional: Estilos específicos si Tailwind Typography no es suficiente */
/* Por ejemplo, para asegurar que las imágenes dentro del contenido no excedan el ancho */
.prose img {
  max-width: 100%;
  height: auto;
  border-radius: 0.5rem; /* rounded-lg */
}
</style>

