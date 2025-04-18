<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Input} from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch'
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue, SelectLabel} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea'
import { BreadcrumbItem, Category,Post, Message} from '@/types';
import { Head, useForm} from '@inertiajs/vue3';
import { ref,watch } from 'vue';
import Swal from 'sweetalert2';
/* import { Plus } from 'lucide-vue-next'; */


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '/posts',
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
});

// Variable reactiva para la URL de la imagen a mostrar (existente o previsualización)
const imagePreviewUrl = ref<string | null>(props.post.image_path || null); // Usa la imagen existente si hay

{{console.log(imagePreviewUrl.value)}}

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
        reader.readAsDataURL(file); // Puedes usar readAsDataURL para obtener un base64
        // O usar URL.createObjectURL(file) para obtener un blob URL (más eficiente)
        // if (imagePreviewUrl.value && imagePreviewUrl.value.startsWith('blob:')) {
        //     URL.revokeObjectURL(imagePreviewUrl.value);
        // }
        // imagePreviewUrl.value = URL.createObjectURL(file);

    } else {
        // Opcional: manejar el caso donde se cancela la selección
        form.image = null;
        // Opcional: volver a la imagen original si se cancela
        // imagePreviewUrl.value = props.post.image_path || null;
    }
};

console.log(imagePreviewUrl.value)

// Función de envío (ajustada para usar post debido a _method)
const submit = () => {
    // Inertia maneja automáticamente _method: 'PUT' con una petición POST
    form.post(route('admin.posts.update', { post: props.post.id }), {
        // Opcional: forzar multipart/form-data si Inertia no lo detecta automáticamente
        // forceFormData: true,
        onSuccess: () => {
            // Acciones post-éxito si es necesario
        },
        onError: (errors) => {
            console.error('Error submitting form:', errors);
            // Manejar errores
        }
    });
};

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


                    <Label for="content">Contenido:</Label>
                    <Textarea id="content" v-model="form.content" rows="20">{{ form.content }}</Textarea>

                    <div class="space-x-2">
                        <Switch v-if="form.is_published" id="is_published" v-model="form.is_published" value="true" />
                        <Switch v-else id="is_published" v-model="form.is_published" value="false" />
                        <Label for="is_published">Publicado</Label>
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