<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Category, Message } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Table, TableCaption, TableBody, TableCell, TableHead, TableHeader, TableRow, } from '@/components/ui/table';
import { Pencil, Trash2 } from 'lucide-vue-next';
import { watch } from 'vue';

import Swal from 'sweetalert2';
//import PlaceholderPattern from '../../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categorias',
        href: '',
    },
];

const props = defineProps<{
    categories: Category;
    message?: Message | null;
}>();

/* const page = usePage; */

const deleteCategory = (id: number) => {
    Swal.fire({
        title: "Esta usted seguro?",
        text: "No pude revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminarlo!",
        }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('admin.categories.destroy', { id }), {
                preserveScroll: true,
                onError: (errors: any) => {
                    console.log(errors);
                }
            });
        }
    });
};

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


</script>

<template>
    <Head title="Categorias" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-end w-full gap-4">
                    <Link :href="route('admin.categories.create')">
                        <Button class="bg-blue-500">
                            Nuevo
                        </Button>
                    </Link>
                </div>

            <!-- <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
            </div> -->
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min p-6">
                <Table>
                    <TableCaption>Lista de categorias.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[1000px]">Nombre</TableHead>
                            <TableHead>Acciones</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="category in categories" :key="category.id">
                            <TableCell class="font-medium">{{ category.name }}</TableCell>
                            <TableCell>
                                <div class="flex gap-2">
                                    <Link :href="route('admin.categories.edit', { category: category.id })">
                                        <Button class="bg-blue-500">      
                                            <Pencil/>
                                        </Button>
                                    </Link>
                                    <Button @click="deleteCategory(category.id)" class="bg-red-500">
                                        <Trash2/>
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
