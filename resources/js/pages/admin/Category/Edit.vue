<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Input} from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { BreadcrumbItem, Category } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categorias',
        href: '/admin/categories',
    },
    {
        title: 'Editar',
        href: '',
    },
];

const props = defineProps<{
    category: Category
}>();

const form = useForm({
    name: props.category.name,
});

</script>

<template>
    
<Head title="Categorias" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min p-6">
                <form @submit.prevent="form.put(route('admin.categories.update', {category: props.category.id}))">    
                    <Label>Nombre:</Label>
                    <Input 
                    class="mt-4" 
                    v-model="form.name"
                    placeholder="Ingrese un nombre"></Input>
                    <div class="flex justify-end w-full gap-4 mt-4">
                        <Button :disabled="form.processing" class="bg-blue-500">
                            Editar
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>