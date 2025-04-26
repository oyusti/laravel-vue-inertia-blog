<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Input} from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch'
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue, SelectLabel} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';
import { defineProps } from 'vue';
import { BreadcrumbItem, Category,Post, Message, Tag} from '@/types';
import { Head, useForm} from '@inertiajs/vue3';
import { ref, watch, toRaw, onMounted } from 'vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';
import Swal from 'sweetalert2';

let quillInstance: Quill | null = null;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '/admin/posts',
    },
    {
        title: 'Crear',
        href: '',
    },
];

const props = defineProps<{
    categories: Category;
    message?: Message | null;
    post: Post;
    tags: Tag[]; // Assuming this holds ALL available tags and should be an array
}>();

const form = useForm({
    _method: 'PUT', // Importante para la subida de archivos con PUT/PATCH en Laravel
    title: props.post.title,
    slug: props.post.slug,
    category_id: props.post.category_id,
    extract: props.post.extract,
    content: props.post.content,
    image: null as File | null, // Inicializa como null, ya que el input espera un File
    is_published: props.post.is_published,
    tags: props.post.tags?.map(tag => tag.id.toString()) || [], // Extrae solo los IDs
    
});

// Variable reactiva para la URL de la imagen a mostrar (existente o previsualización)
const imagePreviewUrl = ref<string | null>(props.post.image_path || null); // Usa la imagen existente si hay

// --- ¡Inicializa Quill cuando el componente esté montado! ---
onMounted(() => {
    const editorElement = document.querySelector('#editor'); // Busca el elemento

    if (editorElement) { // Asegúrate que el elemento exista
        quillInstance = new Quill(editorElement, {
            theme: 'snow', // Elige tu tema (snow o bubble)
            modules: {
                toolbar: [ // Configura tu barra de herramientas
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'font': [] }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video'], // Añade opciones de medios si las necesitas
                    ['clean'] // Botón para limpiar formato
                ]
            },
            placeholder: 'Escribe el contenido aquí...',
        });

        // Establece el contenido inicial del editor desde form.content
        if (form.content) {
            quillInstance.root.innerHTML = form.content;
        }

        // Escucha cambios en el editor y actualiza form.content
        quillInstance.on('text-change', () => {
            // Actualiza form.content con el HTML del editor
            // Usa optional chaining (?) por si acaso la instancia no está lista
            form.content = quillInstance?.root.innerHTML || '';
        });

    } else {
        console.error("Elemento #editor no encontrado en el DOM para inicializar Quill.");
    }
});

// Watch for changes in the title and update the slug accordingly
watch(() => form.title, (newTitle) => {
  if (newTitle) {
    form.slug = generateSlug(newTitle);
  } else {
    form.slug = '';
  }
});

// Watch for changes in the message prop
watch(
    () => props.message,
    (newMessage) => {
        if (newMessage) {
            Swal.fire({
                text: newMessage.text,
                icon: newMessage.icon,
                title: newMessage.title,
            });
        }
    },
    { immediate: true } // Run the callback immediately on component mount
);

// Function to generate the slug
const generateSlug = (text: string) => {
  return text
    .trim() // Remove leading and trailing spaces
    .toLowerCase() // Convert to lowercase
    .replace(/\s+/g, '-') // Replace spaces with hyphens
    .replace(/[^a-z0-9-]/g, '') // Remove any character that is not a letter, number or hyphen
    .replace(/-+/g, '-') // Replace multiple hyphens with a single hyphen
    .replace(/^-+|-+$/g, ''); // Remove hyphens from the beginning and end
};

// Función para manejar la selección de archivos
const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        const file = target.files[0];
        form.image = file; // Asigna el objeto File al form

        // Crear URL de previsualización
        const reader = new FileReader();
        reader.onload = (e) => {
            // Limpiar URL anterior si existe y es un blob URL
             if (imagePreviewUrl.value && imagePreviewUrl.value.startsWith('blob:')) {
                URL.revokeObjectURL(imagePreviewUrl.value);
            }
            //imagePreviewUrl.value = e.target?.result as string;
            imagePreviewUrl.value = URL.createObjectURL(file);
        };
        reader.readAsDataURL(file); 
    } else {
        // Opcional: manejar el caso donde se cancela la selección
        form.image = null;
    }
};

// Función de envío (ajustada para usar post debido a _method)
const submit = () => {
    // Inertia maneja automáticamente _method: 'PUT' con una petición POST
    form.post(route('admin.posts.update', { post: props.post.id }), {
        onSuccess: () => {
            // Acciones post-éxito si es necesario
        },
        onError: (errors) => {
            console.error('Error submitting form:', errors);
            // Manejar errores
        }
    });
};

const handleTagToggle = (tagIdAsString: string, isChecked: boolean) => {
    
    // Obtén una referencia al array actual (¡importante!)
    const currentTags = form.tags; 

    if (isChecked) {
        // Añadir el ID (string) si NO está ya presente
        if (!currentTags.includes(tagIdAsString)) {
            // ¡Muta el array directamente! No hagas form.tags = [...], eso rompe la reactividad de useForm
            currentTags.push(tagIdAsString); 
        }
    } else {
        // Quitar el ID (string) si está presente
        const index = currentTags.indexOf(tagIdAsString);
        if (index > -1) {
            // ¡Muta el array directamente!
            currentTags.splice(index, 1); 
        }
    }
};

// Opcional: Watch para depurar el array de tags mientras seleccionas
watch(() => form.tags, (newTags) => {
    console.log('IDs de Tags seleccionados:', toRaw(newTags));
}, { deep: true }); // deep: true por si acaso, aunque no debería ser necesario para un array de primitivos

</script>

<template>
<Head title="Nuevo Post" />
    <AppLayout :breadcrumbs="breadcrumbs">
        
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min p-6">

                <form 
                    @submit.prevent="submit"
                    class="space-y-4"
                    enctype="multipart/form-data"
                >    
                    <div class="relative mb-4">
                        <img 
                        v-if="imagePreviewUrl"
                        :src="imagePreviewUrl"
                        class="w-full aspect-video rounded-xl object-cover object-center" 
                        alt="Previsualización de imagen"
                        >
                        <img 
                        v-else
                        class="w-full aspect-video rounded-xl object-cover object-center"
                        src="data:image/png;base64,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"
                        >
                        <div class="absolute top-8 right-8 ">
                            <label class="bg-white rounded-full px-4 py-2 cursor-pointer">
                                Cambiar Imagen
                                <input class="hidden" type="file" @input="handleFileChange" accept="image/*">
                            </label>
                        </div>
                    </div>
                    
                    <Label>Titulo:</Label>
                    <Input v-model="form.title"></Input>
                    <span v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</span>

                    <Label>Slug:</Label>
                    <Input v-model="form.slug"></Input>
                    <span v-if="form.errors.slug" class="text-red-500 text-sm">{{ form.errors.slug }}</span>

                    <Label>Categoria:</Label>
                    <Select v-model="form.category_id">    
                        <SelectTrigger>
                            <SelectValue placeholder="Seleccione una categoria" />
                        </SelectTrigger>
                        <SelectContent>
                        <SelectGroup>
                            <SelectLabel>Categorias</SelectLabel>
                            <SelectItem v-for="category in categories" 
                                :key="category.id" 
                                :value="category.id">
                                {{ category.name }}
                            </SelectItem>
                        </SelectGroup>
                        </SelectContent>
                    </Select>
                    <span v-if="form.errors.category_id" class="text-red-500 text-sm">{{ form.errors.category_id }}</span>

                    <Label for="extract">Resumen:</Label>
                    <Textarea id="extract" v-model="form.extract">{{ form.extract }}</Textarea>


                    
                    <Label for="editor">Contenido:</Label>

                    <div id="editor" style="min-height: 250px;">
                        <!-- El contenido se cargará aquí dinámicamente -->
                    </div>

                    <div>
                        <Label>Etiquetas:</Label>
                        <div v-for="tag in tags" :key="tag.id" class="flex items-center space-x-2 py-1">
                            <Checkbox 
                                :id="'tag-checkbox-' + tag.id"
                                :value="tag.id.toString()"  
                                :checked="form.tags.includes(tag.id.toString())"
                                @update:checked="(isChecked) => handleTagToggle(tag.id.toString(), isChecked)"                 
                            />
                            <Label :for="'tag-checkbox-' + tag.id">{{ tag.name }}</Label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <Label>Estado:</Label>
                        <div class="space-x-2">
                            <Switch v-if="form.is_published" id="is_published" v-model="form.is_published" value="true" />
                            <Switch v-else id="is_published" v-model="form.is_published" value="false" />
                            <Label for="is_published">Publicado</Label>
                        </div>
                    </div>

                    <div class="flex justify-end w-full gap-4">
                        <Button type="submit" :disabled="form.processing" class="bg-blue-500 hover:bg-blue-600">
                            Guardar
                        </Button>
                        <Button as="a" variant="outline" :href="route('admin.posts.index')" class="bg-red-500">
                            Cancelar
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>