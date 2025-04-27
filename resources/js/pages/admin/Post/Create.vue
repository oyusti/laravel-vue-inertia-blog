<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Input} from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue} from '@/components/ui/select';
import { BreadcrumbItem, Category} from '@/types';
import { Head, useForm} from '@inertiajs/vue3';
import { watch } from 'vue';


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

const form = useForm({
    title: '',
    slug: '',
    category_id: '',
});

const props = defineProps<{
    categories: Category;
}>();

// Watch for changes in the title and update the slug accordingly
watch(() => form.title, (newTitle) => {
  if (newTitle) {
    form.slug = generateSlug(newTitle);
  } else {
    form.slug = '';
  }
});

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
                    @submit.prevent="form.post(route('admin.posts.store'))"
                    class="space-y-4">    

                    <Label>Titulo:</Label>
                    <Input 
                    v-model="form.title"
                    placeholder="Ingrese un titulo"></Input>

                    <Label>Slug:</Label>
                    <Input 
                    v-model="form.slug"
                    placeholder="Ingrese un Slug"></Input>

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

                    <div class="flex justify-end w-full gap-4">
                        <Button type="submit" :disabled="form.processing" class="bg-blue-500">
                            Crear
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