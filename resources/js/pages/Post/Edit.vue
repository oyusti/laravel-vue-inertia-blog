<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Input} from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch'
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea'
import { BreadcrumbItem, Category,Post, Message} from '@/types';
import { Head, useForm} from '@inertiajs/vue3';
import { watch } from 'vue';
import Swal from 'sweetalert2';


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
    title: props.post.title,
    slug: props.post.slug,
    category_id: props.post.category_id,
    extract: props.post.extract,
    content: props.post.content,
    is_published: props.post.is_published,
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

</script>

<template>
<Head title="Nuevo Post" />
    <AppLayout :breadcrumbs="breadcrumbs">
        
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min p-6">
                <form 
                    @submit.prevent="form.put(route('admin.posts.update', { post: props.post.id }))"
                    class="space-y-4">    

                    <Label>Titulo:</Label>
                    <Input v-model="form.title"></Input>

                    <Label>Slug:</Label>
                    <Input v-model="form.slug"></Input>

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
                        <Button type="submit" :disabled="form.processing" class="bg-blue-500">
                            Editar
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